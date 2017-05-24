<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  require('includes/classes/http_client.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping destination address was selected, use the customers own address as default
  if (!tep_session_is_registered('sendto')) {
    tep_session_register('sendto');
    $sendto = $customer_default_address_id;
  } else {
// verify the selected shipping address
    if ( (is_array($sendto) && empty($sendto)) || is_numeric($sendto) ) {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] != '1') {
        $sendto = $customer_default_address_id;
        if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
      }
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
  if (!tep_session_is_registered('cartID')) tep_session_register('cartID');
  $cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
  if ($order->content_type == 'virtual') {
    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');
    $shipping = false;
    $sendto = false;
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load all enabled shipping modules
  require(DIR_WS_CLASSES . 'shipping.php');
  $shipping_modules = new shipping;

  if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {
    $pass = false;

    switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
      case 'national':
        if ($order->delivery['country_id'] == STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'international':
        if ($order->delivery['country_id'] != STORE_COUNTRY) {
          $pass = true;
        }
        break;
      case 'both':
        $pass = true;
        break;
    }

    $free_shipping = false;
    if ( ($pass == true) && ($customer_group_id == 0)&&($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
      $free_shipping = true;

      include(DIR_WS_LANGUAGES . $language . '/modules/order_total/ot_shipping.php');
    }
  } else {
    $free_shipping = false;
  }
if ($free_shipping == false) {
  $check_free_shipping_basket_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customer_id . "'");
  while ($check_free_shipping_basket = tep_db_fetch_array($check_free_shipping_basket_query)) {
    $check_free_shipping_query = tep_db_query("select products_free_shipping from " . TABLE_PRODUCTS . " where products_id = '" . (int)$check_free_shipping_basket['products_id'] . "'");
	$check_free_shipping = tep_db_fetch_array($check_free_shipping_query);
	$check_free_shipping_array[] = $check_free_shipping['products_free_shipping'];
  }
  if ((in_array("1", $check_free_shipping_array) && !in_array("0", $check_free_shipping_array)) || (in_array("1", $check_free_shipping_array) && in_array("0", $check_free_shipping_array))) {
    $free_shipping = true;
    include_once(DIR_WS_LANGUAGES . $language . '/checkout_shipping.php');
  }
}

//Se declara para donde queremos los Envios Gratis (Esta solo para Peninsula y Portugal)
if ($order->delivery['country_id'] != STORE_COUNTRY && $order->delivery['country_id'] != '171' && FREE_SHIPPING_TO_ALL_COUNTRIES == "false")
{
  $free_shipping = false;
}

if (($order->delivery['state'] == 'Las Palmas') || ($order->delivery['state'] == 'Ceuta') || ($order->delivery['state'] == 'Melilla') || ($order->delivery['state'] == 'Santa Cruz de Tenerife')){
  $free_shipping = false;
}


// DENOX INI - Saltar el proceso de envio si solo hay una forma de envio o es envio gratuito

if ( (tep_count_shipping_modules() == 1) || ($free_shipping == true) ) {
	if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

	if ($free_shipping) {
		$quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
		$quote[0]['methods'][0]['cost'] = '0';
	} else {
		$quote = $shipping_modules->quote($method, $module);
	}

	$shipping = array('id' => $quote[0]['methods'][0]['id'].'_'.$quote[0]['methods'][0]['id'],
					  'title' => (($free_shipping == true) ? $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
					  'cost' => $quote[0]['methods'][0]['cost']);

tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
}
// DENOX FIN - Saltar el proceso de envio si solo hay una forma de envio o es envio gratuito

// process the selected shipping method
  if ( isset($_POST['action']) && ($_POST['action'] == 'process') ) {
    if (!tep_session_is_registered('comments')) tep_session_register('comments');
    if (tep_not_null($_POST['comments'])) {
      $comments = tep_db_prepare_input($_POST['comments']);
    }

    if (!tep_session_is_registered('shipping')) tep_session_register('shipping');

    if ( (tep_count_shipping_modules() > 0) || ($free_shipping == true) ) {
      if ( (isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_')) ) {
        $shipping = $_POST['shipping'];

        list($module, $method) = explode('_', $shipping);
        if ( is_object($$module) || ($shipping == 'free_free') ) {
          if ($shipping == 'free_free') {
            $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
            $quote[0]['methods'][0]['cost'] = '0';
          } else {
            $quote = $shipping_modules->quote($method, $module);
          }
          if (isset($quote['error'])) {
            tep_session_unregister('shipping');
          } else {
            if ( (isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost'])) ) {
			  $shipping = array('id' => $quote[0]['methods'][0]['id'].'_'.$quote[0]['methods'][0]['id'],
                                'title' => (($free_shipping == true) ?  $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                                'cost' => $quote[0]['methods'][0]['cost']);

              tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
            }
          }
        } else {
          tep_session_unregister('shipping');
        }
      }
    } else {
      $shipping = false;
                
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
    }    
  }

// get all available shipping quotes
  $quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the cheapest method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the cheapest shipping
// method if more than one module is now enabled
  if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ) $shipping = $shipping_modules->cheapest();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SHIPPING);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
?>


<?php require(DIR_THEME. 'html/header.php'); ?>
<script type="text/javascript"><!--
var selected;

function selectRowEffect(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_address.shipping[0]) {
    document.checkout_address.shipping[buttonSelect].checked=true;
  } else {
    document.checkout_address.shipping.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>


<?php require(DIR_THEME. 'html/column_left.php'); ?>


<?php echo tep_draw_form('checkout_address', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL')) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

<div class="text-center">
  <ul class="pagination">
    <li><a href="" class="active disable-link"><?php echo CHECKOUT_BAR_DELIVERY; ?></a></li>
    <li><a href="" class="disable-link"><?php echo CHECKOUT_BAR_PAYMENT; ?></a></li>
    <li><a href="" class="disable-link"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></a></li>
  </ul>
 </div>

<h2><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></h2>

<p class="alert alert-info">
	<?php echo TEXT_CHOOSE_SHIPPING_DESTINATION ; ?>
</p>
<p class="infoBoxContents">
	<?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br />'); ?>
</p>
      <?php echo tep_draw_button(IMAGE_BUTTON_CHANGE_ADDRESS, 'icon-home', tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL'), 'btn btn-default pull-right'); ?>
<?php
  if (tep_count_shipping_modules() > 0) {
?>
<h2><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h2>
<?php
    if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
<p class="informacion">
	<?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?>
</p>
<?php
    } elseif ($free_shipping == false) {
?>
<p class="informacion">
	<?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?>
</p>
<?php
    }

    if ($free_shipping == true) {
?>
<p>
	<b><?php echo FREE_SHIPPING_TITLE; ?></b>&nbsp;<?php echo $quotes[$i]['icon']; ?>
</p>
<p>
	<?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?>
</p>
<?php
    } else {
      $radio_buttons = 0;
      for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
		  echo '<div class="envios">';
?>
<p>
	<b><?php echo $quotes[$i]['module']; ?></b>&nbsp;<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?>
</p>    
<?php
        if (isset($quotes[$i]['error'])) {
?>
<div class="alert alert-danger"><?php echo $quotes[$i]['error']; ?></div>

<?php
        } else {
          for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
// set the radio button to be checked if it is the method chosen
            $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);

            if ( ($checked == true) || ($n == 1 && $n2 == 1) ) {
              echo '      <div id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
            } else {
              echo '      <div class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
            }
?>

                    <p><?php echo $quotes[$i]['methods'][$j]['title']; ?> <strong>
<?php
            if ( ($n > 1) || ($n2 > 1) ) {
?>
                    <?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?> 
		    <?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked); ?></strong></p>
<?php
            } else {
?>
                    <?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></strong></p>
<?php
            }
?>
<?php
            $radio_buttons++;
          echo '</div>';
		  }
		  
        }
?>
<?php
      echo '</div>';}
    }
?>
<?php
  }
?>
<h2><?php echo TABLE_HEADING_COMMENTS; ?></h2>
<p>
        <?php echo tep_draw_textarea_field('comments', 'soft', '', '5', '', 'class="form-control"'); ?>
</p>

<p><?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br />' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></p>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', null, 'btn btn-default pull-right'); ?>
<?php 
define('CHECKOUT_BREADCRUMB', 'checkout_shipping_bar');

?>

        </form>

<?php
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
