<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2013 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
// BOF Anti Robot Validation v2.4
  if (ACCOUNT_VALIDATION == 'true' && ACCOUNT_EDIT_VALIDATION == 'true') {
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_VALIDATION);
    include_once('includes/functions/' . FILENAME_ACCOUNT_VALIDATION);
  }
// EOF Anti Robot Registration v2.4

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_EDIT);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $telephone = tep_db_prepare_input($_POST['telephone']);
    $fax = tep_db_prepare_input($_POST['fax']);
    //NIF start
    if (ACCOUNT_NIF == 'true') $nif = tep_db_prepare_input($_POST['nif']);
    //NIF end

    $error = false;

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
      }
    }
    //NIF start
    if (ACCOUNT_NIF == 'true'){
      if (($nif == "") && (ACCOUNT_NIF_REQ == 'true')) {
        $error = true;
        $messageStack->add('account_edit', ENTRY_NO_NIF_ERROR);
      } else if ((strlen($nif) != 9) && ($nif != ""))  {
        $error = true;
        $messageStack->add('account_edit', ENTRY_FORMATO_NIF_ERROR);
      } else if (strlen($nif) == 9) {
		$nif = strtoupper($nif);
        if(preg_match("/([0-9]{8})([A-Za-z]{1})/i", $nif, $regs) ) { //Is a NIF?
          $resto = $regs[1]%23;
          $clave= 'TRWAGMYFPDXBNJZSQVHLCKET';
          if(strtoupper($regs[2])!=$clave[$resto]){
            $error = true;
            $messageStack->add('account_edit', ENTRY_LETRA_NIF_ERROR);
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
              $messageStack->add('account_edit', ENTRY_FORMATO_NIF_ERROR);
			}
		  }else{
			$letras = 'ABCDEFGHIJ';
			if($letras[$resto-1]!=$regs[3]){
	      	  $error = true;
              $messageStack->add('account_edit', ENTRY_FORMATO_NIF_ERROR);
			}
		  }
        } else {
	      $error = true;
          $messageStack->add('account_edit', ENTRY_FORMATO_NIF_ERROR);
		}
      }
    }
    //NIF end

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
    }

    if (ACCOUNT_DOB == 'true') {
      if (!checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))) {
        $error = true;

        $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
    }

    if (!tep_validate_email($email_address)) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }

    $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }

    if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
    }

// BOF Anti Robotic Registration v2.4
    if (ACCOUNT_VALIDATION == 'true' && ACCOUNT_EDIT_VALIDATION == 'true') {
      $sql = "SELECT * FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE session_id = '" . tep_session_id() . "' LIMIT 1";
      if( !$result = tep_db_query($sql) ) {
        $error = true;
        $entry_antirobotreg_error = true;
        $text_antirobotreg_error = ERROR_VALIDATION_1;
      } else {
        $entry_antirobotreg_error = false;
        $anti_robot_row = tep_db_fetch_array($result);
        if (( strtoupper($_POST['antirobotreg']) != $anti_robot_row['reg_key'] ) || ($anti_robot_row['reg_key'] == '') || (strlen($antirobotreg) != ENTRY_VALIDATION_LENGTH)) {
          $error = true;
          $entry_antirobotreg_error = true;
          $text_antirobotreg_error = ERROR_VALIDATION_2;
        } else {
          $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE session_id = '" . tep_session_id() . "'";
          if( !$result = tep_db_query($sql) ) {
            $error = true;
            $entry_antirobotreg_error = true;
            $text_antirobotreg_error = ERROR_VALIDATION_3;
          } else {
            $sql = "OPTIMIZE TABLE " . TABLE_ANTI_ROBOT_REGISTRATION . "";
            if( !$result = tep_db_query($sql) ) {
              $error = true;
              $entry_antirobotreg_error = true;
              $text_antirobotreg_error = ERROR_VALIDATION_4;
            } else {
              $entry_antirobotreg_error = false;
            }
          }
        }
      }
    if ($entry_antirobotreg_error == true) $messageStack->add('account_edit', $text_antirobotreg_error);
    }
// EOF Anti Robotic Registration v2.4
    if ($error == false) {
// BOF Separate Pricing Per Customer
// don't rely on the input field for company_tax_id being there and filled in, the customer might have edited
// his local copy of the page to include that, so we will check for an entry ourselves
    $check_entry_company_tax_id_query = tep_db_query("select entry_company_tax_id from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
    $check_entry_company_tax_id = tep_db_fetch_array($check_entry_company_tax_id_query);
// EOF Separate Pricing Per Customer
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);
// BOF Separate Pricing Per Customer
    if (ACCOUNT_COMPANY == 'true') {
      if (isset($_POST['company_tax_id']) && tep_not_null($_POST['company_tax_id']) && !tep_not_null($check_entry_company_tax_id['entry_company_tax_id'])) {
        $sql_data_array['entry_company_tax_id'] = tep_db_prepare_input($_POST['company_tax_id']);
      }
    }
// EOF Separate Pricing Per Customer

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

      tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname);

      //NIF start
	  if (ACCOUNT_NIF == 'true') $sql_data_array['entry_nif'] = $nif;
	  $sql_data_array['entry_gender'] = $gender;
      //NIF end
      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");
// HMCS: Begin Autologon	**********************************************************
	if (tep_not_null($_COOKIE['email_address'])) {   //Does email address exist in Cookie?
        $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
        $cookie_path = $cookie_url_array['path'];
        setcookie('email_address', $email_address, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
      }
// HMCS: End Autologon		**********************************************************

// reset the session variables
      $customer_first_name = $firstname;

      $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

      tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
    }
  }

  //NIF start
  $account_query = tep_db_query("select c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, c.customers_telephone, c.customers_fax, entry_company_tax_id, a.entry_nif from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$customer_id . "'");
  //NIF end
  $account = tep_db_fetch_array($account_query);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'));
?>

<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require('includes/form_check.js.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>

<?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'), 'post', 'onSubmit="return check_form(account_edit);"') . tep_draw_hidden_field('action', 'process'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>
      
<?php
  if ($messageStack->size('account_edit') > 0) {
    echo $messageStack->output('account_edit');
  }
?>
  <span class="pull-right inputRequirement"><?php echo FORM_REQUIRED_INFORMATION; ?></span>
  <h2><?php echo MY_ACCOUNT_TITLE; ?></h2>
  <div class="form-box">
  
<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($account['customers_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
    <div class="form-group">
      <div class="required">
        <label class="radio-inline"><div class="choice"><span><?php echo tep_draw_radio_field('gender', 'm', $male) . '</span></div>' . MALE . '</label><label class="radio-inline"><div class="choice"><span>' . tep_draw_radio_field('gender', 'f', $female) . '</span></div>' . FEMALE .'</label>'; ?>
      </div>	
    </div>
<?php
  }
?>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('firstname', $account['customers_firstname'], 'class="form-control" placeholder="' . ENTRY_FIRST_NAME . '"'); ?>
      </div>
    </div>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('lastname', $account['customers_lastname'], 'class="form-control" placeholder="' . ENTRY_LAST_NAME . '"'); ?>
      </div>
    </div>

<!--NIF start-->
<?php
  if (ACCOUNT_NIF == 'true') {
?>
    <div class="form-group">
    <?php if (ACCOUNT_NIF_REQ == 'true') { ?> 
    <div class="required"> <?php } ?>
      <?php echo tep_draw_input_field('nif', $account['entry_nif'], 'class="form-control" placeholder="' . ENTRY_NIF . '"'); ?>
      <?php if (ACCOUNT_NIF_REQ == 'true') { ?> 
    </div> <?php } ?>   
    </div>
<?php
  }
?>
<!--NIF end-->
<?php
  if (ACCOUNT_DOB == 'true') {
?>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob']), 'class="form-control" id="dob" placeholder="' . ENTRY_DATE_OF_BIRTH . '"'); ?>
      </div>
    </div>

<?php
  }
?>
    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('email_address', $account['customers_email_address'], 'class="form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"'); ?>
      </div>
    </div>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('telephone', $account['customers_telephone'], 'class="form-control" placeholder="' . ENTRY_TELEPHONE_NUMBER . '"'); ?>
      </div>
    </div>
    
    <div class="form-group">
      <?php echo tep_draw_input_field('fax', $account['customers_fax'], 'class="form-control" placeholder="' . ENTRY_FAX_NUMBER . '"'); ?>
    </div>
    

                 
<?php
// BOF Separate Pricing Per Customer
   if (ACCOUNT_COMPANY == 'true') { 
?>

    <div class="form-group">
        <?php echo tep_draw_input_field('company_tax_id', $account['entry_company_tax_id'], 'class="form-control" placeholder="' . ENTRY_COMPANY_TAX_ID . '"'); ?>
    </div>
                 
                    
<?php 
} // end if (ACCOUNT_COMPANY == 'true')
// EOF Separate Pricing Per Customer
?>      
<!-- // BOF Anti Robot Registration v2.4-->
<?php
  if (ACCOUNT_VALIDATION == 'true' && strstr($PHP_SELF,'account_edit') &&  ACCOUNT_EDIT_VALIDATION == 'true') {
?>
<p class="campo"><strong><?php echo CATEGORY_ANTIROBOTREG; ?></strong>
<?php
    if (ACCOUNT_VALIDATION == 'true' && strstr($PHP_SELF,'account_edit') &&  ACCOUNT_EDIT_VALIDATION == 'true') {
      if ($is_read_only == false || (strstr($PHP_SELF,'account_edit')) ) {
        $sql = "DELETE FROM " . TABLE_ANTI_ROBOT_REGISTRATION . " WHERE timestamp < '" . (time() - 3600) . "' OR session_id = '" . tep_session_id() . "'";
        if( !$result = tep_db_query($sql) ) { die('Could not delete validation key'); }
        $reg_key = gen_reg_key();
        $sql = "INSERT INTO ". TABLE_ANTI_ROBOT_REGISTRATION . " VALUES ('" . tep_session_id() . "', '" . $reg_key . "', '" . time() . "')";
        if( !$result = tep_db_query($sql) ) { die('Could not check registration information'); }
?>
<span class="main">&nbsp;<?php echo ENTRY_ANTIROBOTREG; ?></span>
<?php
          $check_anti_robotreg_query = tep_db_query("select session_id, reg_key, timestamp from anti_robotreg where session_id = '" . tep_session_id() . "'");
          $new_guery_anti_robotreg = tep_db_fetch_array($check_anti_robotreg_query);
          $validation_images = tep_image('validation_png.php?rsid=' . $new_guery_anti_robotreg['session_id']);
          if ($entry_antirobotreg_error == true) {
?>
<span>
<?php
            echo $validation_images . ' <br />&nbsp;';
            echo tep_draw_input_field('antirobotreg') . '&nbsp;<br /><strong><font color="red">' . ERROR_VALIDATION . '<br />' . $text_antirobotreg_error . '</strong></font>';
          } else {
?>
<span>
<?php      
            echo $validation_images . ' <br />&nbsp;';
            echo tep_draw_input_field('antirobotreg', $account['entry_antirobotreg']) . '&nbsp;' . ENTRY_ANTIROBOTREG_TEXT;
          }
        }
      }
?>
</span>
</p>
<?php
    }
?>
</div>
<!-- // EOF Anti Robot Registration v2.4-->

    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', null, 'btn btn-default pull-right'); ?>
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'), 'btn btn-default pull-left'); ?>

</form>

<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php'); 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>