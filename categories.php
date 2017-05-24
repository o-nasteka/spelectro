<?php
    include( 'includes/application_top.php' );
	
	// Variables
	$aCategorias = null;
	$aProductos = null;
	$aCategoriaActual = null;
	$sTitular = TITLE;
	$nCategoriasTotal = 0;
	$nProductosTotal = 0;
	$aDatos = null;
	$sSql = null;
	$aPaginador = null;

	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		// Cargamos la cabecera y la columna izquierda
		include( DIR_THEME. 'html/header.php' );
		include( DIR_THEME. 'html/column_left.php' );
	}

	// Comprobamos que nos esten enviando una categoria
    if( isset( $cPath ) && tep_not_null( $cPath ) )
    {
		// Obtenemos los datos de la categoria actual
		$aDatos = tep_db_query( 'select c.parent_id, cd.categories_name, c.categories_image
								from categories c  
								inner join categories_description cd on(c.categories_id = cd.categories_id) 
								where c.categories_id = ' . $current_category_id . ' and cd.language_id = ' . $languages_id );

		$aCategoriaActual = tep_db_fetch_array( $aDatos );

		// Si la categoria tiene nombre la obtenemos
		if( $aCategoriaActual['categories_name'] != '' ){
			$sTitular = $aCategoriaActual['categories_name'];
			$sImagen = $aCategoriaActual['categories_image'];		
		}
		
		// Obtenemos los productos de la categoria si tuviese	
		$sSql = 'select ' . SQL_SELECT . 'p.products_model, pd.products_name, m.manufacturers_name, p.products_quantity, p.products_image, p.products_weight, p.products_id, p.manufacturers_id, p.products_price, p.products_tax_class_id, p.products_free_shipping, p.products_quantity, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price 
				 from products p 
				 inner join products_description pd on (p.products_id = pd.products_id)
				 left join manufacturers m on (p.manufacturers_id = m.manufacturers_id)
				 left join specials_retail_prices s on (p.products_id = s.products_id)
				 inner join products_to_categories p2c on ( p.products_id = p2c.products_id)
				 ' . SQL_FROM . '
				 where p.products_status = 1		
				 and pd.language_id = ' . $languages_id . ' 
				 and p2c.categories_id = ' . $current_category_id . '
				 order by pd.products_name';

		// Cambiamos el SQL si existe un filtro
		changeFilter( $sSql );	
	
		// Obtenemos el paginador y los productos
		$aAux = changePriceCustomer( $sSql );
		$aProductos = $aAux['PRODUCTOS'];
		$aPaginador = $aAux['PAGE_PRODUCTOS'];
		$nProductosTotal = count( $aProductos );
  
		// Obtenemos las categorias hijas si tuviese
		$aCategorias = tep_db_query( 'select c.categories_id, cd.categories_name, c.categories_image, c.parent_id 
									  from categories c
									  inner join categories_description cd on(c.categories_id = cd.categories_id)
									  where c.parent_id = ' . $current_category_id . ' and c.categories_status = 1 and cd.language_id = ' . $languages_id . '
									  order by sort_order, cd.categories_name ' );
    }
	// Si no nos envian una categoria mostramos todas
	else
	{
		// Obtenemos todas las categorias
		$aCategorias = tep_db_query( 'select c.categories_id, cd.categories_name
									  from categories c
									  inner join categories_description cd on (cd.categories_id = c.categories_id)
									  where c.parent_id = 0 and cd.language_id = ' . $languages_id . '
									  order by c.sort_order asc' );
	}
	
	// Numero total de categorias que existen en la categoria actual
	$nCategoriasTotal = tep_db_num_rows( $aCategorias );

	// Theme
	include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );

	// Liberamos
	unset( $aCategorias, $aProductos, $aCategoriaActual, $sTitular, $nCategoriasTotal, $nProductosTotal, $aDatos, $sSql, $aPaginador );

	// Si no es ajax mostramos todo. Esto se usa para la paginación mediante ajax
	if( ! isAjax() )
	{
		// Cargamos el pie y la columna derecha
		include( DIR_THEME. 'html/column_right.php' );
		include( DIR_THEME. 'html/footer.php' );
		include( DIR_WS_INCLUDES . 'application_bottom.php' );
	}
?>