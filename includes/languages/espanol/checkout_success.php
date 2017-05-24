<?php
/*
  $Id: checkout_success.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Pedido');
define('NAVBAR_TITLE_2', 'Realizado con Exito');

define('HEADING_TITLE', '¡Su pedido ha sido realizado con éxito!');

define('TEXT_SUCCESS', '¡Gracias por su pedido!<br><br>Le hemos enviado un email con la confirmación del pedido y  los detalles del pago a su dirección de correo electrónico.<br><br>Si pasados unos minutos no lo ha recibido, busque en la carpeta SPAM, Correo basura o similar e indique que los correos recibidos de <b>spainelectro.com</b> son de total confianza, para que así le puedan llegar todas nuestras notificaciones correctamente. Si aun así no encuentra un email con la confirmación del pedido, póngase en contacto con nosotros. ');
define('TEXT_NOTIFY_PRODUCTS', 'Por favor notifiqueme de cambios realizados a los productos seleccionados:');
define('TEXT_SEE_ORDERS', 'Puede ver sus pedidos viendo la pagina de <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Su Cuenta\'</a> y pulsando sobre <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'Historial\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', 'Dirija sus preguntas al <a href="' . tep_href_link(FILENAME_CONTACT_US) . '">administrador</a>.');
define('TEXT_THANKS_FOR_SHOPPING', '<br>¡Gracias por confiar en spainelectro.com!');

define('TABLE_HEADING_COMMENTS', 'Introduzca un comentario sobre su pedido');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Fecha Caducidad: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' descargas restantes');
define('HEADING_DOWNLOAD', 'Descargue sus productos aqui:');
define('FOOTER_DOWNLOAD', 'Puede descargar sus productos mas tarde en \'%s\'');
?>

	
