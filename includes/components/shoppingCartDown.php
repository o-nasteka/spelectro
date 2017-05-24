<?php
	// Variables
	$aDatos = $cart->get_products();
	$nTotal = $currencies->format( $cart->show_total() );
	$nCantidad = $cart->count_contents();

	include( DIR_THEME. 'html/components/' . basename(__FILE__) );
?>