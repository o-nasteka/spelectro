<?php
	// Variables
	$nRandom = random();

	$sSql = 'select p.products_id, p.products_image, p.products_tax_class_id, NULL as specstat, NULL as specials_new_products_price, p.products_price, NULL as products_name 
			 from products p 
			 left join featured f on (p.products_id = f.products_id)
			 where p.products_status = 1 and f.status = 1
			 order by rand(' . $nRandom . ') 
			 DESC limit ' . 3;

	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];

	// Mostramos productos
	if( count( $aProductos ) > 0 )
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );

	return;
?>