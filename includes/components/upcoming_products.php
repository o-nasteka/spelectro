<?php
	// Idioma
	include( DIR_WS_LANGUAGES . $language . '/' . basename(__FILE__) );

	// Consulta con los productos
	$sSql = 'select ' . SQL_SELECT . ' p.products_id, pd.products_name, products_date_available as date_expected 
			 from ' . TABLE_PRODUCTS . ' p '
			 . TABLE_PRODUCTS_DESCRIPTION . ' pd on (p.products_id = pd.products_id)
			 ' . SQL_FROM . '
			 where to_days(products_date_available) >= to_days(now()) and pd.language_id = ' . (int)$languages_id . '
			 order by products_date_available asc limit ' . MAX_DISPLAY_UPCOMING_PRODUCTS

	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];

	// Mostramos productos
	if( count( $aProductos ) > 0 )
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );
?>