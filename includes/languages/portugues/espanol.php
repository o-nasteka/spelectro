<?php
/*
  $Id: espanol.php,v 1.107 2003/07/09 18:13:39 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'es_ES'
// on FreeBSD try 'es_ES.ISO_8859-1'
// on Windows try 'sp', or 'Spanish'
@setlocale(LC_ALL, 'es_ES.iso88591'); 

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y');  // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="es"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-1');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Crear Cuenta');
define('HEADER_TITLE_MY_ACCOUNT', 'Mi Cuenta');
define('HEADER_TITLE_CART_CONTENTS', 'Ver Cesta');
define('HEADER_TITLE_CHECKOUT', 'Realizar Pedido');
define('HEADER_TITLE_TOP', 'Inicio');
define('HEADER_TITLE_CATALOG', 'Cat&aacute;logo');
define('HEADER_TITLE_LOGOFF', 'Salir');
define('HEADER_TITLE_LOGIN', 'Entrar');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'peticiones desde');

// text for gender
define('MALE', 'Var&oacute;n');
define('FEMALE', 'Mujer');
define('MALE_ADDRESS', 'Sr.');
define('FEMALE_ADDRESS', 'Sra.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/aaaa');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'CATALOGO');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Fabricantes');
define('BOX_TEXT_MANUFACTURERS', 'Seleccione uno o vea todos');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Novedades');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'B&uacute;squeda R&aacute;pida');
define('BOX_SEARCH_TEXT', 'Use palabras clave para encontrar el producto que busca.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'B&uacute;squeda Avanzada');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Ofertas');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Comentarios');
define('BOX_REVIEWS_WRITE_REVIEW', 'Escriba un comentario para este producto');
define('BOX_REVIEWS_NO_REVIEWS', 'En este momento, no hay ningun comentario');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s de 5 Estrellas!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Compras');
define('BOX_SHOPPING_CART_EMPTY', '0 productos');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Mis Pedidos');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Los Mas Vendidos');
define('BOX_HEADING_BESTSELLERS_IN', 'Los Mas Vendidos en <br />&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Notificaciones');
define('BOX_NOTIFICATIONS_NOTIFY', 'Notifiqueme de cambios a <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'No me notifique de cambios a <b>%s</b>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Fabricante');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', 'P&aacute;gina de %s');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Otros productos');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Idiomas');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'Monedas');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informaci&oacute;n');
define('BOX_INFORMATION_PRIVACY', 'Pol&iacute;tica Privacidad');
define('BOX_INFORMATION_CONDITIONS', 'Cond Generales');
define('BOX_INFORMATION_SHIPPING', 'Info General');
define('BOX_INFORMATION_CONTACT', 'Contactenos');
define('BOX_INFORMATION_COMO_COMPRAR', 'Como Comprar');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'D&iacute;selo a un Amigo');
define('BOX_TELL_A_FRIEND_TEXT', 'Env&iacute;a esta pagina a un amigo con un comentario.');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'entrega');
define('CHECKOUT_BAR_PAYMENT', 'pago');
define('CHECKOUT_BAR_CONFIRMATION', 'confirmaci&oacute;n');
define('CHECKOUT_BAR_FINISHED', 'finalizado!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Seleccione');
define('TYPE_BELOW', 'Escriba Debajo');

// javascript messages
define('JS_ERROR', 'Hay errores en su formulario!\nPor favor, haga las siguientes correciones:\n\n');

define('JS_REVIEW_TEXT', '* Su \'Comentario\' debe tener al menos ' . REVIEW_TEXT_MIN_LENGTH . ' letras.\n');
define('JS_REVIEW_RATING', '* Debe evaluar el producto sobre el que opina.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Por favor seleccione un m&eacute;todo de pago para su pedido.\n');

define('JS_ERROR_SUBMITTED', 'Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Por favor seleccione un m&eacute;todo de pago para su pedido.');

define('CATEGORY_COMPANY', 'Empresa');
define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Direcci&oacute;n');
define('CATEGORY_CONTACT', 'Contacto');
define('CATEGORY_OPTIONS', 'Opciones');
define('CATEGORY_PASSWORD', 'Contrase&ntilde;a');

define('ENTRY_COMPANY', 'Empresa:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Sexo:');
define('ENTRY_GENDER_ERROR', 'Por favor seleccione una opci&oacute;n.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Nombre:');
define('ENTRY_FIRST_NAME_ERROR', 'Su Nombre debe tener al menos ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Apellidos:');
define('ENTRY_LAST_NAME_ERROR', 'Sus apellidos deben tener al menos ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Fecha de Nacimiento:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Su fecha de nacimiento debe tener este formato: DD/MM/AAAA (p.ej. 21/05/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (p.ej. 21/05/1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Su direcci&oacute;n de E-Mail debe tener al menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Su direcci&oacute;n de E-Mail no parece v&aacute;lida - por favor haga los cambios necesarios.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Su direcci&oacute;n de E-Mail ya figura entre nuestros clientes - puede entrar a su cuenta con esta direcci&oacute;n o crear una cuenta nueva con una direcci&oacute;n diferente.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Direcci&oacute;n:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Su direcci&oacute;n debe tener al menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Suburbio');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'C&oacute;digo Postal:');
define('ENTRY_POST_CODE_ERROR', 'Su c&oacute;digo postal debe tener al menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Poblacion:');
define('ENTRY_CITY_ERROR', 'Su poblaci&oacute;n debe tener al menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Provincia/Estado:');
define('ENTRY_STATE_ERROR', 'Su provincia/estado debe tener al menos ' . ENTRY_STATE_MIN_LENGTH . ' letras.');
define('ENTRY_STATE_ERROR_SELECT', 'Por favor seleccione de la lista desplegable.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Pa&iacute;s:');
define('ENTRY_COUNTRY_ERROR', 'Debe seleccionar un pa&iacute;s de la lista desplegable.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Tel&eacute;fono:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Su n&uacute;mero de tel&eacute;fono debe tener al menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Fax:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Bolet&iacute;n de noticias:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'suscribirse');
define('ENTRY_NEWSLETTER_NO', 'no suscribirse');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Contrase&ntilde;a:');
define('ENTRY_PASSWORD_ERROR', 'Su contrase&ntilde;a debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'La confirmaci&oacute;n de la contrase&ntilde;a debe ser igual a la contrase&ntilde;a.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirme Contrase&ntilde;a:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Contrase&ntilde;a Actual:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Su contrase&ntilde;a debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_NEW', 'Nueva Contrase&ntilde;a:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Su contrase&ntilde;a nueva debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'La confirmaci&oacute;n de su contrase&ntilde;a debe coincidir con su contrase&ntilde;a nueva.');
define('PASSWORD_HIDDEN', '--OCULTO--');
//NIF start
define('ENTRY_NIF', 'NIF o CIF:');
define('ENTRY_NO_NIF_ERROR', 'Ha de introducir su NIF o CIF.');
define('ENTRY_FORMATO_NIF_ERROR', 'El NIF o CIF ha de tener 9 caracteres. En el caso del NIF, rellene con ceros a la izquierda si es necesario.');
define('ENTRY_LETRA_NIF_ERROR', 'La letra del NIF es incorrecta.');
define('ENTRY_NIF_TEXT', '*');
define('ENTRY_NIF_EXAMPLE', '(por ejemplo: 01234567L)');
//NIF end

define('FORM_REQUIRED_INFORMATION', '* Dato Obligatorio');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'P&aacute;ginas de Resultados:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> pedidos)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> comentarios)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos nuevos)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Viendo del<b>%d</b> al <b>%d</b> (de <b>%d</b> ofertas)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Principio');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Anterior');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Siguiente');
define('PREVNEXT_TITLE_LAST_PAGE', 'Final');
define('PREVNEXT_TITLE_PAGE_NO', 'P&aacute;gina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Anteriores %d P&aacute;ginas');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Siguientes %d P&aacute;ginas');
define('PREVNEXT_BUTTON_FIRST', 'PRINCIPIO');
define('PREVNEXT_BUTTON_PREV', 'Anterior');
define('PREVNEXT_BUTTON_NEXT', 'Siguiente');
define('PREVNEXT_BUTTON_LAST', 'FINAL');

define('IMAGE_BUTTON_ADD_ADDRESS', 'A&ntilde;adir Direcci&oacute;n');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Direcciones');
define('IMAGE_BUTTON_BACK', 'Volver');
define('IMAGE_BUTTON_BUY_NOW', 'Compre Ahora');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Cambiar Direcci&oacute;n');
define('IMAGE_BUTTON_CHECKOUT', 'Realizar Pedido');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirmar Pedido');
define('IMAGE_BUTTON_CONTINUE', 'Continuar');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Seguir Comprando');
define('IMAGE_BUTTON_DELETE', 'Eliminar');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Editar Cuenta');
define('IMAGE_BUTTON_HISTORY', 'Historial de Pedidos');
define('IMAGE_BUTTON_LOGIN', 'Entrar');
define('IMAGE_BUTTON_IN_CART', 'A&ntilde;adir a la Cesta');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notificaciones');
define('IMAGE_BUTTON_QUICK_FIND', 'B&uacute;squeda R&aacute;pida');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Eliminar Notificaciones');
define('IMAGE_BUTTON_REVIEWS', 'Comentarios');
define('IMAGE_BUTTON_SEARCH', 'Buscar');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Opciones de Env&iacute;o');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'D&iacute;selo a un Amigo');
define('IMAGE_BUTTON_UPDATE', 'Actualizar');
define('IMAGE_BUTTON_UPDATE_CART', 'Actualizar Cesta');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Escribir Comentario');

define('SMALL_IMAGE_BUTTON_DELETE', 'Eliminar');
define('SMALL_IMAGE_BUTTON_EDIT', 'Modificar');
define('SMALL_IMAGE_BUTTON_VIEW', 'Ver');

define('ICON_ARROW_RIGHT', 'm&aacute;s');
define('ICON_CART', 'En Cesta');
define('ICON_ERROR', 'Error');
define('ICON_SUCCESS', 'Correcto');
define('ICON_WARNING', 'Advertencia');

define('TEXT_GREETING_PERSONAL', 'Bienvenido de nuevo <span class="greetUser">%s!</span> &iquest;Le gustaria ver que <a href="%s"><u>nuevos productos</u></a> hay disponibles?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Si no es %s, por favor <a href="%s"><u>entre aqui</u></a> e introduzca sus datos.</small>');
define('TEXT_GREETING_GUEST', 'Bienvenido <span class="greetUser">Invitado!</span> &iquest;Le gustaria <a href="%s"><u>entrar en su cuenta</u></a> o preferiria <a href="%s"><u>crear una cuenta nueva</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Ordenar Productos ');
define('TEXT_DESCENDINGLY', 'Descendentemente');
define('TEXT_ASCENDINGLY', 'Ascendentemente');
define('TEXT_BY', ' por ');

define('TEXT_REVIEW_BY', 'por %s');
define('TEXT_REVIEW_WORD_COUNT', '%s palabras');
define('TEXT_REVIEW_RATING', 'Evaluaci&oacute;n: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Fecha Alta: %s');
define('TEXT_NO_REVIEWS', 'En este momento, no hay ningun comentario.');

define('TEXT_NO_NEW_PRODUCTS', 'Ahora mismo no hay novedades.');

define('TEXT_UNKNOWN_TAX_RATE', 'Impuesto desconocido');

define('TEXT_REQUIRED', '<span class="errorText">Obligatorio</span>');
// +Country-State Selector
define ('DEFAULT_COUNTRY', '195');
// -Country-State Selector

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> No he podido enviar el email con el servidor SMTP especificado. Configura tu servidor SMTP en la secci&oacute;n adecuada del fichero php.ini.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Advertencia: El directorio de instalaci&oacute;n existe en: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/install. Por razones de seguridad, elimine este directorio completamente.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Advertencia: Puedo escribir en el fichero de configuraci&oacute;n: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php. En determinadas circunstancias esto puede suponer un riesgo - por favor corriga los permisos de este fichero.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Advertencia: El directorio para guardar datos de sesi&oacute;n no existe: ' . tep_session_save_path() . '. Las sesiones no funcionar&aacute;n hasta que no se corriga este error.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Avertencia: No puedo escribir en el directorio para datos de sesi&oacute;n: ' . tep_session_save_path() . '. Las sesiones no funcionar&aacute;n hasta que no se corriga este error.');
define('WARNING_SESSION_AUTO_START', 'Advertencia: session.auto_start esta activado - desactive esta caracteristica en el fichero php.ini and reinicie el servidor web.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Advertencia: El directorio para productos descargables no existe: ' . DIR_FS_DOWNLOAD . '. Los productos descargables no funcionar&aacute;n hasta que no se corriga este error.');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'La fecha de caducidad de la tarjeta de cr&eacute;dito es incorrecta.<br />Compruebe la fecha e int&eacute;ntelo de nuevo.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'El n&uacute;mero de la tarjeta de cr&eacute;dito es incorrecto.<br />Compruebe el numero e int&eacute;ntelo de nuevo.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Los primeros cuatro digitos de su tarjeta son: %s<br />Si este n&uacute;mero es correcto, no aceptamos este tipo de tarjetas.<br />Si es incorrecto, int&eacute;ntelo de nuevo.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br />Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br /><a href="http://www.avanzosc.com" target="_blank">P&aacute;gina dise&ntilde;ada y desarrollada por Avanzosc</a>');

// For new header - Para la nueva cabecera:
define('BOX_ALL_CATEGORIES', 'Todas');
define('BOX_HEADER_ADDFAVORITE', 'Agregar a favoritos');

//Star product Start
define('STAR_TITLE', 'Producto estrella'); // star product
define('STAR_READ_MORE', '... leer m&aacute;s.'); // ... read more.
//Star product End

// most_viewed box text in includes/boxes/most_viewed.php
define('BOX_HEADING_MOSTVIEWED', 'Mas Vistos');
define('BOX_HEADING_MOSTVIEWED_IN', 'Mas vistos en ');

//box Information pages unlimited
define('BOX_INFORMATION_ARTICLES', 'Articulos');

// todos los productos
define ('ALL_PRODUCTS_LINK', 'Todos los Productos');

define('BOX_INFORMATION_DYNAMIC_SITEMAP', 'Mapa del Sitio');

// LoginBox Text
require(DIR_WS_LANGUAGES . $language . '/' . 'loginbox.php');

define('BOX_INFORMATION_MANUFACTURERS', 'Todos Fabricantes');

/*aqu&iacute; debes indicar el nombre del archivo de texto de noticias, si no existe, debes crearlo, el nombre del archivo debe tener este formato: 'Tuidioma_news.txt'
Recuerda poner en mayuscula la primera letra. Si tienes dudas ves al administrador de noticias y te indicar&aacute; el nombre de los archivos que tienes que crear, seg&uacute;n los idiomas que tengas en la tienda */

$news_text =( implode ('', file( 'Espa&ntilde;ol_news.txt' ) ) );

//aqu&iacute; defines los textos en tu idioma
define('TABLE_HEADING_NEWS', 'Ultimas noticias');
define('TEXT_NEWS', $news_text );

define('BOX_CATALOG_FEATURED_PRODUCTS', 'Productos destacados');
// featured box text in includes/boxes/featured.php
define('BOX_HEADING_FEATURED', 'Destacados');

//banner header - banner multilingue de la cabecera
define('BANNER_GROUP_1DWN', '468x50_es');

// estas dos siguientes las puede borrar - comienzo:
define('OSCPACK_INSTALLED', 'osCommerce Pack Instalado');
define('OSCPACK_INSTALLED_TEXT', '<p><font color="#CC6600"><strong>&iexcl;&iexcl;&iexcl;osCommerce ha sido instalado con exito!!!</strong></font></p>
<p>La base de datos ha sido importada correctamente y la instalaci&oacute;n de las contribuciones ha sido completada.</p>
<p><font size="1">(El siguiente contenido lo puede encontrar en las instrucciones post_instalacion.txt que vienen en el pack. Tambien las puede ver en <a href="information.php?info_id=7" target="_blank">Post-Instalacion</a> en el menu del modulo de informacion)</font> </p>
<p>&nbsp;</p>
<p><strong>MEDIDAS DE SEGURIDAD</strong> </p>
<p><strong><font color="#FF0000" size="4">1.-</font></strong> El primer paso que debe hacer despues de la instalacion es <strong>borrar la carpeta install</strong> y todo los archivos que contiene, se encuentra en:</p>
<p>catalog/<strong>install</strong>/</p>
<p><strong>y borre el archivo install_htc.php</strong>, que se encuentra en:</p>
<p>catalog/<strong>install_htc.php</strong></p>
<p><strong><font color="#FF0000">Para seguridad de su catalogo debe borrar esa carpeta y ese archivo</font></strong>.</p>
<p>&nbsp;</p>
<p><font size="4"><strong><font color="#FF0000">2.</font></strong>-</font> <strong>Debe proteger el directorio admin con contrase&ntilde;a</strong> ya que queda con libre acceso despues de la instalacion. </p>
<p>catalog/<strong>admin</strong>/ &lt;- protega con contrase&ntilde;a este directorio </p>
<p><strong>En el panel de control de su hosting, Cpanel o Plesk, puede encontrar una opcion de proteger directorios</strong> bajo el nombre Proteccion, Directorios, Proteccion de carpetas,... que le permitira poner proteccion a la carpeta catalog/<strong>admin</strong>/ y crear un usuario y contrase&ntilde;a para acceder solo usted a ese directorio admin desde donde puede administrar y configurar opciones de su catalogo de osCommerce.</p>
<p>En caso de no tener esta opcion debera proteger el directorio admin mediante el archivo .htaccess y Apache, (el proceso es el mismo de arriba solo que se realiza manualmente), instrucciones de como realizar este proceso las puede encontrar en:</p>
<blockquote>
  <p><a href="http://www.oscommerce-tutoriales.com/tutoriales/instalacion/proteger-el-admin-con-contrasena-mediante-htaccess.html" target="_blank">Tutorial de proteccion del admin con contrase&ntilde;a</a></p>
</blockquote>
<p>para consultas sobre la proteccion de directorios tiene estos hilos de soporte de los foros:</p>
<blockquote>
  <p><a href="http://foros.oscommerce-tutoriales.com/index.php/board,14.0.html">http://foros.oscommerce-tutoriales.com/index.php/board,14.0.html</a></p>
  <p><a href="http://oscommerce.qadram.com/modules.php?name=Forums&amp;file=viewtopic&amp;t=7492&amp;highlight=proteccion+admin" target="_blank">http://oscommerce.qadram.com/modules.php?name=Forums...</a></p>
</blockquote>
<p>Otros tutoriales: <a href="http://www.oscommerce-tutoriales.com/tutoriales/instalacion/seguridad-en-oscommerce.html" target="_blank">seguridad en osCommerce</a>  </p>
<p>&nbsp;</p>
<p><strong><font color="#FF0000" size="4">3.-</font></strong> <strong>Advertencias de la cabecera:</strong></p>
<ul>
  <li><em><strong>Advertencia: El directorio de instalaci&oacute;n existe en:   .../catalog/install. Por razones de seguridad, elimine este   directorio completamente. </strong></em></li>
</ul>
<blockquote>
  <p>Borrando esa carpeta install desaparece esta advertencia de seguridad</p>
</blockquote>
<ul>
  <li><em><strong>Advertencia: Puedo escribir en el fichero de configuraci&oacute;n:   .../catalog/includes/configure.php. En determinadas   circunstancias esto puede suponer un riesgo - por favor corriga los permisos de   este fichero. </strong></em></li>
</ul>
<blockquote>
  <p>Desde el panel de control de su hospedaje usando un administrador de ficheros / file manager / webFTP o similar</p>
  <p>o bien conectando con su sitio mediante un programa de FTP como FileZilla o similar puede cambiar los permisos CHMOD de ese archivo.</p>
  <blockquote>
    <p>En el caso del programa FileZilla, conecte con su sitio, necesitara la direccion ftp del servidor, suele ser ftp.susitio.com, el usuario FTP que debe de haberlo asignado en el panel de control de su hosting o creado uno, la contrase&ntilde;a de ese usuario, el puerto es 21. Una vez conectado entre en la carpeta includes y vea el archivo configure.php<br />
    catalog/includes/configure.php<br />
    pulse sobre ese archivo con el boton derecho del raton, vera que aparece un menu contextual, seleccione en Atributos, y aparecera una peque&ntilde;a ventana donde puede configurar los permisos CHMOD a 644, solo lectura (ese archivo contiene los datos de configuracion de su tienda)<br />
    y luego vea la <a href="index.php" target="_blank">portada de su catalogo</a> y si sige apareciendo esa advertencia en la cabecera prueba entonces con permisos 444. </p>
  </blockquote>
</blockquote>
<p>&nbsp;</p>
<p>Mas informacion sobre la instalacion de osCommerce en: <a href="http://www.oscommerce-tutoriales.com/tutoriales/instalacion/instalacion-de-oscommerce.html" target="_blank"><strong>Tutorial de instalacion de osCommerce</strong></a> </p>
<blockquote>
  <p>&nbsp;</p>
  <p>Ahora ya puede <a href="index.php" target="_blank"><strong>ver la portada de su catalogo </strong></a></p>
  <p>y tambien puede <a href="admin/index.php" target="_blank"><strong>ver la zona de administracion de su catalogo</strong></a> </p>
</blockquote>');
// estas dos anteriores las puede borrar - Final:

//individual boxes

//Short description in products modules Start
define('DESCR_READ_MORE', '<FONT color=blue>m&aacute;s info +</FONT>'); // ... read more.
//Short description in products modules End

define('IMAGE_NEW_PRODUCT', '... ver mas productos nuevos');
define('IMAGE_SPECIAL_PRODUCT', '... ver mas ofertas');

define('IMAGE_FEATUREDPRODUCT', '... ver mas productos destacados');

//begin Supportticketsystem
define('BOX_HEADING_SUPPORT', 'Soporte');
define('BOX_SUPPORT_TEXT', 'Entrar a HelpDesk');
//end Supportticketsystem

// newsdesk box text in includes/boxes/newsdesk.php
define('TABLE_HEADING_NEWSDESK', 'Novedades e Informaci&oacute;n');
define('TEXT_NO_NEWSDESK_NEWS', 'Lo sentimos, no hay noticias');
define('TEXT_NEWSDESK_READMORE', 'Leer m&aacute;s');
define('TEXT_NEWSDESK_VIEWED', 'Vistas:');

define('BOX_HEADING_NEWSDESK_CATEGORIES', 'Categorias de Noticias');
define('BOX_HEADING_NEWSDESK_LATEST', 'Ultimas Noticias');

define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', 'Mostrando <b>%d</b> a <b>%d</b> (de <b>%d</b> art&iacute;culos)');
//END -- newsdesk

// box text in includes/boxes/live_support.php
define('BOX_HEADING_LIVESUPPORT', 'Live Support');
define('BOX_INFO_LIVESUPPORT', 'Chatear con Dpto soporte!');

define('BOX_HEADING_SHOP_BY_PRICE', 'Productos por Precio');

// BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID', 'Nº Identificaci&oacute;n (Solo Clientes especiales):');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_COMPANY_TAX_ID_TEXT', '');
// EOF Separate Pricing Per Customer

?>
