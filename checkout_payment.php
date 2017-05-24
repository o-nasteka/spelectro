<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
  if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

// if no shipping method has been selected, redirect the customer to the shipping method selection page
  if (!tep_session_is_registered('shipping')) {
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  }

// avoid hack attempts during the checkout procedure by checking the internal cartID
  if (isset($cart->cartID) && tep_session_is_registered('cartID')) {
    if ($cart->cartID != $cartID) {
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
  }

// Stock Check
  if ( (STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true') ) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        break;
      }
    }
  }

// if no billing destination address was selected, use the customers own address as default
  if (!tep_session_is_registered('billto')) {
    tep_session_register('billto');
    $billto = $customer_default_address_id;
  } else {
// verify the selected billing address
    if ( (is_array($billto) && empty($billto)) || is_numeric($billto) ) {
      $check_address_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
      $check_address = tep_db_fetch_array($check_address_query);

      if ($check_address['total'] != '1') {
        $billto = $customer_default_address_id;
        if (tep_session_is_registered('payment')) tep_session_unregister('payment');
      }
    }
  }

  require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

  if (!tep_session_is_registered('comments')) tep_session_register('comments');
  if (isset($_POST['comments']) && tep_not_null($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }

  $total_weight = $cart->show_weight();
  $total_count = $cart->count_contents();

// load all enabled payment modules
  require(DIR_WS_CLASSES . 'payment.php');
  $payment_modules = new payment;

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_PAYMENT);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
  $breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));

  require(DIR_THEME. 'html/header.php');
?>

<script type="text/javascript"><!--
/* Points/Rewards Module V2.1rc2a bof*/
var submitter = null;
function submitFunction() {
   submitter = 1;
   }
/* Points/Rewards Module V2.1rc2a eof*/
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
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
  } else {
    document.checkout_payment.payment.checked=true;
  }
}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}
//--></script>
<?php echo $payment_modules->javascript_validation(); ?>
<?php require(DIR_THEME. 'html/column_left.php'); ?>

<?php echo tep_draw_form('checkout_payment', tep_href_link(FILENAME_CHECKOUT_CONFIRMATION, '', 'SSL'), 'post', 'onsubmit="return check_form();"', true); ?>
    
    <div class="page-header">
      <h1><?php echo HEADING_TITLE; ?></h1>
    </div>
 
  <div class="text-center">
    <ul class="pagination">
      <li><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" >' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></li>
      <li><a href="" class="active" style="pointer-events: none;"><?php echo CHECKOUT_BAR_PAYMENT; ?></a></li>
      <li><a href="" style="pointer-events: none;"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></a></li>
    </ul>
  </div>

<?php
  if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
?>

    <?php echo '<strong>' . tep_output_string_protected($error['title']) . '</strong>'; ?>

    <p class="messageStackError"><?php echo tep_output_string_protected($error['error']); ?></p>

<?php
  }
?>
<?php //-----   BEGINNING OF ADDITION: MATC   -----// 
if($_GET['matcerror'] == 'true'){
?>
<div class="alert alert-danger"><?php 
echo MATC_ERROR;
?></div>
<?php } //-----   END OF ADDITION: MATC   -----// ?>

  <h2><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></h2>
  
<p><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?></p>
  <div class="well">
    <?php echo tep_address_label($customer_id, $billto, true, ' ', '<br />'); ?>
  </div>
      <?php echo tep_draw_button(IMAGE_BUTTON_CHANGE_ADDRESS, 'icon-home', tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL'), 'btn btn-default btn-sm pull-right'); ?>
<h2><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h2>
<?php
  $selection = $payment_modules->selection();

  if (sizeof($selection) > 1) {
?>
<p><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></p>
<?php
  } else {
?>
<p><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></p>
<?php
  }

  $radio_buttons = 0;
  echo '<div class="pagos">';
	for( $i = 0, $n = sizeof( $selection ); $i < $n; $i++ )	{
		if( ( $selection[$i]['id'] == $payment ) || ( $n == 1 ) )
			echo '<div id="defaultSelected" class="moduleRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
		else
			echo '<div class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
		
		echo '<span id="' . $selection[$i]['id'] . '">';
		echo '<strong>' . $selection[$i]['module'] . '</strong>';
		
		if( sizeof( $selection ) > 1 )
			echo tep_draw_radio_field( 'payment', $selection[$i]['id'], ($selection[$i]['id'] == $payment));
		else
			echo tep_draw_hidden_field('payment', $selection[$i]['id']);

		echo '</span>';
		
		if( isset( $selection[$i]['error'] ) )
			echo '<div class="alert alert-danger">' . $selection[$i]['error'] . '</div>';
		elseif( isset( $selection[$i]['fields'] ) && is_array( $selection[$i]['fields'] ) ) 
			for( $j = 0, $n2 = sizeof( $selection[$i]['fields'] ); $j < $n2; $j++ )
				echo '<p>' . $selection[$i]['fields'][$j]['field'] . $selection[$i]['fields'][$j]['title'] . '</p>';
				
		$radio_buttons++;
		echo '</div>';
    }
  echo '</div>';
?>
<!-- Points/Rewards Module V2.1rc2a Redeemption box bof -->
<?php
/* kgt - discount coupons */
	if( MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' ) {
?>
            <h2 class="cupon"><?php echo TABLE_HEADING_COUPON; ?></h2>
	    <div class="form-group">
	      <?php echo tep_draw_input_field('coupon', '', 'size="32" class="form-control"'); ?>
	    </div>
	    
<?php
	}
/* end kgt - discount coupons */
?>
<?php
  if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
	  echo points_selection();
	  if (tep_not_null(USE_REFERRAL_SYSTEM) && (tep_count_customer_orders() == 0)) {
		  echo referral_input();
	  }
  }
?>
<!-- Points/Rewards Module V2.1rc2a Redeemption box eof -->
      
<h2><?php echo TABLE_HEADING_COMMENTS; ?></h2>
      <div class="form-group">
        <?php echo tep_draw_textarea_field('comments', 'soft', '', '5', $comments, 'class="form-control"'); ?>
      </div>
<?php
//-----   BEGINNING OF ADDITION: MATC   -----// 
if(MATC_AT_CHECKOUT != 'false'){
	require(DIR_WS_COMPONENTS . 'matc.php');
}
//-----   END OF ADDITION: MATC   -----//
?>
<p><?php echo TITLE_CONTINUE_CHECKOUT_PROCEDURE . ' &nbsp;' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?></p>
        <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', null, 'btn btn-default pull-right'); ?>      
<?php 
define('CHECKOUT_BREADCRUMB', 'checkout_payment_bar');

?>

        </form>
<?php 
  require(DIR_THEME. 'html/column_right.php'); 
  require(DIR_THEME. 'html/footer.php');; 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>
