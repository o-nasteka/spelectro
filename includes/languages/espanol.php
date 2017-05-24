<?php
/*
  $Id: espanol.php 1743 2007-12-20 18:02:36Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'es_ES'
// on FreeBSD try 'es_ES.ISO_8859-1'
// on Windows try 'sp', or 'Spanish'
@setlocale(LC_TIME, 'es_ES.ISO_8859-1');
setlocale( LC_CTYPE, 'C' );

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%d/%m/%Y'); // this is used for strftime()
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
define('HTML_PARAMS','dir="LTR" lang="es"');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('BOX_ALL_CATEGORIES', 'Todas');
define('BOX_HEADER_ADDFAVORITE', 'Agregar a favoritos');
define('HEADER_TITLE_CREATE_ACCOUNT', 'Crear Cuenta');
define('HEADER_TITLE_MY_ACCOUNT', 'Mi Cuenta');
define('HEADER_TITLE_ACCOUNT_HISTORY', 'Mis Pedidos');
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
define('BOX_HEADING_CATEGORIES', 'Categorias');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Fabricantes');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Novedades');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'B&uacute;squeda');
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
define('BOX_HEADING_BESTSELLERS', 'Top Ventas');
define('BOX_HEADING_BESTSELLERS_IN', 'Los Mas Vendidos en <br />&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Notificaciones');
define('BOX_NOTIFICATIONS_NOTIFY', 'Notifiqueme de cambios a <strong>%s</strong>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'No me notifique de cambios a <strong>%s</strong>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Fabricantes');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', 'P&aacute;gina de %s');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Otros productos');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Idiomas');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'Monedas');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informaci&oacute;n');
define('BOX_INFORMATION_PRIVACY', 'Confidencialidad');
define('BOX_INFORMATION_CONDITIONS', 'Condiciones de uso');
define('BOX_INFORMATION_SHIPPING', 'Envios/Devoluciones');
define('BOX_INFORMATION_CONTACT', 'Contáctenos');
define('BOX_INFORMATION_MY_POINTS_HELP', 'Programa de Puntos FAQ');//Points/Rewards Module V2.00

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'D&iacute;selo a un Amigo');
define('BOX_TELL_A_FRIEND_TEXT', 'Env&iacute;a esta pagina a un amigo con un comentario.');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Entrega');
define('CHECKOUT_BAR_PAYMENT', 'Pago');
define('CHECKOUT_BAR_CONFIRMATION', 'Confirmaci&oacute;n');
define('CHECKOUT_BAR_FINISHED', 'Finalizado!');

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
define('ENTRY_COMPANY_TAX_ID', 'Nº Identificación (Solo Clientes especiales):');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_COMPANY_TAX_ID_TEXT', '');
define('ENTRY_GENDER', 'Sexo:');
define('ENTRY_GENDER_ERROR', 'Por favor seleccione una opción.');
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
define('ENTRY_REPEAT_EMAIL', 'Repita E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Su dirección de E-Mail debe tener al menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Su dirección de E-Mail no parece válida - por favor haga los cambios necesarios.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Su dirección de E-Mail ya figura entre nuestros clientes - puede entrar a su cuenta con esta dirección o crear una cuenta nueva con una dirección diferente.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Dirección:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Su dirección debe tener al menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Suburbio');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Código Postal:');
define('ENTRY_POST_CODE_ERROR', 'Su código postal debe tener al menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' dígitos.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Ciudad:');
define('ENTRY_CITY_ERROR', 'Su ciudad debe tener al menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Provincia:');
define('ENTRY_STATE_ERROR', 'Su provincia debe tener al menos ' . ENTRY_STATE_MIN_LENGTH . ' letras.');
define('ENTRY_STATE_ERROR_SELECT', 'Por favor seleccione de la lista desplegable.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'País:');
define('ENTRY_COUNTRY_ERROR', 'Debe seleccionar un país de la lista desplegable.');
define('ENTRY_OTHER_ADDRESS', 'Si tienes una dirección de envío distinta a la de facturación, puedes añadirla al hacer el pedido');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Teléfono:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Su número de teléfono debe tener al menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Fax:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Boletín de noticias:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'suscribirse');
define('ENTRY_NEWSLETTER_NO', 'no suscribirse');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Contraseña:');
define('ENTRY_PASSWORD_ERROR', 'Su contraseña debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'La confirmación de la contraseña debe ser igual a la contraseña.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirme Contraseña:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Contraseña Actual:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Su contraseña debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_NEW', 'Nueva Contraseña:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Su contraseña nueva debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'La confirmación de su contraseña debe coincidir con su contraseña nueva.');
define('PASSWORD_HIDDEN', '--OCULTO--');
define('ENTRY_SEARCH', 'Buscar...');
define('ENTRY_READ_MORE', 'Leer+');
define('ENTRY_CHNG_VST', 'Cambiar Vista');
define('ENTRY_DEVELOPED', 'Desarrollado por:');
define('ENTRY_SEARCH', 'Buscar...');
define('ENTRY_TAX_INCL', 'IVA NO Incl.');

define('ENTRY_HOME', 'Inicio');
define('ENTRY_NEWS', 'Novedades');
define('ENTRY_OFFERS', 'Ofertas');
define('ENTRY_INFORMATION', 'Informaci&oacute;n');
define('ENTRY_CONTACT', 'Contacto');

define('ENTRY_NIF', 'DNI/NIF:');
define('ENTRY_NO_NIF_ERROR', 'Ha de introducir su DNI/NIF.');
define('ENTRY_FORMATO_NIF_ERROR', 'El DNI/NIF ha de tener 9 caracteres. En el caso del NIF, rellene con ceros a la izquierda si es necesario.');
define('ENTRY_LETRA_NIF_ERROR', 'La letra del DNI es incorrecta.');
define('ENTRY_NIF_TEXT', '*');
define('ENTRY_NIF_EXAMPLE', '(por ejemplo: 01234567L)');
define('FORM_REQUIRED_INFORMATION', '* Dato Obligatorio');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'P&aacute;ginas de Resultados:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Viendo del <strong>%d</strong> al <strong>%d</strong> (de <strong>%d</strong> productos)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Viendo del <strong>%d</strong> al <strong>%d</strong> (de <strong>%d</strong> pedidos)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Viendo del <strong>%d</strong> al <strong>%d</strong> (de <strong>%d</strong> comentarios)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Viendo del <strong>%d</strong> al <strong>%d</strong> (de <strong>%d</strong> productos nuevos)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Viendo del<strong>%d</strong> al <strong>%d</strong> (de <strong>%d</strong> ofertas)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Principio');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Anterior');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Siguiente');
define('PREVNEXT_TITLE_LAST_PAGE', 'Final');
define('PREVNEXT_TITLE_PAGE_NO', 'P&aacute;gina %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Anteriores %d P&aacute;ginas');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Siguientes %d P&aacute;ginas');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;PRINCIPIO');
define('PREVNEXT_BUTTON_PREV', 'Anterior');
define('PREVNEXT_BUTTON_NEXT', 'Siguiente');
define('PREVNEXT_BUTTON_LAST', 'FINAL&gt;&gt;');

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

define('TEXT_GREETING_PERSONAL', 'Bienvenido <strong><span class="greetUser">%s!</span></strong>');
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
define ('DEFAULT_COUNTRY', '195');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><strong><small>TEP ERROR:</small> No he podido enviar el email con el servidor SMTP especificado. Configura tu servidor SMTP en la secci&oacute;n adecuada del fichero php.ini.</strong></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Advertencia: El directorio de instalaci&oacute;n existe en: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/install. Por razones de seguridad, elimine este directorio completamente.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Advertencia: Puedo escribir en el fichero de configuraci&oacute;n: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php. En determinadas circunstancias esto puede suponer un riesgo - por favor corriga los permisos de este fichero.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Advertencia: El directorio para guardar datos de sesi&oacute;n no existe: ' . tep_session_save_path() . '. Las sesiones no funcionar&aacute;n hasta que no se corriga este error.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Avertencia: No puedo escribir en el directorio para datos de sesi&oacute;n: ' . tep_session_save_path() . '. Las sesiones no funcionar&aacute;n hasta que no se corriga este error.');
define('WARNING_SESSION_AUTO_START', 'Advertencia: session.auto_start esta activado - desactive esta caracteristica en el fichero php.ini and reinicie el servidor web.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Advertencia: El directorio para productos descargables no existe: ' . DIR_FS_DOWNLOAD . '. Los productos descargables no funcionar&aacute;n hasta que no se corriga este error.');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'La fecha de caducidad de la tarjeta de cr&eacute;dito es incorrecta. Compruebe la fecha e int&eacute;ntelo de nuevo.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'El n&uacute;mero de la tarjeta de cr&eacute;dito es incorrecto. Compruebe el numero e int&eacute;ntelo de nuevo.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Los primeros cuatro digitos de su tarjeta son: %s. Si este n&uacute;mero es correcto, no aceptamos este tipo de tarjetas. Si es incorrecto, int&eacute;ntelo de nuevo.');
define('REDEEM_SYSTEM_ERROR_POINTS_NOT', 'El valor de tus puntos no es el suficiente para pagar por completo su compra. Por favor, seleccione otra forma de pago');
define('REDEEM_SYSTEM_ERROR_POINTS_OVER', 'Error en el canje de puntos! Los puntos no tienen el valor del total de la compra. Por favor vuelva a introducir sus puntos.');
define('REFERRAL_ERROR_SELF', 'Lo sentimos pero no te puedes referir tu mismo.');
define('REFERRAL_ERROR_NOT_VALID', 'El email que has introducido parece no valido, por favor corrija los errores.');
define('REFERRAL_ERROR_NOT_FOUND', 'El email de la persona referida que has insertado no existe.');
define('TEXT_POINTS_BALANCE', 'Estado de Puntos');
define('TEXT_POINTS', 'Puntos:');
define('TEXT_VALUE', 'Valor:');
define('REVIEW_HELP_LINK', ' Write a Review and earn <strong>%s</strong> worth of points.<br />Please check the %s for more information.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br />Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');

define('MINIMUM_ORDER_NOTICE', 'Las unidades mínimas permitidas para %s es de %d. Tu carro ha sido actualizado para mostrarlas.');
define('QUANTITY_BLOCKS_NOTICE', '%s puede ser comprado solo en multiples de %d. Tu carro ha sido actualizado para mostrar esto.');
define('MATC_CONDITION_AGREEMENT', 'He leido y acepto los <a href="%s" target="_blank"><strong><u>Terminos y Condiciones de Uso</u></strong></a> de este sitio: ');
define('MATC_HEADING_CONDITIONS', 'Aceptar terminos y condiciones de uso');
define('MATC_ERROR', 'Tienes que aceptar los terminos y condiciones de uso para continuar.');

define('BOX_HEADING_CUSTOMER_TESTIMONIALS', 'Opinión');
define('BOX_HEADING_FEATURED', 'Productos Destacados');
define('BOX_INFORMATION_CUSTOMER_TESTIMONIALS', 'Opinión');
define('TABLE_HEADING_TESTIMONIALS_ID', 'ID');
define('TABLE_HEADING_TESTIMONIALS_NAME', 'Nombre');
define('TABLE_HEADING_TESTIMONIALS_DESCRIPTION', 'Opinión');
define('TEXT_READ_MORE', 'Leer más &raquo; ');
define('TEXT_TESTIM_BY', 'Estrito por:');
define('IMAGE_BUTTON_INSERT', 'Insertar:');

define('BOX_INFORMATION_ALLPRODS', 'Todos los productos');
define('BOX_INFORMATION_RSS', 'RSS');
define('IMAGE_BUTTON_RP_BUY_NOW', 'Comprar');
define('MY_ACCOUNT_DELETE', 'Eliminar Cuenta');

define('ENTRY_DISCOUNT_COUPON_ERROR', 'El cupón introducido no es válido.');
define('ENTRY_DISCOUNT_COUPON_AVAILABLE_ERROR', 'El cupón introducido ha superado el numero de veces de uso.');
define('ENTRY_DISCOUNT_COUPON_USE_ERROR', 'Nuestros registros indican que usted ha utilizado este cup&oacute;n %s vez(ces). Usted no puede utilizar el c&oacute;digo más de %s vez(ces).');
define('ENTRY_DISCOUNT_COUPON_MIN_PRICE_ERROR', 'El total de compra m&iacute;nima para este cup&oacute;n es de %s');
define('ENTRY_DISCOUNT_COUPON_MIN_QUANTITY_ERROR', 'El n&uacute;mero m&iacute;nimo de productos necesarios para este cup&oacute;n es de %s');
define('ENTRY_DISCOUNT_COUPON_EXCLUSION_ERROR', 'Algunos o todos los productos en su cesta est&aacute;n excluidos.' );
define('ENTRY_DISCOUNT_COUPON', 'C&oacute;digo Cup&oacute;n:');
define('ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR', 'Los cargos de env&iacute;o calculados han cambiado.');
// CATALOG_PRODUCTS_WITH_IMAGES_mod
define('BOX_CATALOG_PRODUCTS_WITH_IMAGES', 'Catálogo Imprimible');
define('BOX_CATALOG_PRODUCTS_WITH_IMAGES_FULL', 'Catálogo Imprimible Completo');
define('IMAGE_BUTTON_UPSORT', 'Ordenar Ascendente');
define('IMAGE_BUTTON_DOWNSORT', 'Ordenar Descendente');

define('TABLE_HEADING_REFERRAL', 'Recomendado por');
define('TEXT_REFERRAL_REFERRED', 'Si algún amigo, familiar o conocido le ha recomendado nuestra tienda por favor, introduzca su dirección de email aqui: ');

define('TABLE_HEADING_FEATURED_PRODUCTS', 'Productos Destacados');
define('TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY', 'Productos Destacados en %s');

// Sponsorship
define('MY_SPONSORSHIP', 'Amigo Referido');
define('TEXT_LINK_TO_ALL_GODSON', 'Detalles de Referidos');
define('TEXT_EMAIL_SPONSORSHIP', 'Tu referido es');

// Sponsorship
define('ENTRY_EMAIL_SPONSORSHIP_ERROR', "El Email de tu amigo referido no es correcto");
define('ENTRY_EMAIL_SPONSORSHIP_CHECK_ERROR', "El Email de tu amigo referido no es correcto");
define('ENTRY_EMAIL_SPONSORSHIP_ERROR_EXISTS', "El Email de tu amigo referido no existo");
define('ENTRY_SPONSORSHIP_EMAIL', 'Email de tu amigo referido:');

define('VISUAL_VERIFY_CODE_CHARACTER_POOL', 'abcdefghkmnpstwxyABCDEFGHJKMNPRSTWXY23456789FJWNVB63HDLAJAF');  //no zeros or O
define('VISUAL_VERIFY_CODE_CATEGORY', '<br />Sistema Anti-Spam (Sensitivo a may&uacute;sculas)<br />');
define('VISUAL_VERIFY_CODE_ENTRY_ERROR', 'El codigo de seguridad que has introducido no coincide con el que se muestra en la imagen. Por favor, int&eacute;ntelo de nuevo.');
define('VISUAL_VERIFY_CODE_ENTRY_TEXT', '*');

define('VISUAL_VERIFY_CODE_TEXT_INSTRUCTIONS', 'Escriba el c&oacute;digo de seguridad:');
define('VISUAL_VERIFY_CODE_BOX_IDENTIFIER', '(refrescar p&aacute;gina para renovar)');
define('TABLE_HEADING_TAGCLOUD', 'Nube de tags <span>Lo más vendido y buscado de la tienda</span>');
define('ENTRY_REMEMBER_ME', 'Recordarme');

define('MESSAGE_WAIT','Por favor espere...');
define('TEXT_PRICE_BREAKS', 'Desde');
define('TEXT_ON_SALE', 'On sale');

define('FREE_SHIPPING_TITLE', '¡Envío Gratuito!');
define('FREE_SHIPPING_DESCRIPTION', 'Gastos de envío gratuitos');


// Filtro
define('FILTRO_FILTRO', 'Fabricantes:');
define('FILTRO_ORDENAR', 'Ordenar:');
define('FILTRO_NUMERO', 'Nº Articulos:');
define('FILTRO_NO_EXISTEN', 'No existen productos que correspondan con el filtro seleccionado.');

// Paginador
define('PAGINADOR_MOSTRAR', 'Mostrando %d de %d productos');
define('PAGINADOR_MAS', 'Mostrar más productos');

define('TABLE_HEADING_IMAGEN', 'Imagen');

define('TEXT_COMMENTS_ERROR_POINT', 'Error: Debes seleccionar tu puntuación sobre el producto.');
define('TEXT_COMMENTS_ERROR_COMMENT', 'Error: Debes escribir un comentario válido.');
define('TEXT_COMMENTS_SUCCESS', 'Tu comentario se ha insertado correctamente, esta en espera de moderación');
define('TEXT_COMMENTS_LOGIN', 'Debes estar dado de %salta%s para poder introducir comentarios.');
define('TEXT_COMMENTS_DESCRIPT', '¡Tu opinión nos interesa!. Escribe tu opinión lo más clara posible para que todos podamos entenderla, evitando el spam, comentarios ofensivos, etc. ya que serán eliminados. Tu opinión será moderada antes de ser publicada, por lo que su aparición en la web puede tardar unos minutos. Si deseas ponerte en contacto con nosotros para sugerencias o críticas puedes hacerlo desde el formulario de %s contacto');
define('TEXT_COMMENTS_YOU_COMMENT', 'Tu comentario:');
define('TEXT_COMMENTS_YOU_POINT', 'Tu puntuación:');

define('TEXT_FOOTER_ADDRESS', 'Elenet Componentes SL - CIF: B86789575<br>Calle Valdeon N12 Local 17<br />CP: 28947  - Fuenlabrada - Madrid  (España)');

define('ENTRY_CIF', 'CIF');

?>