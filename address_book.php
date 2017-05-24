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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

?>


<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

<?php
  if ($messageStack->size('addressbook') > 0) {
    echo $messageStack->output('addressbook');
  }
?>


  <h2><?php echo PRIMARY_ADDRESS_TITLE; ?></h2>

 
    <div class="well">
      <div class="row">
        <div class="col-xs-12 col-md-4 col-md-push-8" style="padding:0;">
          <div class="panel panel-default">
            <div class="panel-heading"><?php echo PRIMARY_ADDRESS_TITLE; ?></div>
            <div class="panel-body">
              <?php echo tep_address_label($customer_id, $customer_default_address_id, true, ' ', '<br />'); ?>
            </div>
          </div>
        </div>
     
        <div class="col-xs-12 col-md-8 col-md-pull-4">
          <?php echo PRIMARY_ADDRESS_DESCRIPTION; ?>
        </div>
      </div>
   </div>

  <h2><?php echo ADDRESS_BOOK_TITLE; ?></h2>

<?php
  //NIF start
  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_nif as nif, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
  //NIF end
  while ($addresses = tep_db_fetch_array($addresses_query)) {
    $format_id = tep_get_address_format_id($addresses['country_id']);
?>

    <div class="well">
         <?php echo tep_draw_button(SMALL_IMAGE_BUTTON_DELETE, 'icon-remove', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL'), 'btn btn-default btn-sm pull-right'); ?>
        <?php echo tep_draw_button(SMALL_IMAGE_BUTTON_EDIT, 'icon-pencil', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'), 'btn btn-default btn-sm pull-right'); ?>
	
      <p><strong><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></strong><?php if ($addresses['address_book_id'] == $customer_default_address_id) echo '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>'; ?></p>
      <p><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br />'); ?></p>
    </div>

<?php
  }
?>

<p class="alert alert-info text-center"><i class="icon-warning"></i><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>

<?php
  if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
?>
    <?php echo tep_draw_button(IMAGE_BUTTON_ADD_ADDRESS, 'icon-home', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'), 'btn btn-default pull-right'); ?>
<?php
  }
?>
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'), 'btn btn-default pull-right'); ?>

</div>

<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php'); 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>