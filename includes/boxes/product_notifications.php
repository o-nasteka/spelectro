<?php
	if( isset( $_GET['products_id'] ) )
	{
		if( tep_session_is_registered('customer_id') ) 
		{
			$aDatos = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_id = '" . (int)$customer_id . "'");
			$aDato = tep_db_fetch_array( $aDatos );

			$notification_exists = (($aDato['count'] > 0) ? true : false);
		}
		else
			$notification_exists = false;

		// Incluimos el html
		include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );

		// Liberamos
		unset( $aDatos );
	}
?>

