<?php
/*
  $Id: default.php,v 1.20 2003/02/14 12:51:58 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('TEXT_MAIN', '');
define('TABLE_HEADING_NEW_PRODUCTS', 'Novos Artigos');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Artigos Esperados Brevemente');
define('TABLE_HEADING_DATE_EXPECTED', 'Data Prevista');

if ( ($category_depth == 'products') || ($_GET['manufacturers_id']) ) {
  define('HEADING_TITLE', 'Categoria');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Modelo');
  define('TABLE_HEADING_PRODUCTS', 'Nome');
  define('TABLE_HEADING_MANUFACTURER', 'Marca');
  define('TABLE_HEADING_QUANTITY', 'Quantidade');
  define('TABLE_HEADING_PRICE', 'Preço');
  define('TABLE_HEADING_WEIGHT', 'Peso');
  define('TABLE_HEADING_BUY_NOW', '<span style="color: #000000;">Comprar</span>');
  define('TEXT_NO_PRODUCTS', 'Não existem artigos nesta categoria.');
  define('TEXT_NO_PRODUCTS2', 'Não existem artigos desta marca.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Nº de Artigos: ');
  define('TEXT_SHOW', '<b>Mostrar:</b>');
  define('TEXT_BUY', 'Comprar 1 \'');
  define('TEXT_NOW', '\' agora');
  define('TEXT_ALL', 'Todos');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', '');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categorias');
}
?>
