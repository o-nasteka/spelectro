<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// HMCS: Begin Autologon	**************************************************************

  $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
  $cookie_path = $cookie_url_array['path'];	
setcookie('email_address', '', time() - 3600, $cookie_path);
setcookie('password', '', time() - 3600, $cookie_path);
  
// HMCS: End Autologon		**************************************************************
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('sppc_customer_group_id');
  tep_session_unregister('sppc_customer_group_show_tax');
  tep_session_unregister('sppc_customer_group_tax_exempt');
  tep_session_unregister('coupon');
  if (tep_session_is_registered('sppc_customer_specific_taxes_exempt')) { tep_session_unregister('sppc_customer_specific_taxes_exempt');
  }
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');
  
  $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
  $cookie_path = $cookie_url_array['path'];

  tep_session_unregister('autologon_executed');
  tep_session_unregister('autologon_link');
  
  if (tep_session_is_registered('customer_shopping_points')) tep_session_unregister('customer_shopping_points');
  if (tep_session_is_registered('customer_shopping_points_spending')) tep_session_unregister('customer_shopping_points_spending');
  if (tep_session_is_registered('customer_referral')) tep_session_unregister('customer_referral');

  $cart->reset();
?>

  <?php require(DIR_THEME. 'html/header.php'); ?>

<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">  
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

  <p><?php echo TEXT_MAIN; ?></p>

    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', tep_href_link(FILENAME_DEFAULT), 'btn btn-default pull-right'); ?>

<?php 
  require(DIR_THEME. 'html/column_right.php');
  require(DIR_THEME. 'html/footer.php');;
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
