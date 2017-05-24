<?php
$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define ('UHE_SUBJECT', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Welcome to'. STORE_NAME);
define ('UHE_GREET_MR', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Dear Mr.% s, ');
define ('UHE_GREET_MS', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Dear Ms.% s, ');
define ('UHE_GREET_NONE', '<a href="'.$url.'"> <img src="'.$logo.'" border="0"> </ a> <br /> Dear% s, ');
define ('UHE_WELCOME', 'Welcome to <strong>'. STORE_NAME. '</ strong>.'. "\ n \ n");
define ('UHE_TEXT', 'Now you can enjoy the services <strong> </ strong> we offer. Some of these services include:'. "\ n \ n". '<li> <strong> Permanent Cart </ strong> - Any products added to your online cart remain there until you remove it, or until you make the purchase. '. "\ n".' <li> <strong> Address Book </ strong> - We can ship their products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves. '. "\ n".' <li> <strong> Order History </ strong> - View your history of purchases you have made with us. '. "\ n".' <li> <strong> Comments </ strong> - Share your opinions on products with our other customers. '. "\ n \ n");
define ('UHE_CONTACT', 'For any inquiries about our services, please write to:'. STORE_OWNER_EMAIL_ADDRESS. '.'. "\ n \ n");
define ('UHE_WARNING', '<strong> Note: </ strong> This address was given by one of our customers. If you have not signed up as a customer, please tell'. STORE_OWNER_EMAIL_ADDRESS. '.'. "\ n ");
?>
