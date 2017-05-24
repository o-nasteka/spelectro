<?php
$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define ('UHE_GREET_MR', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Dear Mr.% s, ');
define ('UHE_GREET_MS', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Dear Ms.% s, ');
define ('UHE_GREET_NONE', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Dear% s, ');
define ('UHE_PASSWORD_REMINDER_BODY', 'You requested a new password from'. STORE_NAME. '.'. "<br /> <br />". 'Your new password to access'. STORE_NAME. 'is'. "<br /> <br /> ". '% s'." <br /> <br /> ");
define ('UHE_SIGNATURE', '<i> <strong> Sincerely </ strong> <br />% s (% s) </ i>');
?>
