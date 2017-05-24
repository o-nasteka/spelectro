<?php echo tep_draw_form('cart_quantity', 'popup_cart.php?action=update_product','post','id="popup_cart_form22"'); ?>

<?php if ($cart->count_contents() > 0) { ?>

<h3><?php echo TABLE_HEADING_PRODUCTS; ?></h3>
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
    $html[$cur_row]['image'] = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><img src="product_thumb.php?img=images/productos/'.$products[$i]['image'].'&w=90&h=90" width="90" class="img-responsive"></a>';

    $html[$cur_row]['name'] = $products_name;
    $html[$cur_row]['price'] = $currencies->display_price($products[$i]['price'], tep_get_tax_rate($products[$i]['tax_class_id']));
    $html[$cur_row]['qty'] = $products[$i]['quantity'];
    $html[$cur_row]['price_full'] = $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>';
    $html[$cur_row]['delete'] = '<span style="visibility: hidden;"><input style="display:none;" type="checkbox" name="cart_delete[]" value="'.$products[$i]['id'].'" id="cart_delete'.$r_prodid[$i].'"></span>
      <input class="delete" title="Удалить '.$products[$i]['name'].' из корзины" type="button" name="press1" value="'.$r_prodid[$i].'">';  
   }

?>
    <!-- MAIN TABLE -->
<div class="checkout_right_cart">
  <?php foreach ($html as $key => $value): ?>
    <div class="checkout__cart_item row">
      <div class="checkout__item_image col-xs-4">
        <?php echo $value['image'] ?>
      </div>  
      <div class="checkout__item_name col-xs-8">
        <?php echo $value['name'] ?>
	      <div class="checkout__purchase_price">
	      	<?php echo $value['qty'] ?>* <?php echo $value['price'] ?> = <b><?php echo $value['price_full'] ?></b> 
	      </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<!-- END MAIN TABLE -->

<?php
  } else {
     // Корзина пустая
    echo '<h3>'.TEXT_CART_EMPTY.'</h3>';
    echo '<div><a href="/" class="btn">' . IMAGE_BUTTON_CONTINUE . '</a></div>'; 
  }
?>
</form>

<?php if(CUPONES_MODULE_ENABLED == 'true') { ?>
	<span class="btn btn-default btn_coupon popup_cart"><?php echo TEXT_HAVE_COUPON_KGT; ?></span>
<?php } ?>
<div class="orderTotals"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>