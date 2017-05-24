<?php
/*
  $Id: shop_by_price.php,v 2.5 2008/03/07 $
  
  Contribution by Meltus  http://www.highbarn-consulting.com
  Adapted for OsCommerce MS2 by Sylvio Ruiz suporte@leilodata.com
  Modified by Hugues Deriau on 09/23/2006 - display the price ranges in the selected currency
  Modified by Glassraven for dropdown list 24/10/2006 www.glassraven.com
  Modified by -GuiGui- (http://www.gpuzin.com) - 07/03/2008 - fix the title and work with the contribution " Product Listing in Columns"
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$sel_currency = array();

$price_ranges = Array( 	"Menos de " .  $currencies->format(50) ,
						"De " . $currencies->format(50) . " a " . $currencies->format(100),
						"De " . $currencies->format(100). " a " . $currencies->format(200),
						"De " . $currencies->format(200). " a " . $currencies->format(300),
						"Mas de " . $currencies->format(300));

$price_min = Array(  0,
                    50,
					100,
					200,
					300,
					0);
$price_max = Array( 50,
                    100,
					200,
					300,
					0,
					0);

define('NAVBAR_TITLE', 'Productos por Precio');
// the following $range references come from catalog/shop_by_price.php
if ( isset($price_ranges[$range]) )
	define('HEADING_TITLE', 'Precio:' . $price_ranges[$range]);
else
	define('HEADING_TITLE', 'Productos por Precio');



define('BOX_HEADING_SHOP_BY_PRICE', 'Productos por Precio');
define('TABLE_HEADING_BUY_NOW', 'Compre Ahora');
define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
define('TABLE_HEADING_MODEL', 'Modelo');
define('TABLE_HEADING_PRICE', 'Precio');
define('TABLE_HEADING_PRODUCTS', 'Nombre del Producto');
define('TABLE_HEADING_QUANTITY', 'Cantidad');
define('TABLE_HEADING_WEIGHT', 'Peso');
define('TEXT_NO_PRODUCTS', '<p align="center"><br />Ningun producto encontrado en:' . $price_ranges[$range] . '.<br />Pruebe otra opcion!<br /><br /></p>');
// Product Listing in Columns - Start (You can remove those 3 lines if you are not using it).
define('TABLE_HEADING_MULTIPLE', 'Cantidad: ');
// Product Listing in Columns - End
?>