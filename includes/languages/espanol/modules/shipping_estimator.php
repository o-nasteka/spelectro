<?php
/*
  $Id: shipping_estimator.php,v 2.20 2004/07/01 15:16:07 eml Exp $

  v2.00 by Acheron
  (see Install.txt for partial version history)

  Copyright (c) 2004

  Released under the GNU General Public License

  azer:
  qq modif
*/

define('CART_ITEM', 'Cantidad:'); // azer for 2.20
  define('CART_SHIPPING_CARRIER_TEXT', 'Carrito');
  define('CART_SHIPPING_OPTIONS', 'Estimación del coste de los Gastos de Envío'); //azer
  define('CART_SHIPPING_OPTIONS_LOGIN', 'Por favor accede a <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Mi Cuenta</u></a>, para mostrar los gastos de envío para tu Zona.<br />&nbsp;<br />');
  define('CART_SHIPPING_METHOD_TEXT','Formas de Envio');
  define('CART_SHIPPING_METHOD_RATES','Rates');
  define('CART_SHIPPING_METHOD_TO','Enviar a: ');
  define('CART_SHIPPING_METHOD_TO_NOLOGIN', '<strong>Enviar a:</strong>&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Acceder</u></a>');
  define('CART_SHIPPING_METHOD_FREE_TEXT','Envío Gratis');
  define('CART_SHIPPING_METHOD_ALL_DOWNLOADS',' (Producto Virtual)');
  define('CART_SHIPPING_METHOD_RECALCULATE','Recalcular');
  define('CART_SHIPPING_METHOD_ADDRESS','Dirección:');
  define('CART_OT','Coste total Estimado'); //tradish
  define('CART_SELECT','Seleccionar');
  define('CART_SELECT_THIS_METHOD','Click aqui para seleccionar esta Forma de Envío.'); // added for 2.10

?>