<?php
/*
  $Id: checkout_payment.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Realizar Pedido');
define('NAVBAR_TITLE_2', 'Forma de Pago');

define('HEADING_TITLE', 'Forma de Pago');

define('TABLE_HEADING_BILLING_ADDRESS', 'Direcci&oacute;n de Facturaci&oacute;n');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Elija la direcci&oacute;n de su libreta donde quiera recibir la factura.');
define('TITLE_BILLING_ADDRESS', 'Direcci&oacute;n de Facturaci&oacute;n:');

define('TABLE_HEADING_PAYMENT_METHOD', 'Forma de Pago');
define('TEXT_SELECT_PAYMENT_METHOD', 'Escoja la forma de pago preferida para este pedido.');
define('TITLE_PLEASE_SELECT', 'Seleccione');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'Esta es la unica forma de pago disponible para este pedido.');

define('TABLE_HEADING_COMMENTS', 'Agregue Los Comentarios Sobre Su Pedido');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continuar con el Proceso de Compra');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'para confirmar este pedido.');
define('TABLE_HEADING_COUPON', '&iquest; Tienes un cup&oacute;n de Descuento?' );
define('TABLE_HEADING_REDEEM_SYSTEM', 'Canjeo de Puntos de Compra');
define('TEXT_REDEEM_SYSTEM_START', 'Tienes  ');
define('TEXT_REDEEM_SYSTEM_MIDDLE', ' Puntos de compra que suponen un total de ');
define('TEXT_REDEEM_SYSTEM_SPENDING', 'Escribe el n&uacute;mero de Puntos que quieres gastar: ');
define('TEXT_REDEEM_SYSTEM_SPENDING_ALL', 'O marca aqu&iacute; para usar todos tus puntos disponibles (solo si es menor que el importe total del pedido):');
//define('TEXT_REDEEM_SYSTEM_SPENDING_EXACT', 'Marca aqu&iacute; para utilizar los Puntos de Compra máximos permitidos. (actualmente  ' . number_format(POINTS_EXACT_VALUE,2) . ' puntos).');
define('REDEEM_SYSTEM_JS_ERROR', 'REDEEM POINTS ERROR ¡\n Valor Incorrecto! \n ¡Se aceptan únicametne números!');
?>
