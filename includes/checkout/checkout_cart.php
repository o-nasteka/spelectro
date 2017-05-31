<?php

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  chdir('../../');
  $rootPath = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])));
  require('includes/application_top.php');


  require(DIR_WS_CLASSES . 'order.php');
  require(DIR_WS_CLASSES . 'order_total.php');
//  require(DIR_WS_INCLUDES . 'functions/categories_lookup.php'); // Categories Functions
  $order = new order;
  $order_total_modules = new order_total;
}

//  debug($_SESSION);

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

$r_mycode = $_POST['gv_redeem_code'];

if(CUPONES_MODULE_ENABLED == '') include('ext/coupons/coupon_cart.php');

$order_totals = $order_total_modules->process();
//  $cart->get_products(true);

if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
  // Start - CREDIT CLASS Gift Voucher Contribution
  if ($credit_covers) $paymentMethod = 'credit_covers';
  unset($_POST['gv_redeem_code']);
  $order_total_modules->collect_posts();
  $order_total_modules->pre_confirmation_check();
  // End - CREDIT CLASS Gift Voucher Contribution
}

?>

<?php //echo tep_draw_form('cart_quantity', 'popup_cart.php?action=update_product','post','id="popup_cart_form"'); ?>
  <div id="cart-item">

<?php if ($cart->count_contents() > 0) { ?>

  <link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES . TEMPLATE_NAME; ?>/css/popup_cart.css">
  <h1><?php echo HEADING_TITLE; ?></h1>
  <?php

  $info_box_contents = array();
  $any_out_of_stock = 0;
  $products = $cart->get_products();   // #1

  // Get product attributes
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

  // Get product START    //#2
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
    $products_images = explode(';', $products[$i]['image']);
    $html[$cur_row]['image'] = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><img src="product_thumb.php?img=images/productos/'.$products[$i]['image'].'&w=90&h=90" width="90" class="img-responsive"></a>';


    $html[$cur_row]['id'] = $products[$i]['id'];
    $html[$cur_row]['name'] = $products_name;
    $html[$cur_row]['products_points'] = $products[$i]['products_points'];
    $html[$cur_row]['price'] = $currencies->display_price($products[$i]['price'], tep_get_tax_rate($products[$i]['tax_class_id']));
    $html[$cur_row]['qty'] = tep_draw_input_field('cart_quantity[]', $products[$i]['quantity']) .' '.$products[$i]['model_2'].
        '<span class="ok btn btn-xs"><i class="fa fa-check-circle"></i></span>' .tep_draw_hidden_field('products_id[]', $products[$i]['id']);
    $html[$cur_row]['price_full'] = $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>';
    $html[$cur_row]['delete'] = '<span style="visibility: hidden;"><input style="display:none;" type="checkbox" name="cart_delete[]" value="'.$products[$i]['id'].'" id="cart_delete'.$r_prodid[$i].'"></span>
      <button class="delete btn btn-sm btn-danger" value="'.$r_prodid[$i].'" title="'.TABLE_HEADING_REMOVE.' '.$products[$i]['name'].' '.TABLE_HEADING_REMOVE_FROM.'" name="press1" type="button"><i class="fa fa-trash-o"></i></button>';
  }
  ?>
  <!-- MAIN TABLE -->
  <div class="table-responsive">
    <table id="cartContent-page" width="100%">
      <thead>

      <th><?php echo TABLE_HEADING_IMAGE; ?></th>
      <th class="name"><?php echo TABLE_HEADING_NAME; ?></th>
      <th class="numeric"><?php echo TABLE_HEADING_PRICE; ?></th>
      <th class="numeric"><?php echo TABLE_HEADING_QUANTITY; ?></th>
      <th class="numeric"><?php echo TABLE_HEADING_REMOVE; ?></th>
      <th class="numeric chechout-total" colspan="2"><?php echo TABLE_HEADING_TOTAL; ?></th>
      </thead>
      <tbody>
        <?php
        // Count total price val
        ?>
        <?php foreach ($html as $key => $value):   ?>
            <tr class="cart-inner">
              <td class="product_image" ><?php echo $value['image'] ?></td>
              <td class="product_name" ><?php echo $value['name'] ?> </td>
              <td class="product_price" ><?php echo $value['price'] ?></td>
              <td class="product_qty" ><?php echo $value['qty'] ?></td>
              <td class="product_delete"><?php echo $value['delete'] ?></td>
              <td class="product_total" ><?php echo $value['price_full'] ?></td>
            </tr>
        <?php endforeach; ?>
      </tbody>
    </table>


  </div>
  <div class="row">
    <?php if(CUPONES_MODULE_ENABLED == 'true'): ?>
      <div class="col-md-6 col-sm-6 col-xs-9 form-inline pull-left text-left">
        <div class="form-group ">
          <?php echo SUB_TITLE_COUPON; ?>: <?php echo tep_draw_input_field('gv_redeem_code', $r_mycode,'class="form-control"');?>
        </div>
        <div class="form-group">
          <a href="javascript:;" class="btn btn-default btn-sm" id="voucherRedeem"><?php echo SUB_TITLE_COUPON_SUBMIT; ?></a>
        </div>
      </div>

    <?php endif; ?>

    <!-- END MAIN TABLE -->
    <div class="col-md-6 col-sm-6 col-xs-9 form-inline pull-right text-right">
      <div id="cart_order_total" class="orderTotals right">
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

    </div>
  </div>
  <!--  </form>-->
  </div>
  <?php
} else {
  // Корзина пустая
  echo '<h2>'.TEXT_CART_EMPTY.'</h2>';
  echo '<div><a href="/" class="btn btn-default">' . IMAGE_BUTTON_CONTINUE . '</a></div>';
}
?>

<!--<div class="orderTotals"><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i></div>-->
