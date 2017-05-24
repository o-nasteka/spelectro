<?php
$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define('UHE_GREET_MR', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado Sr. %s,');
define('UHE_GREET_MS', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimada Sra. %s,');
define('UHE_GREET_NONE', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado/a %s,');
define('UHE_PASSWORD_REMINDER_BODY', 'Has solicitado una nueva contraseña desde ' . STORE_NAME . ' para acceder a tu cuenta.' . "<br /><br />" . 'Tu nueva contraseña para acceder a ' . STORE_NAME . ' es:' . "<br /><br />" . '   %s' . "<br /><br />Por favor accede ahora a tu cuenta y cámbiala por una que te sea sencilla de recordar o bien apunta esta en un lugar seguro.<br><br>");
define('UHE_SIGNATURE', '<i><strong>Atentamente</strong>,<br />%s (%s)</i>');
?>