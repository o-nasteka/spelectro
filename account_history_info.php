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

  if (!isset($_GET['order_id']) || (isset($_GET['order_id']) && !is_numeric($_GET['order_id']))) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
  
  $customer_info_query = tep_db_query("select o.customers_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.orders_id = '". (int)$_GET['order_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1'");
  $customer_info = tep_db_fetch_array($customer_info_query);
  if ($customer_info['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  $breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $_GET['order_id'], 'SSL'));

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order($_GET['order_id']);
  // INIC INCLUYE NUM FAC
  $sql_invoice = "select * from facturas where facturas_pedido_id = '".(int)$oID."'";
  $act_invoice = tep_db_query($sql_invoice) or die($sql_invoice);
  while ($row_sql = tep_db_fetch_array($act_invoice)) {
	$factura = $row_sql['facturas_serie'].'-'.$row_sql['facturas_numero'];
	$fecha = $row_sql['facturas_fecha'];
	$abono = $row_sql['facturas_abono'];
  }
  //END INCLUYE NUM FAC 
?>


<?php require(DIR_THEME. 'html/header.php'); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>
  
  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>
  
  <h2><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <span class="contentText">(' . $order->info['orders_status'] . ')</span>'; ?></h2>

	  		  <?php if ($factura!='') { ?>
			    <h2><?php echo 'FACTURA '.$factura; ?><em> | Pedido Numero: <?php echo $oID; ?></em></h2>    
                	<?php if ($abono == 1) echo '<font color="red"><b>*** ABONO ***</b></font>'; ?>
                <?php echo 'FECHA: '.cambiarFormatoFecha($fecha); ?>
<?php } ?>
            <?php echo HEADING_ORDER_DATE . ' ' . tep_date_long($order->info['date_purchased']); ?><br />
            <?php echo HEADING_ORDER_TOTAL . ' ' . $order->info['total']; ?>
	    
  <div class="row">
    <div class="col-md-4">
	    
<?php
  if ($order->delivery != false) {
?>
        <div class="panel-body"><strong><?php echo HEADING_DELIVERY_ADDRESS; ?></strong><br />
        <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></div>
<?php
  }
?>  
       <div class="panel-body">
         <strong><?php echo HEADING_BILLING_ADDRESS; ?></strong><br />
         <?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?>
       </div>
       
<?php
  if ($order->delivery != false) {
    if (tep_not_null($order->info['shipping_method'])) {
?>
        <div class="panel-body">
          <strong><?php echo HEADING_SHIPPING_METHOD; ?></strong><br />
          <?php echo $order->info['shipping_method']; ?>
        </div>
<?php 
    }
  }
?>        
     
       <div class="panel-body">
        <strong><?php echo HEADING_PAYMENT_METHOD; ?></strong><br />
        <?php echo $order->info['payment_method']; ?>  
       </div>  
    </div>
    
    <div class="col-md-8">
    
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
        <strong><?php echo HEADING_PRODUCTS; ?></strong>
        <strong><?php echo HEADING_TAX; ?></strong>
        <strong><?php echo HEADING_TOTAL; ?></strong>
<?php
  } else {
?>
        <strong><?php echo HEADING_PRODUCTS; ?></strong>
        <br />
<?php
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
            echo '<br />' . $order->products[$i]['qty'] . '&nbsp;x&nbsp;'. $currencies->format($order->products[$i]['price']) .'&nbsp;&nbsp;<strong>'. $order->products[$i]['model'] . '</strong>&nbsp;'. $order->products[$i]['name'] . '';

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small></nobr>';
      }
    }


    if (sizeof($order->info['tax_groups']) > 1) {
      echo '            <span class="pull-right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</span>';
    }
        
            echo '<span class="pull-right">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</span>';
  }
?>
        <hr />
        <div class="clearfix"></div>
<?php
  for ($i=0, $n=sizeof($order->totals); $i<$n; $i++) {
        echo 
             '            <span class="pull-left">' . $order->totals[$i]['title'] . '</span>' . "\n" .
             '            <span class="pull-right">' . $order->totals[$i]['text'] . '</span><br />' . "\n";
  }
?>
 
        <hr />
        <strong><?php echo HEADING_ORDER_HISTORY; ?></strong>
         
          <div class="panel panel-default">
            <div class="panel-body">
<?php
  $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from " . TABLE_ORDERS_STATUS . " os, " . TABLE_ORDERS_STATUS_HISTORY . " osh where osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and osh.customer_notified = 1 and os.language_id = '" . (int)$languages_id . "' and osh.customer_notified = '1' and os.public_flag = '1' order by osh.date_added");
  while ($statuses = tep_db_fetch_array($statuses_query)) {
    echo '          <div><span>' . tep_date_short($statuses['date_added']) . '</span><span>&nbsp;&nbsp;' . $statuses['orders_status_name'] . '</span>
	<br /><span>' . (empty($statuses['comments']) ? '&nbsp;' : nl2br(tep_output_string_protected($statuses['comments']))) . '</span></div>';
  }
?>    
            </div>
          </div>

    </div>
  </div>  

<?php
  if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_COMPONENTS . 'downloads.php');
?>
    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'icon-arrow-left2', tep_href_link(FILENAME_ACCOUNT_HISTORY, tep_get_all_get_params(array('order_id')), 'SSL'), 'btn btn-default pull-left'); ?>
<?php 
  require(DIR_THEME. 'html/column_right.php');
  require(DIR_THEME. 'html/footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>