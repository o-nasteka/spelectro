<?php
/*
  $Id: english.php,v 1.107 2003/02/17 11:49:25 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
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
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="pt"');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Criar Conta');
define('HEADER_TITLE_MY_ACCOUNT', 'Conta Pessoal');
define('HEADER_TITLE_ACCOUNT_HISTORY', 'Meus Pedidos');
define('HEADER_TITLE_CART_CONTENTS', 'Compras');
define('HEADER_TITLE_CHECKOUT', 'Saída');
define('HEADER_TITLE_TOP', 'Início');
define('HEADER_TITLE_CATALOG', 'Catálogo');
define('HEADER_TITLE_LOGOFF', 'Sair');
define('HEADER_TITLE_LOGIN', 'Entrar');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', ' visitas desde');


// text for gender
define('MALE', 'Masculino');
define('FEMALE', 'Feminino');
define('MALE_ADDRESS', 'Sr.');
define('FEMALE_ADDRESS', 'Sra.');
define('ENTRY_SUBJECT', 'Asunto/Num. Pedido');
define('TEXT_COPY_CUSTOMER', 'Enviar Copia');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// categories box text in includes/boxes/categories.php
define('BOX_HEADING_CATEGORIES', 'Categorias');

// manufacturers box text in includes/boxes/manufacturers.php
define('BOX_HEADING_MANUFACTURERS', 'Marcas');
define('BOX_TEXT_MANUFACTURERS', 'Seleccione uno o vea todos');

// whats_new box text in includes/boxes/whats_new.php
define('BOX_HEADING_WHATS_NEW', 'Novidades');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_HEADING_SEARCH', 'Pesquisa Rápida');
define('BOX_SEARCH_TEXT', 'Use palavras-chave para pesquisar o artigo que deseja.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Pesquisa Avançada');

// specials box text in includes/boxes/specials.php
define('BOX_HEADING_SPECIALS', 'Promoções');

// reviews box text in includes/boxes/reviews.php
define('BOX_HEADING_REVIEWS', 'Comentários');
define('BOX_REVIEWS_WRITE_REVIEW', 'Escreva um comentário sobre este artigo!');
define('BOX_REVIEWS_NO_REVIEWS', 'Não existem comentários.');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s em 5 estrelas!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_HEADING_SHOPPING_CART', 'Compras');
define('BOX_SHOPPING_CART_EMPTY', '0 artigos');

// order_history box text in includes/boxes/order_history.php
define('BOX_HEADING_CUSTOMER_ORDERS', 'Histórico de Encomendas');

// best_sellers box text in includes/boxes/best_sellers.php
define('BOX_HEADING_BESTSELLERS', 'Os Mais Vendidos');
define('BOX_HEADING_BESTSELLERS_IN', 'Os Mais Vendidos em<br>&nbsp;&nbsp;');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_HEADING_NOTIFICATIONS', 'Notificações');
define('BOX_NOTIFICATIONS_NOTIFY', 'Informe-me das alterações de <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Não me informe das alterações de <b>%s</b>');

// manufacturer box text
define('BOX_HEADING_MANUFACTURER_INFO', 'Informações da Marca');
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Outros artigos');

// languages box text in includes/boxes/languages.php
define('BOX_HEADING_LANGUAGES', 'Línguas');

// currencies box text in includes/boxes/currencies.php
define('BOX_HEADING_CURRENCIES', 'Moeda');

// information box text in includes/boxes/information.php
define('BOX_HEADING_INFORMATION', 'Informação');
define('BOX_HEADING_INFORMATION_PAR', 'Informaci&oacute;n');
define('BOX_HEADING_INFORMATION_IMPAR', 'GUIAS');
define('BOX_INFORMATION_PRIVACY', 'Termos de Privacidade');
define('BOX_INFORMATION_CONDITIONS', 'Condições de Utilização');
define('BOX_INFORMATION_SHIPPING', 'Envios e Devoluções');
define('BOX_INFORMATION_CONTACT', 'Enviar Comentários');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_HEADING_TELL_A_FRIEND', 'Indique a um amigo');
define('BOX_TELL_A_FRIEND_TEXT', 'Informe um amigo deste artigo.');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'morada de entrega');
define('CHECKOUT_BAR_PAYMENT', 'pagamento');
define('CHECKOUT_BAR_CONFIRMATION', 'confirmação');
define('CHECKOUT_BAR_FINISHED', 'finalizar');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Seleccione');
define('TYPE_BELOW', 'Escreva');

// javascript messages
define('JS_ERROR', 'Ocorreram erros durante o envio do formulário!\nAltere o seguinte:\n\n');

define('JS_REVIEW_TEXT', '* O \'Comentário\' tem de ter pelo menos ' . REVIEW_TEXT_MIN_LENGTH . ' letras.\n');
define('JS_REVIEW_RATING', '* Tem de pontuar o artigo (de 1 a 5 estrelas).\n');

define('JS_GENDER', '* O valor \'Sexo\' tem de ser escolhido.\n');
define('JS_FIRST_NAME', '* O seu \'Primeiro Nome\' tem de ter pelo menos' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.\n');
define('JS_LAST_NAME', '* O seu \'Último Nome\' tem de ter pelo menos' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.\n');
define('JS_DOB', '* A \'Data de Nascimento\' tem que estar no formato seguinte: xx/xx/xxxx (dia/mês/ano).\n'); 
define('JS_EMAIL_ADDRESS', '* O \'E-Mail\' tem de ter pelo menos' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.\n');
define('JS_ADDRESS', '* A \'Morada\' tem de ter pelo menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.\n');
define('JS_POST_CODE', '* O \'Código Postal\' tem de ter pelo menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras.\n');
define('JS_CITY', '* A \'Cidade\' tem de ter pelo menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.\n');
define('JS_STATE', '* O \'Distrito\' tem de ser seleccionado.\n');
define('JS_STATE_SELECT', '-- Seleccione --');
define('JS_ZONE', '* O \'Distrito\' tem de ser seleccionado da lista deste país.\n');
define('JS_COUNTRY', '* O \'País\' tem de ser seleccionado.\n');
define('JS_TELEPHONE', '* O \'Número de Telefone\' tem de ter pelo menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.\n');
define('JS_PASSWORD', '* A \'Password\' e a sua  \'Confirmação\' não condizem ou têm menos de ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Seleccione um método de pagamento.\n');
define('JS_ERROR_SUBMITTED', 'Este pedido já foi enviado. Por favor prima Ok e espere que o processo termine.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Por favor, seleccione um método de pagamento.');

define('CATEGORY_COMPANY', 'Informações sobre a Empresa');
define('CATEGORY_PERSONAL', 'Informações Pessoais');
define('CATEGORY_ADDRESS', 'Morada');
define('CATEGORY_CONTACT', 'Contato');
define('CATEGORY_OPTIONS', 'Opções');
define('CATEGORY_PASSWORD', 'Senha');
define('ENTRY_COMPANY', 'Empresa:');
define('ENTRY_COMPANY_ERROR', '*');
define('ENTRY_COMPANY_TEXT', '*');
// BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID', 'NIPC / DNI:');
define('ENTRY_COMPANY_TAX_ID_ERROR', 'Debe ingresar el DNI/NIPC');
define('ENTRY_COMPANY_TAX_ID_TEXT', '*');
// EOF Separate Pricing Per Customer
define('ENTRY_GENDER', 'Sexo:');
define('ENTRY_GENDER_ERROR', '*');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Nome:');
define('ENTRY_FIRST_NAME_ERROR', 'Seu Nome deve ser de pelo menos ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Sobrenome:');
define('ENTRY_LAST_NAME_ERROR', 'Seu Sobrenome deve ter pelo menos ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Data de Nascimento:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Sua data de nascimento deve ter este formato: DD / MM / AAAA (p.ex., 21/05/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (p.ej. 21/05/1970)');
define('ENTRY_DISCOUNT_COUPON_ERROR', 'El cupón de Descuento que has introducido no es valido.');
define('ENTRY_DISCOUNT_COUPON_AVAILABLE_ERROR', 'El cupón de Descuento que has introducido no esta disponible.');
define('ENTRY_DISCOUNT_COUPON_USE_ERROR', 'Ya has usado este cupón %s veces.  Tu no puedes utilizar este cupón mas de %s veces.');
define('ENTRY_DISCOUNT_COUPON_MIN_PRICE_ERROR', 'La cantidad minima del total de la compra para usar este cupón es de %s');
define('ENTRY_DISCOUNT_COUPON_MIN_QUANTITY_ERROR', 'El minima numero de productos que debes de comprar para usar este cupón es de %s');
define('ENTRY_DISCOUNT_COUPON_EXCLUSION_ERROR', 'Alguno de los productos de tu carro está excluido de la oferta del cupon.' );
define('ENTRY_DISCOUNT_COUPON', 'C&oacute;digo Descuento:');
define('ENTRY_DISCOUNT_COUPON_SHIPPING_CALC_ERROR', 'El calculo de tus gastos de envios ha cambiado.');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_REPEAT_EMAIL', 'Repita E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Seu e-mail deve ser de pelo menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'O seu endereço de e-mail não parece válido - por favor, faça as alterações necessárias.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Seu e-mail já entre os nossos clientes - pode acessar sua conta com esse endereço ou criar uma nova conta com um endereço diferente.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Morada:');
define('ENTRY_STREET_ADDRESS_ERROR', 'O seu endereço deve ser de pelo menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Freguesia:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Código Postal:');
define('ENTRY_POST_CODE_ERROR', 'O código postal deve ser de pelo menos '. ENTRY_POSTCODE_MIN_LENGTH. ' dígitos.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Cidade:');
define('ENTRY_CITY_ERROR', 'Sua cidade deve ter pelo menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Distrito:');
define('ENTRY_STATE_ERROR', 'Sua província deve ter pelo menos '. ENTRY_STATE_MIN_LENGTH. ' letras.');
define('ENTRY_STATE_TEXT', 'Por favor seleccione a partir da lista suspensa.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'País:');
define('ENTRY_COUNTRY_ERROR', 'É obligatório preencher a paós.');
define('ENTRY_OTHER_ADDRESS', 'Se você tem um endereço de entrega diferente do faturamento, você pode adicioná-lo ao requisitar');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_COUNTRY_TEXT', 'É obligatório preencher a cidade que vives.');
define('ENTRY_TELEPHONE_NUMBER', 'Telefone:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'O seu número de telefone deve estar pelo menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Fax:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Receber Novidades:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscrever');
define('ENTRY_NEWSLETTER_NO', 'Não Subscrever');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Senha:');
define('ENTRY_PASSWORD_ERROR', 'Sua senha deve ter ao menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'A confirmaçao da senha deve ser igual a senha.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Confirmação da Senha:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_ERROR', 'Sya Palavra passe deve estar pelo menos' . ENTRY_PASSWORD_MIN_LENGTH . ' letras</font></small>');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Sua senha deve ter ao menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_NEW', 'Nueva Contrase&ntilde;a:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Su contrase&ntilde;a nueva debe tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'La confirmación de su contrase&ntilde;a debe coincidir con su contrase&ntilde;a nueva.');
define('PASSWORD_HIDDEN', '--OCULTA--');
//NIF start
define('ENTRY_NIF', 'Nùmero de contribuinte:');
define ('ENTRY_NO_NIF_ERROR', 'Você tem que digitar sua identificação fiscal.');
define ('ENTRY_FORMATO_NIF_ERROR', 'A identificação fiscal deve ter 9 caracteres para o NIF, preencher com zeros à esquerda se necessário.');
define ('ENTRY_LETRA_NIF_ERROR', 'A letra do NIF está incorreta.');
define('ENTRY_NIF_TEXT', '*');
define('ENTRY_NIF_EXAMPLE', '(por exemplo: 01234567L)');
//NIF end
define('MATC_CONDITION_AGREEMENT', 'Eu aceito os <a href="%s" target="_blank"><strong><u>Termos e Condições</u></strong></a> deste site: ');
define('MATC_HEADING_CONDITIONS', 'Aceitar Termos e Condições');
define('MATC_ERROR', 'Você deve aceitar os termos e condições de uso para continuar.');
define('ENTRY_SEARCH', 'Pesquisar...');
define('ENTRY_READ_MORE', 'Ler+');
define('ENTRY_CHNG_VST', 'Alterar ver');
define('ENTRY_DEVELOPED', 'Desenvolvido pela:');

define('ENTRY_HOME', 'In&iacute;cio');
define('ENTRY_NEWS', 'Novo');
define('ENTRY_OFFERS', 'Ofertas');
define('ENTRY_INFORMATION', 'Informação');
define('ENTRY_CONTACT', 'Contato');
define('ENTRY_SEARCH', 'Pesquisar...');
define('ENTRY_TAX_INCL', 'IVA Incl.');

define('FORM_REQUIRED_INFORMATION', '* Dato Obligatorio');
// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Pág.');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Produtos de <b>%d</b> a <b>%d</b> (Total <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Encomendas de <b>%d</b> a <b>%d</b> (Total <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Comentários de <b>%d</b> a <b>%d</b> (Total <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Novos Produtos de <b>%d</b> a <b>%d</b> (Total <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW2', '%d');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Promoções de <b>%d</b> a <b>%d</b> (Total <b>%d</b> promoções)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Primeira Página');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Página Anterior');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Página Seguinte');
define('PREVNEXT_TITLE_LAST_PAGE', 'Última Página');
define('PREVNEXT_TITLE_PAGE_NO', 'Página %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Conjunto de %d Páginas Anteriores');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Conjunto de %d Páginas Seguintes');
define('PREVNEXT_BUTTON_FIRST', 'Primeira');
define('PREVNEXT_BUTTON_PREV', 'Anterior');
define('PREVNEXT_BUTTON_NEXT', 'Seguinte');
define('PREVNEXT_BUTTON_LAST', 'Última');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Adicionar Endereço');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Livro de Endereços');
define('IMAGE_BUTTON_BACK', 'Voltar');
define('IMAGE_BUTTON_BUY_NOW', 'Compre Ahora');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Alterar Morada');
define('IMAGE_BUTTON_CHECKOUT', 'Saída');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirmar Encomenda');
define('IMAGE_BUTTON_CONTINUE', 'Continuar');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continuar a Comprar');
define('IMAGE_BUTTON_DELETE', 'Apagar');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Editar Conta Pessoal');
define('IMAGE_BUTTON_HISTORY', 'Histórico de Encomendas');
define('IMAGE_BUTTON_LOGIN', 'Entrar');
define('IMAGE_BUTTON_IN_CART', 'Encomendas');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Notificações');
define('IMAGE_BUTTON_QUICK_FIND', 'Pesquisa Rápida');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remover Notificações');
define('IMAGE_BUTTON_REVIEWS', 'Comentários');
define('IMAGE_BUTTON_SEARCH', 'Pesquisa');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Opções de Envio');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Indicar a um Amigo');
define('IMAGE_BUTTON_UPDATE', 'Alterar');
define('IMAGE_BUTTON_UPDATE_CART', 'Actualizar');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Escrever um Comentário');

define('SMALL_IMAGE_BUTTON_DELETE', 'Eliminar');
define('SMALL_IMAGE_BUTTON_EDIT', 'Modificar');
define('SMALL_IMAGE_BUTTON_VIEW', 'Ver');
define('ICON_ARROW_RIGHT', 'mais');
define('ICON_CART', 'Encomendas');
define('ICON_ERROR', 'Error');
define('ICON_SUCCESS', 'Correcto');
define('ICON_WARNING', 'Atenção');

define('TEXT_GREETING_PERSONAL', 'Bem vindo de novo <span class="greetUser">%s!</span>');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Caso não seja %s, por favor <a href="%s"><u>registe-se</u></a> com os seus dados correctos.</small>');
define('TEXT_GREETING_GUEST', 'Bem vindo! Deseja <a href="%s"><u>inscrever-se</u></a>? ou <a href="%s"><u>criar uma nova conta cliente</u></a>?');
define('TEXT_CLICK_TO_ENLARGE', 'Clique para ampliar'); 
define('TEXT_SORT_PRODUCTS', 'Ordenação de artigos');
define('TEXT_DESCENDINGLY', 'decrescente');
define('TEXT_ASCENDINGLY', 'crescente');
define('TEXT_BY', ' por ');

define('TEXT_REVIEW_BY', 'por %s');
define('TEXT_REVIEW_WORD_COUNT', '%s palavras');
define('TEXT_REVIEW_RATING', 'Pontuação: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Data de introdução: %s');
define('TEXT_NO_REVIEWS', 'Actualmente não há comentários sobre este artigo.');

define('TEXT_NO_NEW_PRODUCTS', 'Actualmente não há artigos.');

define('TEXT_UNKNOWN_TAX_RATE', 'Taxa desconhecida');

define('TEXT_REQUIRED', 'Necessário');
define('ENTRY_PASSWORD_CURRENT', 'Senha Atual:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Não foi possível enviar o correio electrónico através do servidor SMTP configurado. Verifique o ficheiro php.ini e corrija os dados referentes ao servidor SMTP caso seja mecessário.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'ATENÇÃO: A pasta de instalação existe aqui: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/install. Elimine esta pasta por motivos de segurança.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'ATENÇÃO: É possível escrever no ficheiro de configuração: ' . dirname($_SERVER['SCRIPT_FILENAME']) . '/includes/configure.php. Isto é um potencial problema de seguraça - defina as permissões certas neste ficheiro.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'ATENÇÃO: O directório de sessões não existe: ' . tep_session_save_path() . '. As sessões não irão funcionar até que esta pasta esteja criada.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'ATENÇÃO: Não é possível escrever na pasta das sessões: ' . tep_session_save_path() . '. As Sessões não irão funcionar até as permissões estejam correctamente definidas.');
define('WARNING_SESSION_AUTO_START', 'ATENÇÃO: session.auto_start está activo - desactive esta opção do PHP no ficheiro php.ini e reinicie o servidor web.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'ATENÇÃO: A pasta dos produtos disponíveis para download não existe: ' . DIR_FS_DOWNLOAD . '. Os downloads não irão funcionar até que a pasta seja válida.');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'A data de expiração do cartão de crédito inserida não é válida.<br>Verifique a data e tente novamente.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'O número de cartão de crédito inserido não é válido.<br>Verifique o número do cartão e tente novamente.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Os primeiros quatro dígitos do número inserido são: %s<br>Se este número está correcto, não aceitamos actualmente esse tipo de cartão de crédito.<br>Se inseriu os números errados, tente novamente.');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'La fecha de caducidad de la tarjeta de cr&eacute;dito es incorrecta.<br />Compruebe la fecha e int&eacute;ntelo de nuevo.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'El n&uacute;mero de la tarjeta de cr&eacute;dito es incorrecto.<br />Compruebe el numero e int&eacute;ntelo de nuevo.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Los primeros cuatro digitos de su tarjeta son: %s<br />Si este n&uacute;mero es correcto, no aceptamos este tipo de tarjetas.<br />Si es incorrecto, int&eacute;ntelo de nuevo.');
/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.
  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/

define('MY_ACCOUNT_DELETE', 'Excluir Conta');

define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');
define('STAR_TITLE', 'Producto estrella'); // star product
define('STAR_READ_MORE', ' ... leer m&aacute;s.'); // ... read more.

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

@$news_text =( implode ('', file( 'Espanol_news.txt' ) ) );

//aqu&iacute; defines los textos en tu idioma
define('TABLE_HEADING_NEWS', 'Ultimas noticias');
define('TEXT_NEWS', $news_text );

define('BOX_CATALOG_FEATURED_PRODUCTS', 'Productos Destacados');
// featured box text in includes/boxes/featured.php
define('BOX_HEADING_FEATURED', '<img src="theme/enuc/images/titulos/destacados.jpg" alt="Productos destacadas" />');

//banner header - banner multilingue de la cabecera
define('BANNER_GROUP_1DWN', '468x50_es');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a>');

//Short description in products modules Start
define('DESCR_READ_MORE', 'Leia mais'); // ... read more.
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
define('MY_ACCOUNT_DELETE', 'Eliminar Cuenta');

define('BOX_HEADING_NEWSDESK_CATEGORIES', 'Categorias de Noticias');
define('BOX_HEADING_NEWSDESK_LATEST', 'Ultimas Noticias');

define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', 'Mostrando <b>%d</b> a <b>%d</b> (de <b>%d</b> art&iacute;culos)');
//END -- newsdesk

// box text in includes/boxes/live_support.php
define('BOX_HEADING_LIVESUPPORT', 'Live Support');
define('BOX_INFO_LIVESUPPORT', 'Chatear con Dpto soporte!');

define('BOX_HEADING_SHOP_BY_PRICE', 'Productos <span>¿Cuánto quieres gastar?</span>');

// BOF Separate Pricing Per Customer
define('ENTRY_COMPANY_TAX_ID', 'Nº Identificaci&oacute;n (Solo Clientes especiales):');
define('ENTRY_COMPANY_TAX_ID_ERROR', '');
define('ENTRY_COMPANY_TAX_ID_TEXT', '');
// EOF Separate Pricing Per Customer
define('BOX_HEADING_PHPONLINE', 'Dpto de SOPORTE');
define('BOX_INFORMATION_PHPONLINE', 'Chatea con Dpto de SOPORTE');

define('BOX_INFORMATION_PDF_CATALOGUE', 'Catalogo Descargable'); // PDF Catalog v.1.51

define('TABLE_HEADING_ESTIMATED_SHIPPING', 'Gastos de Env&iacute;o Estimados');

	define('BOX_INFORMATION_RSS', 'Catalogo Feed');
	define('BOX_INFORMATION_RSS_CATEGORY', 'Categorias RSS Feed');
	define('BOX_INFORMATION_RSS_MANUFACTURER', 'Fabricantes RSS Feed');
	define('IMAGE_BUTTON_MORE_INFO', 'M&aacute;s informaci&oacute;n');

define('OPENING_HOURS','<p><strong>Horario Atenci&oacute;n al Cliente:</strong></p>
<p>De Lunes a Viernes	: 9:30h a 14:00h</p>
<p>16:30h a 19:00h</p>');

define('EXTRA_SUBJECT_CUSTOMER','[Consulta desde E-nuc.com]');

define('FREE_SHIPPING_TITLE', 'Gastos de env&iacute;o &iexcl;Gratis!');
define('FREE_SHIPPING_DESCRIPTION', 'Los gastos de env&iacute;o para estos productos son gratuitos');   


define('DENOX_PRODUCTO_SIGUIENTE', 'Próximo produto');
define('DENOX_PRODUCTO_ANTERIOR', 'Produto Anterior');
define('DENOX_BUSCAR', 'Pesquisa');
define('DENOX_CESTA_ESTADO', 'Estado do carrinho de compras');
define('DENOX_CESTA_VACIA', 'Cesta vazia');
define('DENOX_PRODUCTOS_VALOR', 'Produtos no valor de');
define('DENOX_VER_CESTA', 'Ver carrinho');
define('DENOX_FINALIZAR_COMPRA', 'Finalizar');
define('DENOX_CONECTAR_CUENTA', 'Conectar-se a sua conta de usuário');
define('DENOX_CREAR_CUENTA', 'Registrar');
define('DENOX_RECUPERAR_CLAVE', 'Esqueceu sua senha <span>aqui</span>');
define('DENOX_PRODUCTOS_DESTACADOS', 'Produtos em destaque');
define('DENOX_ACTUALMENTE', 'Atualmente temos');
define('DENOX_ACTUALMENTE_ESTAS', 'Está actualmente em &raquo; ');
define('DENOX_PRODUCTOS', 'produtos');
define('DENOX_EN_ESTE_MOMENTO', 'Existem atualmente');
define('DENOX_VISITANTES', 'visitantes online');

define('DENOX_PRODUCTOS_CATEGORIAS', 'Produtos nesta categoria');
define('DENOX_USUARIO_HISTORIAL', 'História da encomenda');
define('DENOX_USUARIO_INFO', 'Informações Gerais');
define('DENOX_USUARIO_MODIFICAR', 'Alterar senha');
define('DENOX_USUARIO_DATOS', 'Dados Pessoais');
define('DENOX_USUARIO_DIRECCIONES', 'Address Management');
define('DENOX_USUARIO_DESCONECTAR', 'Desligar');
define('PIE', 'Preços de venda podem ser alterados sem aviso prévio, exceto typo todos os preços não incluem IVA.');
define('PIE2', 'Preços de venda podem ser alterados sem aviso prévio, exceto typo todos os preços incluem IVA.');

define('DENOX_ESTADO_PRODUCTO', 'Status do Produto');
define('DENOX_SIN_STOCK', 'Fora de Stock / Book');
define('DENOX_ULTIMAS_UNIDADES', '&Uacute;ltima');
define('DENOX_ENVIO_INMEDIATO', 'Enviar imediatamente');

define('DENOX_CARACTERISTICAS', 'Características');
define('DENOX_ESCRIBIR_COMENTARIO', 'Escreva a revisão');
define('DENOX_COMENTARIOS', 'comentários');
define('DENOX_NINGUN_COMENTARIO', '0 comentários');
define('DENOX_COMPARTIR', 'Compartilhar');
define('DENOX_SIN_IMAGEN', 'Nenhuma imagem');
define('DENOX_TEXTO_NEWSLETTER', 'Sim, eu quero ser o último e receber (não mais de uma vez por mês), as últimas notícias para ficar atualizado e melhores ofertas');
define('ENTRY_FORMATO_NIF_ERROR2', 'El NIF o NIPC No es correcto, verifique que lo ha escrito correctamente.');
define('DENOX_CONTACTO', 'Contato');
define('DENOX_CONFIRMACION_PEDIDO', 'Confirmação da Encomenda');
define('DENOX_SUBIR', 'Acima');
define('DENOX_MODO_REGISTRO','Seleccione la manera en la que desea registrarse:');

define('COMPRAR','ADICIONAR');

define('FB','fb_pt');

define('FILTRO_NO_EXISTEN', 'Não existe um produto que corresponde ao filtro selecionado.');

define('IZQ1','izq1_pt');
define('IZQ2','izq2_pt');
define('IZQ3','izq3_pt');
define('DER1','der1_pt');
define('DER2','der2_pt');
define('DER2','der3_pt');

define('DENOX_PRODUCTOS_RELACIONADOS', 'Produtos relacionados');
define('DENOX_COMENTARIOS', 'Comentários');

define('TEXT_RELATED_PRODUCTS', 'Other Products You Might Like');
define('RELATED_PRODUCTS_MODEL_COMBO', ' (%s)');
define('RELATED_PRODUCTS_PRICE_TEXT', '- %s');
define('RELATED_PRODUCTS_QUANTITY_TEXT', 'Only %s left!');

define('NO_HAY_COMENTARIOS', 'Este produto não tem nenhum comentário até agora');	
define('INSERTAR_COMENTARIO', 'Inserir comentário');	
define('IMAGEN_ADJUNTA', 'Imagem anexada');	
define('IMAGEN', 'Imagem');	
define('NOMBRE', 'Nome');	
define('TITULO', 'Título');	
define('CONTENIDO', 'Conteúdo');	
define('ESCRIBE_ESTO', 'Escreve esta');	
define('RECARGAR_CAPTCHA', 'Atualizar captcha');	
define('ENVIAR', 'Enviar');	
define('COMENTAR', 'Responder');	

define('TEXT_COMMENTS_ERROR_POINT', 'Erro: Você deve selecionar a sua classificação para o produto.');
define('TEXT_COMMENTS_ERROR_COMMENT', 'Erro: Por favor insira um comentário válido.');
define('TEXT_COMMENTS_SUCCESS', 'O seu comentário foi inserido corretamente, aguarda moderação');
define('TEXT_COMMENTS_LOGIN', 'Você deve ser %sregistrado%s para postar um novo comentário.');
define('TEXT_COMMENTS_DESCRIPT', 'A sua opinião conta!. Escreva sua tão clara quanto possível para que todos possamos compreender, prevenir ofensiva, spam, etc. uma vez que irá ser eliminado. Sua opinião será moderado antes de ser publicado, pelo que a sua aparência na web pode levar alguns minutos. Se você quiser entrar em contato para sugestões ou críticas você pode fazer isso a partir do formulário de %s contato');
define('TEXT_COMMENTS_YOU_COMMENT', 'Seu comentario:');
define('TEXT_COMMENTS_YOU_POINT', 'Seu índice:');

define('TEXT_FOOTER_ADDRESS', 'Avd. San Antón, 83<br />CEP: 29018  -  Málaga  (Espanha)');

define('ENTRY_CIF', 'NIPC');
?>