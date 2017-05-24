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

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
?>


<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>
  
<?php
  $orders_total = tep_count_customer_orders();

  if ($orders_total > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1' order by orders_id DESC";
    $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
    $history_query = tep_db_query($history_split->sql_query);

    while ($history = tep_db_fetch_array($history_query)) {
      $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      if (tep_not_null($history['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = $history['delivery_name'];
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = $history['billing_name'];
      }
?>

    <div class="panel panel-default">
      <div class="panel-body">
      <h2><?php echo TEXT_ORDER_NUMBER . ' ' . $history['orders_id'] . ' <span class="contentText">(' . $history['orders_status_name'] . ')</span>'; ?></h2>

        <div class="row">
          <div class="col-xs-12 col-md-5">
            <?php echo '<strong>' . TEXT_ORDER_DATE . '</strong> ' . tep_date_long($history['date_purchased']) . '<br /><strong>' . $order_type . '</strong> ' . tep_output_string_protected($order_name); ?>
          </div>
          <div class="col-xs-9 col-md-5">
            <?php echo '<strong>' . TEXT_ORDER_PRODUCTS . '</strong> ' . $products['count'] . '<br /><strong>' . TEXT_ORDER_COST . '</strong> ' . strip_tags($history['order_total']); ?>
          </div>
          <div class="col-xs-3 col-md-2">
            <?php echo tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 'icon-cart', tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL'), 'btn btn-default btn-sm'); ?>
          </div>
        </div>
      </div>
    </div>

<?php
    }
?>

<div>
    <span class="pull-right"><ul class="pagination">
    <?php echo $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
	  </ul></span>

      <span style="line-height:60px;"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></span>
</div>   

<?php
  } else {
?>

    <p class="alert alert-info text-center"><?php echo TEXT_NO_PURCHASES; ?></p>
  
<?php
  }
?>
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT, '', 'SSL'), 'btn btn-default pull-left'); ?>


<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
