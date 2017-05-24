<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

// start the timer for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

// Cambiamos el tiempo de vida de la sesion
  ini_set( 'session.gc_maxlifetime', 2880 );

// check support for register_globals
  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {
    exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
  }

// load server configuration parameters
  if (file_exists('includes/local/configure.php')) { // for developers
    include('includes/local/configure.php');
  } else {
    include('includes/define.php');
    include('includes/configure.php');
  }

  if (strlen(DB_SERVER) < 1) {
    if (is_dir('install')) {
      header('Location: install/index.php');
    }
  }

// define the project version
  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.2 RC2a');

// some code to solve compatibility issues
  require(DIR_WS_FUNCTIONS . 'compatibility.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  $PHP_SELF = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);

  if ($request_type == 'NONSSL') {
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
  } else {
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
  }

// include the list of project filenames
  require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
  require(DIR_WS_INCLUDES . 'database_tables.php');

// customization for the design layout
  define('BOX_WIDTH', 185); // how wide the boxes should be in pixels (default: 125)

// include the database functions
  require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
 // $conn=tep_db_connect() or die('Unable to connect to database server!');
  $conn=tep_db_connect();

// set the application parameters
  $configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

// if gzip_compression is enabled, start to buffer the output
  if ( (GZIP_COMPRESSION == 'true') && ($ext_zlib_loaded = extension_loaded('zlib')) && (PHP_VERSION >= '4') ) {
    if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
      if (PHP_VERSION >= '4.0.4') {
        ob_start('ob_gzhandler');
      } else {
        include(DIR_WS_FUNCTIONS . 'gzip_compression.php');
        ob_start();
        ob_implicit_flush();
      }
    } else {
      ini_set('zlib.output_compression_level', GZIP_LEVEL);
    }
  }

// set the HTTP GET parameters manually if search_engine_friendly_urls is enabled
  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    if (strlen(getenv('PATH_INFO')) > 1) {
      $GET_array = array();
      $PHP_SELF = str_replace(getenv('PATH_INFO'), '', $PHP_SELF);
      $vars = explode('/', substr(getenv('PATH_INFO'), 1));
      for ($i=0, $n=sizeof($vars); $i<$n; $i++) {
        if (strpos($vars[$i], '[]')) {
          $GET_array[substr($vars[$i], 0, -2)][] = $vars[$i+1];
        } else {
          $_GET[$vars[$i]] = $vars[$i+1];
        }
        $i++;
      }

      if (sizeof($GET_array) > 0) {
        while (list($key, $value) = each($GET_array)) {
          $_GET[$key] = $value;
        }
      }
    }
  }

// define general functions used application-wide
  require(DIR_WS_FUNCTIONS . 'general.php');
  require(DIR_WS_FUNCTIONS . 'html_output.php');

	if ( strpos($_SERVER['REQUEST_URI'],'&amp;') && tep_db_prepare_input( $_GET['dxfilter'] ) )
	{
		header('Location: ' . HTTP_SERVER . DIR_WS_HTTP_CATALOG . str_replace( '&amp;','&',$_SERVER['REQUEST_URI'] ) );
		exit;
	}

//Cerrado por mantenimiento - Denox
if (($_SERVER['REMOTE_ADDR'] != IP) && (MANTENIMIENTO == 'Cerrar'))  { tep_redirect(tep_href_link('mantenimiento.html')); }


// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
  if (USE_CACHE == 'true') include(DIR_WS_FUNCTIONS . 'cache.php');

// include shopping cart class
  require(DIR_WS_CLASSES . 'shopping_cart.php');

// include navigation history class
  require(DIR_WS_CLASSES . 'navigation_history.php');

// check if sessions are supported, otherwise use the php3 compatible session class
  if (!function_exists('session_start')) {
    define('PHP_SESSION_NAME', 'osCsid');
    define('PHP_SESSION_PATH', $cookie_path);
    define('PHP_SESSION_DOMAIN', $cookie_domain);
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
  }

// define how the session functions will be used
  require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
  tep_session_name('osCsid');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// Determine if cookies are enabled
  setcookie("TEMPCOOKIE", "CookieOn", time() + 60 * 60);
  $cookieinfo = $_COOKIE["TEMPCOOKIE"];
  if ($cookieinfo == "CookieOn") {
    global $cookies_on;
    $cookies_on = true;
  }

// set the session cookie parameters
   if (function_exists('session_set_cookie_params')) {
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
  } elseif (function_exists('ini_set')) {
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
  }

// set the session ID if it exists
   if (isset($_POST[tep_session_name()])) {
     tep_session_id($_POST[tep_session_name()]);
   } elseif ( ($request_type == 'SSL') && isset($_GET[tep_session_name()]) ) {
     tep_session_id($_GET[tep_session_name()]);
   }

// start the session
  $session_started = false;
  if (SESSION_FORCE_COOKIE_USE == 'True') {
    tep_setcookie('cookie_test', 'please_accept_for_session', time()+60*60*24*30, $cookie_path, $cookie_domain);

    if (isset($_COOKIE['cookie_test'])) {
      tep_session_start();
      $session_started = true;
    }
  } elseif (SESSION_BLOCK_SPIDERS == 'True') {
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if (tep_not_null($user_agent)) {
      $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

      for ($i=0, $n=sizeof($spiders); $i<$n; $i++) {
        if (tep_not_null($spiders[$i])) {
          if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
            $spider_flag = true;
            break;
          }
        }
      }
    }

    if ($spider_flag == false) {
      tep_session_start();
      $session_started = true;
    }
  } else {
    tep_session_start();
    $session_started = true;
  }

  if ( ($session_started == true) && (PHP_VERSION >= 4.3) && function_exists('ini_get') && (ini_get('register_globals') == false) ) {
    extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);
  }

// set SID once, even if empty
  $SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
  if ( ($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true) ) {
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if (!tep_session_is_registered('SSL_SESSION_ID')) {
      $SESSION_SSL_ID = $ssl_session_id;
      tep_session_register('SESSION_SSL_ID');
    }

    if ($SESSION_SSL_ID != $ssl_session_id) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
  }

// verify the browser user agent if the feature is enabled
  if (SESSION_CHECK_USER_AGENT == 'True') {
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if (!tep_session_is_registered('SESSION_USER_AGENT')) {
      $SESSION_USER_AGENT = $http_user_agent;
      tep_session_register('SESSION_USER_AGENT');
    }

    if ($SESSION_USER_AGENT != $http_user_agent) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// verify the IP address if the feature is enabled
  if (SESSION_CHECK_IP_ADDRESS == 'True') {
    $ip_address = tep_get_ip_address();
    if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
      $SESSION_IP_ADDRESS = $ip_address;
      tep_session_register('SESSION_IP_ADDRESS');
    }

    if ($SESSION_IP_ADDRESS != $ip_address) {
      tep_session_destroy();
      tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
  }

// create the shopping cart & fix the cart if necesary
  if (tep_session_is_registered('cart') && is_object($cart)) {
    if (PHP_VERSION < 4) {
      $broken_cart = $cart;
      $cart = new shoppingCart;
      $cart->unserialize($broken_cart);
    }
  } else {
    tep_session_register('cart');
    $cart = new shoppingCart;
  }

// include currencies class and create an instance
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
// BOF QPBPP for SPPC
  // include the price formatter classes for the price breaks contribution
  require(DIR_WS_CLASSES . 'PriceFormatter.php');
  $pf = new PriceFormatter;
  require(DIR_WS_CLASSES . 'PriceFormatterStore.php');
  $pfs = new PriceFormatterStore;
// EOF QPBPP for SPPC

// include the mail classes
  require(DIR_WS_CLASSES . 'mime.php');
  require(DIR_WS_CLASSES . 'email.php');

// set the language
  //if (!tep_session_is_registered('language') || isset($_GET['language'])) {
  if (!tep_session_is_registered('language') || isset($_GET['language']) || empty($language)) {
    if (!tep_session_is_registered('language')) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if (isset($$_GET['language']) && tep_not_null($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

// include the language translations
  //require(DIR_WS_LANGUAGES . $language . '.php');
  require(DIR_WS_LANGUAGES . $language . '.php');

// BOF Ultimate SEO URLs EDITABLE
    if (SEO_ENABLED == 'true' or (SEO_ENABLED != 'true' and SEO_ENABLED != 'false')) {
    include_once(DIR_WS_CLASSES . 'seo.class.php');
    if ( !is_object($seo_urls) )
    {
      $seo_urls = new SEO_URL($languages_id);
    }
  }
// EOF Ultimate SEO URLs EDITABLE

// currency
  if (!tep_session_is_registered('currency') || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ) ) {
    if (!tep_session_is_registered('currency')) tep_session_register('currency');

    if (isset($_GET['currency']) && $currencies->is_set($_GET['currency'])) {
      $currency = $_GET['currency'];
    } else {
      $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
  }

/*// navigation history
  if (tep_session_is_registered('navigation')) {
    if (PHP_VERSION < 4) {
      $broken_navigation = $navigation;
      $navigation = new navigationHistory;
      $navigation->unserialize($broken_navigation);
    }
  } else {
    tep_session_register('navigation');
    $navigation = new navigationHistory;
  }
  //$navigation->add_current_page();*/

  // navigation history
if (tep_session_is_registered('navigation')) {
if (PHP_VERSION < 4) {
$broken_navigation = $navigation;
$navigation = new navigationHistory;
$navigation->unserialize($broken_navigation);
} elseif (!is_object($navigation)) {
$navigation = new navigationHistory;
}
} else {
tep_session_register('navigation');
$navigation = new navigationHistory;
}
$navigation->add_current_page();

// Shopping cart actions
  if (isset($_GET['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if ($session_started == false) {
      tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if (DISPLAY_CART == 'true') {
      $goto =  FILENAME_SHOPPING_CART;
      $parameters = array('action', 'cPath', 'products_id', 'pid');
      
/* Optional Related Products (ORP) */
      } elseif ($_GET['action'] == 'rp_buy_now') {
        $goto = FILENAME_PRODUCT_INFO;
        $parameters = array('action', 'pid', 'rp_products_id');
//ORP: end
	
    } else {
      $goto = basename($PHP_SELF);
      if ($_GET['action'] == 'buy_now') {
        $parameters = array('action', 'pid', 'products_id');
      } else {
        $parameters = array('action', 'pid');
      }
    }
    switch ($_GET['action']) {
      // customer wants to update the product quantity in their shopping cart
      case 'update_product' : for ($i=0, $n=sizeof($_POST['products_id']); $i<$n; $i++) {
                                if (in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) {
                                  $cart->remove($_POST['products_id'][$i]);
                                } else {
                                  if (PHP_VERSION < 4) {
                                    // if PHP3, make correction for lack of multidimensional array.
                                    reset($_POST);
                                    while (list($key, $value) = each($_POST)) {
                                      if (is_array($value)) {
                                        while (list($key2, $value2) = each($value)) {
                                          if (ereg ("(.*)\]\[(.*)", $key2, $var)) {
                                            $id2[$var[1]][$var[2]] = $value2;
                                          }
                                        }
                                      }
                                    }
                                    $attributes = ($id2[$_POST['products_id'][$i]]) ? $id2[$_POST['products_id'][$i]] : '';
                                  } else {
                                    $attributes = ($_POST['id'][$_POST['products_id'][$i]]) ? $_POST['id'][$_POST['products_id'][$i]] : '';
                                  }
                                  $cart->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
                                }
                              }
                              if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
      // customer adds a product from the products page
      case 'add_product' :    if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
                                $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $_POST['id'])) + $_POST['cart_quantity'], $_POST['id']);
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));    
                              break;
      // performed by the 'buy now' button in product listings and review page
      case 'buy_now' :        if (isset($_GET['products_id'])) {
                                if (tep_has_product_attributes($_GET['products_id'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['products_id']));
                                } else {
                                  $cart->add_cart($_GET['products_id'], $cart->get_quantity($_GET['products_id'])+1);
                                }
                              } 
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;

	case 'rp_buy_now' :     if (isset($_GET['rp_products_id'])) {
                                    if (tep_has_product_attributes($_GET['rp_products_id'])) {
                                      tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['rp_products_id']));
                                    } else {
                                      $cart->add_cart($_GET['rp_products_id'], $cart->get_quantity($_GET['rp_products_id'])+1);
                                    }
                                  }
                                  tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                                break;

      case 'notify' :         if (tep_session_is_registered('customer_id')) {
                                if (isset($_GET['products_id'])) {
                                  $notify = $_GET['products_id'];
                                } elseif (isset($_GET['notify'])) {
                                  $notify = $_GET['notify'];
                                } elseif (isset($_POST['notify'])) {
                                  $notify = $_POST['notify'];
                                } else {
                                  tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                                }
                                if (!is_array($notify)) $notify = array($notify);
                                for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
                                  $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$customer_id . "'");
                                  $check = tep_db_fetch_array($check_query);
                                  if ($check['count'] < 1) {
                                    tep_db_query("insert into " . TABLE_PRODUCTS_NOTIFICATIONS . " (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$customer_id . "', now())");
                                  }
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'notify'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'notify_remove' :  if (tep_session_is_registered('customer_id') && isset($_GET['products_id'])) {
                                $check_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                $check = tep_db_fetch_array($check_query);
                                if ($check['count'] > 0) {
                                  tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
                                }
                                tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action'))));
                              } else {
                                $navigation->set_snapshot();
                                tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
                              }
                              break;
      case 'cust_order' :     if (tep_session_is_registered('customer_id') && isset($_GET['pid'])) {
                                if (tep_has_product_attributes($_GET['pid'])) {
                                  tep_redirect(tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_GET['pid']));
                                } else {
                                  $cart->add_cart($_GET['pid'], $cart->get_quantity($_GET['pid'])+1);
                                }
                              }
                              tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));
                              break;
    }
  }

// include the who's online functions
  require(DIR_WS_FUNCTIONS . 'whos_online.php');
  tep_update_whos_online();

// include the password crypto functions
  require(DIR_WS_FUNCTIONS . 'password_funcs.php');

// include validation functions (right now only email address)
  require(DIR_WS_FUNCTIONS . 'validations.php');

// split-page-results
  require(DIR_WS_CLASSES . 'split_page_results.php');

// infobox
  require(DIR_WS_CLASSES . 'boxes.php');

  require(DIR_WS_FUNCTIONS . 'redemptions.php');
// auto activate and expire banners
  require(DIR_WS_FUNCTIONS . 'banner.php');
  tep_activate_banners();
  tep_expire_banners();

// auto expire special products
  require(DIR_WS_FUNCTIONS . 'specials.php');
  tep_expire_specials();

  // auto activate and expire slides
  if (STATUS_SLIDE=='true'){
  	require(DIR_WS_FUNCTIONS . 'slideshow.php');
  	tep_activate_slides();
  	tep_expire_slides();
  }


// auto expire featured products
 // require(DIR_WS_FUNCTIONS . 'featured.php');
  //tep_expire_featured();

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } elseif (isset($_GET['products_id']) && !isset($_GET['manufacturers_id'])) {
    $cPath = tep_get_product_path($_GET['products_id']);
  } else {
    $cPath = '';
  }

  if (tep_not_null($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// include the breadcrumb class and start the breadcrumb trail
  require(DIR_WS_CLASSES . 'breadcrumb.php');
  
if ( ($_GET['currency']) ) {
   tep_session_register('kill_sid');
   $kill_sid=false;
  }
if (basename($_SERVER['HTTP_REFERER']) == 'allprods.php' ) $kill_sid = true;
if ( ( !tep_session_is_registered('customer_id') ) && ( $cart->count_contents()==0 ) && (!tep_session_is_registered('kill_sid') ) ) $kill_sid = true;
if ((basename($PHP_SELF) == FILENAME_LOGIN) && ($_GET['action'] == 'process') ) $kill_sid = false;
if (basename($PHP_SELF) == FILENAME_CREATE_ACCOUNT_PROCESS) $kill_sid = false;
// Uncomment line bellow to disable SID Killer
// $kill_sid = false; 
  $breadcrumb = new breadcrumb;

// responsive-oscommerce.com   
// $breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
  $breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link(FILENAME_DEFAULT));

// add category names or the manufacturer name to the breadcrumb trail
  if (isset($cPath_array)) {
    for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {
      $categories_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int)$cPath_array[$i] . "' and language_id = '" . (int)$languages_id . "'");
      if (tep_db_num_rows($categories_query) > 0) {
        $categories = tep_db_fetch_array($categories_query);
        $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_CATEGORIES, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i+1)))));
      } else {
        break;
      }
    }
  } elseif (isset($_GET['manufacturers_id'])) {
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
    if (tep_db_num_rows($manufacturers_query)) {
      $manufacturers = tep_db_fetch_array($manufacturers_query);
      $breadcrumb->add(BOX_HEADING_MANUFACTURERS, tep_href_link(FILENAME_ALLMANUFACTURERS));
      $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_MANUFACTURERS, 'manufacturers_id=' . $_GET['manufacturers_id']));
    }
  }

// add the products name to the breadcrumb trail
  if (isset($_GET['products_id'])) {
    $model_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$_GET['products_id'] . "'");
    if (tep_db_num_rows($model_query)) {
      $model = tep_db_fetch_array($model_query);
      $breadcrumb->add($model['products_name'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
    }
  }

// initialize the message stack for output messages
  require(DIR_WS_CLASSES . 'message_stack.php');
  $messageStack = new messageStack;

// set which precautions should be checked
  define('WARN_INSTALL_EXISTENCE', 'true');
  define('WARN_CONFIG_WRITEABLE', 'true');
  define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
  define('WARN_SESSION_AUTO_START', 'true');
  define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

// HMCS: Begin Autologon	******************************************************************

if (ALLOW_AUTOLOGON == 'true') {                                // Is Autologon enabled?
  if ($PHP_SELF != FILENAME_LOGIN) {                  // yes
    if (!tep_session_is_registered('customer_id')) {
         include( DIR_WS_INCLUDES . 'autologon.php' );
	}
  }
} else {
  setcookie("email_address", "", time() - 3600, $cookie_path);  //no, delete email_address cookie
  setcookie("password", "", time() - 3600, $cookie_path);       //no, delete password cookie
}

// HMCS: End Autologon		******************************************************************
  //  OrderCheck
define('FILENAME_ORDERCHECK_FUNCTIONS', 'functions/ordercheck_functions.php');
define('FILENAME_CHECKOUT_PAYMENT_EXT', 'checkout_payment_ext.php');

define('TABLE_HOLDING_ORDERS', 'holding_orders');
define('TABLE_HOLDING_ORDERS_PRODUCTS', 'holding_orders_products');
define('TABLE_HOLDING_ORDERS_STATUS_HISTORY', 'holding_orders_status_history');
define('TABLE_HOLDING_ORDERS_PRODUCTS_ATTRIBUTES', 'holding_orders_products_attributes');
define('TABLE_HOLDING_ORDERS_PRODUCTS_DOWNLOAD', 'holding_orders_products_download');
define('TABLE_HOLDING_ORDERS_TOTAL', 'holding_orders_total');

  require(DIR_WS_FUNCTIONS . 'ajax.php');
  // Graphical Boxes
  require(DIR_WS_INCLUDES . 'mws_functions.php');


  if ($cookies_on == true) {
    if (ALLOW_AUTOLOGON == 'true') {                                // Is Autologon enabled?
      if (basename($_SERVER['PHP_SELF']) != FILENAME_LOGIN) {                  // yes
        if (!tep_session_is_registered('customer_id')) {
          include( DIR_WS_INCLUDES . 'autologon.php' );
    	}
      }
    } else {
      setcookie("email_address", "", time() - 3600, $cookie_path);  //no, delete email_address cookie
      setcookie("password", "", time() - 3600, $cookie_path);       //no, delete password cookie
    }
  }

  if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
  $customer_group_id = $_SESSION['sppc_customer_group_id'];
  } else {
   $customer_group_id = '0';
  }
  
  require_once(DIR_WS_CLASSES . 'preventDuplicates.php');
  $preventDuplicates = new preventDuplicates();
// EOF Ultimate SEO URLs EDITABLE

// Comprobamos la vista de los productos
if( empty( $_SESSION['vista'] ) )
{
	if( PRODUCT_SHOW_METHOD == 'columnas' )
		$vista = 'chng-vsta-vrtl';
	else
		$vista = 'chng-vsta-hrzt';

	tep_session_register( "vista" );
}

// Vemos si nos encontramos solo en el index
if( (basename( $_SERVER['SCRIPT_NAME'] ) == 'index.php' && count( $_GET ) == 0 && count( $_POST ) == 0) || (basename( $_SERVER['SCRIPT_NAME'] ) == 'index.php' && $_GET['language']) )
	define( 'ONLY_INDEX', true );
else
	define( 'ONLY_INDEX', false );

// Incluimos partial de productos
include(DIR_THEME. 'html/partial/_product.php');

// Incluimos las funciones de modulos
include( DIR_THEME . 'functions/general.php' );
include( DIR_THEME . 'functions/general_custom.php' );

// Incluimos las funciones
include(DIR_THEME. 'functions/functions.php');


// Inicio, cookie control
// Si no existe la cookie y deseamos eliminar todas las cookies
if( !array_key_exists( cookie_control_nombre, $_COOKIE ) && cookie_control_borrado == 'on' )
{
	// Recorremos las cookies para eliminarlas
	foreach( $_COOKIE as $key => $value )
		setcookie( $key, "", time() - 60 * 60 * 24 * 365 );
}

// Si deseamos la aceptación automatica de la cookie
if( cookie_control_automatico == 'on' && ! array_key_exists( cookie_control_nombre, $_COOKIE ) )
{
	// Si no existe la cookie y no existe la variable de session o venimos desde otra web que no sea la nuestra volvemos a recalcular el tiempo
	if( !array_key_exists( cookie_control_nombre, $_COOKIE ) && ( !preg_match( '/' . preg_replace( '/\..+$/i', '', $_SERVER['HTTP_HOST'] ) . '/', $_SERVER['HTTP_REFERER'] ) || !array_key_exists( 'dx_cookie_control', $_SESSION ) ) )
		$_SESSION['dx_cookie_control'] = new DateTime();

	// Si estamos en la página de politicas volvemos a recargar el tiempo para que nos de tiempo leer las politicas
	if( preg_match( '/-i-' . cookie_control_url . '/i', $_SERVER['REQUEST_URI'] ) )
		$_SESSION['dx_cookie_control'] = new DateTime();

	// Si existe la variable de control de tiempo de cookie, comprobamos si llevamos ya X o mas navegando
	if( array_key_exists( 'dx_cookie_control', $_SESSION ) )
	{
		// Si llevamos ya X minuto o mas eliminamos el tiempo de control de cookie y creamos la cookie de aceptacion
		$dtInterval = $_SESSION['dx_cookie_control']->diff( new DateTime() );

		if( $dtInterval->format('%i') >= cookie_control_automatico_minuto )
		{
			setcookie( cookie_control_nombre, "true", time() + (10 * 365 * 24 * 60 * 60 ) );
			unset( $_SESSION['dx_cookie_control'] );
		}
	}

	// Cookie automatico al navegar por la web
	$aAux = parse_url( $_SERVER['HTTP_HOST'] );

	// Si no acabamos de entrar en la web y la web que nos referencia es la misma que nosotros y no es estamos actualmente en la politicas de cookie, aceptamos las cookies automaticamente por que estamos navegando
	if( $_SERVER['HTTP_REFERER'] != '' && preg_match( '/^http:\/\/' . str_replace( '.', '\.', $aAux['path'] ) . '/', $_SERVER['HTTP_REFERER'] ) && !preg_match( '/-i-' . cookie_control_url . '/i', $_SERVER['REQUEST_URI'] ) )
	{
		setcookie( cookie_control_nombre, "true", time() + (10 * 365 * 24 * 60 * 60 ) );
		unset( $_SESSION['dx_cookie_control'] );
	}
}
// Fin, cookie control
