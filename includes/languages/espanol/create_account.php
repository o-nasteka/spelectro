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
define('EMAIL_TEXT', 'Ahora puede disfrutar de los <strong>servicios</strong> que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><strong>Carrito Permanente</strong> - Cualquier producto a�adido a su carrito permanecer� en el hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><strong>Libro de Direcciones</strong> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumplea�os directamente a la persona que cumple a�os.' . "\n" . '<li><strong>Historial de Pedidos</strong> - Vea la relaci�n de compras que ha realizado con nosotros.' . "\n" . '<li><strong>Comentarios</strong> - Comparta su opini�n sobre los productos con otros clientes.' . "\n" . '<li><strong>Bolet�n de Noticias</strong> - subscr�base a nuestro Bolet�n y estar�s al d�a de todas nuestras ofertas y novedades.' . "\n\n");
define('EMAIL_CONTACT', 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<strong>Nota:</strong> Esta direcci�n fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comun�quelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_WELCOME_POINTS', '<li><strong>Programa de Puntos</strong> - Ahora formas parte del programa de Puntos de nuestra web! Al ser un nuevo usuario, nosotros le regalamos %s con un total de %s puntos para que realice su pr�xima compra valorados en %s .' . "\n" . 'Por favor visita %s y las condiciones de uso.');
define('EMAIL_POINTS_ACCOUNT', 'Cuenta del Sistema de Puntos');
define('EMAIL_POINTS_FAQ', 'Programa de Puntos FAQ');
?>
