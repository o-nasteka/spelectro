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
require(DIR_FS_CATALOG . DIR_WS_LANGUAGES . $language . '/modules/UHtmlEmails/Standard/orders.php');
$ArrayLNTargets = array("\r\n", "\n\r", "\n", "\r", "\t"); //This will be used for taking away linefeeds with str_replace() throughout the mail. Tabs is invisible so we take them away to

//Define the background images here
$bg_sides_url = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_MODULES .'UHtmlEmails/'.ULTIMATE_HTML_EMAIL_LAYOUT.'/bg_red.jpg';
$bg_tableheading_url = HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_MODULES .'UHtmlEmails/'.ULTIMATE_HTML_EMAIL_LAYOUT.'/bg_black.jpg';

$html_email = '
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body style="margin:0; padding:0;">

<table width="100%"  border="0" cellpadding="0" cellspacing="0" style="border:black solid 0px; height:100%;">
<tr>
	<td background="'.$bg_sides_url.'">&nbsp;</td>
	<td width="600" align="center">
	<div align="left" style="font-size: 14px; width:600; padding:0 2em; border: black solid 0px; background-color:#FFFFFF; height:100%;">
		<font face="Times New Roman, Times, serif" style="font-size:14px;">
			&nbsp;<br />
			
<span style="font-size: 24px;">'. UHE_TEXT_DEAR .' '. $check_status['customers_name'] .',</span><br/>'. UHE_MESSAGE_GREETING .'<br/><br/>

		<strong>'.UHE_TEXT_STATUS.'</strong> '. $orders_status_array[$status] .'<br /><br />
		</font>
		';
		
		if ($comments != '') {// Add the customers order comments If the exists.
    		$html_email .= '<strong>'.UHE_TEXT_COMMENTS.'</strong><br /><font face="Courier New, Courier, monospace" size="-1">'. str_replace($ArrayLNTargets, '<br />', $comments) .'</font><br /><br />';
  		}
		
		$html_email .='
		<table style="font-size:14px; font-family:\'times\';" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_ORDER_NUMBER.'</strong></font></td>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;">&nbsp;'.$oID.'</font></td>
		  </tr>
		  <tr>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_DATE_ORDERED.'</strong></font></td>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;">&nbsp;'.tep_date_long($check_status['date_purchased']).'</font></td>
		  </tr>
		  <tr>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;"><strong>'.UHE_TEXT_INVOICE_URL.'</strong></font></td>
			<td><font face="Times New Roman, Times, serif" style="font-size:14px;">&nbsp;<a href="' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') .'">' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') .'</a></font></td>
		  </tr>
		</table>
			&nbsp;<br />
			
			
	</div>
	</td>
	<td background="'.$bg_sides_url.'">&nbsp;</td>
</tr>
</table>

</body>
</html>';


$html_email = str_replace($ArrayLNTargets, '', $html_email);
?>