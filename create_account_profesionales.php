<?php

  require('includes/application_top.php');

  // +Country-State Selector

if (isset($_POST['action']) && $_POST['action'] == 'getStates' && isset($_POST['country'])) {
	ajax_get_zones_html(tep_db_prepare_input($_POST['country']), 0);
} else {
  // -Country-State Selector
// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);

  $process = false;
  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') {
      if (isset($_POST['gender'])) {
        $gender = tep_db_prepare_input($_POST['gender']);
      } else {
        $gender = false;
      }
    }
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
    $email_address = tep_db_prepare_input($_POST['email_address']);
	if (defined('MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS') && MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS == 'true') {
	  $sponsorship_email = tep_db_prepare_input($_POST['sponsorship_email']);
	}
// BOF Separate Pricing Per Customer, added: field for tax id number
    if (ACCOUNT_COMPANY == 'true') {
    $company = tep_db_prepare_input($_POST['company']);
    $company_tax_id = tep_db_prepare_input($_POST['company_tax_id']);
    }
// EOF Separate Pricing Per Customer, added: field for tax id number
	/* [+] subir el archivo al servidor */
	
	if (is_uploaded_file($HTTP_POST_FILES['iae']['tmp_name'])) {
		$nombre_iae=str_replace('@', '_', $email_address);
		$nombre_iae=str_replace('.', '_', $nombre_iae);
		$partes_ruta = pathinfo($HTTP_POST_FILES['iae']['name']);
		$nombre_iae='_admin/iae/'.$nombre_iae.'.'.$partes_ruta['extension'];
		copy($HTTP_POST_FILES['iae']['tmp_name'], $nombre_iae);
		require("includes/classes/classmail/class.phpmailer.php");	
		$varname = $_FILES['iae']['name'];
		$vartemp = $_FILES['iae']['tmp_name'];
	   
		$mail = new PHPMailer();
		$mail->Host = "localhost";
		$mail->From = STORE_OWNER_EMAIL_ADDRESS;
		$mail->FromName = TITLE;
		$mail->Subject = 'Archivo IAE de '.$email_address;
		$mail->AddAddress(STORE_OWNER_EMAIL_ADDRESS);
		if ($varname != "") {
			$mail->AddAttachment($vartemp, $varname);
		}
		$body = $email_address;
		$mail->Body = $body;
		$mail->IsHTML(true);
		$mail->Send();
	} 
	/* [-] fin de subir archivo al servidor */
    //NIF start
    if (ACCOUNT_NIF == 'true') 
	if ($_POST['cif']!='')
	$nif = tep_db_prepare_input($_POST['cif']);
	else
	$nif = tep_db_prepare_input($_POST['nif']);
    //NIF end
    $street_address = tep_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city = tep_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
      $state = tep_db_prepare_input($_POST['state']);
      if (isset($_POST['zone_id'])) {
        $zone_id = tep_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
    }
    $country = tep_db_prepare_input($_POST['country']);
    $telephone = tep_db_prepare_input($_POST['telephone']);
    $fax = tep_db_prepare_input($_POST['fax']);
    $_POST['newsletter']=1;
	if (isset($_POST['newsletter'])) {
      $newsletter = tep_db_prepare_input($_POST['newsletter']);
    } else {
      $newsletter = false;
    }
    $password = tep_db_prepare_input($_POST['password']);
    $confirmation = tep_db_prepare_input($_POST['confirmation']);

//-----   BEGINNING OF ADDITION: MATC   -----// 
	if (tep_db_prepare_input($_POST['TermsAgree']) != 'true' and MATC_AT_REGISTER != 'false') {
        $error = true;
        $messageStack->add('create_account', MATC_ERROR);
    }
//-----   END OF ADDITION: MATC   -----//

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('create_account', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
    } elseif (tep_validate_email($email_address) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $error = true;

        $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
      }
    }
	
	  /**************************************
      Si le client � renseigner le champs :
	  On v�rifie son int�grit�.
    ***************************************/
	if (defined('MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS') && MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS == 'true') {
	  if ( isset($sponsorship_email) && tep_not_null($sponsorship_email) ) {
	    if (strlen($sponsorship_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_EMAIL_SPONSORSHIP_ERROR);
	    } else if (tep_validate_email($sponsorship_email) == false) {
          $error = true;

          $messageStack->add('create_account', ENTRY_EMAIL_SPONSORSHIP_CHECK_ERROR);
	    }
	  
	    if ($error == false) {
	      // V�rifie si cette adresse est bien dans la base
          $check_email_sponsorship_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($sponsorship_email) . "'");
          $check_email_sponsorship = tep_db_fetch_array($check_email_sponsorship_query);
          if ($check_email_sponsorship['total'] <= 0) {
            $error = true;

            $messageStack->add('create_account', ENTRY_EMAIL_SPONSORSHIP_ERROR_EXISTS);
          }
	    }
	  }
	}
	// Fin

    //NIF start
    if (ACCOUNT_NIF == 'true'){
      if (($nif == "") && (ACCOUNT_NIF_REQ == 'true')) {
        $error = true;
        $messageStack->add('create_account', ENTRY_NO_NIF_ERROR);
      } else if ((strlen($nif) != 9) && ($nif != ""))  {
        $error = true;
        $messageStack->add('create_account', ENTRY_FORMATO_NIF_ERROR);
      } else if (strlen($nif) == 9) { 
		$nif = strtoupper($nif);
        if(ereg("([0-9]{8})([A-Za-z]{1})", $nif, $regs) ) { //Is a NIF?
          $resto = $regs[1]%23;
          $clave= 'TRWAGMYFPDXBNJZSQVHLCKET';
          if(strtoupper($regs[2])!=$clave[$resto]){
            $error = true;
            $messageStack->add('create_account', ENTRY_LETRA_NIF_ERROR);
          }
		} else if(ereg("([A-HK-NP-S]{1})([0-9]{7})([A-J0-9]{1})", $nif, $regs) ){
		  //Maybe it's a CIF
		  $sumapar = $regs[2][1]+$regs[2][3]+$regs[2][5];
		  $sumaimpar = 0;
		  for($ind=0 ; $ind<=6 ; $ind+=2){
			$dobledig = 2 * $regs[2][$ind];
			if($dobledig>=10){ //Suma de los dos d�gitos del n�mero
			  $dobledig = 1 + $dobledig-10;
			}
			$sumaimpar += $dobledig;
		  }
		  $sumaTotal = $sumapar + $sumaimpar;
		  $resto = $sumaTotal%10;
		  $resto = 10 - $resto;
		  if(is_numeric($regs[3])){
			if($resto == 10){ //Si resto = 10 el digito de control es 0.
			  $resto = 0;
			}
			if($regs[3] != $resto){
	      	  $error = true;
              $messageStack->add('create_account', ENTRY_FORMATO_NIF_ERROR);
			}
		  }else{
			$letras = 'ABCDEFGHIJ';
			if($letras[$resto-1]!=$regs[3]){
	      	  $error = true;
              $messageStack->add('create_account', ENTRY_FORMATO_NIF_ERROR);
			}
		  }
        } else {
	      $error = true;
          $messageStack->add('create_account', ENTRY_FORMATO_NIF_ERROR);
		}
      }	
    }
    //NIF end
    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_CITY_ERROR);
    }

    if (is_numeric($country) == false) {
      $error = true;

      $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      // +Country-State Selector
      if ($zone_id == 0) {
      // -Country-State Selector

        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('create_account', ENTRY_STATE_ERROR);
        }
      }
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
    }


    if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
    } elseif ($password != $confirmation) {
      $error = true;

      $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
							  'proveedor' => '1',
							  'proveedor_iae' => $nombre_iae,
                              'customers_newsletter' => $newsletter,
							  'customers_group_id' => '1',
							  'member_level' => '0',
                              'customers_password' => tep_encrypt_password($password));

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
// BOF Separate Pricing Per Customer
   // if you would like to have an alert in the admin section when either a company name has been entered in
   // the appropriate field or a tax id number, or both then uncomment the next line and comment the default
   // setting: only alert when a tax_id number has been given
   //    if ( (ACCOUNT_COMPANY == 'true' && tep_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) ) {
	  if ( ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id)  ) {
      $sql_data_array['customers_group_ra'] = '1';
// entry_company_tax_id moved from table address_book to table customers in version 4.2.0
      $sql_data_array['entry_company_tax_id'] = $company_tax_id; 
    }
// EOF Separate Pricing Per Customer

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      //NIF start
      if (ACCOUNT_NIF == 'true') $sql_data_array['entry_nif'] = $nif;
      //NIF end
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

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

	  	  if (defined('MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS') && MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS == 'true') {
	    if (tep_not_null($sponsorship_email) && tep_not_null($email_address) ) {
	      $cs_query = tep_db_query("select customers_id, customers_gender, customers_lastname, customers_firstname from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($sponsorship_email) . "'");
	      $cs = tep_db_fetch_array($cs_query);
	  
          tep_db_query("insert into " . TABLE_CUSTOMERS_SPONSORSHIP . " (customers_godson_id, customers_sponsorship_id, customers_email_address, customers_sponsorship_email, date_added) values ('" . (int)$customer_id . "', '" . (int)$cs['customers_id'] . "', '" . $email_address . "', '" . $sponsorship_email . "', now())");
	    }
	  }

	  
      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }
// BOF Separate Pricing Per Customer
// register SPPC session variables for the new customer
// if there is code above that puts new customers directly into another customer group (default is retail)
// then the below code need not be changed, it uses the newly inserted customer group
      $check_customer_group_info = tep_db_query("select c.customers_group_id, cg.customers_group_show_tax, cg.customers_group_tax_exempt, cg.group_specific_taxes_exempt from " . TABLE_CUSTOMERS . " c left join " . TABLE_CUSTOMERS_GROUPS . " cg using(customers_group_id) where c.customers_id = '" . $customer_id . "'");
      $customer_group_info = tep_db_fetch_array($check_customer_group_info);
      $sppc_customer_group_id = 1;
      $sppc_customer_group_show_tax = (int)$customer_group_info['customers_group_show_tax'];
      $sppc_customer_group_tax_exempt = (int)$customer_group_info['customers_group_tax_exempt'];
      $sppc_customer_specific_taxes_exempt = '';
      if (tep_not_null($customer_group_info['group_specific_taxes_exempt'])) {
        $sppc_customer_specific_taxes_exempt = $customer_group_info['group_specific_taxes_exempt'];
      }
// EOF Separate Pricing Per Customer

      $customer_first_name = $firstname;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
/*      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');
      tep_session_register('sppc_customer_group_id');
      tep_session_register('sppc_customer_group_show_tax');
      tep_session_register('sppc_customer_group_tax_exempt');
      tep_session_register('sppc_customer_specific_taxes_exempt');*/

// restore cart contents
      $cart->restore_contents();

// build the message content
//---  Beginning of addition: Ultimate HTML Emails  ---//
if (EMAIL_USE_HTML == 'true') {
	require(DIR_WS_MODULES . 'UHtmlEmails/'. ULTIMATE_HTML_EMAIL_LAYOUT .'/create_account.php');
	$email_text = $html_email;
}else{
//---  End of addition: Ultimate HTML Emails  ---//
      $name = $firstname . ' ' . $lastname;

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $email_text = sprintf(EMAIL_GREET_MR, $lastname);
         } else {
           $email_text = sprintf(EMAIL_GREET_MS, $lastname);
         }
      } else {
        $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
      }

      $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
//---  Beginning of addition: Ultimate HTML Emails  ---//
}
if(ULTIMATE_HTML_EMAIL_DEVELOPMENT_MODE === 'true'){
	//Save the contents of the generated html email to the harddrive in .htm file. This can be practical when developing a new layout.
	$TheFileName = 'Last_mail_from_create_account.php.htm';
	$TheFileHandle = fopen($TheFileName, 'w') or die("can't open error log file");
	fwrite($TheFileHandle, $email_text);
	fclose($TheFileHandle);
}
//---  End of addition: Ultimate HTML Emails  ---//
      tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
// BOF Separate Pricing Per Customer: alert shop owner of account created by a company
// if you would like to have an email when either a company name has been entered in
// the appropriate field or a tax id number, or both then uncomment the next line and comment the default
// setting: only email when a tax_id number has been given
//    if ( (ACCOUNT_COMPANY == 'true' && tep_not_null($company) ) || (ACCOUNT_COMPANY == 'true' && tep_not_null($company_tax_id) ) ) {

      $alert_email_text = "Se informa que " . $firstname . " " . $lastname . " de la compa�ia: " . ((empty($company)) ? "-no especificada-" : $company) . " ha creado una cuenta.";
      tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, 'Profesional Registrado', $alert_email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

// EOF Separate Pricing Per Customer: alert shop owner of account created by a company
      tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
    }
  }
 // +Country-State Selector 
if (!isset($country)){$country = DEFAULT_COUNTRY;}
// -Country-State Selector

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('create_account_profesionales.php', '', 'SSL'));
?>

<?php require(DIR_THEME. 'html/header.php'); ?>

<?php 
 // +Country-State Selector 
require('includes/form_check.js.php'); 
require('includes/ajax.js.php'); 
// -Country-State Selector
?>


<!-- header_eof //-->

<!-- body //-->
<!-- left_navigation //-->
<?php require(DIR_THEME. 'html/column_left.php'); ?>
<!-- left_navigation_eof //-->
<!-- body_text //-->

<?php echo tep_draw_form('create_account', tep_href_link('create_account_profesionales.php', '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);" enctype="multipart/form-data"') . tep_draw_hidden_field('action', 'process'); ?>

<h1 class="pageHeading"><span><?php echo HEADING_TITLE; ?></span></h1>
<div class="mensaje info"><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL')); ?></div>
<?php
  if ($messageStack->size('create_account') > 0) {
?>
<div class="alert alert-info"><?php echo $messageStack->output('create_account'); ?></div>

<?php
  }
  ?>
<div class="grupo">  
  <?php
  
   /*************************************
  Champs pour entrer l'email du parrain
  **************************************/
  if (defined('MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS') && MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS == 'true') {
?>
<p class="campo"><label for="sponsorship_email"><?php echo ENTRY_SPONSORSHIP_EMAIL; ?></label> <?php echo tep_draw_input_field('sponsorship_email'); ?></p>
<?php } 
?>
<div class="overflow">  
<h4><?php echo CATEGORY_PERSONAL; ?> <span><?php echo FORM_REQUIRED_INFORMATION; ?></span></h4>
<?php
  if (ACCOUNT_GENDER == 'true') {
?>
<p class="campo"><label for="gender"><?php echo ENTRY_GENDER; ?></label>
									<?php echo tep_draw_radio_field('gender', 'm') . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f') . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></p>
<?php
  }
?>
<p class="campo"><label for="firstname"><?php echo ENTRY_FIRST_NAME; ?></label>
										<?php echo tep_draw_input_field('firstname') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>': ''); ?></p>
                                        
<p class="campo"><label for="lastname"><?php echo ENTRY_LAST_NAME; ?></label>
                <?php echo tep_draw_input_field('lastname') . '&nbsp;' . (tep_not_null(ENTRY_LAST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_LAST_NAME_TEXT . '</span>': ''); ?>
              </p>
<!--NIF start-->
<?php  if (ACCOUNT_NIF == 'true') { ?>
<p class="campo"><label for="nif"><?php echo ENTRY_NIF; ?></label><?php echo tep_draw_input_field('nif') . '&nbsp;' . ((tep_not_null(ENTRY_NIF_TEXT) && (ACCOUNT_NIF_REQ == 'true')) ? '<span class="inputRequirement">' . ENTRY_NIF_TEXT . '</span>': '') . '&nbsp;' . (tep_not_null(ENTRY_NIF_EXAMPLE) ? '<span class="inputRequirement">' . ENTRY_NIF_EXAMPLE . '</span>': ''); ?></p>
<?php  }?>
<!--NIF end-->
<?php
  if (ACCOUNT_DOB == 'true') {
?>
<p class="campo"><label for="dob"><?php echo ENTRY_DATE_OF_BIRTH; ?></label><?php echo tep_draw_input_field('dob') . '&nbsp;' . (tep_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="inputRequirement">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>': ''); ?></p>
<?php
  }
?>
</div>
<div class="overflow"> 
<p class="campo"><label for="email_address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label><?php echo tep_draw_input_field('email_address') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></p>
<p class="campo"><label for="email_address_re">Repita E-Mail</label><?php echo tep_draw_input_field('email_address_re') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>': ''); ?></p>
</div>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>

<div class="overflow">  
<h4><?php echo CATEGORY_COMPANY; ?></h4>
<p class="campo"><label for="company"><?php echo ENTRY_COMPANY; ?></label><?php echo tep_draw_input_field('company') . '&nbsp;' . (tep_not_null(ENTRY_COMPANY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?></p>
<p class="campo"><label for="cif">CIF</label><?php echo tep_draw_input_field('cif'); ?></p>
<p class="campo"><label for="company">IAE o Modelo 036:</label><input type="file" name="iae" id="iae" /></p>
<?php
  }
?>
</div>
<div class="overflow">  
<h4><?php echo CATEGORY_ADDRESS; ?></h4>
<p class="campo"><label for="street_address"><?php echo ENTRY_STREET_ADDRESS; ?></label><?php echo tep_draw_input_field('street_address') . '&nbsp;' . (tep_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_STREET_ADDRESS_TEXT . '</span>': ''); ?></p>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<p class="campo"><label for="suburb"><?php echo ENTRY_SUBURB; ?></label><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></p>
<?php
  }
?>
<p class="campo"><label for="postcode"><?php echo ENTRY_POST_CODE; ?></label><?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>': ''); ?></p>
<div id="indicator"></div>
<p class="campo"><label for="country"><?php echo ENTRY_COUNTRY; ?></label>
                      <?php // +Country-State Selector ?>
                      <?php echo tep_get_country_list('country',$country,'onChange="getStates(this.value, \'states\');"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?></p>
                      <?php // -Country-State Selector ?>
<?php
  if (ACCOUNT_STATE == 'true') {
?>
<p class="campo"><label for="state"><?php echo ENTRY_STATE; ?></label><span id="states">
                          <?php
				// +Country-State Selector
				echo ajax_get_zones_html($country,'',false);
				// -Country-State Selector
				?>
                        </span></p>
<?php
  }
?>
<p class="campo"><label for="city"><?php echo ENTRY_CITY; ?></label><?php echo tep_draw_input_field('city') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>': ''); ?></p>

</div>
<div class="overflow">                        
<h4><?php echo CATEGORY_CONTACT; ?></h4>
<p class="campo"><label for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label><?php echo tep_draw_input_field('telephone') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>': ''); ?></p>
<p class="campo"><label for="fax"><?php echo ENTRY_FAX_NUMBER; ?></label><?php echo tep_draw_input_field('fax') . '&nbsp;' . (tep_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_FAX_NUMBER_TEXT . '</span>': ''); ?></p>
</div>
<div class="overflow">  
<h4><?php echo CATEGORY_PASSWORD; ?></h4>
<p class="campo"><label for="password"><?php echo ENTRY_PASSWORD; ?></label><?php echo tep_draw_password_field('password') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>': ''); ?></p>
<p class="campo"><label for="confirmation"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label><?php echo tep_draw_password_field('confirmation') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>': ''); ?></p>
  </div>    
<?php
//-----   BEGINNING OF ADDITION: MATC   -----// 
if(MATC_AT_REGISTER != 'false'){
	require(DIR_WS_COMPONENTS . 'matc.php');
}
//-----   END OF ADDITION: MATC   -----//
?>

<div class="botonera"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE,'id="TheSubmitButton"'); ?></div>
</div></form>
<!-- body_text_eof //-->

<!-- right_navigation //-->
<?php require(DIR_THEME. 'html/column_right.php'); ?>
<!-- right_navigation_eof //-->
<!-- body_eof //-->

<!-- footer //-->

<?php require(DIR_THEME. 'html/footer.php'); ?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
// +Country-State Selector 
}
// -Country-State Selector 
?>