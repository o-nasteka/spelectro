<?php
class osC_onePageCheckout {

	function __construct(){
		$this->buildSession();
	}

	function reset(){
		$this->buildSession(true);
	}

	function buildSession($forceReset = false){
		global $onepage, $payment, $shipping, $customer_id, $sendto, $billto;
		if (!tep_session_is_registered('onepage') || $forceReset === true){
			if (tep_session_is_registered('onepage')){
				tep_session_unregister('onepage');
			}
			if (tep_session_is_registered('payment')){
				tep_session_unregister('payment');
			}
			if (tep_session_is_registered('shipping')){
				tep_session_unregister('shipping');
			}
			if (tep_session_is_registered('billto')){
				tep_session_unregister('billto');
			}
			if (tep_session_is_registered('sendto')){
				tep_session_unregister('sendto');
			}
			if (tep_session_is_registered('customer_shopping_points_spending'))
			{
				tep_session_unregister('customer_shopping_points_spending');
			}

			$onepage = array(
			'info'           => array(
			'payment_method' => '', 'shipping_method' => '', 'comments' => '', 'coupon' => ''
			),
			'customer'       => array(
			'firstname' => '',  'lastname' => '', 'company' => '',  'street_address' => '', 'nif' => '',
			'suburb' => '',     'city' => '',     'postcode' => '', 'state' => '',
			'zone_id' => '',    'country' => array('id' => '', 'title' => '', 'iso_code_2' => '', 'iso_code_3' => ''),
			'format_id' => '',  'telephone' => '', 'email_address' => '', 'password' => '', 'newsletter' => ''
			),
			'delivery'       => array(
			'firstname' => '',  'lastname' => '', 'company' => '',  'street_address' => '',
			'suburb' => '',     'city' => '',     'postcode' => '', 'state' => '',
			'zone_id' => '',    'country' => array('id' => '', 'title' => '', 'iso_code_2' => '', 'iso_code_3' => ''),
			'country_id' => '', 'format_id' => ''
			),
			'billing'        => array(                                                      
			'firstname' => '',  'lastname' => '', 'company' => '',  'street_address' => '', 'nif' => '',
			'suburb' => '',     'city' => '',     'postcode' => '', 'state' => '',
			'zone_id' => '',    'country' => array('id' => '', 'title' => '', 'iso_code_2' => '', 'iso_code_3' => ''),
			'country_id' => '', 'format_id' => ''
			),
			'create_account'  => false,
			'shippingEnabled' => true
			);
			$payment = false;
			$shipping = false;
			$sendto = 0;
			$billto = 0;
			tep_session_register('onepage');
			tep_session_register('payment');
			tep_session_register('shipping');
			tep_session_register('billto');
			tep_session_register('sendto');
		}
    

		if(empty($onepage['customer']['postcode']))
		{
			$onepage['customer']['postcode'] = ONEPAGE_AUTO_SHOW_DEFAULT_ZIP;
		}
		if(empty($onepage['billing']['postcode']))
		{
			$onepage['billing']['postcode'] = ONEPAGE_AUTO_SHOW_DEFAULT_ZIP;
		}
		if(empty($onepage['delivery']['postcode']))
		{
			$onepage['delivery']['postcode'] = ONEPAGE_AUTO_SHOW_DEFAULT_ZIP;
		}

		if (tep_session_is_registered('customer_id') && is_numeric($customer_id)){
			$onepage['create_account'] = false;

			$QcustomerEmail = tep_db_query('select customers_firstname, customers_email_address, customers_telephone from ' . TABLE_CUSTOMERS . ' where customers_id = "' . $customer_id . '"');
			$customerEmail = tep_db_fetch_array($QcustomerEmail);
			$onepage['customer']['email_address'] = $customerEmail['customers_email_address'];
			$onepage['customer']['telephone'] = $customerEmail['customers_telephone'];
            //для имени клиента в заказах, в админке
            $onepage['customer']['firstname'] = $customerEmail['customers_firstname'];             
		}
	}

	function fixZoneName($zone_id,$country,&$state)
	{
		if ( $zone_id >0 && $country>0 ) {
			$zone_query = tep_db_query("select distinct zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and zone_id = '" . tep_db_input($zone_id) . "' ");
			if (tep_db_num_rows($zone_query) == 1) {
				$zone = tep_db_fetch_array($zone_query);
				$state = $zone['zone_name'];
			}
		}
	}

	function loadSessionVars($type = 'checkout'){
		global $order, $onepage, $payment, $shipping, $comments, $coupon;
		if (tep_not_null($onepage['info']['payment_method'])){
			$payment = $onepage['info']['payment_method'];
			if (isset($GLOBALS[$payment])){
				$pModule = $GLOBALS[$payment];
				if (isset($pModule->public_title)) {
					$order->info['payment_method'] = $pModule->public_title;
				} else {
					$order->info['payment_method'] = $pModule->title;
				}

				if (isset($pModule->order_status) && is_numeric($pModule->order_status) && ($pModule->order_status > 0)){
					$order->info['order_status'] = $pModule->order_status;
				}
			}
		}
		if (tep_not_null($onepage['info']['shipping_method'])){
			$shipping = $onepage['info']['shipping_method'];
			$order->info['shipping_method'] = $shipping['title'];
			$order->info['shipping_cost'] = $shipping['cost'];
		}
		if (tep_not_null($onepage['info']['comments'])){

			$comments = $onepage['info']['comments'];
			if (!tep_session_is_registered('comments')) tep_session_register('comments');
		}

		//BOF KGT
		if(MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS=='true')
		{
			//kgt - discount coupons
			if (tep_not_null($onepage['info']['coupon'])) {
				//this needs to be set before the order object is created, but we must process it after

				$order->info['coupon'] = $onepage['info']['coupon'];
				if (!tep_session_is_registered('coupon')) tep_session_register('coupon');
				//$order->info['applied_discount'] = $onepage['info']['applied_discount'];
				//$order->info['subtotal'] = $onepage['info']['subtotal'];
			}
			//end kgt - discount coupons
		}
		//EOF KGT

	//	if ($onepage['customer']['firstname'] == ''){
			$onepage['customer'] = array_merge($onepage['customer'], $onepage['billing']);
//		}

		if ($onepage['delivery']['firstname'] == ''){
			$onepage['delivery'] = array_merge($onepage['delivery'], $onepage['billing']);
		}

		if (ACCOUNT_STATE == 'true') {
			$this->fixZoneName($onepage['customer']['zone_id'],$onepage['customer']['country']['id'],$onepage['customer']['state']);
			$this->fixZoneName($onepage['billing']['zone_id'],$onepage['billing']['country']['id'],$onepage['billing']['state']);
			$this->fixZoneName($onepage['delivery']['zone_id'],$onepage['delivery']['country']['id'],$onepage['delivery']['state']);
		}

		$order->customer = $onepage['customer'];
		$order->billing = $onepage['billing'];
		$order->delivery = $onepage['delivery'];
	}

	function init(){ 
		$this->verifyContents();
		if (!isset($_GET['payment_error'])){
			$this->reset();
		}

		if (STOCK_CHECK == 'true' && STOCK_ALLOW_CHECKOUT != 'true') {
			$this->checkStock();
		}
		        
		$this->setDefaultSendTo();
		$this->setDefaultBillTo();

		$this->removeCCGV(); 
	}

	function checkEmailAddress($emailAddress, $ajax=true){
		$success = 'true';
		$errMsg = '';

		$Qcheck = tep_db_query('select customers_id from ' . TABLE_CUSTOMERS . ' where customers_email_address = "' . tep_db_prepare_input($emailAddress) . '"');
		if (tep_db_num_rows($Qcheck)){
			$success = 'false';
			$errMsg = TEXT_EMAIL_EXISTS.' <a href=login.php?from=/checkout.php>'.TEXT_EMAIL_EXISTS2.'</a> '.TEXT_EMAIL_EXISTS3;
		}else{
			require_once('includes/functions/validations.php');
			if (tep_validate_email($emailAddress) === false){
				$success = 'false';
				$errMsg = TEXT_EMAIL_WRONG;
			}
		}
		if($ajax == true)
		{
			return '{
        "success": "' . $success . '",
        "errMsg": "' . $errMsg . '"
      }';
		}else
		{
			return $success;
		}
	}
	
	function getAjaxStateFieldAddress($manualCid = false, $zone_id=0, $state=''){
		global $onepage;
		$country = $manualCid;
		$name = 'state';
		$key = '';
		$html = '';
		$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
		$check = tep_db_fetch_array($check_query);
		if ($check['total'] > 0) {
			$zones_array = array(
			array('id' => '', 'text' => TEXT_PLEASE_SELECT)
			);
			$zones_query = tep_db_query("select zone_id, zone_code, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' order by zone_name");
			$selected = '';
			while ($zones_values = tep_db_fetch_array($zones_query)) {
				if ($zone_id >0 || !empty($state)){
					if ($zone_id == $zones_values['zone_id']){
						$selected = $zones_values['zone_name'];
					}elseif (!empty($state) && $state == $zones_values['zone_name']){
						$selected = $zones_values['zone_name'];
					}elseif (isset($_POST['curValue']) && $_POST['curValue'] == $zones_values['zone_name']){
						$selected = $zones_values['zone_name'];
					}
				}
				$zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
			}
			$html .= tep_draw_pull_down_menu($name, $zones_array, $selected, 'class="required" style="width:70%;float:left;"');
		} else {
			$html .= tep_draw_input_field($name, (!empty($state) ? $state: ''), 'class="required" style="width:70%;float:left;"');
		}
		return $html;
	}

	function setPaymentMethod($method){
		global $payment_modules, $language, $order, $cart, $payment, $onepage, $customer_shopping_points_spending;
		/* Comment IF statement below for oscommerce versions before MS2.2 RC2a */
//		if (tep_session_is_registered('payment') && tep_not_null($payment) && $payment != $method){
//			$GLOBALS[$payment]->selection();
//		}

		$payment = $method;
		if (!tep_session_is_registered('payment')){
			tep_session_register('payment');
		}
		$onepage['info']['payment_method'] = $method;

		$order->info['payment_method'] = $GLOBALS[$payment]->title;

			/* Comment line below for oscommerce versions before MS2.2 RC2a */
	//		$confirmation = $GLOBALS[$payment]->confirmation();

			/* Uncomment line below for oscommerce versions before MS2.2 RC2a */
			$confirmation = $GLOBALS[$payment]->selection();

		$inputFields = '';
		if ($confirmation !== false){
			for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
				$inputFields .= '<tr>' .
				'<td width="10"></td>' .
				'<td class="main" width="150px">' . $confirmation['fields'][$i]['title'] . '</td>' .
				'<td></td>' .
				'<td class="main" width="350px">' . $confirmation['fields'][$i]['field'] . '</td>' .
				'<td width="10"></td>' .
				'</tr>';
			}

			if ($inputFields != ''){
				$inputFields = '<tr class="paymentFields">' .
				'<td width="10"></td>' .
				'<td colspan="2"><table border="0" cellspacing="0" cellpadding="2">' .
				$inputFields .
				'</table></td>' .
				'<td width="10"></td>' .
				'</tr>';
			}
		}

	// raid ------ минимальный заказ!!!---------------- //	
     //         if(MIN_ORDER>$cart->show_total()) $r_minsum = '<input type="hidden" id="minsum" value="'.MIN_ORDER.'" />';
     //         else $r_minsum = '';
     //        $inputFields .= $r_minsum;
	// raid ------ минимальный заказ!!!---------------- //	
		
    $_SESSION['payment'] = $spayment;   
    $_SESSION['onepage'] = $onepage;
    
$input_fields = array($inputFields);  
		return '{
      "success": "true",
      "inputFields": ' . json_encode($input_fields) . '
    }';
	}


	function setShippingMethod($method = ''){
		global $shipping_modules, $language, $order, $cart, $shipping, $onepage;
    
		if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') {
			$pass = false;

			switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
				case 'national':
					if ($order->delivery['country_id'] == STORE_COUNTRY) {
						$pass = true;
					}
					break;
				case 'international':
					if ($order->delivery['country_id'] != STORE_COUNTRY) {
						$pass = true;
					}
					break;
				case 'both':
					$pass = true;
					break;
			}

			// disable free shipping for Alaska and Hawaii
			$zone_code = tep_get_zone_code($order->delivery['country']['id'], $order->delivery['zone_id'], '');
			if(in_array($zone_code, array('AK', 'HI'))) {
				$pass = false;
			}

			$free_shipping = false;
			if ($pass == true && $order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) {
				$free_shipping = true;
				include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
			}
		} else {
			$free_shipping = false;
		}

		if (!tep_session_is_registered('shipping')){
			tep_session_register('shipping');
		}
		$shipping = false;
		$onepage['info']['shipping_method'] = false;

		if (tep_count_shipping_modules() > 0 || $free_shipping == true) {
			if (strpos($method, '_')) {
				$shipping = $method;

				list($module, $method) = explode('_', $shipping);
				global $$module;
				if (is_object($$module) || $shipping == 'free_free') {
					$quote = $shipping_modules->quote($method, $module);

					if (isset($quote['error'])) {
						unset($shipping);
					} else {
						if (isset($quote[0]['methods'][0]['title']) && isset($quote[0]['methods'][0]['cost']) || $shipping == 'free_free') {
							$shipping = array(
							'id' => $shipping,
				//			'title' => (($shipping == 'free_free') ?  FREE_SHIPPING_TITLE : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
							'title' => (($shipping == 'free_free') ?  FREE_SHIPPING_TITLE : $quote[0]['module'] . ''),
							'cost' => (($shipping == 'free_free')?'0':$quote[0]['methods'][0]['cost'])
							);
							$onepage['info']['shipping_method'] = $shipping;
						}
					}
				} else {
					unset($shipping);
				}
			}
		}  
    $_SESSION['shipping'] = $shipping;  
    $_SESSION['onepage'] = $onepage; 
		return '{
        "success": "true"
      }';
	}

	function setCheckoutAddress($action){
		global $order, $onepage,$customer_id;
		if ($action == 'setSendTo' && !tep_not_null($_POST['shipping_country'])){
			$prefix = 'billing_';
		}else{
			$prefix = ($action == 'setSendTo' ? 'shipping_' : 'billing_');
		}


		if (ACCOUNT_GENDER == 'true') $gender = $_POST[$prefix . 'gender'];
		if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST[$prefix . 'company']);
		if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST[$prefix . 'suburb']);

		if (!isset($_POST[$prefix . 'zipcode'])){
			if(ONEPAGE_AUTO_SHOW_BILLING_SHIPPING == 'True'){
				$zip_code = tep_db_prepare_input(ONEPAGE_AUTO_SHOW_DEFAULT_ZIP);
			}
		}else{
			$zip_code = tep_db_prepare_input($_POST[$prefix . 'zipcode']);
		}
		if (!isset($_POST[$prefix . 'country'])){
			if(ONEPAGE_AUTO_SHOW_BILLING_SHIPPING == 'True'){
				$country = tep_db_prepare_input(ONEPAGE_AUTO_SHOW_DEFAULT_COUNTRY);
			}
		}else{
			$country = tep_db_prepare_input($_POST[$prefix . 'country']);
		}
		if (ACCOUNT_STATE == 'true') {
			if (isset($_POST[$prefix . 'zone_id'])) {
				$zone_id = tep_db_prepare_input($_POST[$prefix . 'zone_id']);
			} else {
				if (!isset($_POST[$prefix . 'zone_id'])){
					if(ONEPAGE_AUTO_SHOW_BILLING_SHIPPING == 'True'){
						if($country == ONEPAGE_AUTO_SHOW_DEFAULT_COUNTRY)
						$zone_id = tep_db_prepare_input(ONEPAGE_AUTO_SHOW_DEFAULT_STATE);
					}
				}else{
					$zone_id = false;
				}
			}
			if ($prefix == 'shipping_')
			{
				$state = tep_db_prepare_input($_POST['delivery_state']);
			}
			else
			{
				$state = tep_db_prepare_input($_POST[$prefix . 'state']);
			}
			$zone_name = '';
			$zone_id = 0;
			$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
			$check = tep_db_fetch_array($check_query);
			$entry_state_has_zones = ($check['total'] > 0);
			if ($entry_state_has_zones == true) {
				$zone_query = tep_db_query("select distinct zone_id, zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
				if (tep_db_num_rows($zone_query) == 1) {
					$zone = tep_db_fetch_array($zone_query);
					$zone_id = $zone['zone_id'];
					$zone_name = $zone['zone_name'];
				}
			}
		}

		$QcInfo = tep_db_query('select * from ' . TABLE_COUNTRIES . ' where countries_id = "' . $country . '"');
		$cInfo = tep_db_fetch_array($QcInfo);
		if ($action == 'setBillTo')
		{
			$varName = 'billing';
			if (ACCOUNT_DOB == 'true' && tep_not_null($_POST[$prefix . 'dob'])) $dob = $_POST[$prefix . 'dob'];
		}
		else
		{
			$varName = 'delivery';
		}
		if ($action == 'setBillTo'){
    
				$order->customer['nif'] = tep_db_prepare_input($_POST['billing_nif']);
				$onepage['customer']['nif'] = tep_db_prepare_input($_POST['billing_nif']);
        
			if (ACCOUNT_DOB == 'true'){
				$dob = tep_db_prepare_input($_POST[$prefix . 'dob']);
				$order->customer['dob'] = $dob;
				$onepage['customer']['dob'] = $dob;
			}
			if (tep_not_null($_POST['billing_email_address'])){
				$order->customer['email_address'] = tep_db_prepare_input($_POST['billing_email_address']);
				$onepage['customer']['email_address'] = $order->customer['email_address'];
			}
			if (tep_not_null($_POST['billing_telephone'])){
				$order->customer['telephone'] = tep_db_prepare_input($_POST['billing_telephone']);
				$onepage['customer']['telephone'] = $order->customer['telephone'];
			}
			
			if (tep_not_null($_POST['password'])){
				$onepage['customer']['password'] = tep_encrypt_password($_POST['password']);
			}
		}


		$order->{$varName}['gender'] = $gender;
		$order->{$varName}['firstname'] =  $_POST[$prefix . 'firstname'];
//		$onepage['customer']['firstname'] = $order->customer['firstname'];
//		$order->{$varName}['firstname'] = $_POST['r_firstname'];
		$order->{$varName}['lastname'] = $_POST[$prefix . 'lastname'];
		$order->{$varName}['company'] = $_POST[$prefix . 'company'];
    $order->{'billing'}['nif'] = $_POST['billing_nif'];
		$order->{$varName}['street_address'] = $_POST[$prefix . 'street_address'];
		$order->{$varName}['suburb'] = $suburb;
		$order->{$varName}['city'] = $_POST[$prefix . 'city'];
		$order->{$varName}['postcode'] = $zip_code;
		$order->{$varName}['state'] = ((isset($zone_name) && tep_not_null($zone_name)) ? $zone_name : $state);
		$order->{$varName}['zone_id'] = $zone_id;
		$order->{$varName}['country'] = array(
		'id'         => $cInfo['countries_id'],
		'title'      => $cInfo['countries_name'],
		'iso_code_2' => $cInfo['countries_iso_code_2'],
		'iso_code_3' => $cInfo['countries_iso_code_3']
		);
		$order->{$varName}['country_id'] = $cInfo['countries_id'];
		$order->{$varName}['format_id'] = $cInfo['address_format_id'];
		if ($action == 'setSendTo' && !tep_not_null($_POST['shipping_firstname'])){
			$onepage['customer'] = array_merge($onepage['customer'], $order->billing);
		}
    $onepage['customer'] = array_merge($onepage['customer'],  array('customer_id'=>$customer_id));  // raid
    
		$onepage[$varName] = array_merge($onepage[$varName], $order->{$varName});

    $_SESSION['onepage'] = $onepage;

		return '{
        "success": "true"
      }';          
	}

	function setAddress($addressType, $addressID){
		global $billto, $sendto, $customer_id, $onepage;
		switch($addressType){
			case 'billing':
				$billto = $addressID;
				if (!tep_session_is_registered('billto')) tep_session_register('billto');
				$sessVar = 'billing';
				break;
			case 'shipping':
				$sendto = $addressID;
				if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
				$sessVar = 'delivery';
				break;
		}

		$Qaddress = tep_db_query('select ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_NIF, ab.entry_street_address, ab.entry_suburb, ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name, ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, c.address_format_id, ab.entry_state from ' . TABLE_ADDRESS_BOOK . ' ab left join ' . TABLE_ZONES . ' z on (ab.entry_zone_id = z.zone_id) left join ' . TABLE_COUNTRIES . ' c on (ab.entry_country_id = c.countries_id) where ab.customers_id = "' . (int)$customer_id . '" and ab.address_book_id = "' . (int)$addressID . '"');
		$address = tep_db_fetch_array($Qaddress);

		$onepage[$sessVar] = array_merge($onepage[$sessVar], array(
		'firstname' => $address['entry_firstname'], 'lastname'       => $address['entry_lastname'],
		'company'   => $address['entry_company'],   'street_address' => $address['entry_street_address'],
		'suburb'    => $address['entry_suburb'],    'city'           => $address['entry_city'], 'nif'           => $address['entry_NIF'],
		'postcode'  => $address['entry_postcode'],  'state'          => $address['entry_state'],
		'zone_id'   => $address['entry_zone_id'],   'country' => array(
		'id'         => $address['countries_id'],         'title'      => $address['countries_name'],
		'iso_code_2' => $address['countries_iso_code_2'], 'iso_code_3' => $address['countries_iso_code_3']
		),
		'country_id' => $address['entry_country_id'], 'format_id' => $address['address_format_id']
		));

		if (ACCOUNT_STATE == 'true') {
			$this->fixZoneName($onepage[$sessVar]['zone_id'],$onepage[$sessVar]['country']['id'],$onepage[$sessVar]['state']);
		}

		return '{
      "success": "true"
    }';
	}

	function saveAddress($action){
		global $customer_id;
		if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
		if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
    $nif = tep_db_prepare_input($_POST['nif']);
		$firstname = tep_db_prepare_input($_POST['firstname']);
		$lastname = tep_db_prepare_input($_POST['lastname']);
		$street_address = tep_db_prepare_input($_POST['street_address']);
		if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
		$postcode = tep_db_prepare_input($_POST['postcode']);
		$city = tep_db_prepare_input($_POST['city']);
		$country = tep_db_prepare_input($_POST['country']);
		if (ACCOUNT_STATE == 'true') {
			if (isset($_POST['zone_id'])) {
				$zone_id = tep_db_prepare_input($_POST['zone_id']);
			} else {
				$zone_id = false;
			}
			$state = tep_db_prepare_input($_POST['state']);

			$zone_id = 0;
			$check_query = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
			$check = tep_db_fetch_array($check_query);
			$entry_state_has_zones = ($check['total'] > 0);
			if ($entry_state_has_zones == true) {
				$zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
				if (tep_db_num_rows($zone_query) == 1) {
					$zone = tep_db_fetch_array($zone_query);
					$zone_id = $zone['zone_id'];
				}
			}
		}

		$sql_data_array = array(
		'customers_id'         => $customer_id,
		'entry_firstname'      => $firstname,
		'entry_lastname'       => $lastname,
		'entry_street_address' => $street_address,
		'entry_postcode'       => $postcode,
		'entry_city'           => $city,
    'entry_NIF'           => $nif,
		'entry_country_id'     => $country
		);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
		if (ACCOUNT_STATE == 'true') {
			if ($zone_id > 0) {
				$sql_data_array['entry_zone_id'] = $zone_id;
				$sql_data_array['entry_state'] = '';
			} else {
				$sql_data_array['entry_zone_id'] = '0';
				$sql_data_array['entry_state'] = $state;
			}
		}

		if ($action == 'saveAddress'){
			$Qcheck = tep_db_query('select address_book_id from ' . TABLE_ADDRESS_BOOK . ' where address_book_id = "' . $_POST['address_id'] . '" and customers_id = "' . $customer_id . '"');
			if (tep_db_num_rows($Qcheck)){
				tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', 'address_book_id = "' . $_POST['address_id'] . '"');
			}
		}else{
			tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);
		}

		return '{
      "success": "true"
	  
    }';
	}


	function processCheckout(){
		global $customer_id, $comments, $coupon, $order, $currencies, $request_type, $languages_id, $currency,
		$customer_shopping_points_spending, $customer_referral, $cart_PayPal_Standard_ID, $cart_PayPal_IPN_ID,
		$cart_Worldpay_Junior_ID, $shipping, $cartID, $order_total_modules, $onepage, $credit_covers, $payment,
		$payment_modules,$cart,$wishList;

		$comments = tep_db_prepare_input($_POST['comments']);
		if (!tep_session_is_registered('comments')) tep_session_register('comments');
		$onepage['customer']['comments'] = $_POST['comments'];

		if(MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS=='true') {
			$onepage['info']['coupon'] = $order->info['coupon'];
		}  

    $onepage['customer']['newsletter'] = (isset($_POST['billing_newsletter']) ? $_POST['billing_newsletter'] : '0');

    // 16.05.16 перестраховка данных:

		$this->setCheckoutAddress('setSendTo');
    $this->setCheckoutAddress('setBillTo');
    
    $order->customer = array_merge($order->customer,$onepage['customer']);
    $order->delivery = array_merge($order->delivery,$onepage['delivery']);
    $order->billing = array_merge($order->billing,$onepage['billing']);  

    
		if (tep_session_is_registered('customer_id')){
			$onepage['createAccount'] = false;
		}else{
			if (tep_not_null($_POST['password'])){
				$onepage['createAccount'] = true;
				$onepage['customer']['password'] = $_POST['password'];
				$this->createCustomerAccount();
			}elseif (ONEPAGE_ACCOUNT_CREATE == 'create'){
				$onepage['createAccount'] = true;
				$onepage['customer']['password'] = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
				$this->createCustomerAccount();
			}
		}   
			 
		$payment_modules->update_status();
		$paymentMethod = $onepage['info']['payment_method'];

		if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'){
			// Start - CREDIT CLASS Gift Voucher Contribution
			if ($credit_covers) $paymentMethod = 'credit_covers';
			unset($_POST['gv_redeem_code']);
			unset($_POST['gv_redeem_code']);
			$order_total_modules->collect_posts();
			$order_total_modules->pre_confirmation_check();
			// End - CREDIT CLASS Gift Voucher Contribution
		}
		if(($order->info['total']) <=0) //if(($order->info['total'] - $order->info['tax'] - $order->info['shipping_cost']) <=0)
		{
			$payment = '';
			$paymentMethod = '';       
			$onepage['info']['payment_method'] = '';
			//$onepage['info']['order_id'] = '';
		}
		$html = '';
		$hiddenFields = '';
		$infoMsg = 'Please press the continue button to confirm your order.';
		$formUrl = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type);
		if ($paymentMethod != ''){
			if (tep_not_null($GLOBALS[$paymentMethod]->form_action_url)){
				$formUrl = $GLOBALS[$paymentMethod]->form_action_url;
				$infoMsg = 'Please press the continue button to proceed to the payment processors page.';
			}
			
			$GLOBALS[$paymentMethod]->pre_confirmation_check();
			$GLOBALS[$paymentMethod]->confirmation();

			$hiddenFields = $GLOBALS[$paymentMethod]->process_button();

			if (!tep_not_null($GLOBALS[$paymentMethod]->form_action_url)){
				if (tep_not_null($hiddenFields)){
					foreach($_POST as $varName => $val){
						if (is_array($_POST[$varName])){
							foreach($_POST[$varName] as $varName2 => $val2){
								$hiddenFields .= tep_draw_hidden_field($varName2, $val2);
							}
						}else{
							$hiddenFields .= tep_draw_hidden_field($varName, $val);
						}
					}
				}
			}
		}       

	  if (tep_not_null($GLOBALS[$paymentMethod]->form_action_url)){
			$html .= '<html dir="ltr" lang="ru"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>Checkout</title></head><body>';
      
      $html .= '<form name="redirectForm" action="' . $formUrl . '" method="POST">
	           	<noscript>' . $infoMsg . tep_image_submit('button_continue.gif',IMAGE_CONTINUE) . '</noscript>' . 
			tep_image_submit('button_continue.gif',IMAGE_CONTINUE,'style="display:none;"') . $hiddenFields . 
			'<script>
	           		document.write(\'<div style="width:100%;height:100%;margin-left:auto;margin-top:auto;text-align:center"><img src="' . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES . 'ajax-loader.gif"><br>'.TEXT_ORDER_PROCESSING.'</div>\');
	            		setTimeout("redirectForm.submit()", 1);  
	           	</script></form>';
              
	    $html .= '</body></html>';
	   
			return $html; 
		}
	  else {
     include('checkout_process_sm.php');
	  }	 

	}

	function createCustomerAccount(){
		global $currencies, $customer_id, $onepage, $customer_default_address_id, $customer_first_name, $customer_country_id, $customer_zone_id, $languages_id, $sendto, $billto;
		//$this->checkCartValidity();
		if ($onepage['createAccount'] === true && $this->checkEmailAddress($onepage['customer']['email_address'])){
			$sql_data_array = array(
			'customers_firstname'     => $onepage['billing']['firstname'],
			'customers_lastname'      => $onepage['billing']['lastname'],
			'customers_email_address' => $onepage['customer']['email_address'],
			'customers_telephone'     => $onepage['customer']['telephone'],
			'customers_fax'           => $onepage['customer']['fax'],
			'customers_newsletter'    => $onepage['customer']['newsletter'],
			'customers_password'      => tep_encrypt_password($onepage['customer']['password'])
			);

			if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $onepage['billing']['gender'];
			if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($onepage['customer']['dob']);

			tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);
			$customer_id = tep_db_insert_id();

			$sql_data_array = array(
			'customers_id'         => $customer_id,
			'entry_firstname'      => $onepage['billing']['firstname'],
			'entry_lastname'       => $onepage['billing']['lastname'],
			'entry_street_address' => $onepage['billing']['street_address'],
			'entry_postcode'       => $onepage['billing']['postcode'],
			'entry_city'           => $onepage['billing']['city'],
      'entry_NIF'           => $onepage['billing']['nif'],
			'entry_country_id'     => $onepage['billing']['country_id']
			);

			if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $onepage['billing']['gender'];
			if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $onepage['billing']['company'];
			if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $onepage['billing']['suburb'];

			tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

			$address_id = tep_db_insert_id();
			$billto = $address_id;
			$sendto = $address_id;

			$customer_default_address_id = $address_id;
			$customer_first_name = $onepage['billing']['firstname'];
			$customer_country_id = $onepage['billing']['country_id'];
			$customer_zone_id = $zone_id;

			tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
			tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

			$Qcustomer = tep_db_query('select customers_firstname, customers_lastname, customers_email_address from ' . TABLE_CUSTOMERS . ' where customers_id = "' . $customer_id . '"');
			$customer = tep_db_fetch_array($Qcustomer);

			// build the message content
			$name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];

			if (ACCOUNT_GENDER == 'true') {
				if ($sql_data_array['entry_gender'] == ''){
					$email_text = sprintf(EMAIL_GREET_NONE, $customer['customers_firstname'] . ' ' . $customer['customers_lastname']);
				}elseif ($sql_data_array['entry_gender'] == 'm') {
					$email_text = sprintf(EMAIL_GREET_MR, $customer['customers_lastname']);
				} else {
					$email_text = sprintf(EMAIL_GREET_MS, $customer['customers_lastname']);
				}
			} else {
				$email_text = sprintf(EMAIL_GREET_NONE, $customer['customers_firstname']);
			}

			$email_text .= EMAIL_WELCOME;

			$email_text .= ' <br>' .
			TEXT_EMAIL_LOGIN.': ' . $onepage['customer']['email_address'] . ':<br>' .
			TEXT_EMAIL_PASS.': ' . $onepage['customer']['password'] . '<br><br>';

			$email_text .= EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;

			$onepage['createAccount'] = false;
			tep_mail($name, $customer['customers_email_address'], EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

	//		if (isset($onepage['info']['order_id'])){
	//			tep_db_query('update ' . TABLE_ORDERS . ' set customers_id = "' . $customer_id . '" where orders_id = "' . $onepage['info']['order_id'] . '"');
	//			unset($onepage['info']['order_id']);
	//		}
			if (!tep_session_is_registered('customer_id')) tep_session_register('customer_id');
			if (!tep_session_is_registered('customer_default_address_id')) tep_session_register('customer_default_address_id');
			if (!tep_session_is_registered('customer_first_name')) tep_session_register('customer_first_name');
			if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
			if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');
			if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
			if (!tep_session_is_registered('billto')) tep_session_register('billto');

			if (!tep_session_is_registered('customer_default_address_id')) tep_session_register('customer_default_address_id');
			if (!tep_session_is_registered('customer_first_name')) tep_session_register('customer_first_name');
			if (!tep_session_is_registered('customer_country_id')) tep_session_register('customer_country_id');
			if (!tep_session_is_registered('customer_zone_id')) tep_session_register('customer_zone_id');
			if (!tep_session_is_registered('sendto')) tep_session_register('sendto');
			if (!tep_session_is_registered('billto')) tep_session_register('billto');
		}else
		{
			$onepage['createAccount'] = false;
			//tep_redirect(tep_href_link(FILENAME_CHECKOUT,'error='.url_encode('Your email address already exists in our records')));
		}
	}

	function getAddressFormatted($type){
		global $order;
		switch($type){
			case 'sendto':
				$address = $order->delivery;
				break;
			case 'billto':
				$address = $order->billing;
				break;
		}
		if($address['format_id']==''){
			$address['format_id'] = 1;
		}
		return tep_address_format($address['format_id'], $address, false, '', "\n"); 
	}

	function verifyContents(){
		global $cart;
		// if there is nothing in the customers cart, redirect them to the shopping cart page
		if ($cart->count_contents() < 1) {
			tep_redirect(tep_href_link(FILENAME_DEFAULT));
		}
	}

	function checkStock(){
		global $cart;
		$products = $cart->get_products();  
		for ($i=0, $n=sizeof($products); $i<$n; $i++) {
			if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) { 
				tep_redirect(tep_href_link(FILENAME_DEFAULT));
				break;
			}
		}
	}

	function setDefaultSendTo(){
		global $sendto, $customer_id, $customer_default_address_id, $shipping;
		// if no shipping destination address was selected, use the customers own address as default
		if (!tep_session_is_registered('sendto')) {

			$sendto = $customer_default_address_id;
			tep_session_register('sendto');
		} else {
			// verify the selected shipping address
			if ((is_array($sendto) && !tep_not_null($sendto)) || is_numeric($sendto)) {
				$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
				$check_address = tep_db_fetch_array($check_address_query);

				if ($check_address['total'] != '1') {
					$sendto = $customer_default_address_id;
					if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
				}
			}
		}
		$this->setAddress('shipping', $sendto);
	}

	function setDefaultBillTo(){
		global $billto, $customer_id, $customer_default_address_id, $shipping;
		// if no billing destination address was selected, use the customers own address as default
		if (!tep_session_is_registered('billto')) {

			$billto = $customer_default_address_id;
			tep_session_register('billto');
		} else {
			// verify the selected billing address
			if ( (is_array($billto) && !tep_not_null($billto)) || is_numeric($billto) ) {
				$check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
				$check_address = tep_db_fetch_array($check_address_query);

				if ($check_address['total'] != '1') {
					$billto = $customer_default_address_id;
					if (tep_session_is_registered('payment')) tep_session_unregister($payment);
				}
			}
		}
		$this->setAddress('billing', $billto);
	}

	function removeCCGV(){
		global $credit_covers, $cot_gv;
		// Start - CREDIT CLASS Gift Voucher Contribution
		if (tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
		if (tep_session_is_registered('cot_gv')) tep_session_unregister('cot_gv');
		// End - CREDIT CLASS Gift Voucher Contribution
	}

	function decode_post_vars()
	{
		global $_POST;
		$_POST = $this->decode_inputs($_POST);
	}

	function decode_inputs($inputs)
	{
		if (!is_array($inputs) && !is_object($inputs)) {
			if(function_exists('mb_check_encoding') && mb_check_encoding($inputs,'windows-1251'))
	//		return utf8_decode($inputs);    
			return $inputs;
//			return mb_check_encoding($inputs,'windows-1251');
			else
			return $inputs;
		}
		elseif (is_array($inputs))
		{
			reset($inputs);
			while (list($key, $value) = each($inputs)) {
				$inputs[$key] = $this->decode_inputs($value);
			}
			return $inputs;
		}
		else
		{
			return $inputs;
		}
	}
}
?>