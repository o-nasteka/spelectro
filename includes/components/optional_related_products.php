<?php
	// Consulta con los productos
	$orderBy .= ( RELATED_PRODUCTS_RANDOMIZE ) ? 'rand()' : 'pop_order_id, pop_id';
	$orderBy .= (RELATED_PRODUCTS_MAX_DISP) ? ' limit ' . RELATED_PRODUCTS_MAX_DISP : '';

	$sSql = 'select ' . SQL_SELECT . ' p.products_id, pop_products_id_slave, products_name, products_model, products_price, products_quantity, products_tax_class_id, products_image
			 from ' . TABLE_PRODUCTS_RELATED_PRODUCTS . ', ' .
			 TABLE_PRODUCTS_DESCRIPTION . ' pa, '.
			 TABLE_PRODUCTS . ' p
			 ' . SQL_FROM . '
			 where pop_products_id_slave = pa.products_id
			 and pa.products_id = p.products_id
			 and language_id = ' . (int)$languages_id . '
			 and pop_products_id_master = ' . $_GET['products_id'] . '
			 and products_status = 1 
			 ORDER BY ' . $orderBy;

	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];

	// Mostramos productos
	if( count( $aProductos ) > 0 )
	{
		// Idioma
		include( DIR_WS_LANGUAGES . $language . '/' . basename(__FILE__) );
		
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );
	}
?>
