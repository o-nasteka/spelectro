<?php
$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define('UHE_SUBJECT', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Bienvenido a ' . STORE_NAME);
define('UHE_GREET_MR', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado Sr. %s,');
define('UHE_GREET_MS', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimada Sra. %s,');
define('UHE_GREET_NONE', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado/a %s,');
define('UHE_WELCOME', 'Le damos la bienvenida a <strong>' . STORE_NAME . '</strong>.' . "\n\n");

define('UHE_TEXT', 'Ahora puedes disfrutar de los <strong>servicios</strong> que ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><strong>Carrito Permanente</strong> - Cualquier producto añadido a su carrito permanecerá en él hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><strong>Libro de Direcciones</strong> - Podemos enviar sus productos a otras direcciones aparte de la tuya! Esto es perfecto para enviar regalos de cumpleaños directamente a un amigo o ser querido o por cualquier otro motivo. ¡Alégrele el día a alguien mandándole un regalo!' . "\n" . '<li><strong>Historial de Pedidos</strong> - Vea la relación de compras que ha realizado con nosotros.' . "\n" . '<li><strong>Comentarios</strong> - Comparta su opinión sobre los productos con otros clientes.' . "\n\n");

define('UHE_CONTACT', 'Para cualquier consulta sobre nuestros servicios, escríbenos a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");

define('UHE_WARNING', '<strong>Nota:</strong> La dirección de correo electrónico a la que fue enviado este mensaje nos fue suministrada por alguien durante el proceso de registro. Si usted no se ha registrado como cliente de OutletSalud, por favor comuníquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
?>
