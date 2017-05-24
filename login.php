<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) {
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
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
if (($check_customer['member_level']=='0') && ($check_customer['proveedor']=='1')) {
    $error = '1';
	$_GET['login'] = 'invalid';
	$messageStack->add('login', TEXT_NOT_APPROVED);
}else{

// EOF Separate Pricing Per Customer
    if (!tep_db_num_rows($check_customer_query) || $error=='1') {
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

	if (!$passwordgood) {
		$error = true;
	} else {

        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }

// BOF Separate Pricing Per Customer: choice for logging in under any customer_group_id
// note that tax rates depend on your registered address!
if ($_POST['skip'] != 'true' && $_POST['email_address'] == SPPC_TOGGLE_LOGIN_PASSWORD ) {
   $existing_customers_query = tep_db_query("select customers_group_id, customers_group_name from " . TABLE_CUSTOMERS_GROUPS . " order by customers_group_id ");
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
print ("\n<html ");
echo HTML_PARAMS;
print (">\n<head>\n<title>Choose a Customer Group</title>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=");
echo CHARSET;
print ("\"\n<base href=\"");
echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
print ("\">\n<link rel=\"stylesheet\" type=\"text/css\" href=\"stylesheet.css\">\n");
echo '<body bgcolor="#ffffff" style="margin:0">';
print ("\n<table border=\"0\" width=\"100%\" height=\"100%\">\n<tr>\n<td style=\"vertical-align: middle\" align=\"middle\">\n");
echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'));
print ("\n<table border=\"0\" bgcolor=\"#f1f9fe\" cellspacing=\"10\" style=\"border: 1px solid #7b9ebd;\">\n<tr>\n<td class=\"main\">\n");
  $index = 0;
  while ($existing_customers =  tep_db_fetch_array($existing_customers_query)) {
 $existing_customers_array[] = array("id" => $existing_customers['customers_group_id'], "text" => "&#160;".$existing_customers['customers_group_name']."&#160;");
    ++$index;
  }
print ("<h1>Choose a Customer Group</h1>\n</td>\n</tr>\n<tr>\n<td align=\"center\">\n");
echo tep_draw_pull_down_menu('new_customers_group_id', $existing_customers_array, $check_customer['customers_group_id']);
print ("\n<tr>\n<td class=\"main\">&#160;<br />\n&#160;");
print ("<input type=\"hidden\" name=\"email_address\" value=\"".$_POST['email_address']."\">");
print ("<input type=\"hidden\" name=\"skip\" value=\"true\">");
print ("<input type=\"hidden\" name=\"password\" value=\"".$_POST['password']."\">\n</td>\n</tr>\n<tr>\n<td align=\"right\">\n");
echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE);
print ("</td>\n</tr>\n</table>\n</form>\n</td>\n</tr>\n</table>\n</body>\n</html>\n");
exit;
}
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
        tep_redirect($origin_href);
        } else {
			# Inicio, recordar url ultima
			if( tep_session_is_registered('dxUrlLast') && $dxUrlLast != '' )
				tep_redirect( $dxUrlLast );
			elseif ($cart->count_contents() < 1)
			  tep_redirect(tep_href_link(FILENAME_DEFAULT));   
			else
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING));   
			# Fin, recordar url ultima
			
        }

      }
    }
  }
}

  if ($error == true) {
    $messageStack->add('login', TEXT_LOGIN_ERROR);
  }

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));
?>


<?php require(DIR_THEME. 'html/header.php'); ?>

<script language="javascript"><!--
function session_win() {
  window.open("<?php echo tep_href_link(FILENAME_INFO_SHOPPING_CART); ?>","info_shopping_cart","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>
<script language="javascript"><!--
function win_autologon() {
  window.open("<?php echo FILENAME_INFO_AUTOLOGON; ?>","info_autologon","height=460,width=430,toolbar=no,statusbar=no,scrollbars=yes").focus();
}
//--></script>

<?php require(DIR_THEME. 'html/column_left.php'); ?>

<?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>
      <div class="page-header">      
	    <h1><?php echo HEADING_TITLE; ?></h1>
      </div>
<?php
  if ($messageStack->size('login') > 0) {
?>
   <div class="alert alert-danger"><?php echo $messageStack->output('login'); ?></div>
<?php
  }

  if ($cart->count_contents() > 0) {
?>
<div class="alert alert-info"><?php echo TEXT_VISITORS_CART; ?></div>
<?php
  }
?>

  <div class="col-xs-12 col-sm-6">

	<h2><?php echo HEADING_NEW_CUSTOMER; ?></h2>
  	<p><?php echo TEXT_NEW_CUSTOMER . '<br /><br />' . TEXT_NEW_CUSTOMER_INTRODUCTION; ?></p> 
        <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-user', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'btn btn-default pull-right'); ?>

</div>

  <div class="col-xs-12 col-sm-6">
	<h2><?php echo HEADING_RETURNING_CUSTOMER; ?></h2>
    <p><?php echo TEXT_RETURNING_CUSTOMER; ?></p>
    <div class="form-group">

    <label for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
    <?php echo tep_draw_input_field('email_address', '', 'class="form-control"'); ?>
        </div>
      
        <div class="form-group">
    <label for="password"><?php echo ENTRY_PASSWORD; ?></label>
    <?php echo tep_draw_password_field('password', '', 'class="form-control"'); ?>
    
    <?php if((ALLOW_AUTOLOGON != 'false') && ($cookies_on == true)) { ?>
    	<?php echo tep_draw_checkbox_field('remember_me','on', (($password == '') ? false : true)) . '&nbsp;' . ENTRY_REMEMBER_ME; ?>&nbsp;&nbsp;<a href="password_forgotten.php" title="Recuperar Contrase?a"><?php echo TEXT_PASSWORD_FORGOTTEN; ?></a>
    <?php } ?>
    	<?php echo tep_draw_button(IMAGE_BUTTON_LOGIN, 'icon-lock', null, 'btn btn-default pull-right'); ?>
</div>     
</div>
</form>
<?php require(DIR_THEME. 'html/column_right.php'); ?>
<?php require(DIR_THEME. 'html/footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>