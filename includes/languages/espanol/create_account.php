<?php												

/*
  $Id: create_account.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
define('NAVBAR_TITLE', 'Crear una Cuenta');
define('NAVBAR_TITLE_1', 'Crear una Cuenta');
define('NAVBAR_TITLE_2', 'Proceso');
define('HEADING_TITLE', 'Datos de Mi Cuenta');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><strong>NOTA:</strong></font></small> Si ya ha pasado por este proceso y tiene una cuenta, por favor <a href="%s">entre</a> en ella.');

define('EMAIL_SUBJECT', 'Bienvenido a ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Estimado ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Estimado ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Estimado ' . stripslashes($_POST['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'Le damos la bienvenida a <strong>' . STORE_NAME . '</strong>.' . "\n\n");
define('EMAIL_TEXT', 'Ahora puede disfrutar de los <strong>servicios</strong> que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><strong>Carrito Permanente</strong> - Cualquier producto añadido a su carrito permanecerá en el hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><strong>Libro de Direcciones</strong> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumpleaños directamente a la persona que cumple años.' . "\n" . '<li><strong>Historial de Pedidos</strong> - Vea la relación de compras que ha realizado con nosotros.' . "\n" . '<li><strong>Comentarios</strong> - Comparta su opinión sobre los productos con otros clientes.' . "\n" . '<li><strong>Boletín de Noticias</strong> - subscríbase a nuestro Boletín y estarás al día de todas nuestras ofertas y novedades.' . "\n\n");
define('EMAIL_CONTACT', 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<strong>Nota:</strong> Esta dirección fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuníquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_WELCOME_POINTS', '<li><strong>Programa de Puntos</strong> - Ahora formas parte del programa de Puntos de nuestra web! Al ser un nuevo usuario, nosotros le regalamos %s con un total de %s puntos para que realice su próxima compra valorados en %s .' . "\n" . 'Por favor visita %s y las condiciones de uso.');
define('EMAIL_POINTS_ACCOUNT', 'Cuenta del Sistema de Puntos');
define('EMAIL_POINTS_FAQ', 'Programa de Puntos FAQ');
?>
