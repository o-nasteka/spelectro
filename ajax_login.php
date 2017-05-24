<?php
/*
  $Id: login.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
$salida='';
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    $messageStack->add('login', 'Las cookies no estan activadas');
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $password = tep_db_prepare_input($_POST['password']);

// Check if email exists
// BOF Separate Pricing per Customer
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, member_level, proveedor, customers_group_id, customers_password, customers_email_address, customers_default_address_id, customers_specific_taxes_exempt from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
    $check_customer = tep_db_fetch_array($check_customer_query);
// EOF Separate Pricing Per Customer
    if (!tep_db_num_rows($check_customer_query)) {
      $error = true;
    } else {
// Check that password is good
      $mastpw_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MAST_PW'");
   $mastpw = tep_db_fetch_array($mastpw_query);

   $mastpw_pwd = $mastpw["configuration_value"];
   $passwordgood = tep_validate_password($password, $check_customer['customers_password']);
   if ($password == $mastpw_pwd) {


$passwordgood = 1;
} else {
$passwordgood = $passwordgood;
}

        if (($check_customer['member_level'] == 0) && ($check_customer['proveedor']==1)) {
          $_GET['login'] = 'invalid';
          $messageStack->add('login', TEXT_NOT_APPROVED);
        }else{
if (!$passwordgood) {
$error = true;
} else {
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }
}
// BOF Separate Pricing Per Customer: choice for logging in under any customer_group_id
// note that tax rates depend on your registered address!
// EOF Separate Pricing Per Customer: choice for logging in under any customer_group_id

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
// BOF Separate Pricing Per Customer
	      $customers_specific_taxes_exempt = $check_customer['customers_specific_taxes_exempt'];
	if ($_POST['skip'] == 'true' && $_POST['email_address'] == SPPC_TOGGLE_LOGIN_PASSWORD && isset($_POST['new_customers_group_id']))  {
	  $sppc_customer_group_id = $_POST['new_customers_group_id'] ;
	  $check_customer_group_tax = tep_db_query("select customers_group_show_tax, customers_group_tax_exempt, group_specific_taxes_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .(int)$_POST['new_customers_group_id'] . "'");
	} else {
	  $sppc_customer_group_id = $check_customer['customers_group_id'];
	  $check_customer_group_tax = tep_db_query("select customers_group_show_tax, customers_group_tax_exempt, group_specific_taxes_exempt from " . TABLE_CUSTOMERS_GROUPS . " where customers_group_id = '" .(int)$check_customer['customers_group_id'] . "'");
	}
	$customer_group_tax = tep_db_fetch_array($check_customer_group_tax);
	$sppc_customer_group_show_tax = (int)$customer_group_tax['customers_group_show_tax'];
	$sppc_customer_group_tax_exempt = (int)$customer_group_tax['customers_group_tax_exempt'];
	$group_specific_taxes_exempt = $customer_group_tax['group_specific_taxes_exempt'];
	if (tep_not_null($customers_specific_taxes_exempt)) {
		$sppc_customer_specific_taxes_exempt = $customers_specific_taxes_exempt;
	} elseif (tep_not_null($group_specific_taxes_exempt)) {
		$sppc_customer_specific_taxes_exempt = $group_specific_taxes_exempt;
	} else {
		$sppc_customer_specific_taxes_exempt = '';
	}
	// EOF Separate Pricing Per Customer
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
	tep_session_register('sppc_customer_group_id');
	tep_session_register('sppc_customer_group_show_tax');
	tep_session_register('sppc_customer_group_tax_exempt');
	if (tep_not_null($sppc_customer_specific_taxes_exempt)) {
		tep_session_register('sppc_customer_specific_taxes_exempt');
	}
// PriceFormatterStore is already instantiated with the retail customer group id
	if ($sppc_customer_group_id != 0) {
	  unset($pfs);
	  $pfs = new PriceFormatterStore;
	}
// EOF Separate Pricing per Customer
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');

// HMCS: Begin Autologon	**********************************************************
		$cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
		$cookie_path = $cookie_url_array['path'];


        if ((ALLOW_AUTOLOGON == 'false') || ($_POST['remember_me'] == '')) {
              setcookie("email_address", "", time() - 3600, $cookie_path);   // Delete email_address cookie
              setcookie("password", "", time() - 3600, $cookie_path);	       // Delete password cookie
		}
            else {
              setcookie('email_address', $email_address, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
              setcookie('password', $check_customer['customers_password'], time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
		}
// HMCS: End Autologon		**********************************************************

        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

// restore cart contents
        $cart->restore_contents();

        if (sizeof($navigation->snapshot) > 0) {
          $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
          $navigation->clear_snapshot();
          $salida='1';
        } else {
          $salida='1';
        }
      }
    }
  }
if ($salida=='') {
	echo 'Ha ocurrido Error';
}
require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>