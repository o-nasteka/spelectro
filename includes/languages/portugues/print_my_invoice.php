<?php
/*
  $Id: print_my_invoice.php,v 6.1 2005/06/05 18:17:59 PopTheTop Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

//// START Edit the following defines to your liking ////

// Footing
define('INVOICE_TEXT_THANK_YOU', 'Gracias por comprar en'); // Printed at the bottom of your invoices
define('STORE_URL_ADDRESS', 'http://www.e-nuc.com'); // Your web address Printed at the bottom of your invoices
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Estado de la Orden:');

// Image Info
define('INVOICE_IMAGE', 'theme/enuc/images/cabecera/logo-trans.png'); //Change this to match your logo image and foler it is in
define('INVOICE_IMAGE_WIDTH', '175'); // Change this to your logo's width
define('INVOICE_IMAGE_HEIGHT', '114'); // Change this to your logo's height
define('INVOICE_IMAGE_ALT_TEXT', STORE_NAME); // Change this to your logo's ALT text or leave blank

// Product Table Info Headings
define('TABLE_HEADING_PRODUCTS_MODEL', 'Modelo'); // Change this to "Model #" or leave it as "SKU #"

//// END Editing the above defines to your liking ////

define('INVOICE_TEXT_INVOICE_NR', 'Factura Nº: ');
define('INVOICE_TEXT_INVOICE_DATE', 'Fecha de Factura: ');
// Misc Invoice Info
define('INVOICE_TEXT_NUMBER_SIGN', '#');
define('INVOICE_TEXT_DASH', '-');
define('INVOICE_TEXT_COLON', ':');

define('INVOICE_TEXT_INVOICE', 'Factura');
define('INVOICE_TEXT_ORDER', 'Pedido');
define('INVOICE_TEXT_DATE_OF_ORDER', 'Fecha de Pedido');
define('INVOICE_TEXT_DATE_DUE_DATE', 'Payment Date');
define('ENTRY_PAYMENT_CC_NUMBER', 'Tarjeta de Credito:');

// Customer Info
define('ENTRY_SOLD_TO', 'Vendido a:');
define('ENTRY_SHIP_TO', 'Enviar a:');
define('ENTRY_PAYMENT_METHOD', 'Metodo de Pago:');

// Product Table Info Headings
define('TABLE_HEADING_PRODUCTS', 'Productos');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Unitario');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'IVA');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'B.I.');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Total');

define('TABLE_HEADING_TAX', 'IVA');
define('TABLE_HEADING_UNIT_PRICE', 'Precio Unitario');
define('TABLE_HEADING_TOTAL', 'Total');

// Order Total Details Info
define('ENTRY_SUB_TOTAL', 'Sub-Total:');
define('ENTRY_SHIPPING', 'Env&iacute;o:');
define('ENTRY_TAX', 'IVA:');
define('ENTRY_TOTAL', 'Total:');

//Order Comments
define('TABLE_HEADING_COMMENTS', 'Comentarios de la orden:');
define('TABLE_HEADING_DATE_ADDED', 'Fecha');
define('TABLE_HEADING_COMMENT_LEFT', 'Comentarios omitidos');
define('INVOICE_TEXT_NO_COMMENT', 'No se han incluido comentarios para este pedido');
?>