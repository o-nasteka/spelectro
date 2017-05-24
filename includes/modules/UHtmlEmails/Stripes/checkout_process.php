<?php
/*
=========================================================================================
This module does one thing: It creates the contents of the html email in the variable $html_email.

If you would like to customize this html email you should first make sure you know a bit about html emails.
Here are some liks that might be interesting for you: 

http://www.xavierfrenette.com/articles/css-support-in-webmail/
http://www.alistapart.com/articles/cssemail/
http://www.campaignmonitor.com/blog/archives/2006/03/a_guide_to_css_1.html
http://www.reachcustomersonline.com/content/2004/11/11/09.27.00/index.php
=========================================================================================
*/
require(DIR_WS_LANGUAGES . $language . '/modules/UHtmlEmails/Standard/checkout_process.php');
$ArrayLNTargets = array("\r\n", "\n\r", "\n", "\r", "\t"); //This will be used for taking away linefeeds with str_replace() throughout the mail. Tabs is invisible so we take them away to

for ($i=0, $n=sizeof($order_totals); $i<$n; $i++) {//walk through the order totals and create a string containing them
	//The title sometimes ends with ':' and somtimes not and sometimes there is a space. Take it all away =)
	$temp_title = trim(strip_tags($order_totals[$i]['title']));
	if(substr($temp_title, -1) == ':'): $temp_title = substr($temp_title, 0, -1); endif;
	
	$HTMLEmailOrderTotals .= '<em>'. $temp_title .': </em><strong>' . strip_tags($order_totals[$i]['text']) . "</strong>\n";
}

//Now we chall create an array with the attribute info for every product.
$HTML_Email_product_attributes = array();

for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
	if (isset($order->products[$i]['attributes'])) {//This product has attributes
		$HTML_Email_product_attributes[$i] ='';//make sure the array position is a string
		for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			if (DOWNLOAD_ENABLED == 'true') {
			  $attributes_query = "select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
								   from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
								   left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
									on pa.products_attributes_id=pad.products_attributes_id
								   where pa.products_id = '" . $order->products[$i]['id'] . "' 
									and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
									and pa.options_id = popt.products_options_id 
									and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
									and pa.options_values_id = poval.products_options_values_id 
									and popt.language_id = '" . $languages_id . "' 
									and poval.language_id = '" . $languages_id . "'";
			  $attributes = tep_db_query($attributes_query);
			} else {
			  $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
			}
			$attributes_values = tep_db_fetch_array($attributes);
			
			$HTML_Email_product_attributes[$i] .= '<br /><em style="font-size:12px;">&nbsp;&nbsp;&nbsp;-'. $attributes_values['products_options_name'] . ':&nbsp;' . $attributes_values['products_options_values_name'] .'</em>';
		}
	}else{ //This product does not have attributes
		$HTML_Email_product_attributes[$i] ='';
	}
}


//Define the background images here
$bg_sides_url = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_MODULES .'UHtmlEmails/'.ULTIMATE_HTML_EMAIL_LAYOUT.'/bg_red.jpg';
$bg_tableheading_url = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_MODULES .'UHtmlEmails/'.ULTIMATE_HTML_EMAIL_LAYOUT.'/bg_black.jpg';

$html_email = '
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body style="margin:0; padding:0;">

<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:black solid 0px; height:100%;">
<tr>
	<td background="'.$bg_sides_url.'">&nbsp;</td>
	<td width="600" align="center">
	<div align="left" style="font-size: 14px; width:600; padding:0 2em; border: black solid 0px; background-color:#FFFFFF; height:100%;">
		
		<font face="Times New Roman, Times, serif" style="font-size:14px;">
		&nbsp;<br />
		<span style="font-size: 24px;">'.UHE_TEXT_DEAR . ' ' . $order->customer['firstname'] . ' ' . $order->customer['lastname'].',</span><br/>
		'.UHE_MESSAGE_GREETING.'<br /><br />
		<font face="Times New Roman, Times, serif" style="font-size:14px;">
		
		<table style="font-size:14px; font-family:\'times\';" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_ORDER_NUMBER.'</strong></font></td>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;">&nbsp;'.$insert_id.'</font></td>
		  </tr>
		  <tr>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_INVOICE_URL.'</strong></font></td>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;">&nbsp;<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) .'">' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) .'</a></font></td>
		  </tr>
		  <tr>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_DATE_ORDERED.'</strong></font></td>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;">&nbsp;'.strftime(DATE_FORMAT_LONG).'</font></td>
		  </tr>
		</table>';
		
		if ($order->info['comments']) {// Add the customers order comments If the exists.
    		$html_email .= '<strong>'.UHE_TEXT_COMMENTS.'</strong><br /><font face="Courier New, Courier, monospace" size="-1">'. str_replace($ArrayLNTargets, '<br />', tep_db_output($order->info['comments'])) .'</font><br />';
  		}
		
		//Now we will add a table containing the products of the order.
		$html_email .='
		<br />
		<font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_ORDER_CONTENTS.'</strong></font><br />
		
        <table style="font-size:14px; font-family:\'times\';" border="0" cellpadding="3" cellspacing="2">
          <tr style="color:#FFFFFF; font-weight:bold;" >
				<td align="left" width="300" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'.UHE_TEXT_PRODUCTS_ARTICLE.'</font></td>
				<td align="left" width="160" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'.UHE_TEXT_PRODUCTS_MODEL.'</font></td>
				<td align="center" width="100" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'.UHE_TEXT_PRODUCTS_PRICE.'</font></td>
				<td align="center" width="40" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'.UHE_TEXT_PRODUCTS_QTY.'</font></td>
				<td align="right" width="100" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'.UHE_TEXT_PRODUCTS_TOTAL.'</font></td>
			</tr>';
			for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {//Add one row for each product. The array $order exists already in checkout_process.php; to which we will include this file.
			$html_email .='
			<tr style="background-color:#DDDDDD;"> 
				<td valign="top" align="left"><font face="Times New Roman, Times, serif" style="font-size:14px;">'.$order->products[$i]['name'] . $HTML_Email_product_attributes[$i].'</font></td>
				<td valign="top" align="left"><font face="Times New Roman, Times, serif" style="font-size:14px;">'.$order->products[$i]['model'].'</font></td>
				<td valign="top" align="center"><font face="Times New Roman, Times, serif" style="font-size:14px;">'.$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], 1).'</font></td>
				<td valign="top" align="center"><font face="Times New Roman, Times, serif" style="font-size:14px;">'.$order->products[$i]['qty'].'</font></td>
				<td valign="top" align="right"><font face="Times New Roman, Times, serif" style="font-size:14px;">'.$currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']).'</font></td>
			</tr>';
			}
		$html_email .='
		</table>
		<font face="Times New Roman, Times, serif" style="font-size:14px;">
		<br />
		'.str_replace($ArrayLNTargets, '<br />', $HTMLEmailOrderTotals).'
		<br />';
		
		if (is_object($$payment)) {
			$html_email .= '<strong>'.UHE_TEXT_PAYMENT_METHOD . ': </strong>'. $order->info['payment_method'] . '<br />';
    		$payment_class = $$payment;
			if ($payment_class->email_footer) { 
      			$html_email .=str_replace($ArrayLNTargets, '<br />', $payment_class->email_footer).'<br />';
    		}else{
				$html_email .= '<br />';
			}
  		}
		
		$html_email .='
		</font>
		<table style="font-size:14px; font-family:\'times\';" border="0" cellpadding="3" cellspacing="2" bgcolor=white>
			<tr style="color:#FFFFFF; font-weight:bold;">';
				if ($order->content_type != 'virtual') {
					$html_email .= '<td width="160" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'. UHE_TEXT_DELIVERY_ADDRESS .'</font></td>';
				}
				$html_email .= '<td width="160" background="'.$bg_tableheading_url.'" bgcolor=black><font face="Times New Roman, Times, serif" style="font-size:14px;">'. UHE_TEXT_BILLING_ADDRESS .'</font></td>
			</tr>
			<tr style="background-color:#DDDDDD;">';
				if ($order->content_type != 'virtual') {
					$html_email .= '<td><font face="Times New Roman, Times, serif" style="font-size:14px;">'. tep_address_label($customer_id, $sendto, 0, '', '<br />') .'</font></td>';
				}
				$html_email .= '<td><font face="Times New Roman, Times, serif" style="font-size:14px;">'. tep_address_label($customer_id, $billto, 0, '', '<br />') .'</font></td>
			</tr>
		</table>
		';

		$html_email .='
		</font>
		<table style="font-size:14px; font-family:\'times\';" border="0" cellpadding="3" cellspacing="2" bgcolor=white>'. UHE_TEXT_FINAL .'</table>';

$html_email .= '
&nbsp;<br />
	</div>
	</td>
	<td background="'.$bg_sides_url.'">&nbsp;</td>
</tr>
</table>

</body>
</html>';

//This erases the newlines =) if this is not done the mail sent will have adittional <br /> in it. 
//Why? Hint: look at the function tep_convert_linefeeds and where it is used.

$html_email = str_replace($ArrayLNTargets, '', $html_email);

?>