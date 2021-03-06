<?php
/*
  $Id: index.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', 'Esta es la configuraci&oacute;n por defecto de osCommerce, los productos mostrados aqui son &uacute;nicamente para demonstraci&oacute;n, <b>cualquier compra realizada no ser&aacute; entregada al cliente, ni se le cobrar&aacute;</b>. Cualquier informaci&oacute;n que vea sobre estos productos debe ser tratada como ficticia.<br /><br />Si desea descargar la soluci&oacute;n que hace posible esta tienda, o si quiere contribuir al proyecto de osCommerce, por favor visite <a href="http://www.oscommerce.com" target="_blank"><u>la web de soporte de osCommerce</u></a>. Esta tienda corre bajo la version <font color="#f0000"><b>' . PROJECT_VERSION . '</b></font>.<br /><br />Este texto se puede cambiar editando el siguiente fichero, uno por cada idioma: [camino&nbsp;al&nbsp;cat&aacute;logo]/includes/languages/[language]/default.php.<br /><br />Puede editarlo manualmente, o a traves de la Herramienta de Administraci&oacute;n con la opci&oacute;n Idiomas->[idioma]->Definir, o utilizando el Herramientas->Administrador de Ficheros.');
define('TABLE_HEADING_NEW_PRODUCTS', 'Novos produtos de %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Brevemente');
define('TABLE_HEADING_DATE_EXPECTED', 'Lan�amento');
define('TABLE_HEADING_DEFAULT_SPECIALS', 'Ofertas');

if ( ($category_depth == 'products') || (isset($_GET['manufacturers_id'])) ) {
  define('HEADING_TITLE', 'A ver que tenemos aqui');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Modelo');
  define('TABLE_HEADING_PRODUCTS', 'Productos');
  define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
  define('TABLE_HEADING_QUANTITY', 'Cantidad');
  define('TABLE_HEADING_PRICE', 'Precio');
  define('TABLE_HEADING_WEIGHT', 'Peso');
  define('TABLE_HEADING_BUY_NOW', 'Compre Ahora');
  define('TEXT_NO_PRODUCTS', 'No hay productos en esta categoria.');
  define('TEXT_NO_PRODUCTS2', 'No hay productos de este fabricante.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'N&uacute;mero de Productos: ');
  define('TEXT_SHOW', '<b>Mostrar:</b>');
  define('TEXT_BUY', 'Compre 1 \'');
  define('TEXT_NOW', '\' ahora');
  define('TEXT_ALL_CATEGORIES', 'Todas');
  define('TEXT_ALL_MANUFACTURERS', 'Todos');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'NUEVOS PRODUCTOS'); //&iquest;Que hay de nuevo por aqui?
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categorias');
}
define('FREE_SHIPPING_FOR_THIS_PRODUCT', '�Env�o Gratuito!');
define('FREE_SHIPPING_FOR_THIS_PRODUCT_FOR_SHOP_COUNTRY', '�Env�o Gratuito! (Solo para Espa�a Peninsular))');
  define('HEADING_TITLE_STAR', 'PRODUCTO ESTRELLA');
  define('HEADING_TITLE_SPECIALS', 'OFERTAS');
  define('HEADING_TITLE_FEATURED', 'PRODUCTOS DESTACADOS');

//aqu&iacute; defines los textos en tu idioma
define('TABLE_HEADING_NEWS', 'Ultimas noticias');
define('TEXT_NEWS', $news_text );

define('TABLE_HEADING_FEATURED_PRODUCTS', 'Productos Destacados');
define('TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY', 'Productos Destacados en %s');

define('TABLE_HEADING_MULTIPLE', 'Cantidad');
?>
