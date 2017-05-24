<?php
/*
	This is the PHP backend file for the AJAX Driven shopping cart.

	You may use this code in your own projects as long as this copyright is left
	in place.  All code is provided AS-IS.
	This code is distributed in the hope that it will be useful,
 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

	Copyright 2005 Eliot Rayner / ersd.net.
*/

require("includes/application_top.php");
// make sure we set the right character set
header('Content-type: text/html; charset='.CHARSET);

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

//Check to ensure the user is in the Shoping Cart.
if(!isset($_GET['Cart'])) {
	echo "<b>Not in Cart session !! </b></br>";
} else {

// Check to see if item should be removed
if(isset($_POST['cart_delete']) && $_POST['cart_delete'] != '' && isset($_POST['products_id']) && $_POST['products_id'] != '') {
	// customer wants to remove the product from their shopping cart
	$cart->remove($_POST['products_id']);
}

//Check to see if a ChangeQty was sent.
if(isset($_POST['quantity']) && $_POST['quantity'] != '' && isset($_POST['products_id']) && $_POST['products_id'] != '') {
	// customer wants to update the product quantity in their shopping cart

	// customer wants to update the product quantity in their shopping cart
  // attributes are working now - update by Kavita Aggarwal
          $prid = $_POST['products_id'];
	      $attributes = explode('{', substr($prid, strpos($prid, '{')+1));

          for ($i=0, $n=sizeof($attributes); $i<$n; $i++) {
            $pair = explode('}', $attributes[$i]);

            if (is_numeric($pair[0]) && is_numeric($pair[1])) {
              $_POST['id'][$pair[0]] .= $pair[1];
            } 
          }

	$cart->add_cart($_POST['products_id'], $_POST['quantity'], $_POST['id'], false);
	// attributes are working now - update by Kavita Aggarwal
}

    $info_box_contents = array();
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_REMOVE);

    $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_IMAGEN);
									
    $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_PRODUCTS);

    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_QUANTITY);

    $info_box_contents[0][] = array('align' => 'right',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_TOTAL);

    $any_out_of_stock = 0;

	$products = $cart->get_products();

	for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, popt.products_options_track_stock, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
		  $products[$i][$option]['track_stock'] = $attributes_values['products_options_track_stock'];
        }
      }
    }

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (($i/2) == floor($i/2)) {
        $info_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $info_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($info_box_contents) - 1;

	$info_box_contents[$cur_row][] = array('align' => 'center',
									'params' => 'class="productListing-data" valign="top" width="50"',
									'text' => '<img src="theme/'.THEME.'/images/general/borrar.gif" class="clicable" onClick="sendCartRemoveItem(\'' . $products[$i]['id'] . '\', \'getCart.php\',\'' . tep_session_name() . '=' . tep_session_id() . '\',\'span_cart\', \' <img src=theme/'.THEME.'/images/general/loading_sc.gif alt=loading> Por favor, espere...\', \'rem_' . $products[$i]['id'] . '\', \'' . $products[$i]['id'] . '\');"onclick="this.blur();" id="rem_'.$products[$i]['id'].'" />');

      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data" valign="center" width="53"',
                                             'text' => tep_image(DIR_WS_IMAGES . 'productos/' . $products[$i]['image'], $products[$i]['name'], 53, 53, ($products[$i]['id'] == (int)BORDADOS_ID ? 'class="brdd-cart"' : '')));
      $products_name = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><strong>' . $products[$i]['name'] . '</strong></a><br />
	  ';

      if (STOCK_CHECK == 'true') {
        if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
          $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']); 
        }else{
		$stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
		}
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i>';
        }
      }

	  if( $products[$i]['model'] )
      $products_name .= '<strong>Modelo:</strong> <a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['model'] . '</a>';

      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data" valign="center"',
                                             'text' => $products_name);

	 $info_box_contents[$cur_row][] = array('align' => 'center',
                                         	'params' => 'class="productListing-data cantidad" valign="center"',
											 'text' => '<a href="javascript:sendCartChangeQty(\'' . $products[$i]['id'] . '\', \'getCart.php\',\'' . tep_session_name() . '=' . tep_session_id() . '\',\'span_cart\', \' <img src=theme/'.THEME.'/images/general/loading_sc.gif alt=loading> Por favor, espere...<br/>\', \'qty_' . $products[$i]['id'] . '\', \'' . $products[$i]['id'] . '\', -1)">'.tep_image('theme/'.THEME.'/images/general/minusBtn.gif').'</a> '.
											 			tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" onKeyPress="if((event.keyCode==10)||(event.keyCode==13)) this.blur();" onChange="sendCartChangeQty(\'' . $products[$i]['id'] . '\', \'getCart.php\',\'' . tep_session_name() . '=' . tep_session_id() . '\',\'span_cart\', \' <img src=theme/'.THEME.'/images/general/loading_sc.gif alt=loading> Por favor, espere...<br/>\', \'qty_' . $products[$i]['id'] . '\', \'' . $products[$i]['id'] . '\', 0);" id="qty_'.$products[$i]['id'].'"').'
														 <a href="javascript:sendCartChangeQty(\'' . $products[$i]['id'] . '\', \'getCart.php\',\'' . tep_session_name() . '=' . tep_session_id() . '\',\'span_cart\', \'&nbsp;<img src=theme/'.THEME.'/images/general/loading_sc.gif alt=loading>&nbsp;Por favor, espere...<br/>\', \'qty_' . $products[$i]['id'] . '\', \'' . $products[$i]['id'] . '\', 1)">'.tep_image('theme/'.THEME.'/images/general/plusBtn.gif').'</a>'. tep_draw_hidden_field('products_id[]', $products[$i]['id']));



      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data carrito_total_individual" valign="center"',
                                             'text' => '<b>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>');
	}

	$info_box_contents[] = array('align' => 'right',
								 'params' => 'colspan="5" class="carrito_total"',
								 'text' => SUB_TITLE_SUB_TOTAL . '<strong> '.$currencies->format($cart->show_total()).'</strong>');

    if ($any_out_of_stock == 1) {
	  if (STOCK_ALLOW_CHECKOUT == 'true') {
		  $info_box_contents[] = array('align' => 'center',
									 'params' => 'colspan="5" class="stockWarning"',
									 'text' => '<b>' . OUT_OF_STOCK_CAN_CHECKOUT . '</b>');
      } else {
		  $info_box_contents[] = array('align' => 'center',
									 'params' => 'colspan="5" class="stockWarning"',
									 'text' => '<b>' . OUT_OF_STOCK_CANT_CHECKOUT . '</b>');
      }
    }

    new noborderBox($info_box_contents,true);
}
?>