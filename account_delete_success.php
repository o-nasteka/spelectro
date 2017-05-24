<?php
/*
  $Id: logoff.php,v 1.12 2003/02/13 03:01:51 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_DELETE_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE);

//  tep_session_destroy();

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');

  $cart->reset();

?>


<?php require(DIR_THEME. 'html/header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php require(DIR_THEME. 'html/column_left.php'); ?>
<!-- body_text //-->
<h1 class="pageHeading"><span><?php echo HEADING_TITLE; ?></span></h1>
<p><?php echo TEXT_MAIN; ?></p>
<div class="botonera"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></div>

<!-- body_text_eof //-->
<?php require(DIR_THEME. 'html/column_right.php'); ?>

<?php require(DIR_THEME. 'html/footer.php'); ?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
