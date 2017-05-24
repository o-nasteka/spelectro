<?php
    include( 'includes/application_top.php' );
	
	// Variables
	$aFabricante = null;
	$aProductos = null;
	$sTitular = TITLE;
	$nProductosTotal = 0;
	$sSql = null;
	$aPaginador = null;
	$aDatos = null;

	// Comprobamos que nos esten enviando un fabricante si no redireccionamos a todos
    if( ! isset( $manufacturers_id ) && ! tep_not_null( $manufacturers_id ) )
		tep_redirect( FILENAME_ALLMANUFACTURERS );

	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		// Cargamos la cabecera y la columna izquierda
		include( DIR_THEME. 'html/header.php' );
		include( DIR_THEME. 'html/column_left.php' );
	}

	// Obtenemos los datos del fabricante
	$aDatos = tep_db_query( 'select manufacturers_name, manufacturers_image
							 from manufacturers
							 where manufacturers_id = ' . $_GET['manufacturers_id'] );
	$aFabricante = tep_db_fetch_array( $aDatos );

	// Si la categoria tiene nombre la obtenemos
	if( $aFabricante['manufacturers_name'] != '' )
		$sTitular = $aFabricante['manufacturers_name'];

	$sSql = 'select ' . SQL_SELECT . 'p.products_model, pd.products_name, m.manufacturers_name, p.products_quantity, p.products_image, p.products_weight, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_free_shipping, p.products_quantity, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price
			 from products p
			 inner join products_description pd on (p.products_id = pd.products_id)
			 left join specials_retail_prices s on (p.products_id = s.products_id)
			 inner join manufacturers m on (p.manufacturers_id = m.manufacturers_id)
			 inner join products_to_categories p2c on (p.products_id = p2c.products_id)'
			 . SQL_FROM . '
			where p.products_status = 1 and m.manufacturers_id = ' . $_GET['manufacturers_id'] . ' and pd.language_id = ' . $languages_id;

	// Cambiamos el SQL si existe un filtro
	changeFilter( $sSql );	

	// Obtenemos el paginador y los productos
	$aAux = changePriceCustomer( $sSql );
	$aProductos = $aAux['PRODUCTOS'];
	$aPaginador = $aAux['PAGE_PRODUCTOS'];
	$nProductosTotal = count( $aProductos );
	
	// Theme
	include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );
	
	// Liberamos
	unset( $aFabricante, $aProductos, $sTitular, $nProductosTotal, $sSql, $aPaginador, $aDatos );
	
	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		// Cargamos el pie y la columna derecha
		include( DIR_THEME. 'html/column_right.php' );
		include( DIR_THEME. 'html/footer.php' );
		include( DIR_WS_INCLUDES . 'application_bottom.php' );
	}
?>