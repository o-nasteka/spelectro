<?php
/*
  $Id: index.php,v 1.3 2003/07/08 16:56:04 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*aqu&iacute; debes indicar el nombre del archivo de texto de noticias, si no existe, debes crearlo, el nombre del archivo debe tener este formato: 'Tuidioma_news.txt' 
Recuerda poner en mayuscula la primera letra. Si tienes dudas ves al administrador de noticias y te indicar&aacute; el nombre de los archivos que tienes que crear, seg&uacute;n los idiomas que tengas en la tienda */

$news_text =( implode ('', file( 'Espa&ntilde;ol_news.txt' ) ) ); 

//aqu&iacute; defines los textos en tu idioma
define('TABLE_HEADING_NEWS', 'Ultimas noticias');
define('TEXT_NEWS', $news_text );
?>
