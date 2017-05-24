<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
  } else {
    $origin_href = tep_href_link(FILENAME_DEFAULT);
  }
?>

<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

  <p><?php echo TEXT_ACCOUNT_CREATED; ?></p>

<!-- Points/Rewards Module V2.1rc2a bof-->
<?php 
   if ((USE_POINTS_SYSTEM == 'true') && (NEW_SIGNUP_POINT_AMOUNT > 0)) {
?>
<p class="informacion"><?php echo sprintf(TEXT_WELCOME_POINTS_TITLE, '<a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '" title="' . TEXT_POINTS_BALANCE . '">' . TEXT_POINTS_BALANCE . '</a>', number_format(NEW_SIGNUP_POINT_AMOUNT,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT))); ?>.</p>
<p class="informacion"><?php echo sprintf(TEXT_WELCOME_POINTS_LINK, '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=13', 'NONSSL') . '" title="' . BOX_INFORMATION_MY_POINTS_HELP . '">' . BOX_INFORMATION_MY_POINTS_HELP . '</a>'); ?></p>
<?php
   }
?>               
<!-- Points/Rewards Module V2.1rc2a eof-->

    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', $origin_href, 'btn btn-default pull-right'); ?>


<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php');; 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
