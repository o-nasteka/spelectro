<?php
	// Variables
	$sEmail = 'info@prueba.com';

	// Si tenemos iniciada sesion
	if( tep_session_is_registered('customer_id') )
	{
		$account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
		$account = tep_db_fetch_array($account_query);
		$sEmail = $account['customers_email_address'];
	}

	// Incluimos el html
	include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );

	// Liberamos
	unset( $sEmail );
?>