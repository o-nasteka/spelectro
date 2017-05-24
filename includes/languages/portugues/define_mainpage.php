<?
 /* 
     mainpage.php ESPANOL by Matthijs - mattice@xs4all.nl
    
     This page will be shown on the first page of the catalog
     (default.php) instead of the standard message.

     You can write your php/html straight into this.
     This means you can drop in another table on the main
     page where you could create (manually) a featured product
     or just drop in some nice graphics. I've included a small sample
     below using existing box-code/stylesheets so you get the idea.   

     BE AWARE that changes (and possible errors/typo's) are
     directly used by the catalog. There is no preview option,
     changes are directly 'live'. So be carefull. 
     
     All changed and all fixes have been fixed for current snap shot...
     Edited by Steven Pignataro (steven_joseph_p@yahoo.com)

 */
/***************************************************************/
/* FCKeditor V2.00 RC3 for OsCommerce 2.2                      */
/***************************************************************/
/* Released March 3, 2005                                      */
/* FCK Editor for OsCommerce 2.2:                              */
/*                   By: J. Werzinske(computrguru)             */
/*			   (jody@total-computer-solutionz.com)       */
/* 			   http://www.total-computer-solutionz.com   */
/*                                                             */
/* Questions, Support and Screenshots:                         */
/* http://total-computer-solutionz.com				   */
/***************************************************************/
/* Based on:                                                   */
/* FCKeditor v.2.00 RC3                                        */
/* Copyright 2003 - 2005 Frederico Caldeira Knabben            */
/* http://www.fckeditor.net/default.html                       */
/***************************************************************/
/* FCKeditor for Product Descriptions                          */
/* By: Nick Marques                                            */
/* muzicman9382@hotmail.com                                    */
/***************************************************************/
/* Define Mainpage                                             */
/* By:  Mattice                                                */
/* http://www.oscommerce.com/community/contributions,86        */
/***************************************************************/
?>

<!-- MAIN PAGE EXAMPLE START //-->
 <table width="100%" cellpadding="5" cellspacing="0" border=0>
    <tr>
     <td class="main" width=50% valign="top" align="right">
<?php
$mainpage_title = "We've just installed the \"Define Mainpage\" module!";
$mainpage_info = "
\"Define Mainpage\" module v1.3 by Matthijs (mattice@xs4all.nl)<p>
TRANSLATED BY http://babelfish.altavista.com - MIGHT BE FUNNY 
<p>Este m&oacute;dulo demuestra c&oacute;mo es f&aacute;cil puede deber adaptar c&oacute;digo existente en OSC. Todo lo que tuve que agregaba una sola l&iacute;nea a define_languages.php (admin) para cerciorarse de &eacute;l busca siempre el fichero de mainpage.php. Tuve que entonces ponerlo obviamente en ejecuci&oacute;n en la cara del Admin y del cat&aacute;logo, pero eso no es un reparto grande como usted acaba de descubrir. Usted puede corregir el contenido de este fichero dentro del Admin (- > CAT&aacute;LOGO - > DEFINA MAINPAGE) para otro, menos disposiciones avanzadas eligen alem&aacute;n o a espa&ntilde;ol del men&uacute; del lenguaje. (proporcionado le han instalado esos lenguajes)<p> si usted no puede open/edit / [ fichero de language]/mainpage.php usted debe fijar probablemente los permisos derechos. Usted conseguir&aacute; una alerta de todos modos. <p>Todo? Planes futuros? Paz del mundo con la potencia superior... ehmm. del fuego. ning&uacute;n significo una opci&oacute;n de la inspecci&oacute;n previo;) Y quiz&aacute;s haga algunos modelos para la disposici&oacute;n... <p>M&aacute;s adelante, Mattice   
";
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $mainpage_title );
  new infoBoxHeading($info_box_contents, true, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $mainpage_info);
  new infoBox($info_box_contents);
?>
   </td>
<td width=30% valign="top" bgcolor="ffffff" align="center">
<? echo tep_image(DIR_WS_IMAGES . 'oscommerce.gif'); ?><br />
<? echo tep_image(DIR_WS_IMAGES . 'manufacturer_microsoft.gif'); ?><br />
<? echo tep_image(DIR_WS_IMAGES . 'manufacturer_matrox.gif'); ?><br />
<? echo tep_image(DIR_WS_IMAGES . 'manufacturer_canon.gif'); ?><br />
<? echo tep_image(DIR_WS_IMAGES . 'manufacturer_sierra.gif'); ?><br />
<? echo tep_image(DIR_WS_IMAGES . 'manufacturer_logitech.gif'); ?><br />
</td>
  </tr>
</table>
<!-- MAIN PAGE EXAMPLE END //-->
