<?php
/*
  $Id: catalog_products_with_images.php V 3.5
  by Tom St.Croix <managememt@betterthannature.com> V 3.5

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('TOP_BAR_TITLE', 'Printable Catalog');
//define('HEADING_TITLE', '<table border="0" width="100%" cellspacing="3" cellpadding="3"><tr><td width="30%" class="pageHeading"><h5>' . STORE_NAME_ADDRESS . '</h5></td><td width="70%"><h6><u>Note</U>:<br /> All prices are subject to change without notice. All products will be billed in Canadian Currency only. The Currency feature is just for your convenience. This catalog is current on the day of printing.</h6></td></tr></table>');
define('HEADING_TITLE', '<h6>'.STORE_NAME.'<br />'.nl2br(STORE_NAME_ADDRESS).'<br />Email: <a href="mailto:'.STORE_OWNER_EMAIL_ADDRESS.'">'.STORE_OWNER_EMAIL_ADDRESS.'</a><br />Note: All prices are subject to change without notice. All products will be billed in our shop Currency only.<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The Currency feature is just for your convenience. This catalog is current on the day of printing.</h6>');
// comment the above line and uncomment out the line above it if you have an older OSC Release before Nov1 2002
define('TABLE_HEADING_IMAGES', 'Image');
define('TABLE_HEADING_MANUFACTURERS', 'Manufacturer');
define('TABLE_HEADING_PRODUCTS', 'Name');
define('TABLE_HEADING_DESCRIPTION', 'Description');
define('TABLE_HEADING_OPTIONS','Options');
define('TABLE_HEADING_CATEGORIES', 'Category');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_UPC', 'UPC');
define('TABLE_HEADING_QUANTITY', 'Quantity');
define('TABLE_HEADING_WEIGHT', 'Weight');
define('TABLE_HEADING_PRICE', 'Price');
define('BOX_STATS_PRODUCTS_WITH_IMAGES', 'Printable Catalog');
define('FONT_STYLE_TOP_BAR', 'Printable Catalog');
define('NAVBAR_TITLE', 'Printable Catalog');
define('TABLE_HEADING_DATE','Date added'); 
define('BOX_CATALOG_NEXT', 'NEXT');
define('BOX_CATALOG_PREV', 'PREV');
?>
