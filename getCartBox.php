<?php
/*
	This is the PHP backend file for the AJAX Driven shopping cart info box.

	You may use this code in your own projects as long as this copyright is left
	in place.  All code is provided AS-IS.
	This code is distributed in the hope that it will be useful,
 	but WITHOUT ANY WARRANTY; without even the implied warranty of
 	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

	Copyright 2005 Eliot Rayner / ersd.net.
*/

include('includes/application_top.php');
// make sure we set the right character set
header('Content-type: text/html; charset='.CHARSET);

if(!isset($_GET['Cart'])) {
	echo "<strong>Not in Cart session !! </strong><br />";
} else {

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => BOX_HEADING_SHOPPING_CART);

  new infoBoxHeading($info_box_contents, false, true, tep_href_link(FILENAME_SHOPPING_CART));

  $cart_contents_string = '';
  if ($cart->count_contents() > 0) {
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      $cart_contents_string .= '<p>';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string_class = ' class="nuevo_producto"';
      } else {
        $cart_contents_string_class = ' ';
      }

      $cart_contents_string .= '<strong'.$cart_contents_string_class.'>'.$products[$i]['quantity'] . ' x </strong><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        $cart_contents_string .= '<span class="nuevo_producto">';
      } else {
        $cart_contents_string .= '<span>';
      }

      $cart_contents_string .= $products[$i]['name'] . '</span></a>  <a href="borrar_carrito.php?pId='.$products[$i]['id'].'"><img src="theme/'.THEME.'/iconos/borrar.gif" /></a></p>';

      if ((tep_session_is_registered('new_products_id_in_cart')) && ($new_products_id_in_cart == $products[$i]['id'])) {
        tep_session_unregister('new_products_id_in_cart');
      }
    }
  } else {
    $cart_contents_string .= '<p class="no_productos">'.BOX_SHOPPING_CART_EMPTY.'</p>';
  }

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $cart_contents_string);

  if ($cart->count_contents() > 0) {
    $info_box_contents[] = array('align' => 'right',
                                 'text' => '<p class="total">Total: <strong>' . $currencies->format($cart->show_total()) . '</strong></p>');
  }

// Bof Shopping Cart Box Enhancement
$showcheckoutbutton = 1; // set to 1: show checkout button (default); set to 0: never show checkout button
$showhowmuchmore = 1;    // set to 1: show how much more to spend for free shipping (default); set to 0: don't show

$cart_show_string = '';





$info_box_contents[] = array('text' => $cart_show_string); 
// Eof Shopping Cart Box Enhancement

  new noborderBox($info_box_contents);
}
?>