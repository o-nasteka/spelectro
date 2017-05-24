<div id="container_white">
<br/>
<h3 class="heading_title_grey"><?php echo HEADING_TITLE; ?></h3>
<?php
 if (tep_count_customer_orders() > 0) {
?>
 <h4 class="h4mobile"><?php echo OVERVIEW_TITLE; ?></h4>
 <p class="centrar_texto"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . OVERVIEW_SHOW_ALL_ORDERS . '</a>'; ?></p>
<br/>
<h4 class="h4mobile"><?php echo OVERVIEW_PREVIOUS_ORDERS; ?></h4>

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
 
 
 <a  href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>">
 <div class="nodecoration order_preview_item">
 
 <p>
 <strong>
 <?php echo tep_date_short($orders['date_purchased']); ?>	<?php echo '#' . $orders['orders_id']; ?>
 </strong>
 </p>
 <p><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></p>
 <p><?php echo $orders['orders_status_name']; ?></p>
 <p><strong><?php echo $orders['order_total']; ?></strong></p>
 
 
 
 </div>
 </a>
 
 
 
 
 
 
 <?php
 }// end while
 
 
 
 
 } //end orderas greater than 0
 ?>
<br/>
 <h4 class="h4mobile"><?php echo MY_ACCOUNT_TITLE; ?></h4>
<ul class="personal centrar_texto">
    <li><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></li>
    <li><?php echo ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></li>
    <li><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></li>
    <li><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_DELETE, '', 'SSL') . '">' . MY_ACCOUNT_DELETE . '</a>'; ?></li>
</ul>
 <br/>
 
 <h4 class="h4mobile"><?php echo MY_ORDERS_TITLE; ?></h4>
<ul class="mis_pedidos centrar_texto">
    <li><?php echo  ' <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MY_ORDERS_VIEW . '</a>'; ?></li>
</ul>
<br/>
<h4 class="h4mobile"><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></h4>
<ul class="notificaciones centrar_texto">
    <li><?php echo ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></li>
</ul>
 <br/>
 <br/>
 <br/>
 </div>