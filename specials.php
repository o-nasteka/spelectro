<?php
    include( 'includes/application_top.php' );
	
	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		// Incluimos el archivo de lenguaje
		require( DIR_WS_LANGUAGES . $language . '/' . FILENAME_SPECIALS );

		// Titulo
		$sTitular = HEADING_TITLE;
		
		// Breadcrumb
		$breadcrumb->add( NAVBAR_TITLE, tep_href_link( FILENAME_SPECIALS ) );

		require(DIR_THEME. 'html/header.php');
		require(DIR_THEME. 'html/column_left.php');
	}

	// Variables
	$customer_group_id = getCustomerGroupId();

	// Consulta con los productos
	$sSql = 'select ' . SQL_SELECT . ' p.products_quantity, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price as final_price
			 from ' . TABLE_PRODUCTS . ' p
			 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on (p.products_id = pd.products_id)
			 inner join ' . TABLE_SPECIALS . ' s on (s.products_id = p.products_id)
			 ' . SQL_FROM . '
			 where p.products_status = 1 and pd.language_id = ' . (int)$languages_id . ' and s.status = 1 and s.customers_group_id = ' . (int)$customer_group_id . '
			 order by s.specials_date_added DESC';

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
	unset( $aAux, $aProductos, $aPaginador, $nProductosTotal, $sSql );
	
	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		include( DIR_THEME. 'html/column_right.php' );
		include( DIR_THEME. 'html/footer.php' );
		include( DIR_WS_INCLUDES . 'application_bottom.php' );
	}
?>