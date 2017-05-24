<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($_POST['payment'])) $payment = $_POST['payment'];

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }

  //kgt - discount coupons
  if (!tep_session_is_registered('coupon')) tep_session_register('coupon');
  //this needs to be set before the order object is created, but we must process it after
  $coupon = tep_db_prepare_input($_POST['coupon']);
  // Si el cupon esta vacio lo eliminamos
  if( $coupon == '' )
  {
	unset( $coupon );
	tep_session_unregister( 'coupon' );
  }  
  //end kgt - discount coupons
// load the selected payment module
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment($payment);

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  $payment_modules->update_status();

##### Points/Rewards Module V2.1rc2a check for error BOF #######
  if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
	  if (isset($_POST['customer_shopping_points_spending']) && is_numeric($_POST['customer_shopping_points_spending']) && ($_POST['customer_shopping_points_spending'] > 0)) {
		  $customer_shopping_points_spending = false;
		  if (tep_calc_shopping_pvalue($_POST['customer_shopping_points_spending']) < $order->info['total'] && !is_object($$payment)) {
			  $customer_shopping_points_spending = false;
			  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REDEEM_SYSTEM_ERROR_POINTS_NOT), 'SSL'));
		  } else {
			  $customer_shopping_points_spending = $_POST['customer_shopping_points_spending'];
			  if (!tep_session_is_registered('customer_shopping_points_spending')) tep_session_register('customer_shopping_points_spending');
		  }
	  }
	  
	  if (tep_not_null(USE_REFERRAL_SYSTEM)) {
		  if (isset($_POST['customer_referred']) && tep_not_null($_POST['customer_referred'])) {
			  $customer_referral = false;
			  $check_mail = trim($_POST['customer_referred']);
			  if (tep_validate_email($check_mail) == false) {
				  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_VALID), 'SSL'));
			  } else {
				  $valid_referral_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $check_mail . "' limit 1");
				  $valid_referral = tep_db_fetch_array($valid_referral_query);
				  if (!tep_db_num_rows($valid_referral_query)) {
					  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_FOUND), 'SSL'));
				  }
				  
				  if ($check_mail == $order->customer['email_address']) {
					  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_SELF), 'SSL'));
				  } else {
					  $customer_referral = $valid_referral['customers_id'];
					  if (!tep_session_is_registered('customer_referral')) tep_session_register('customer_referral');
				  }
			  }
		  }
	  }
  }
//if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) && (!$customer_shopping_points_spending) || (is_object($$payment) && ($$payment->enabled == false)) ) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }
########  Points/Rewards Module V2.1rc2a EOF #################*/

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

  //kgt - discount coupons
  if( tep_not_null( $coupon ) && is_object( $order->coupon ) ) { //if they have entered something in the coupon field
    $order->coupon->verify_code();
    if( MODULE_ORDER_TOTAL_DISCOUNT_COUPON_DEBUG != 'true' ) {
		  if( !$order->coupon->is_errors() ) { //if we have passed all tests (no error message), make sure we still meet free shipping requirements, if any
// ChilliNr1`s Coupon AND Free Shipping Fix START
/**************if( $order->coupon->is_recalc_shipping() ) tep_redirect( tep_href_link( FILENAME_CHECKOUT_SHIPPING, 'error_message=' . urlencode( ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR ), 'SSL' ) ); //redirect to the shipping page to reselect the shipping method**************/
// ChilliNr1`s Coupon AND Free Shipping Fix END
		  } else {
			  if( tep_session_is_registered('coupon') ) tep_session_unregister('coupon'); //remove the coupon from the session
			  tep_redirect( tep_href_link( FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode( implode( ' ', $order->coupon->get_messages() ) ), 'SSL' ) ); //redirect to the payment page
		  }
    }
	} else { //if the coupon field is empty, unregister the coupon from the session
		if( tep_session_is_registered('coupon') ) { //we had a coupon entered before, so we need to unregister it
      tep_session_unregister('coupon');
      //now check to see if we need to recalculate shipping:
      require_once( DIR_WS_CLASSES.'discount_coupon.php' );
      if( discount_coupon::is_recalc_shipping() ) tep_redirect( tep_href_link( FILENAME_CHECKOUT_SHIPPING, 'error_message=' . urlencode( ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR ), 'SSL' ) ); //redirect to the shipping page to reselect the shipping method
    }
	}
	//end kgt - discount coupons
// load the selected shipping module
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping($shipping);

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;
  $order_total_modules->process();

// Stock Check
  $any_out_of_stock = false;
  if (STOCK_CHECK == 'true') {
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
  }

//-----   BEGINNING OF ADDITION: MATC   -----// 
	if (tep_db_prepare_input($_POST['TermsAgree']) != 'true' and MATC_AT_CHECKOUT != 'false') {
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'matcerror=true', 'SSL'));
    }
//-----   END OF ADDITION: MATC   -----//
  // test last orders_id
  $_oders_max_query = tep_db_query("select max(orders_id) as max_id from " . TABLE_ORDERS . "");
  $_oders_max = tep_db_fetch_array($_oders_max_query);
  $_orders_id = $_oders_max["max_id"];

// test last holding_orders_id
  $holding_oders_max_query = tep_db_query("select max(orders_id) as max_id from " . TABLE_HOLDING_ORDERS . "");
  $holding_oders_max = tep_db_fetch_array($holding_oders_max_query);
  $holding_insert_id = $holding_oders_max["max_id"];

// assign last orders_in to prevent duplicate entry
   $insert_id = ($_orders_id >= $holding_insert_id )? $_orders_id+1 : $holding_insert_id+1 ;
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_CONFIRMATION);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2);

?>

<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>
<?php
  if (isset($$payment->form_action_url)) {
// Begin OrderCheck
//    $form_action_url = $$payment->form_action_url;
    $form_action_temp = $$payment->form_action_url;
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PAYMENT_EXT, '', 'SSL');
// End OrderCheck
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
?>
<style>
.table tbody>tr>td.vert-align {vertical-align: middle!important;}
</style>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

  <div class="text-center">
    <ul class="pagination">
      <li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" >' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></li>
      <li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></li>
      <li><a href="" class="active disable-link"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></a></li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-6">
      <h2 class="page-heading"><?php echo HEADING_DELIVERY_ADDRESS; ?></h2>
<?php
  if ($sendto != false) {
?>
      <?php echo '<strong>' . HEADING_DELIVERY_ADDRESS . '</strong> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '">&nbsp;<i class="icon-pencil">(' . TEXT_EDIT . ')</i></a>'; ?><br /><br />
      <div class="well">
        <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?>
      </div>
<?php 
  }
?>
    </div>


    <div class="col-md-6">
      <h2><?php echo HEADING_BILLING_INFORMATION; ?></h2>
      <?php echo '<strong>' . HEADING_BILLING_ADDRESS . '</strong> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '">&nbsp;<i class="icon-pencil">(' . TEXT_EDIT . ')</i></a>'; ?><br /><br />
        <div class="well">
          <?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?>
        </div>

    </div>
  </div>
  <br />
  <div class="row">
    <div class="col-md-6">
<?php
  if ($sendto != false) {
    if ($order->info['shipping_method']) {
?>
                <?php echo '<strong>' . HEADING_SHIPPING_METHOD . '</strong> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">&nbsp;<i class="icon-pencil">(' . TEXT_EDIT . ')</i></a>'; ?>
         <p class="alert alert-success"><?php echo $order->info['shipping_method'][0]; ?></p>
<?php
    }
  }
?>
    </div>
    <div class="col-md-6">
      <?php echo '<strong>' . HEADING_PAYMENT_METHOD . '</strong> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '">&nbsp;<i class="icon-pencil">(' . TEXT_EDIT . ')</i></a>'; ?>
      <p class="alert alert-success"><?php echo $order->info['payment_method']; ?></p>
    </div>
  </div>



<div class="row">
<div class="col-md-12">
<h2><?php echo CESTA_DE_LA_COMPRA ?></h2>

<table class="table table-condensed">	  
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>

          <tr>
            <td colspan="2"><?php echo '<strong>' . HEADING_PRODUCTS . '</strong> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">&nbsp;<i class="icon-pencil">(' . TEXT_EDIT . ')</i></a>'; ?></td>
            <td align="right"><strong><?php echo HEADING_TAX; ?></strong></td>
            <td align="right"><strong><?php echo HEADING_TOTAL; ?></strong></td>
          </tr>

<?php
  } else {
?>
          
                <?php echo '<strong>' . HEADING_PRODUCTS . '</strong> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '">&nbsp;<i class="icon-pencil">(' . TEXT_EDIT . ')</i></a>'; ?><br /><br />
         

<?php
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '          <tr>' . "\n" .
           '<td class="vert-align" align="right" width="30">' . $order->products[$i]['qty'] . '&nbsp;x&nbsp; &nbsp;</td>' . "\n" .
           '<td class="vert-align">' . $order->products[$i]['name'];

    if (STOCK_CHECK == 'true') {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }


                    if (sizeof($order->info['tax_groups']) > 1) echo '            ' . tep_display_tax_value($order->products[$i]['tax']) . '%' . "\n";

                    echo '<span class="pull-right">        ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</span> <br /><br />';
  }
?>

    </table>
  </div>

  <div class="col-md-12">
    <div class="pull-right">
	  <table class="table">

<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    echo $order_total_modules->output();
  }
?>
      </table>
    </div>
  </div>
</div>

<?php
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>

  <h2><?php echo HEADING_PAYMENT_INFORMATION; ?></h2>

  <div class="well">
        <?php echo $confirmation['title']; ?>
    </div>
<?php
      if (isset($confirmation['fields'])) {
        for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>

          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
          <?php echo $confirmation['fields'][$i]['title']; ?>
          <?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?>
          <?php echo $confirmation['fields'][$i]['field']; ?>

<?php
        }
      }
?>

<?php
    }
  }

  if (tep_not_null($order->info['comments'])) {
?>

  
    <h2><?php echo '<strong>' . HEADING_ORDER_COMMENTS . '</strong> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></h2>

      <div class="well">
        <?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?>
      </div>

<?php
  }
?>
<?php
  if (isset($$payment->form_action_url)) {
// Begin OrderCheck
//    $form_action_url = $$payment->form_action_url;
    $form_action_temp = $$payment->form_action_url;
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PAYMENT_EXT, '', 'SSL');
// End OrderCheck
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
// Begin OrderCheck
  echo tep_draw_hidden_field( 'module_link', $form_action_temp );
// End OrderCheck

  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }

  echo tep_draw_button(IMAGE_BUTTON_CONFIRM_ORDER, 'icon-arrow-right2', null, 'btn btn-default pull-right');
?>

</form>

<?php 
  require(DIR_THEME. 'html/column_right.php');
  require(DIR_THEME. 'html/footer.php');;
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
