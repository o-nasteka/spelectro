<?php
/*
  raid
*/

  require('includes/application_top.php');
  //require('includes/classes/http_client.php');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL & ~E_NOTICE);

  if (ONEPAGE_LOGIN_REQUIRED == 'true'){
	  if (!tep_session_is_registered('customer_id')){
		  tep_redirect(tep_href_link(FILENAME_LOGIN));
	  }
  }

  if (isset($_GET['rType'])){
	  header('content-type: text/html; charset=utf-8');
  }
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_ONEPAGE);

  //if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code']) && $_REQUEST['gv_redeem_code'] == 'redeem code'){
  if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code'])){
    $_REQUEST['gv_redeem_code'] = '';
    $_POST['gv_redeem_code'] = '';
  }  


  if(isset($_REQUEST['coupon']) && tep_not_null($_REQUEST['coupon']) && $_REQUEST['coupon'] == 'redeem code'){
    $_REQUEST['coupon'] = '';
    $_POST['coupon'] = '';
  }

  require('includes/classes/onepage_checkout.php');
  $onePageCheckout = new osC_onePageCheckout();    
     
  if (!isset($_GET['rType']) && !isset($_GET['action']) && !isset($_POST['action']) && !isset($_GET['error_message']) && !isset($_GET['payment_error'])){
	  $onePageCheckout->init();   
  } 
  //BOF KGT
  if (MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true'){
    if(isset($_POST['code']))
    {
      if(!tep_session_is_registered('coupon'))
        tep_session_register('coupon');
      $coupon = $_POST['code'];
    }
  }                     
  //EOF KGT          
  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;
                       
  $onePageCheckout->loadSessionVars();
                     
//  print_r($order);
// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed

  if (!isset($_GET['action']) && !isset($_POST['action'])){
	  // Start - CREDIT CLASS Gift Voucher Contribution
	  //  if ($order->content_type == 'virtual') {
	  if ($order->content_type == 'virtual' || $order->content_type == 'virtual_weight' ) {
		  // End - CREDIT CLASS Gift Voucher Contribution
		  $shipping = false;
		  $sendto = false;
	  }
  }else
  {
  	// if there is nothing in the customers cart, redirect them to the shopping cart page
	if ($cart->count_contents() < 1) {
		tep_redirect(tep_href_link(FILENAME_DEFAULT));
	}

  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();
  if (method_exists($cart, 'count_contents_virtual')){
	  // Start - CREDIT CLASS Gift Voucher Contribution
	  $total_count = $cart->count_contents_virtual();
	  // End - CREDIT CLASS Gift Voucher Contribution
  }

// load all enabled shipping modules
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_CLASSES . 'order_total.php');
  $order_total_modules = new order_total;       


  $action = (isset($_POST['action']) ? $_POST['action'] : '');
  if (isset($_POST['updateQuantities_x'])){
	  $action = 'updateQuantities';
  }    
  if (isset($_GET['action']) && $_GET['action']=='process_confirm'){
	  $action = 'process_confirm';
  }

  if (tep_not_null($action)){
	  ob_start();
	  if(isset($_POST) && is_array($_POST))
			$onePageCheckout->decode_post_vars();
	  switch($action){
		  case 'process_confirm':
  //      echo $onePageCheckout->confirmCheckout();
      break;
      case 'process':
			  echo $onePageCheckout->processCheckout();
		  break;
		  case 'countrySelect':
			//  echo $onePageCheckout->getAjaxStateField();
		  break;
		  case 'processLogin':
//			  echo $onePageCheckout->processAjaxLogin($_POST['email'], $_POST['pass']);
		  break;
		  case 'removeProduct':
//			  echo $onePageCheckout->removeProductFromCart($_POST['pID']);
		  break;
		  case 'updateQuantities':
	//		  echo $onePageCheckout->updateCartProducts($_POST['qty'], $_POST['id']);
		  break;
		  case 'setPaymentMethod':
			  echo $onePageCheckout->setPaymentMethod($_POST['method']);
		  break;
		  case 'setGV':
//			  echo $onePageCheckout->setGiftVoucher($_POST['method']);
		  break;
		  case 'redeemPoints':
//			  echo $onePageCheckout->redeemPoints($_POST['points']);
		  break;
		  case 'clearPoints':
//			  echo $onePageCheckout->clearPoints();
		  break;
		  case 'setShippingMethod':
			  echo $onePageCheckout->setShippingMethod($_POST['method']);
		  break;
		  case 'setSendTo':
		  case 'setBillTo':
			  echo $onePageCheckout->setCheckoutAddress($action);
		  break;
		  case 'checkEmailAddress':
			  echo $onePageCheckout->checkEmailAddress($_POST['emailAddress']);
		  break;
		  case 'saveAddress':
		  case 'addNewAddress':
			  echo $onePageCheckout->saveAddress($action);
		  break;
		  case 'selectAddress':
			  echo $onePageCheckout->setAddress($_POST['address_type'], $_POST['address']);
		  break;
		  case 'redeemVoucher':
	//		  echo $onePageCheckout->redeemCoupon($_POST['code']);
		  break;
		  case 'setMembershipPlan':
		//	  echo $onePageCheckout->setMembershipPlan($_POST['planID']);
		  break;
		  case 'updateCartView':
		//	  if ($cart->count_contents() == 0){
		//		  echo 'none';
		//	  }else{
		//		  include(DIR_WS_INCLUDES . 'checkout/cart.php');
		//	  }
		  break;
		  case 'updatePoints':
		  case 'updateShippingMethods':
			  include(DIR_WS_INCLUDES . 'checkout/shipping_method.php');
		  break;
		  case 'updatePaymentMethods':
			  include(DIR_WS_INCLUDES . 'checkout/payment_method.php');
		  break;
		  case 'getOrderTotals':
			  if (MODULE_ORDER_TOTAL_INSTALLED){
				  echo '<table cellpadding="2" cellspacing="0" border="0" width="100%">';
		
					$order_total_modules->process();
	
					echo  $order_total_modules->output();
					echo '</table>';
			  }
		  break;
		  case 'updateRadiosforTotal':
						$order_total_modules->output();
						echo $order->info['total'];
		  break;
		  case 'getProductsFinal':
			  include(DIR_WS_INCLUDES . 'checkout/products_final.php');
		  break;
		  case 'getNewAddressForm':
		  case 'getAddressBook':
			  $addresses_count = tep_count_customer_address_book_entries();
			  if ($action == 'getAddressBook'){
				  $addressType = $_POST['addressType'];
				  include(DIR_WS_INCLUDES . 'checkout/address_book.php');
			  }else{
				  include(DIR_WS_INCLUDES . 'checkout/new_address.php');
			  }
		  break;
		  case 'getEditAddressForm':
			  $aID = tep_db_prepare_input($_POST['addressID']);
			  $Qaddress = tep_db_query('select * from ' . TABLE_ADDRESS_BOOK . ' where customers_id = "' . $customer_id . '" and address_book_id = "' . $aID . '"');
			  $address = tep_db_fetch_array($Qaddress);
			  include(DIR_WS_INCLUDES . 'checkout/edit_address.php');
		  break;
		  case 'getBillingAddress':
			  include(DIR_WS_INCLUDES . 'checkout/billing_address.php');
		  break;
		  case 'getShippingAddress':
			  include(DIR_WS_INCLUDES . 'checkout/shipping_address.php');
		  break;
	  }
    
    

	  $content = ob_get_contents();
	  ob_end_clean();
	  if($action=='process')
      echo $content;
    else
      echo $content;
	  tep_session_close();
	  tep_exit();
  }

  function fixSeoLink($url){
	  return str_replace('&amp;', '&', $url);
  }   

?>


<?php

		// Titulo
		$sTitular = HEADING_TITLE;
		
		// Breadcrumb
		$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CHECKOUT_ONEPAGE ));

		require(DIR_THEME. 'html/header.php');
?>      
<script type="text/javascript" language="javascript" src="includes/checkout/jquery.ajaxq-0.0.1.js"></script>
<script type="text/javascript" language="javascript" src="includes/checkout/checkout.js"></script>

<script language="javascript">
                                    
  var onePage = checkout;
  onePage.initializing = true;
  onePage.ajaxCharset = 'utf-8';
  onePage.storeName = '<?php echo STORE_NAME; ?>';
  onePage.loggedIn = <?php echo (tep_session_is_registered('customer_id') ? 'true' : 'false');?>;
  onePage.autoshow = <?php echo ((ONEPAGE_AUTO_SHOW_BILLING_SHIPPING == 'False') ? 'false' : 'true');?>;
  onePage.stateEnabled = <?php echo (ACCOUNT_STATE == 'true' ? 'true' : 'false');?>;
  onePage.showAddressInFields = <?php echo ((ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False') ? 'false' : 'true');?>;
  onePage.showMessagesPopUp = <?php echo ((ONEPAGE_CHECKOUT_LOADER_POPUP == 'True') ? 'true' : 'false');?>;
  onePage.ccgvInstalled = <?php echo (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true' ? 'true' : 'false');?>;
  
  onePage.refresh = '<?php echo CH_JS_REFRESH; ?>';
  onePage.refresh_method = '<?php echo CH_JS_REFRESH_METHOD; ?>';
  onePage.setting_method = '<?php echo CH_JS_SETTING_METHOD; ?>';
  onePage.setting_address = '<?php echo CH_JS_SETTING_ADDRESS; ?>';
  onePage.setting_address_ship = '<?php echo CH_JS_SETTING_ADDRESS_SHIP; ?>';
  onePage.setting_address_bil = '<?php echo CH_JS_SETTING_ADDRESS_BIL; ?>';
  
  onePage.error_scart = '<?php echo CH_JS_ERROR_SCART; ?>';
  onePage.error_some1 = '<?php echo CH_JS_ERROR_SOME1; ?>';
  onePage.error_some2 = '<?php echo CH_JS_ERROR_SOME2; ?>';
  onePage.error_set_some1 = '<?php echo CH_JS_ERROR_SET_SOME1; ?>';
  onePage.error_set_some2 = '<?php echo CH_JS_ERROR_SET_SOME2; ?>';
  onePage.error_set_some3 = '<?php echo CH_JS_ERROR_SET_SOME3; ?>';
  onePage.error_req_bil = '<?php echo CH_JS_ERROR_REQ_BIL; ?>';
  onePage.error_err_bil = '<?php echo CH_JS_ERROR_ERR_BIL; ?>';
  onePage.error_req_ship = '<?php echo CH_JS_ERROR_REQ_SHIP; ?>';
  onePage.error_err_ship = '<?php echo CH_JS_ERROR_ERR_SHIP; ?>';
  onePage.error_address = '<?php echo CH_JS_ERROR_ADDRESS; ?>';
  onePage.error_pmethod = '<?php echo CH_JS_ERROR_PMETHOD; ?>';
  onePage.error_select_pmethod = '<?php echo CH_JS_ERROR_SELECT_PMETHOD; ?>';
  onePage.check_email = '<?php echo CH_JS_CHECK_EMAIL; ?>';      
  onePage.error_email = '<?php echo CH_JS_ERROR_EMAIL; ?>';       

  //BOF KGT
  onePage.kgtInstalled = <?php echo (MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' ? 'true' : 'false');?>;
  //EOF KGT
  onePage.shippingEnabled = <?php echo ($onepage['shippingEnabled'] === true ? 'true' : 'false');?>;
  onePage.pageLinks = {

  }

  function getFieldErrorCheck($element){
	  var rObj = {};
	  switch($element.attr('name')){  
		  case 'billing_firstname':
		  case 'shipping_firstname':
			  rObj.minLength = <?php echo addslashes(ENTRY_FIRST_NAME_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_FIRST_NAME_ERROR);?>';
		  break;
		  case 'billing_lastname':
		  case 'shipping_lastname':
			  rObj.minLength = <?php echo addslashes(ENTRY_LAST_NAME_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_LAST_NAME_ERROR);?>';
		  break;
		  case 'billing_email_address':
			  rObj.minLength = <?php echo addslashes(ENTRY_EMAIL_ADDRESS_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_EMAIL_ADDRESS_ERROR);?>';
		  break;
		  case 'billing_street_address':
		  case 'shipping_street_address':
			  rObj.minLength = <?php echo addslashes(ENTRY_STREET_ADDRESS_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_STREET_ADDRESS_ERROR);?>';
		  break;
		  case 'billing_zipcode':
		  case 'shipping_zipcode':
			  rObj.minLength = <?php echo addslashes(ENTRY_POSTCODE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_POST_CODE_ERROR);?>';
		  break;
		  case 'billing_city':
		  case 'shipping_city':
			  rObj.minLength = <?php echo addslashes(ENTRY_CITY_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_CITY_ERROR);?>';
		  break;
		  case 'billing_telephone':
			  rObj.minLength = <?php echo addslashes(ENTRY_TELEPHONE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_TELEPHONE_NUMBER_ERROR);?>';
		  break;
		  case 'billing_country':
		  case 'shipping_country':
			  rObj.errMsg = '<?php echo addslashes(ENTRY_COUNTRY_ERROR);?>';
		  break;
		  case 'billing_state':
		  case 'delivery_state':
			  rObj.minLength = <?php echo addslashes(ENTRY_STATE_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_STATE_ERROR);?>';
		  break;
		  case 'password':
		  case 'confirmation':
			  rObj.minLength = <?php echo addslashes(ENTRY_PASSWORD_MIN_LENGTH);?>;
			  rObj.errMsg = '<?php echo addslashes(ENTRY_PASSWORD_ERROR);?>';
		  break;
	  }
	return rObj;
  }

//jQuery.noConflict();
jQuery(function($) {
	$('#pageContentContainer').show();
	onePage.initCheckout();
});

function clearRadeos(){
	 return true;
}

</script>


<?php
	//	require(DIR_THEME. 'html/column_left.php');

    include( DIR_THEME. 'html/templates/checkout.php' );
                       
	//	include( DIR_THEME. 'html/column_right.php' );
		include( DIR_THEME. 'html/footer.php' );
		include( DIR_WS_INCLUDES . 'application_bottom.php' );

?>    
