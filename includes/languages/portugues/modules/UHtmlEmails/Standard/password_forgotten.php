<?php
$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define('UHE_GREET_MR', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado . %s,');
define('UHE_GREET_MS', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado . %s,');
define('UHE_GREET_NONE', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado %s,');
define('UHE_PASSWORD_REMINDER_BODY', 'Solicitou uma nova senha de ' . STORE_NAME . '.' . "<br /><br />" . 'Sua nova senha para acesso à ' . STORE_NAME . ' isto é:' . "<br /><br />" . '   %s' . "<br /><br />");
define('UHE_SIGNATURE', '<i><strong>Atenção</strong>,<br />%s (%s)</i>');
?>
