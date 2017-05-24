<?php
/*
  $Id: product_reviews_write.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Comentarios');

define('SUB_TITLE_FROM', 'De:');
define('SUB_TITLE_REVIEW', 'Comentario:');

define('ADMIN_EMAIL_SUBJECT', 'Comentarios del producto - Requiere Aprobación');
define('ADMIN_EMAIL_MESSAGE', 'Hay un nuevo comentario de producto pendiente de aprobación. Puedes cliquear en el siguiente enlace para revisar el comentario: <a href="' . tep_href_link(FILENAME_PRODUCT_REVIEW_EMAIL) . '">' . tep_href_link(FILENAME_PRODUCT_REVIEW_EMAIL) . '</a>');
define('ADMIN_EMAIL_FROM_NAME', 'Comentarios del Producto');
define('SUB_TITLE_EXPLAIN', '
<hr/>
<h2>Normas para comentar productos</h2>
<h3>Queremos tus comentarios!</h3>
<p>Nos interesa tu opinión sobre este producto. A nosotros y a nuestros clientes nos gustaría saber que te parece este producto, rellenando el campo de comentarios a continuación. Nos reservamos el derecho de aceptar, rechazar o editar los comentarios de productos. Debido a su revisión puede que no aparezcan de inmediato. </P>
<ul>
<li><strong>NORMAS:</strong>
<ul>
<li>Escribir 50-300 palabras para el producto </li>
<li>Comentario sobre el valor del producto y la eficacia </li>
<li>Nos dices si te gusta el producto, pero lo más importante <strong> explicar por qué </strong> te gusta o no</li>
</ul>
</li>
<li><strong>NO PUBLICAR PARA:</strong>
<ul>
<li>Profanidad uso, obscenidades o formular observaciones rencorosas</li>
<li>Tipo de números de teléfono, direcciones de correo electrónico, o URL</li>
<li>Tomar nota de la disponibilidad, precio, o alternativas de pedido/información de envío</li>
</ul>
</li>
</ul>
<hr />
');
//*** </Reviews Mod>
define('SUB_TITLE_RATING', 'Evaluaci&oacute;n:');

define('TEXT_NO_HTML', '<small><font color="#ff0000"><strong>NOTA:</strong></font></small>&nbsp;No se traducir&aacute; el codigo HTML!');
define('TEXT_BAD', '<strong class="rojo">MALO</strong>');
define('TEXT_GOOD', '<strong class="verde">BUENO</strong>');

define('TEXT_CLICK_TO_ENLARGE', 'Haga Click para agrandar');
?>
