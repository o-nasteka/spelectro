<?php
/*
  $Id: advanced_search.php,v 1.13 2002/05/27 13:57:38 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Pesquisa Avançada');
define('HEADING_TITLE', 'Pesquisa Avançada');

define('NAVBAR_TITLE_1', 'Busqueda avanzada');
define('NAVBAR_TITLE_2', 'Resultados da Pesquisa');

define('HEADING_SEARCH_CRITERIA', 'Critério de Pesquisa');
define('HEADING_TITLE_2', 'Resultados da pesquisa');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Pesquisar também nas descrições');
define('ENTRY_CATEGORIES', 'Categorias:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Incluir Sub-Categorias');
define('ENTRY_MANUFACTURERS', 'Marcas:');
define('ENTRY_PRICE_FROM', 'Preço Mínimo:');
define('ENTRY_PRICE_TO', 'Preço Máximo:');
define('ENTRY_DATE_FROM', 'Data Mínima:');
define('ENTRY_DATE_TO', 'Data Máxima:');

define('TEXT_SEARCH_HELP_LINK', '<u>Ajuda</u>');

define('TEXT_ALL_CATEGORIES', 'Todas as Secções');
define('TEXT_ALL_MANUFACTURERS', 'Todas as Marcas');

define('HEADING_SEARCH_HELP', 'Pesquisa Avançada - Ajuda');
define('TEXT_SEARCH_HELP', 'As palavras chave podem ser intercaladas com AND e/ou OR para maior controlo da pesquisa e seus resultados.<br><br>Por exemplo, <u>Kyosho AND Inferno</u> pesquisará todos os artigos que contenham ambas as palavras. No entanto, <u>Kyosho OR Inferno</u>, a pesquisa devolverá resultados de artigos que contenham ambas as palavras ou uma das duas.<br><br>');
define('TEXT_CLOSE_WINDOW', '<u>fechar janela</u>');

define('JS_AT_LEAST_ONE_INPUT', '* Pelo menos um dos seguintes campos têm de ser preenchido:\n    Critério de Pesquisa\n    Data Mínima\n    Data Máxima\n    Preço Mínimo\n    Preço Máximo\n');
define('JS_INVALID_FROM_DATE', '* Data Mínima Inválida\n');
define('JS_INVALID_TO_DATE', '* Data Máxima Inválida\n');
define('JS_TO_DATE_LESS_THAN_FROM_DATE', '* A Data Máxima tem de ser superior à Data Mínima\n');
define('JS_PRICE_FROM_MUST_BE_NUM', '* O Preço Mínimo tem que ser um valor numérico\n');
define('JS_PRICE_TO_MUST_BE_NUM', '* O Preço Máximo tem que ser um valor numérico\n');
define('JS_PRICE_TO_LESS_THAN_PRICE_FROM', '* O Preço Máximo tem de ser superior ou igual ao Preço Mínimo\n');
define('JS_INVALID_KEYWORDS', '* Palavras Chave Inválidas\n');
?>
