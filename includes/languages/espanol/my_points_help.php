<?php
/*
  $Id: my_points_help.php v 1.50 2005/AUG/10 15:17:12 dsa_ Exp $
  http://www.deep-silver.com

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2005 osCommerce

  Released under the GNU General Public License

***********************************************************

Ten cuidado al editar este fichero de no tocar nada entre
--- DO NOT EDIT  BOF ---  y  --- DO NOT EDIT  EOF ---

************************************************************/


define('NAVBAR_TITLE', 'Ayuda del Sistema de Canje de Puntos');
define('HEADING_TITLE', 'Ayuda del Sistema de Canje de Puntos');

define('POINTS_FAQ_1', '¿Que es el Sistema de Canje de Puntos?');
define('POINTS_FAQ_2', '¿C&oacute;mo funciona?');
define('POINTS_FAQ_3', 'Puntos y Valores');
define('POINTS_FAQ_4', '¿C&oacute;mo se canjean los Puntos de Compra');
define('POINTS_FAQ_5', 'Cantidad M&iacute;nima Requerida');
define('POINTS_FAQ_6', 'Cantidad M&aacute;xima de Puntos canjeados por Pedido');
define('POINTS_FAQ_7', '¿Se consiguen puntos por los gastos de env&iacute;o?');
define('POINTS_FAQ_8', '¿Se consiguen puntos por los impuestos del pedido?');
define('POINTS_FAQ_9', '¿Se consiguen puntos por los productos rebajados?');
define('POINTS_FAQ_10', '¿Se consiguen puntos por compras pagadas por puntos?');
define('POINTS_FAQ_11', 'Restricciones de algunos Productos');
define('POINTS_FAQ_12', 'Restricciones de algunas Categor&iacute;as');
define('POINTS_FAQ_13', 'Condiciones de Uso');
define('POINTS_FAQ_14', 'Dudas complementarias');

define('TEXT_INFORMATION', '<a name="Top"></a>
<ol>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=1','NONSSL').'">' . POINTS_FAQ_1 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=2','NONSSL').'">' . POINTS_FAQ_2 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=3','NONSSL').'">' . POINTS_FAQ_3 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=4','NONSSL').'">' . POINTS_FAQ_4 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=5','NONSSL').'">' . POINTS_FAQ_5 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=6','NONSSL').'">' . POINTS_FAQ_6 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=7','NONSSL').'">' . POINTS_FAQ_7 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=8','NONSSL').'">' . POINTS_FAQ_8 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=9','NONSSL').'">' . POINTS_FAQ_9 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=10','NONSSL').'">' . POINTS_FAQ_10 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=11','NONSSL').'">' . POINTS_FAQ_11 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=12','NONSSL').'">' . POINTS_FAQ_12 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=13','NONSSL').'">' . POINTS_FAQ_13 . '</a></li>
  <li><a href="'.tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=14','NONSSL').'">' . POINTS_FAQ_14 . '</a></li>
</ol>');
#---------------------- DO NOT EDIT  BOF ----------------------------
switch ($_GET['faq_item']) {
  case '1':
  $key = 'USE_POINTS_SYSTEM';
define('SUB_HEADING_TITLE','' . POINTS_FAQ_1 . '');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
El Sistema de Canje de Puntos se ha creado para agradecerte tu apoyo y ofrecerte futuros incentivos.
<br /><br />
Nuestro Sistema de Canje de Puntos es tan simple como su propio nombre indica. Comprando en  ' . STORE_NAME . ' tu ir&aacute;s acumulando Puntos de Compra por el dinero que vayas gastando.
<br />
Una vez acumulados, podr&aacute;s usarlos para pagar futuras compras en ' . STORE_NAME . '.
<br /><br />
El Sistema de Canje de Puntos empieza el ' . tep_get_last_date($key) . ' . Todas las compras realizadas a partir de esta fecha podr&aacute;n acumular puntos.');

#---------------------- DO NOT EDIT  BOF ----------------------------
  break;
  case '2':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_2 . '');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Cuando se realiza un pedido, el importe total <span class="smalltext"><font color="FF6633">*</font></span> del pedido se usar&aacute; para calcular la cantidad de puntos conseguidos.
Estos puntos se añaden en tu cuenta de Puntos de Compra como puntos pendientes.
<br />
Todos tus puntos pendients se muestran en tu  <a href="' . tep_href_link(FILENAME_MY_POINTS) . '"> <u>Cuenta de Puntos de Compra</u></a> y estar&aacute;n ah&iacute; hasta que sean confirmados/aprobados por ' . STORE_NAME . '.
<br /><br />
Una vez que tus puntos pendientes han sido aprobados, se añadir&aacute;n a tu cuenta con el valor de estos puntos, listos para que sean canjeados por lo que quieras.
<br />
El canje de puntos no caduca y pueden ser acumulados hasta que decidas usarlos.
<br />Para ver el estado y cantidad total de puntos acumulados deber&aacute;s acceder a tu cuerta.
<br /><br />
Durante el proceso de formalizaci&oacute;n del pedido, podr&aacute;s pagar tu pedido con los puntos acumulados.
<br />
<br />
<span class="smalltext"><font color="FF6633">*</font> En muchos casos los gastos de env&iacute;o e impuestos del pedido se excluyen. Consulta esta Ayuda del Sistema de Canje de Puntos para ver mas detalles.');

#---------------------- DO NOT EDIT  BOF ----------------------------
  break;
  case '3':
if (POINTS_PER_AMOUNT_PURCHASE > 1) {
  $point_or_points = 'puntos';
} else {
  $point_or_points = 'punto';
}
define('SUB_HEADING_TITLE','' . POINTS_FAQ_3 . '<br /></font></strong><span class="smalltext">&Uacute;ltima actualizaci&oacute;n de Valor de Puntos: ' . tep_get_last_date('REDEEM_POINT_VALUE') . '</span>');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Actualmente, por cada ' .  $currencies->format(1) . ' gastado en ' . STORE_NAME . ' consigues  ' . number_format(POINTS_PER_AMOUNT_PURCHASE,2)  . ' ' . $point_or_points . '.  Cada punto est&aacute; valorado en  ' .  $currencies->format(REDEEM_POINT_VALUE) . ' de descuento para futuras compras.
<br />Por ejemplo:<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Coste del Producto:</strong>&nbsp; ' .  $currencies->format(100) . '<br />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Cantidad de puntos conseguidos:</strong>&nbsp; ' .  $currencies->format(tep_calc_shopping_pvalue(100 * POINTS_PER_AMOUNT_PURCHASE)) . '<br /><br />
Es importante se&ntilde;alar que nos reservamos el derecho de hacer cambios en el porcentaje indicado sin previo aviso.  El porcentaje mostrado aqu&iacute; es el vigente en estos momentos.');

#---------------------- DO NOT EDIT  BOF ----------------------------
  break;
  case '4':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_4 . '');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Si tienes puntos disponibles en tu cuenta de Puntos de Compra, los puedes utilizar para pagar compras realizadas en ' . STORE_NAME . '.
<br />
Durante el proceso de confirmaci&oacute;n del pedido, en la misma p&aacute;gina donde seleccionas el m&eacute;todo de pago, se mostrar&aacute; un espacio donde introducir la cantidad de puntos que deseas canjear.  Escribe la cantidad de puntos que deseas gastar o se&ntilde;ala el campo para canjear todos tus puntos disponibles.
Es importante señalar que puede que tengas que seleccionar otro m&eacute;todo de pago si no tienes suficientes puntos para compensar el importe de la compra.
<br />
Una vez pasado a la p&aacute;gina de confirmaci&oacute;n del pedido, se te indicar&aacute; la cantidad de puntos que se han generado por la compra. Una vez confirmado el pedido, tu cuenta de Puntos de Compra se actualizar&aacute; y los puntos usados se deducir&aacute;n de tu cuenta.
<br /><br />
<strong>Nota:</strong> Cualquier compra compensada con puntos dará Puntos por Compra adicionales UNIDAMENTE por la parte del importe que NO se haya pagado con puntos.');

#---------------------- DO NOT EDIT  BOF ----------------------------
  break;
  case '5':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_5 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('POINTS_LIMIT_VALUE') . '</span>');
    if(POINTS_LIMIT_VALUE  > 0)  {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Actualmente se requiere una cantidad m&iacute;nima de  <strong>' . number_format(POINTS_LIMIT_VALUE) . '</strong> puntos <strong>(' . $currencies->format(tep_calc_shopping_pvalue(POINTS_LIMIT_VALUE)) . ')' . '</strong> para ser canjeados.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
    } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Actualmente no se requiere una cantidad m&iacute;nima para canjear tus puntos.  Puedes seleccionar otro m&eacute;todo de pago si no tienes suficientes Puntos de Compra en tu cuenta para cubrir el importe de la compra.<br />
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '6':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_6 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('POINTS_MAX_VALUE') . '</span>');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Se permite un m&aacute;ximo de  <strong>' . number_format(POINTS_MAX_VALUE) . '</strong> puntos <strong>(' . $currencies->format(tep_calc_shopping_pvalue(POINTS_MAX_VALUE)) . ')' . '</strong> para canjear por pedido.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
  break;
  case '7':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_7 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('USE_POINTS_FOR_SHIPPING') . '</span>');
    if(USE_POINTS_FOR_SHIPPING == 'false')  {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
No.
<br />
En el c&aacute;lculo de puntos conseguidos, se exluyen los gastos de env&iacute;o.');

#---------------------- DO NOT EDIT  BOF ----------------------------
    } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Si.
<br />
En el c&aacute;lculo de puntos conseguidos, se incluyen los gastos de env&iacute;o.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '8':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_8 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('USE_POINTS_FOR_TAX') . '</span>');
    if(USE_POINTS_FOR_TAX == 'false')  {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
No.
<br />
En el c&aacute;lculo de puntos conseguidos, se exluye el valor de los impuestos.');

#---------------------- DO NOT EDIT  BOF ----------------------------
    } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Si.
<br />
En el c&aacute;lculo de puntos conseguidos, se incluyen el valor de los impuestos.
&nbsp;<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '9':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_9 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('USE_POINTS_FOR_SPECIALS') . '</span>');
    if(USE_POINTS_FOR_SPECIALS == 'false')  {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
No.
<br />
En el c&aacute;lculo de puntos conseguidos, se exluyen el importe de las referencias rebajadas.');

#---------------------- DO NOT EDIT  BOF ----------------------------
    } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Si.
<br />
En el c&aacute;lculo de puntos conseguidos, se incluyen el importe de las referencias rebajadas.
&nbsp;<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '10':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_10 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('USE_POINTS_FOR_REDEEMED') . '</span>');
    if(USE_POINTS_FOR_REDEEMED == 'false')  {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
No.
<br />
En el c&aacute;lculo de puntos conseguidos, no se acumulan puntos por compras realizadas con el canje de puntos.');

#---------------------- DO NOT EDIT  BOF ----------------------------
    } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Si.
<br />
En el c&aacute;lculo de puntos conseguidos, tambi&eacute;n se acumulan puntos por compras realizadas con el canje de puntos.
&nbsp;<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '11':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_11 . '');
	  if (REDEMPTION_RESTRICTION == 'true' && tep_not_null(RESTRICTION_MODEL)) {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','<span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('REDEMPTION_RESTRICTION') . '</span><br />
Actualmente &uacute;nicamente referencias que tienen el modelo <strong>[' . RESTRICTION_MODEL . ']</strong> pueden comprarse usando tus puntos acumulados.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');


#---------------------- DO NOT EDIT  BOF ----------------------------
 }
	  if (REDEMPTION_RESTRICTION == 'true' && tep_not_null(RESTRICTION_PID)) {
          $p_ids = split("[,]", RESTRICTION_PID);
        for ($i = 0; $i < count($p_ids); $i++) {
           $prods_query = tep_db_query("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $languages_id . "'and products.products_id = '" . $p_ids[$i] . "'");
           if ($list = tep_db_fetch_array($prods_query)) {
             $prods .= '<li>' . $list['products_name'] .'</li>';
           }
        }
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','<span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('RESTRICTION_PID') . '</span><br />
Actualmente &uacute;nicamente las siguientes referencias pueden comprarse utilizando tus puntos acumulados.<ul>' . $prods . '</ul>
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
	  if (REDEMPTION_RESTRICTION == 'true' && tep_not_null(RESTRICTION_PATH)) {
          $cat_path = split("[,]", RESTRICTION_PATH);
        for ($i = 0; $i < count($cat_path); $i++) {
           $cat_path_query = tep_db_query("select * from " . TABLE_CATEGORIES . ", " . TABLE_CATEGORIES_DESCRIPTION . " WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = '" . $languages_id . "' and categories.categories_id='" . $cat_path[$i] . "'");
           if ($list = tep_db_fetch_array($cat_path_query)) {
             $cats .= '<li>' . $list['categories_name'] .'</li>';
           }
        }
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','<span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('RESTRICTION_PATH') . '</span><br />
Actualmente &uacute;nicamente referencias de las siguientes categorias pueden comprarse usando tus puntos acumulados.<ul>' . $cats . '</ul>
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','<span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('REDEMPTION_RESTRICTION') . '</span><br />

Actualmente no se aplica ning&uacute;n tipo de restricciones para la utilizaci&oacute;n de tus puntos acumulados.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');

#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '12':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_12 . '<br /></font></strong><span class="smalltext">&Uacute;ltima Actualizaci&oacute;n: ' . tep_get_last_date('REDEMPTION_DISCOUNTED') . '</span>');
    if (REDEMPTION_DISCOUNTED == 'true') {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Actualmente no se pueden comprar referencias rebajadas usando tus puntos acumulados.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');


#---------------------- DO NOT EDIT  BOF ----------------------------
    } else {
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','

Actualmente no se aplica ninguna restricci&oacute;n sobre referencias a comprar con tus puntos acumulados.
<br />
Te recomendamos consultes esta p&aacute;gina con asiduidad por si realizamos cambios en este apartado.');


#---------------------- DO NOT EDIT  BOF ----------------------------
 }
  break;
  case '13':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_13 . '');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
<ul>
  <li>Los Puntos de Compra est&aacute;n disponibles &uacute;nicamente para miembros registrados en ' . STORE_NAME . '.</li>
  <li>Los Puntos de Compra se consiguen y aplican &uacute;nicamente en compras realizadas a través de la web y tendr&aacute;n que ser validadas por ' . STORE_NAME . '.</li>
  <li>Los Puntos de Compra no se pueden canjear, ni traspasarse entre clientes.</li>
  <li>Los Puntos de Compra no se pueden canjear por dinero bajo ninguna circunstancia.</li>
  <li>Los Puntos de Compra no se acumulan en compras canceladas.</li>
  <li>Cuando compras con Puntos, puedes tambi&eacute;n seleccionar otro m&eacute;todo de pago si no tienes suficientes Puntos de Compra para compensar el coste de tu compra.</li>
  <li>Cuando se calculan los puntos conseguidos, los gastos de env&iexcl;o y sus impuestos se exluyen (probablemente a parte de otros conceptos. Para mas informaci&oacute;n consultar esta Ayuda del Sistema de Canje de Puntos).</li>
</ul>
<strong>Importante: </strong>' . STORE_NAME . ' se reserva el derecho de hacer cambios de estas normas en cualquier momento sin previo aviso.');

#---------------------- DO NOT EDIT  EOF ----------------------------
  break;
  case '14':
define('SUB_HEADING_TITLE','' . POINTS_FAQ_14 . '');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','
Para cualquier aclaraci&oacute;n concerniente a nuestro Sistema de Canje de Puntos, <a href="' . tep_href_link(FILENAME_CONTACT_US) . '"> <u>contacta con nosotros</u></a>. Aseg&uacute;rate de indicarnos la mayor informaci&oacute;n posible en el e-mail.');

#---------------------- DO NOT EDIT  BOF ----------------------------
  default:
define('SUB_HEADING_TITLE','');
#---------------------- DO NOT EDIT  EOF ----------------------------

define('SUB_HEADING_TEXT','<font color="FF0000"><strong>Elige uno de los apartados se&ntilde;alados arriba.</strong></font>');

  }
?>
