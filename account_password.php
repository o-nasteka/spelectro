<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// needs to be included earlier to set the success message in the messageStack
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_PASSWORD);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    $password_current = tep_db_prepare_input($_POST['password_current']);
    $password_new = tep_db_prepare_input($_POST['password_new']);
    $password_confirmation = tep_db_prepare_input($_POST['password_confirmation']);

    $error = false;

    if (strlen($password_current) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_CURRENT_ERROR);
    } elseif (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR);
    } elseif ($password_new != $password_confirmation) {
      $error = true;

      $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
    }

    if ($error == false) {
      $check_customer_query = tep_db_query("select customers_password from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $check_customer = tep_db_fetch_array($check_customer_query);

      if (tep_validate_password($password_current, $check_customer['customers_password'])) {
// you will need to overwrite some existing code here
// HMCS: Begin Autologon	**********************************************************
        $new_encrypted_password = tep_encrypt_password($password_new);

        tep_db_query("update " . TABLE_CUSTOMERS . " set customers_password = '" . $new_encrypted_password . "' where customers_id = '" . (int)$customer_id . "'");
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

    	if (tep_not_null($_COOKIE['password'])) {   //Autologon, Was it enabled?
          $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
          $cookie_path = $cookie_url_array['path'];
          setcookie('password', $new_encrypted_password, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
        }
// HMCS: End Autologon		**********************************************************

        $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

        tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
      } else {
        $error = true;

        $messageStack->add('account_password', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
      }
    }
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'));
?>



<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>
  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

<?php
  if ($messageStack->size('account_password') > 0) {
    echo $messageStack->output('account_password');
  }
?>

<?php echo tep_draw_form('account_password', tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'), 'post', 'onsubmit="return check_form(account_password);"', true) . tep_draw_hidden_field('action', 'process'); ?>

  <span class="pull-right"><?php echo FORM_REQUIRED_INFORMATION; ?></span>
  <h2><?php echo MY_PASSWORD_TITLE; ?></h2>

  <div class="well">
    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_password_field('password_current', '', 'class="form-control" placeholder="' . ENTRY_PASSWORD_CURRENT . '"'); ?>
      </div>  
     </div>
    
    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_password_field('password_new', '', 'class="form-control" placeholder="' . ENTRY_PASSWORD_NEW . '"'); ?>
      </div>  
    </div>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_password_field('password_confirmation', '', 'class="form-control" placeholder="' . ENTRY_PASSWORD_CONFIRMATION . '"'); ?>
      </div>
    </div>
  </div>

    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', null, 'btn btn-default pull-right'); ?>
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'), 'btn btn-default pull-left'); ?>

</form>
<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require('includes/form_check.js.php'); 
  require(DIR_THEME. 'html/footer.php'); 
?>
