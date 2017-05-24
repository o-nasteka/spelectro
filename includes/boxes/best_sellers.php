<?php

	// Si nos encontramos en un categoria
	if( isset( $current_category_id ) && $current_category_id > 0 ) 
	{
		$aDatos = tep_db_query( 'select distinct p.products_id, p.products_price, p.products_tax_class_id, pd.products_name 
							 	 from ' . TABLE_PRODUCTS . ' p 
								 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on (p.products_id = pd.products_id)
								 inner join ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c on (p.products_id = p2c.products_id)
								 inner join ' . TABLE_CATEGORIES . ' c on (p2c.categories_id = c.categories_id)
								 where p.products_status = 1 and p.products_ordered > 0 and pd.language_id = ' . (int)$languages_id . ' and ' . (int)$current_category_id . ' in (c.categories_id, c.parent_id)
								 order by p.products_ordered desc, pd.products_name
								 limit ' . MAX_DISPLAY_BESTSELLERS );
	}
	else
	{
		$aDatos = tep_db_query( 'select distinct p.products_id, p.products_price, p.products_tax_class_id, pd.products_name
								 from ' . TABLE_PRODUCTS . ' p 
								 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on(p.products_id = pd.products_id)
								 where p.products_status = 1 and p.products_ordered > 0 and pd.language_id = ' . (int)$languages_id . '
								 order by p.products_ordered desc, pd.products_name
								 limit ' . MAX_DISPLAY_BESTSELLERS );
	}

	// Si hemos obtenido respuesta
	if( tep_db_num_rows( $aDatos ) >= MIN_DISPLAY_BESTSELLERS )
	{
		// Titulo del box
		$sTitulo = $column['box_heading'];

		// Incluimos el html
		include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );

		// Liberamos
		unset( $aDatos, $sTitulo );
	}
?>