<?php
/*
  $Id: stats_monthly_sales.php,v 2.2 $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Sumario de compras por mes');
define('HEADING_TITLE_STATUS','Estado');
define('HEADING_TITLE_REPORTED','Reportado');
define('TEXT_ALL_ORDERS', 'Todas las ordenes');
define('TEXT_NOTHING_FOUND', 'No hay facturas para esta selecci&oacute;n de fechas y estados');
define('TEXT_BUTTON_REPORT_BACK','Atras');
define('TEXT_BUTTON_REPORT_INVERT','Invertir');
define('TEXT_BUTTON_REPORT_PRINT','Imprimir');
define('TEXT_BUTTON_REPORT_SAVE','Guardar CSV');
define('TEXT_BUTTON_REPORT_HELP','Ayuda');
define('TEXT_BUTTON_REPORT_BACK_DESC', 'Volver al resumen por mes');
define('TEXT_BUTTON_REPORT_INVERT_DESC', 'Invertir filas de arriba por las de abajo');
define('TEXT_BUTTON_REPORT_PRINT_DESC', 'Mostrar informe para imprimir');
define('TEXT_BUTTON_REPORT_HELP_DESC', 'Acerca de este informe y c&oacute;mo usarlo');
define('TEXT_BUTTON_REPORT_GET_DETAIL', 'Haga Clic en el resumen para este mes');
define('TEXT_REPORT_DATE_FORMAT', 'j M Y -   g:i a'); // date format string
//  as specified in php manual here: http://www.php.net/manual/en/function.date.php
define('TABLE_HEADING_YEAR','A&ntilde;o');
define('TABLE_HEADING_MONTH', 'Mes');
define('TABLE_HEADING_DAY', 'Di');
define('TABLE_HEADING_INCOME', 'Total<br /> ');
define('TABLE_HEADING_SALES', 'Productos<br /> Iva Inc');
define('TABLE_HEADING_NONTAXED', 'Sin<br />Impuestos');
define('TABLE_HEADING_TAXED', 'Productos <br />Iva Exc');
define('TABLE_HEADING_TAX_COLL', 'Total <br /> Iva');
define('TABLE_HEADING_SHIPHNDL', 'Gastos<br />de Env&iacute;o');
define('TABLE_HEADING_SHIP_TAX', 'Iva<br /> en Env&iacute;o');
define('TABLE_HEADING_LOWORDER', 'Tasas<br /> ');
define('TABLE_HEADING_OTHER', 'Vales<br /> Descuento');  // could be any other extra class value
define('TABLE_FOOTER_YTD','Total Anual');
define('TABLE_FOOTER_YEAR','A&ntilde;o');
define('TEXT_HELP', '<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title>Monthly Sales/Tax Report</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<BODY>
<center>
<table width="95%"><tr><td>
<p class="main" align="center">
<b>Como ver y usar el informe 347</b>
<p class="main" align="justify">
<b>informe de ventas mensual</b>
<p class="smallText" align="justify">
Inicalmente cuando se selecciona el informe del menu, muestra un resumen financiero de todas las ventas por mes. Cada mes del historico de la tienda se resume en una fila, mostrando lo facturado y sus componentes. Adem&aacute;s, se obtiene un listado de los importes por iva, env&iacute;o e impuestos por env&iacute;o. 
<p class="smallText" align="justify">
La fila superior, es el mes corriente y las filas bajo &eacute;l resumen cada mes del a&ntilde;o. En la fila inferior de cada bloque se muestra el resumen anual.
<p class="smallText" align="justify">
Para invertir el orden de las filas, haga Click en bot&oacute;n Invertir
<p class="main" align="justify">
<b>Reporte mensual resumiendo por d&iacute;as</b>
<p class="smallText" align="justify">
El resumen de la actividad diaria se muestra, haciendo Clidk en el nombre del mes, a la izquiera de la fila. Para volver del informe diario al mensual, haga click en el bot&oacute;n "Atr&aacute;s" del informe diario
<p class="main" align="justify">
<b>Cabeceras de las columnas</b>
<p class="smallText" align="justify">
En la izquierda, se muestan el a&ntilde;o y el mes. Las otras columnas son, de izquierda a derecha:
<ul><li class="smallText"><b>Bruto</b> - Total de todas las &oacute;rdenes 
<li class="smallText"><b>Subtotal</b> - Ventas totales de los productos comprados en el mes
<br />Entonces, las ventas del producto se dividen en 2 categor&iacute;as:
<li class="smallText"><b>Ventas Sin Impuestos</b> - Subtotal de ventas sin impuestos 
<li class="smallText"><b>Ventas con Impuestos</b> - Subtotal de ventas con impuestos
<li class="smallText"><b>Impuestos</b> - Cantidad facturada por IVA
<li class="smallText"><b>Gastos de Env&iacute;o</b> - Total facturado por gastos de Env&iacute;o  
<li class="smallText"><b>Impuestos de Gastos de Env&iacute;o</b> - Impuestos facturados por Gastos de Env&iacute;o
<li class="smallText"><b>Honorarios por pedidos bajo coste</b> y <b>Vales de Regalo</b> - Si la tienda tiene habilitados los componentes de bajo coste y vales de regalo, estos datos se muestran en columna aparte.
</ul>
<p class="main" align="justify">
<b>Seleccionar el resumen por estados</b>
<p class="smallText" align="justify">
Para mostrar el sumario diario o mensual para s&oacute;lo un estado de la orden, selecciones el estado en el combo de la derecha arriba.
<p class="main" align="justify">
<b>Showing detail of taxes</b>
<p class="smallText" align="justify">
El importe del impuesto en cualquier fila del informe es un link a una ventana emergente que muestra el nombre el impuesto cargado y sus cargos individuales.
<p class="main" align="justify">
<b>Imprimiendo el informe</b>
<p class="smallText" align="justify">
Para ver el informe en una ventana de impresi&oacute;n, haga Click en el bot&oacute;n "Imprimir" y utilice el comando de impresi&oacute;n de su navegador. Se a&ntilde;adir&aacute;n el nombre de la tienda y la fecha en que gener&oacute; el informe.
<p class="main" align="justify">
<b>Guardar el informe en un fichero CSV</b>
<p class="smallText" align="justify">
Pulse el bot&oacute;n Salvar como CSV y guarde el fichero en un directorio local. Podr&aacute; utilizar este fichero para importarlo desde cualquier hoja de c&aacute;lculo como Excel.
<p class="smallText">v 2.1.1
</td></tr>
</table>
</BODY>
</HTML>');
?>
