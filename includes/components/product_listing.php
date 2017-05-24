<?php
	/* El codigo anterior de oscommerce realizaba cambios en los precios por cantidades, peso, etc. Valores que no usamos normalmente en los listados de productos. Para ver el codigo anterior dirigete a las copias de seguridad antiguas */

	// Cambiamos el SQL si existe un filtro
	changeFilter( $listing_sql );	

	// Obtenemos el paginador y los productos
	$aAux = changePriceCustomer( $listing_sql, array( 'COUNT_KEY' => 'p.products_id' ) );
	$aProductos = $aAux['PRODUCTOS'];
	$aPaginador = $aAux['PAGE_PRODUCTOS'];
	$nProductosTotal = count( $aProductos );
	
	// Pintamos los productos
	include( DIR_THEME_ROOT . 'html/partial/_product_listing.php' );

	// Liberamos
	unset( $aAux, $aProductos, $aPaginador, $sSql );
?>