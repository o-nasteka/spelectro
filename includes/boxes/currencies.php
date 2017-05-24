<?php
	if( isset( $currencies ) && is_object( $currencies ) ) 
	{
		// Variables
		$currencies_array = array();

		// Obtenemos las monedas
		reset($currencies->currencies);
		while( list($key, $value) = each( $currencies->currencies ) )
			$currencies_array[] = array( 'id' => $key, 'text' => $value['title'] );

		include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );
  }
?>