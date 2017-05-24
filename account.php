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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
?>


<?php require(DIR_THEME. 'html/header.php'); ?>

<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>
           
<?php
  if ($messageStack->size('account') > 0) {
    echo $messageStack->output('account');
  }

  if (tep_count_customer_orders() > 0) {
?>
<h2><?php echo OVERVIEW_TITLE; ?></h2>
<p><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . OVERVIEW_SHOW_ALL_ORDERS . '</a>'; ?></p>
<h2><?php echo OVERVIEW_PREVIOUS_ORDERS; ?></h2>
<table class="table table-condensed">
<?php
    $orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1' order by orders_id desc limit 3");
    while ($orders = tep_db_fetch_array($orders_query)) {
      if (tep_not_null($orders['delivery_name'])) {
        $order_name = $orders['delivery_name'];
        $order_country = $orders['delivery_country'];
      } else {
        $order_name = $orders['billing_name'];
        $order_country = $orders['billing_country'];
      }
?>
                  <tr onClick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>'">
                    <td width="80"><?php echo tep_date_short($orders['date_purchased']); ?></td>
                    <td><?php echo '#' . $orders['orders_id']; ?></td>
                    <td><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></td>
                    <td><?php echo $orders['orders_status_name']; ?></td>
                    <td class="text-center"><?php echo $orders['order_total']; ?></td>
                    <td class="text-center"><?php echo tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 'icon-cart', tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'), 'btn btn-default btn-sm') . '</a>'; ?></td>
                  </tr>
<?php
    }
?>
                </table>
<?php
  }
?>
<h2><?php echo MY_ACCOUNT_TITLE; ?></h2>

<ul class="accountLinkList">
    <li><i class="icon-user "></i><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></li>
    <li><i class="icon-home "></i><?php echo ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></li>
    <li><i class="icon-lock "></i><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></li>
    <li><i class="icon-lock "></i><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_DELETE, '', 'SSL') . '">' . MY_ACCOUNT_DELETE . '</a>'; ?></li>
</ul>
<h2><?php echo MY_ORDERS_TITLE; ?></h2>

<ul class="accountLinkList">
    <li><i class="icon-cart "></i><?php echo  ' <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MY_ORDERS_VIEW . '</a>'; ?></li>
</ul>

<h2><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h2>

<ul class="accountLinkList">
    <li><i class="icon-envelop "></i><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></li>
</ul>
<!-- // Points/Rewards Module V2.1rc2a points_system_box_bof //-->
<?php
    if (USE_POINTS_SYSTEM == 'true') {
?>
<h2><?php echo MY_POINTS_TITLE; ?></h2>
<ul class="accountLinkList">
<?php
  $has_points = tep_get_shopping_points($customer_id);
  if ($has_points > 0) {
?>
	<li><?php echo  sprintf(MY_POINTS_CURRENT_BALANCE, number_format($has_points,POINTS_DECIMAL_PLACES),$currencies->format(tep_calc_shopping_pvalue($has_points))); ?></li>
<?php
  }
?>
    <li><?php echo  ' <a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '">' . MY_POINTS_VIEW . '</a>'; ?></li>
    <li><?php echo  ' <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP, '', 'SSL') . '">' . MY_POINTS_VIEW_HELP . '</a>'; ?></li>
</ul>
<?php
  }
?>
<!-- // Points/Rewards Module V2.1rc2a points_system_box_eof //-->
	  <?php
  /****************************************************
      Module de parrainage
  *****************************************************/
  if (defined('MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS') && MODULE_ORDER_TOTAL_SPONSORSHIP_STATUS == 'true') {
?>
<h2><?php echo MY_SPONSORSHIP; ?></h2>
<ul class="accountLinkList">
<?php
    // Parrain du filleul
    $sponsorship_query = tep_db_query("select customers_sponsorship_email as email from " . TABLE_CUSTOMERS_SPONSORSHIP . " where customers_godson_id = '" . (int)$customer_id . "'");
	$sponsorship = tep_db_fetch_array($sponsorship_query);

	if ( tep_not_null($sponsorship['email']) ) {?>
        <li><?php echo ' <strong>' . TEXT_EMAIL_SPONSORSHIP . ' :</strong> ' . $sponsorship['email']; ?></li>
	<?php }

	echo '<li>' . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_SPONSORSHIP, '', 'SSL') . '">' . TEXT_LINK_TO_ALL_GODSON . '</a></li>' . "\n";
?>
</ul>
<?php } // Fin filleul ?>

<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php'); 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>