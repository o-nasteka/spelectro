<?php
    include( 'includes/application_top.php' );
	
	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		// Incluimos el archivo de lenguaje
		require( DIR_WS_LANGUAGES . $language . '/' . FILENAME_FEATURED );

		// Titulo
		$sTitular = HEADING_TITLE;
		
		// Breadcrumb
		$breadcrumb->add( NAVBAR_TITLE, tep_href_link( FILENAME_FEATURED ) );

		require(DIR_THEME. 'html/header.php');
		require(DIR_THEME. 'html/column_left.php');
	}

	$sSql = 'select ' . SQL_SELECT . ' p.products_quantity, p.products_id, pd.products_name, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price
			 from products p 
			 left join manufacturers m on (p.manufacturers_id = m.manufacturers_id)
			 left join products_description pd on (p.products_id = pd.products_id)
			 left join specials s on (p.products_id = s.products_id)
			 left join featured f on (p.products_id = f.products_id)
			 where p.products_status = 1 and f.status = 1 and pd.language_id = ' . $languages_id . '
			 order by p.products_date_added DESC, pd.products_name';

	// Cambiamos el SQL si existe un filtro
	changeFilter( $sSql );	
	
	// Obtenemos el paginador y los productos
	$aAux = changePriceCustomer( $sSql );
	$aProductos = $aAux['PRODUCTOS'];
	$aPaginador = $aAux['PAGE_PRODUCTOS'];
	$nProductosTotal = count( $aProductos );
		
	// Pintamos los productos
	include( DIR_THEME_ROOT . 'html/partial/_product_listing.php' );

	// Liberamos
	unset( $aAux, $aProductos, $aPaginador, $sSql );
	
	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		include( DIR_THEME. 'html/column_right.php' );
		include( DIR_THEME. 'html/footer.php' );
		include( DIR_WS_INCLUDES . 'application_bottom.php' );
	}
?>