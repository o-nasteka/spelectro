<?php
/*
  $Id: ordercheck_functions.php, v 2.0 20/08/2006 Gnidhal Exp $
  Part of contribution OrdersCheck. 
  This script is not included in the original version of osCommerce

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

//  ------------------------------------------------------------------------------------------
//  data insert functions below.
  if (MODULE_ORDER_TOTAL_INSTALLED) {
   $order_totals = $order_total_modules->process();
   $output_orders_total =  $order_total_modules->output();
  }
// test last orders_id
  $_oders_max_query = tep_db_query("select max(orders_id) as max_id from " . TABLE_ORDERS . "");
  $_oders_max = tep_db_fetch_array($_oders_max_query);
  $_orders_id = $_oders_max["max_id"];

// test last holding_orders_id
  $holding_oders_max_query = tep_db_query("select max(orders_id) as max_id from " . TABLE_HOLDING_ORDERS . "");
  $holding_oders_max = tep_db_fetch_array($holding_oders_max_query);
  $holding_insert_id = $holding_oders_max["max_id"];

// assign last orders_in to prevent duplicate entry 
   $insert_id = ($_orders_id >= $holding_insert_id )? $_orders_id+1 : $holding_insert_id+1 ;

//  ------------------------------------------------------------------------------------------
    $sql_data_array = array(
                            'orders_id' => $insert_id,              
                          	'customers_id' => $customer_id,
                          	'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
                          	'customers_company' => $order->customer['company'],
                          	'customers_street_address' => $order->customer['street_address'],
                          	'customers_suburb' => $order->customer['suburb'],
                          	'customers_city' => $order->customer['city'],
                          	'customers_postcode' => $order->customer['postcode'],
                          	'customers_state' => $order->customer['state'],
                          	'customers_country' => $order->customer['country']['title'],
                          	'customers_telephone' => $order->customer['telephone'],
                          	'customers_email_address' => $order->customer['email_address'],
                          	'customers_address_format_id' => $order->customer['format_id'],
                          
                          	'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
                          	'delivery_company' => $order->delivery['company'],
                          	'delivery_street_address' => $order->delivery['street_address'],
                          	'delivery_suburb' => $order->delivery['suburb'],
                          	'delivery_city' => $order->delivery['city'],
                          	'delivery_postcode' => $order->delivery['postcode'],
                          	'delivery_state' => $order->delivery['state'],
                          	'delivery_country' => $order->delivery['country']['title'],
                          	'delivery_address_format_id' => $order->delivery['format_id'],
                          
                          	'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
                          	'billing_company' => $order->billing['company'],
                          	'billing_street_address' => $order->billing['street_address'],
                          	'billing_suburb' => $order->billing['suburb'],
                          	'billing_city' => $order->billing['city'],
                          	'billing_postcode' => $order->billing['postcode'],
                          	'billing_state' => $order->billing['state'],
                          	'billing_country' => $order->billing['country']['title'],
                          	'billing_address_format_id' => $order->billing['format_id'],
                          
                          	'payment_method' => $order->info['payment_method'],
                          	'cc_type' => $order->info['cc_type'],
                          	'cc_owner' => $order->info['cc_owner'],
                          	'cc_number' => $order->info['cc_number'],
                          	'cc_expires' => $order->info['cc_expires'],
                          	'date_purchased' => 'now()',
                          	
                          	'orders_status' => $order->info['order_status'],
                          	'currency' => $order->info['currency'],
                          	'currency_value' => $order->info['currency_value']
                            );
//save orders data in pre registring table
    tep_db_perform(TABLE_HOLDING_ORDERS, $sql_data_array);


//  ------------------------------------------------------------------------------------------
//  Orders's Total

    for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {
    	$sql_data_array = array(
                          		'orders_id' => $insert_id,
                          		'title' => $order_totals[$i]['title'],
                          		'text' => $order_totals[$i]['text'],
                          		'value' => $order_totals[$i]['value'],
                          		'class' => $order_totals[$i]['code'],
                          		'sort_order' => $order_totals[$i]['sort_order']
                          	);
    	tep_db_perform(TABLE_HOLDING_ORDERS_TOTAL, $sql_data_array);
    }

//  ------------------------------------------------------------------------------------------
//  Time for the Order histroy
    $sql_data_array = array(
                          	'orders_id' => $insert_id,
                          	'orders_status_id' => $order->info['order_status'],
                          	'date_added' => 'now()',
                          	'customer_notified' => $customer_notification,
                          	'comments' => $order->info['comments']
                          );
    tep_db_perform(TABLE_HOLDING_ORDERS_STATUS_HISTORY, $sql_data_array);

// initializing variables
        $products_ordered = '';
        $subtotal = 0;
        $total_tax = 0;

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
//  ------------------------------------------------------------------------------------------
//  Now time for a loopy through the products
	$sql_data_array = array(
                      		'orders_id' => $insert_id,
                      		'products_id' => tep_get_prid($order->products[$i]['id']),
                      		'products_model' => $order->products[$i]['model'],
                      		'products_name' => $order->products[$i]['name'],
                      		'products_price' => $order->products[$i]['price'],
                      		'final_price' => $order->products[$i]['final_price'],
                      		'products_tax' => $order->products[$i]['tax'],
                      		'products_quantity' => $order->products[$i]['qty']
                      	);
	tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS, $sql_data_array);
	$order_products_id = tep_db_insert_id();

//------insert customer choosen option to order--------
	$attributes_exist = '0';
	$products_ordered_attributes = '';
	if (isset($order->products[$i]['attributes'])) {
		$attributes_exist = '1';
		for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			if (DOWNLOAD_ENABLED == 'true') {
				$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                                    from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad on pa.products_attributes_id=pad.products_attributes_id
                                    where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
			} else {
				$attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix 
                                    from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                                    where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
			}
			$attributes_values = tep_db_fetch_array($attributes);

//  ------------------------------------------------------------------------------------------
// The product attributes
			$sql_data_array = array(
                      				'orders_id' => $insert_id,
                      				'orders_products_id' => $order_products_id,
                      				'products_options' => $attributes_values['products_options_name'],
                      				'products_options_values' => $attributes_values['products_options_values_name'],
                      				'options_values_price' => $attributes_values['options_values_price'],
                      				'price_prefix' => $attributes_values['price_prefix']
                      			);
			tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);

			if ((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename'])) {
//  ------------------------------------------------------------------------------------------
// Do we have a downloadable product?
				$sql_data_array = array(
                      					'orders_id' => $insert_id,
                      					'orders_products_id' => $order_products_id,
                      					'orders_products_filename' => $attributes_values['products_attributes_filename'],
                      					'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                      					'download_count' => $attributes_values['products_attributes_maxcount']
                      				);
				tep_db_perform(TABLE_HOLDING_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
			}
//			$products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
		}
	}
//  ------------------------------------------------------------------------------------------
// OHHH, let's not forget what we're going to charge the customer
// 	$total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
// 	$total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
// 	$total_cost += $total_products_price;
// 
// 	$products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . 
// 		' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], 
// 		$order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
}

?>
