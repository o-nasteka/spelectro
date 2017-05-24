<?php
/*
  $Id: account_edit.php,v 1.62 2003/02/13 01:58:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_DELETE);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_DELETE, '', 'SSL'));

    require(DIR_THEME. 'scripts/scripts.php');
    require('includes/form_check.js.php');
?>

<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

  <p class="alert alert-danger"><?php echo TEXT_INFORMATION; ?></p>
  
  <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT_DELETE_PROCESS, '', 'SSL'), 'btn btn-default pull-left'); ?>			
  <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-right2', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'), 'btn btn-default pull-right'); ?>			

<?php
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php'); 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>