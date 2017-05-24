<?php
/*
  $Id: my_points.php, v 1.50 2005/AUG/10 15:21:10 dsa_ Exp $
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Informaci&oacute;n de Puntos');

define('HEADING_TITLE', 'Informaci&oacute;n de mis Puntos');

define('HEADING_ORDER_DATE', 'Fecha');
define('HEADING_ORDERS_NUMBER', 'Nº Pedido y Estado');
define('HEADING_ORDERS_STATUS', 'Estado del Pedido');
define('HEADING_POINTS_COMMENT', 'Comentarios');
define('HEADING_POINTS_STATUS', 'Estado de los Puntos');
define('HEADING_POINTS_TOTAL', 'Puntos');

define('TEXT_DEFAULT_COMMENT', 'Puntos por Compra');
define('TEXT_DEFAULT_REDEEMED', 'Puntos Canjeados');

define('TEXT_ORDER_ADMINISTATION', '---');
define('TEXT_STATUS_ADMINISTATION', '-----------');

define('TEXT_POINTS_PENDING', 'Pendiente');
define('TEXT_POINTS_PROCESSING', 'En Proceso');
define('TEXT_POINTS_CONFIRMED', 'Confirmado');
define('TEXT_POINTS_CANCELLED', 'Cancelado');
define('TEXT_POINTS_REDEEMED', 'Canjeado');

define('MY_POINTS_CURRENT_BALANCE', 'El valor actual de tus puntos acumulados es : <strong>%s</strong> puntos. ');
define('MY_POINTS_CURRENT_VALUE', 'Valorados en : <strong>%s</strong>');

define('MY_POINTS_HELP_LINK', ' Por favor, visita nuestro <a href="' . tep_href_link(FILENAME_MY_POINTS_HELP) . '"><u>Ayuda del Sistema de Canjeo de Puntos</u></a> para obtener informaci&oacute;n complementaria.');

define('TEXT_NO_PURCHASES', 'No has hecho ninguna compra, por lo que no tienes de momento ning&uacute;n punto');
define('TEXT_NO_POINTS', 'En estos momentos No tienes puntos aprobados.');

define('TEXT_DISPLAY_NUMBER_OF_RECORDS', 'Mostrando <strong>%d</strong> a <strong>%d</strong> (de <strong>%d</strong> registros)');
?>
