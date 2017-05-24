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
require(DIR_WS_LANGUAGES . $language . '/modules/UHtmlEmails/Standard/tell_a_friend.php');
$ArrayLNTargets = array("\r\n", "\n\r", "\n", "\r", "\t"); //This will be used for taking away linefeeds with str_replace() throughout the mail. Tabs is invisible so we take them away to

$html_email = '<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<font face="Times New Roman, Times, serif">
<div style="font-size:14px;">

<span style="font-size: 24px;">'. sprintf(UHE_GREET, $to_name) .'</span><p>'. sprintf(UHE_INTRO, $from_name, $product_info['products_name'], STORE_NAME).'</p>';

if (tep_not_null($message)) {// Add the comments If the exists.
	$html_email .= '<strong>'.sprintf(UHE_TEXT_COMMENTS, $from_name).'</strong><br /><font face="Courier New, Courier, monospace" size="-1">'. str_replace($ArrayLNTargets, '<br />', $message) .'</font><br /><br />';
}

$html_email.= UHE_LINK . '<br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id'], 'NONSSL', false) . '">' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id'], 'NONSSL', false) . '</a><br /><br />' . sprintf(UHE_SIGNATURE, STORE_NAME, HTTP_SERVER . DIR_WS_CATALOG).'

</div>
</font>
</body>
</html>';

$html_email = str_replace($ArrayLNTargets, '', $html_email);

?>