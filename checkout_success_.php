<?php
/*
  $Id: checkout_success.php 1749 2007-12-21 04:23:36Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'update')) {
    $notify_string = '';

    if (isset($_POST['notify']) && !empty($_POST['notify'])) {
      $notify = $_POST['notify'];

      if (!is_array($notify)) {
        $notify = array($notify);
      }

      for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
        if (is_numeric($notify[$i])) {
          $notify_string .= 'notify[]=' . $notify[$i] . '&';
        }
      }

      if (!empty($notify_string)) {
        $notify_string = 'action=notify&' . substr($notify_string, 0, -1);
      }
    }

    tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$orders['orders_id'] . "' order by products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>
<script src="checkout/funciones_confirmation.js" type="text/javascript"></script>
<?php require(DIR_THEME. 'scripts/scripts.php'); ?>
<link href="checkout/checkout.css" rel="stylesheet" type="text/css" />
</head>


<?php require(DIR_THEME. 'html/header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<!-- left_navigation //-->
<?php //require(DIR_THEME. 'html/column_left.php'); ?>
<!-- left_navigation_eof //-->
<div id="web-cntd" class="contenido_checkout contenido_checkout_success">
<div class="progreso_contenedor">
    <div class="progreso">100%</div>
</div>
<!-- body_text //-->
<?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL'),'post', 'id="checkout_confirmation"'); ?>

<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
<p class="informacion"><?php echo TEXT_SUCCESS; ?></p>
<?php
  if ($global['global_product_notifications'] != '1') {
    echo '<p class="informacion">'.TEXT_NOTIFY_PRODUCTS . '</p>';

    $products_displayed = array();
    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
      if (!in_array($products_array[$i]['id'], $products_displayed)) {
        echo '<p class="productsNotifications">'.tep_draw_checkbox_field('notify[]', $products_array[$i]['id'], false ,'id="not_'.$products_array[$i]['id'].'"') . ' <label for="not_'.$products_array[$i]['id'].'">' . $products_array[$i]['text'].'</label></p>';
        $products_displayed[] = $products_array[$i]['id'];
      }
    }
  } else {
    echo '<p>'.TEXT_SEE_ORDERS . '</p><p>' . TEXT_CONTACT_STORE_OWNER.'</p>';
  }
?>
<h5><?php echo TEXT_THANKS_FOR_SHOPPING; ?></h5>
<div class="botonera">
    <a id="confirmar_pedido" href="javascript:void(0)">Continuar</a>
</div>
<?php if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php'); ?>
    </form>
</div>
<!-- right_navigation //-->
<?php //require(DIR_THEME. 'html/column_right.php'); ?>
<!-- right_navigation_eof //-->

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_THEME. 'html/footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
