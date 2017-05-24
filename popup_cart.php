<?php 
  if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);
  require(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'order_total.php');
  $order = new order;
  $order_total_modules = new order_total;
  $order_totals = $order_total_modules->process();
//  $cart->get_products(true);

?>

<?php echo tep_draw_form('cart_quantity', 'popup_cart.php?action=update_product','post','id="popup_cart_form"'); ?>

<?php if ($cart->count_contents() > 0) { ?>

<style>

#cartContent-page {width: 100%;}
#cartContent-page th.name {
  text-align: left;
}
#cartContent-page th {
	font-size: 14px;
  font-weight: normal;
  border-bottom: 1px solid #CCC;
}
#cartContent-page th,
#cartContent-page td {
  padding: 4px;
  vertical-align: middle;
  border:0;
}
#cartContent-page .product_image {
}
#cartContent-page .product_image img {vertical-align: middle;max-width:150px;}
#cartContent-page.product_name {
  vertical-align: top;
  padding: 10px 0;
  font-weight: bold;
}
#cartContent-page .attributes_list {
  line-height: 12px;
}
#cartContent-page .attributes_list li {font-style: oblique;font-size: 10px;}
#cartContent-page .attributes_list li span {font-style: normal;}
#cartContent-page td.product_price,
#cartContent-page td.product_total,
#cartContent-page td.product_qty,
#cartContent-page td.product_delete {
}#cartContent-page td.product_total {font-weight: bold;}
#cartContent-page td.product_qty input {width: 30px;text-align: center;}
#cartContent-page td.product_qty input:focus {
  box-shadow: none;
  border-radius: 0;

}

#cartContent-page .product_delete .btn {
  font-size: 17px;
  padding: 0 6px;
  border-radius: 30px;

}
#cartContent-page .btn.ok { display:none; position:absolute; color:#49B3F6; font-size:19px; margin-top:-6px;
}
#cartContent-page #cart_order_total {
  margin: 0 0 20px 0;
  float: right;
  text-align: right;
}

@media (min-width:768px){
  #cartContent-page .product_image {
    width: 150px;
  }
  #cartContent-page th, #cartContent-page td {
    padding: 10px;
  }
}

@media only screen and (max-width: 800px) {
  #cartContent-page td{
    padding: 5px 10px !important;
  } 
}
</style>
<h1><?php echo HEADING_TITLE; ?></h1>
<?php

  $info_box_contents = array();
  $any_out_of_stock = 0;
  $products = $cart->get_products();
  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      
  $r_prodid[$i] = preg_replace('/{/','_',$products[$i]['id']);
  $r_prodid[$i] = preg_replace('/}/','_',$r_prodid[$i]);
  
    // Push all attributes information in an array
    if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      while (list($option, $value) = each($products[$i]['attributes'])) {
      $attributes = tep_db_query("SELECT 
                                    popt.products_options_name, 
                                    poval.products_options_values_name, 
                                    pa.options_values_price, 
                                    pa.price_prefix
                                  FROM " . TABLE_PRODUCTS_OPTIONS . " popt, " . 
                                    TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . 
                                    TABLE_PRODUCTS_ATTRIBUTES . " pa
                                  WHERE pa.products_id = '" . $products[$i]['id'] . "'
                                    and pa.options_id = '" . $option . "'
                                    and pa.options_id = popt.products_options_id
                                    and pa.options_values_id = '" . (int)$value . "'
                                    and pa.options_values_id = poval.products_options_values_id
                                    and popt.language_id = '" . $languages_id . "'
                                    and poval.language_id = '" . $languages_id . "'");

        $attributes_values = tep_db_fetch_array($attributes);
        if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . TEXT_PREFIX . $option . ']',  $products[$i]['attributes_values'][$option]);
          $attr_value = $products[$i]['attributes_values'][$option];
        } else {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attr_value = $attributes_values['products_options_values_name'];
        }

        $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
        $products[$i][$option]['options_values_id'] = $value;
        $products[$i][$option]['products_options_values_name'] = $attr_value ;
        $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
        $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
      }
    }
  }

  for ($i=0, $n=sizeof($products); $i<$n; $i++) {
  
    if (($i/2) == floor($i/2)) {
      $info_box_contents[] = array('params' => '');
    } else {
      $info_box_contents[] = array('params' => '');
    }
  
    $cur_row = sizeof($info_box_contents) - 1;
    $products_name = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['name'] . '</a>';

    if (STOCK_CHECK == 'true') {
      $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
      if (tep_not_null($stock_check)) {
        $any_out_of_stock = 1;
        $products_name .= $stock_check;
      }
    }

    if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
      reset($products[$i]['attributes']);
      $products_name .= '<ul class="attributes_list">';
      while (list($option, $value) = each($products[$i]['attributes'])) {
        $products_name .= '<li><span>'.$products[$i][$option]['products_options_name'].'</span> : '.$products[$i][$option]['products_options_values_name'] . '</li>';
      }
      $products_name .= '</ul>';
    }

    // Explode first image of product
    $html[$cur_row]['image'] = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><img src="product_thumb.php?img=images/productos/'.$products[$i]['image'].'&w=185&h=185" width="250" class="img-responsive"></a>';

    $html[$cur_row]['name'] = $products_name;
    $html[$cur_row]['price'] = $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']));
    $html[$cur_row]['qty'] = tep_draw_input_field('cart_quantity[]', $products[$i]['quantity']) .' '.$products[$i]['model_2'].
    '<span class="ok btn btn-xs"><i class="fa fa-check-circle"></i></span>' .tep_draw_hidden_field('products_id[]', $products[$i]['id']);
    $html[$cur_row]['price_full'] = $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>';
    $html[$cur_row]['delete'] = '<span style="visibility: hidden;"><input style="display:none;" type="checkbox" name="cart_delete[]" value="'.$products[$i]['id'].'" id="cart_delete'.$r_prodid[$i].'"></span>
      <button class="delete btn btn-sm btn-danger" value="'.$r_prodid[$i].'" title="'.TABLE_HEADING_REMOVE.' '.$products[$i]['name'].' '.TABLE_HEADING_REMOVE_FROM.'" name="press1" type="button"><i class="fa fa-trash-o"></i></button>';  
   }

?>
    <!-- MAIN TABLE -->
<div class="table-responsive">  
<table id="cartContent-page">
    <thead>
      <th>Imagen</th>
      <th class="name">Producto(s)</th>
      <th class="numeric"><?php echo TABLE_HEADING_TOTAL; ?></th>
      <th class="numeric"><?php echo TABLE_HEADING_QUANTITY; ?></th>
      <th class="numeric" colspan="2"><?php echo TABLE_HEADING_TOTAL; ?></th>
    </thead>
    <tbody>
    <?php foreach ($html as $key => $value): ?>
      <tr>
        <td class="product_image" ><?php echo $value['image'] ?></td>
        <td class="product_name" ><?php echo $value['name'] ?></td>
        <td class="product_price" ><?php echo $value['price'] ?></td>
        <td class="product_qty" ><?php echo $value['qty'] ?></td>
        <td class="product_total" ><?php echo $value['price_full'] ?></td>
        <td class="product_delete"><?php echo $value['delete'] ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<div class="row">

<!-- END MAIN TABLE -->
	<div class="col-md-6 col-sm-6 col-xs-9 form-inline pull-right text-right">
      <div id="cart_order_total" class="right">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <?php echo $coupon_text; ?>
            <?php echo $order_total_modules->output(); ?>
        </table> 
      </div>
<?php 
  // Товары, выделенные *** имеются на нашем складе в недостаточном для Вашего заказа количестве...
  if ($any_out_of_stock == 1){
    if (STOCK_ALLOW_CHECKOUT == 'true') {
      echo '<div><br />'.OUT_OF_STOCK_CAN_CHECKOUT.'</div>';
    }else{
      echo '<div><br />'.OUT_OF_STOCK_CANT_CHECKOUT.'</div>';
    }
  }
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">

      <tr>

        <!-- Оформить заказ -->
        <td align="right" class="main">
          <button class="btn" data-dismiss="modal" aria-hidden="true">SEGUIR COMPRANDO</button>
          <a href="<?php echo tep_href_link(FILENAME_CHECKOUT, '', 'SSL'); ?>" id="checkoutButton" class="btn btn-default"><?php echo HEADER_TITLE_CHECKOUT; ?></a>    
        </td>
      </tr>
    </table>
  </div>   
</div>  
  </form>
<?php
  } else {
     // Корзина пустая
    echo '<h2>'.TEXT_CART_EMPTY.'</h2>';
    echo '<div><a href="/" class="btn btn-default">' . IMAGE_BUTTON_CONTINUE . '</a></div>'; 
  }
?>