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
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_NEWSLETTERS);

  $newsletter_query = tep_db_query("select customers_newsletter from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
  $newsletter = tep_db_fetch_array($newsletter_query);

  if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
    if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
      $newsletter_general = tep_db_prepare_input($_POST['newsletter_general']);
    } else {
      $newsletter_general = '0';
    }

    if ($newsletter_general != $newsletter['customers_newsletter']) {
      $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$customer_id . "'");
    }

    $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

    tep_redirect(tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  }

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'));

?>

<?php require(DIR_THEME. 'html/header.php'); ?>

<script language="javascript"><!--
function rowOverEffect(object) {
  if (object.className == "moduleRow") object.className = "moduleRowOver";
}

function rowOutEffect(object) {
  if (object.className == "moduleRowOver") object.className = "moduleRow";
}

function checkBox(object) {
  //document.account_newsletter.elements[object].checked = !document.account_newsletter.elements[object].checked;
}
//--></script>

<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>
  
<?php echo tep_draw_form('account_newsletter', tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'), 'post', 'class="form-group"', true) . tep_draw_hidden_field('action', 'process'); ?>

  <h2><?php echo MY_NEWSLETTERS_TITLE; ?></h2>

  <div class="well">
    <div class="form-group">
      <label><?php echo tep_draw_checkbox_field('newsletter_general', '1', (($newsletter['customers_newsletter'] == '1') ? true : false)); ?></label>
      <?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?></strong><br /><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?>
    </div>
  </div>

    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', null, 'btn btn-default pull-right'); ?>

    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'), 'btn btn-default pull-left'); ?>


</form>
<?php require(DIR_THEME. 'html/column_right.php'); ?>
<?php 
 require(DIR_THEME. 'html/footer.php'); 
 require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
