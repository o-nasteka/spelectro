<?php
	// Consulta con los productos
	$sSql = 'select p.products_id, p.products_image, p.products_tax_class_id, p.products_price, pd.products_name 
			 FROM ' . TABLE_PRODUCTS . ' p 
			 LEFT JOIN ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on p.products_id = pd.products_id 
			 WHERE products_status = 1 AND pd.language_id = ' . (int)$languages_id . ' 
			 ORDER BY p.products_date_added DESC 
			 LIMIT ' . MAX_DISPLAY_NEW_PRODUCTS;

	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];

	// Incluimos el html
	if( count($aProductos) > 0 )
		include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );

	// Liberamos
    unset( $aProductos );
?>