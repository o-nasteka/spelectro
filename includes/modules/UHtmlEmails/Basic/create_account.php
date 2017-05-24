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
require(DIR_WS_LANGUAGES . $language . '/modules/UHtmlEmails/Standard/create_account.php');
$ArrayLNTargets = array("\r\n", "\n\r", "\n", "\r", "\t"); //This will be used for taking away linefeeds with str_replace() throughout the mail. Tabs is invisible so we take them away to

$HTMLMailMainContent = str_replace($ArrayLNTargets, '<br />', UHE_WELCOME . UHE_TEXT . UHE_CONTACT . UHE_WARNING);

      if (ACCOUNT_GENDER == 'true') {
         if ($gender == 'm') {
           $HTMLGreet = sprintf(trim(UHE_GREET_MR), $lastname);
         } else {
           $HTMLGreet = sprintf(trim(UHE_GREET_MS), $lastname);
         }
      } else {
        $HTMLGreet = sprintf(trim(UHE_GREET_NONE), $firstname.' '.$lastname);
      }
	
$html_email = '<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"></head>
<body>
<font face="Times New Roman, Times, serif">
<div style="font-size:14px;">

<span style="font-size: 24px;">'.$HTMLGreet.'</span><br/><br/>'. $HTMLMailMainContent.'

</div>
</font>
</body>
</html>';


$html_email = str_replace($ArrayLNTargets, '', $html_email);

?>