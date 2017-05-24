<?php

if ( ($category_depth == 'products') ) {

define('HEADING_TITLE', 'Listado de art&iacute;culos');
define('NEWS_BOX_CATEGORIES_CHOOSE', 'Seleccione categoria');
define('TABLE_HEADING_IMAGE', 'Imagen');
define('TABLE_HEADING_ARTICLE_NAME', 'Titular');
define('TABLE_HEADING_ARTICLE_SHORTTEXT', 'Sumario');
define('TABLE_HEADING_ARTICLE_DESCRIPTION', 'Contenido');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_DATE_AVAILABLE', 'Fecha');
define('TABLE_HEADING_ARTRICLE_URL', 'URL al recurso exterior');
define('TABLE_HEADING_ARTICLE_URL', 'URL externa');

define('TEXT_NO_ARTICLES', 'No hay art&iacute;culos en esta categor&iacute;a.');

define('TEXT_NUMBER_OF_ARTICLES', 'N&uacute;mero de art&iacute;culos: ');
define('TEXT_SHOW', '<b>Mostrar:</b>');

} elseif ($category_depth == 'top') {

define('HEADING_TITLE', 'Que hay de nuevo aqu&iacute;?');

} elseif ($category_depth == 'nested') {

define('HEADING_TITLE', 'Categor&iacute;as de noticias');

}
define('NEWSDESK_TEXT_FILTER1', 'Filtre Noticias por Categoria');
define('NEWSDESK_TEXT_FILTER2', 'Introduzca Texto de Busqueda');
/*

	osCommerce, Open Source E-Commerce Solutions ---- http://www.oscommerce.com
	Copyright (c) 2002 osCommerce
	Released under the GNU General Public License

	IMPORTANT NOTE:

	This script is not part of the official osC distribution but an add-on contributed to the osC community.
	Please read the NOTE and INSTALL documents that are provided with this file for further information and installation notes.

	script name:    	    	NewsDesk
	version:        		1.48.2
	date:       			06-05-2004 (dd/mm/yyyy)
        original author:		Carsten aka moyashi
        web site:			www..com
	modified code by:		Wolfen aka 241
*/
?>
