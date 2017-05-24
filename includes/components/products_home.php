<?php
	$sSql = 'select ' . SQL_SELECT . ' p.products_id, p.products_quantity, p.products_model, p.products_weight, p.products_image, p.products_tax_class_id, NULL as specstat, NULL as specials_new_products_price, p.products_price, NULL as products_name
			 from ' . TABLE_PRODUCTS . ' p 
			 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on (pd.products_id = p.products_id)
			 inner join ' . TABLE_PRODUCTS_HOME . ' f on (p.products_id = f.products_id)
			 ' . SQL_FROM . '
			 where p.products_status = 1 and f.status = 1 and language_id = ' . (int)$languages_id . '
			 order by rand(' . random() . ') DESC
			 limit ' . MAX_DISPLAY_FEATURED_PRODUCTS;
			 
	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];
	$nProductosTotal = count( $aProductos );	

	// Mostramos productos
	if( $nProductosTotal > 0 )
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );
		
	unset( $sSql, $aAux, $aProductos, $nProductosTotal );
?>