<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  // +Country-State Selector

if (isset($_POST['action']) && $_POST['action'] == 'getStates' && isset($_POST['country'])) {
	ajax_get_zones_html(tep_db_prepare_input($_POST['country']), true);
} else {
  // -Country-State Selector
	
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS);

  if (isset($_GET['action']) && ($_GET['action'] == 'deleteconfirm') && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$customer_id . "'");

    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
  }

// error checking when updating or adding an entry
  $process = false;
  if (isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update'))) {
    $process = true;
    $error = false;

    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
    //NIF start
    if (ACCOUNT_NIF == 'true') $nif = tep_db_prepare_input($_POST['nif']);
    //NIF end
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
    }

    if (ACCOUNT_GENDER == 'true') {
      if ( ($gender != 'm') && ($gender != 'f') ) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
      }
    }
    //NIF start
    if (ACCOUNT_NIF == 'true'){
      if (($nif == "") && (ACCOUNT_NIF_REQ == 'true')) {
        $error = true;
        $messageStack->add('addressbook', ENTRY_NO_NIF_ERROR);
      } else if ((strlen($nif) != 9) && ($nif != ""))  {
        $error = true;
        $messageStack->add('addressbook', ENTRY_FORMATO_NIF_ERROR);
      } else if (strlen($nif) == 9) { 
		$nif = strtoupper($nif);
        if(preg_match("/([0-9]{8})([A-Za-z]{1})/", $nif, $regs) ) { //Is a NIF?
          $resto = $regs[1]%23;
          $clave= 'TRWAGMYFPDXBNJZSQVHLCKET';
          if(strtoupper($regs[2])!=$clave[$resto]){
            $error = true;
            $messageStack->add('addressbook', ENTRY_LETRA_NIF_ERROR);
          }
		} else if(preg_match("/([A-HK-NP-S]{1})([0-9]{7})([A-J0-9]{1})/", $nif, $regs) ){
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
              $messageStack->add('addressbook', ENTRY_FORMATO_NIF_ERROR);
			}
		  }else{
			$letras = 'ABCDEFGHIJ';
			if($letras[$resto-1]!=$regs[3]){
	      	  $error = true;
              $messageStack->add('addressbook', ENTRY_FORMATO_NIF_ERROR);
			}
		  }
        } else {
	      $error = true;
          $messageStack->add('addressbook', ENTRY_FORMATO_NIF_ERROR);
		}
      }	
    }
    //NIF end

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_CITY_ERROR);
    }

    if (!is_numeric($country)) {
      $error = true;

      $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
      // +Country-State Selector
      if ($zone_id == 0) {
      // -Country-State Selector

        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('addressbook', ENTRY_STATE_ERROR);
        }
      }
    }

    if ($error == false) {
      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => (int)$country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
      //NIF start
      if (ACCOUNT_NIF == 'true') $sql_data_array['entry_nif'] = $nif;
      //NIF end
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = (int)$zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      if ($_POST['action'] == 'update') {
        $check_query = tep_db_query("select address_book_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['edit'] . "' and customers_id = '" . (int)$customer_id . "' limit 1");
        if (tep_db_num_rows($check_query) == 1) {
          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_GET['edit'] . "' and customers_id ='" . (int)$customer_id . "'");

// reregister session variables
          if ( (isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($_GET['edit'] == $customer_default_address_id) ) {
            $customer_first_name = $firstname;
            $customer_country_id = $country;
            $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
            $customer_default_address_id = (int)$_GET['edit'];

            $sql_data_array = array('customers_firstname' => $firstname,
                                    'customers_lastname' => $lastname,
                                    'customers_default_address_id' => (int)$_GET['edit']);

            if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;

            tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
          }

          $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
        }
      } else {
        if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
          $sql_data_array['customers_id'] = (int)$customer_id;
          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

          $new_address_book_id = tep_db_insert_id();

// reregister session variables
          if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
            $customer_first_name = $firstname;
            $customer_country_id = $country;
            $customer_zone_id = (($zone_id > 0) ? (int)$zone_id : '0');
            if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) $customer_default_address_id = $new_address_book_id;

            $sql_data_array = array('customers_firstname' => $firstname,
                                    'customers_lastname' => $lastname);

            if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
            if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) $sql_data_array['customers_default_address_id'] = $new_address_book_id;

            tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

            $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');
          }
        }
      }

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    //NIF start
    $entry_query = tep_db_query("select entry_gender, entry_company, entry_nif, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$_GET['edit'] . "'");
    //NIF end

    if (!tep_db_num_rows($entry_query)) {
      $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = tep_db_fetch_array($entry_query);
  } elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if ($_GET['delete'] == $customer_default_address_id) {
      $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
      $check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$customer_id . "'");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] < 1) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
      }
    }
  } else {
    $entry = array();
	// +Country-State Selector
    if (!isset($country)) $country = DEFAULT_COUNTRY;
		$entry['entry_country_id'] = $country;
	// -Country-State Selector
  }

  if (!isset($_GET['delete']) && !isset($_GET['edit'])) {
    if (tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
      $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

      tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

  if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $_GET['edit'], 'SSL'));
  } elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'], 'SSL'));
  } else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
  }
?>

<?php require(DIR_THEME. 'html/header.php'); ?>

  if (!isset($_GET['delete'])) {
    include('includes/form_check.js.php');
    require('includes/ajax.js.php');
  }
?>

<?php require(DIR_THEME. 'html/column_left.php'); ?>

<?php if (!isset($_GET['delete'])) echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'onSubmit="return check_form(addressbook);"'); ?>
  
  <div class="page-header">
    <h1><?php if (isset($_GET['edit'])) { echo HEADING_TITLE_MODIFY_ENTRY; } elseif (isset($_GET['delete'])) { echo HEADING_TITLE_DELETE_ENTRY; } else { echo HEADING_TITLE_ADD_ENTRY; } ?></h1>
  </div>
  
<?php
  if ($messageStack->size('addressbook') > 0) {
    echo $messageStack->output('addressbook');
  }
?>

<?php
  if (isset($_GET['delete'])) {
?>

  <h2><?php echo DELETE_ADDRESS_TITLE; ?></h2>

    <p class="alert alert-warning"><i class="icon-warning"></i><?php echo DELETE_ADDRESS_DESCRIPTION; ?></p>

    <p><?php echo tep_address_label($customer_id, $_GET['delete'], true, ' ', '<br />'); ?></p>

  <div>
        <?php echo tep_draw_button(IMAGE_BUTTON_DELETE, 'icon-remove', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&action=deleteconfirm', 'SSL'), 'btn btn-default btn-sm pull-right'); ?>
        <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'), 'btn btn-default btn-sm pull-left'); ?>
  </div>

<?php
  } else {
?>


<?php include(DIR_WS_COMPONENTS . 'address_book_details.php'); ?>

<?php
    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
?>
      <?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $_GET['edit']) . tep_draw_button(IMAGE_BUTTON_UPDATE, 'icon-loop', null, 'btn btn-default pull-right'); ?>
    	
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'), 'btn btn-default pull-left'); ?>

<?php
    } else {
      if (sizeof($navigation->snapshot) > 0) {
        $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
      } else {
        $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
      }
?>

      <?php echo tep_draw_hidden_field('action', 'process') . tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2 ', null, 'btn btn-default pull-right'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', $back_link, 'btn btn-default pull-left'); ?>
<?php
    }
  }
?>
<?php if (!isset($_GET['delete'])) echo '</form>'; ?>

<?php require(DIR_THEME. 'html/column_right.php'); ?>
<?php require(DIR_THEME. 'html/footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
// +Country-State Selector 
}
// -Country-State Selector 
?>