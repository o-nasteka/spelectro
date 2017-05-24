<?php
/*
  $Id: checkout_confirmation.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$one_checkout=false;
  require('includes/application_top.php');
	
// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT_PAYMENT));
    if ($one_checkout)
	tep_redirect(tep_href_link('checkout.php#login', '', 'SSL'));
	else
	tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
		if ($one_checkout)
		tep_redirect(tep_href_link('checkout.php#cambiar-forma-envio', '', 'SSL'));
		else
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    if ($one_checkout)
		tep_redirect(tep_href_link('checkout.php#cambiar-forma-envio', '', 'SSL'));
		else
		tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

  if (!tep_session_is_registered('payment')) tep_session_register('payment');
  if (isset($_POST['payment'])) $payment = $_POST['payment'];

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }
  $_SESSION['comments']=$comments;
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
			  if ($one_checkout)
				tep_redirect(tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL'));
				else
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
				  if ($one_checkout)
				tep_redirect(tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL'));
				else
				  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_VALID), 'SSL'));
			  } else {
				  $valid_referral_query = tep_db_query("select customers_id from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $check_mail . "' limit 1");
				  $valid_referral = tep_db_fetch_array($valid_referral_query);
				  if (!tep_db_num_rows($valid_referral_query)) {
					  if ($one_checkout)
				tep_redirect(tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL'));
				else
					  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(REFERRAL_ERROR_NOT_FOUND), 'SSL'));
				  }
				  
				  if ($check_mail == $order->customer['email_address']) {
					  if ($one_checkout)
				tep_redirect(tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL'));
				else
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
    if ($one_checkout)
	tep_redirect(tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL'));
	else
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED), 'SSL'));
  }
########  Points/Rewards Module V2.1rc2a EOF #################*/

  if (is_array($payment_modules->modules)) {
    $payment_modules->pre_confirmation_check();
  }

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
        if ($one_checkout)
		tep_redirect(tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL'));
		else
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="checkout/funciones_confirmation.js" type="text/javascript"></script>
<?php require(DIR_THEME. 'scripts/scripts.php'); ?>
<link href="checkout/checkout.css" rel="stylesheet" type="text/css" />
</head>


<?php require(DIR_THEME. 'html/header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<!-- left_navigation //-->
<?php //require(DIR_THEME. 'html/column_left.php'); ?>
<!-- left_navigation_eof //-->
<div class="contenido_checkout">

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

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post', 'id="checkout_confirmation"');
?>
<div class="confirmacion">
<div class="progreso_contenedor">
    <div class="progreso" style="width:90%">90%</div>
</div>
<?php
  if ($sendto != false) {
?>
	<div class="confirmacion_interior">
		<p class="titulo_confirmacion"><?php echo '<strong>' . HEADING_DELIVERY_ADDRESS . '</strong> <a href="' . tep_href_link('checkout.php#cambiar-direccion', '', 'SSL') . '"><span class="orderEdit">' . TEXT_EDIT . '</span></a>'; ?></p>
        <p><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></p>
    </div>
<?php
  }
?>
	<div class="confirmacion_interior">
        <p class="titulo_confirmacion"><?php echo '<strong>' . HEADING_BILLING_ADDRESS . '</strong> <a href="' . tep_href_link('checkout.php#cambiar-direccion-facturacion', '', 'SSL') . '"><span class="orderEdit">' . TEXT_EDIT . '</span></a>'; ?></p>
        <p><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></p>
    </div>
<?php
  if ($sendto != false) {
    if ($order->info['shipping_method']) {
?>
    <div class="confirmacion_interior">
		<p class="titulo_confirmacion"><?php echo '<strong>' . HEADING_SHIPPING_METHOD . '</strong> <a href="' . tep_href_link('checkout.php#cambiar-forma-envio', '', 'SSL') . '"><span class="orderEdit">' . TEXT_EDIT . '</span></a>'; ?></p>
        <p><?php echo $order->info['shipping_method']; ?></p>
    </div>
<?php
    }
  }
?>    
    <div class="confirmacion_interior">
        <p class="titulo_confirmacion"><?php echo '<strong>' . HEADING_PAYMENT_METHOD . '</strong> <a href="' . tep_href_link('checkout.php#cambiar-forma-pago', '', 'SSL') . '"><span class="orderEdit">' . TEXT_EDIT . '</span></a>'; ?></p>
        <p><?php echo $order->info['payment_method']; ?></p>
    </div>
            
<?php
/*  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
?>
    <h4><?php echo HEADING_PAYMENT_INFORMATION; ?></h4>
    <div class="confirmacion_interior expandido">
    <p><?php echo $confirmation['title']; ?></p>
    <?php
          for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
    ?>
    <p><strong><?php echo $confirmation['fields'][$i]['title']; ?></strong></p>
    <p><?php echo $confirmation['fields'][$i]['field']; ?></p>
    <?php
          }
    ?>
    </div>
<?php
    }
  }*/
?>
      
<?php
/*  if (tep_not_null($order->info['comments'])) {
?>
<h4><?php echo HEADING_ORDER_COMMENTS . ' <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">' . TEXT_EDIT . '</span></a>'; ?></h4>
<div class="confirmacion_interior expandido">
    <p><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></p>
</div>
<?php
  }*/
?>
        </div>
        <div class="checkout columna_checkout visible">
            <div class="confirmation cestita">
            	<div class="listado_productos">
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30"><span class="cambiar_cantidad">' . $order->products[$i]['qty'] . '</span></td>' . "\n" .
         '            <td class="main" valign="top">' . $order->products[$i]['name'];

    if (STOCK_CHECK == 'true') {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }

    echo '</td>' . "\n";

    if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

    echo '            <td class="main precio" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
                </table>
 </div>                 
<div class="totales">
	<?php
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        //$order_total_modules->process();
        echo '<table border="0" width="100%" cellspacing="0" cellpadding="2">'.str_replace('Total:', '<span class="total_string">Total:</span>', $order_total_modules->output()).'</table>';
      }
    ?>                
</div> 

<div class="botonera">
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

?>
<a id="seguir_comprando" href="<?php echo tep_href_link(FILENAME_DEFAULT) ?>">Seguir comprando</a>
<a id="confirmar_pedido" href="javascript:void(0)">Confirmar Pedido</a>
</div>
            </div>
        </div>
</form>        
         </div>
<?php //require(DIR_THEME. 'html/column_right.php'); ?>
<!-- right_navigation_eof //-->

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_THEME. 'html/footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
