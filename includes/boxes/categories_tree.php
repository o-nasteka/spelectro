<?php
	// Variables
	$aListas = array();

	// Obtenemos las categorias
	$aDatos = tep_db_query( 'select c.categories_id, cd.categories_name, c.parent_id, c.categories_image
							 from ' . TABLE_CATEGORIES . ' c
							 inner join ' . TABLE_CATEGORIES_DESCRIPTION . ' cd on (c.categories_id = cd.categories_id)
							 where c.categories_status != 0 and cd.language_id=' . (int)$languages_id . '
							 order by sort_order, cd.categories_name' );

	// Creamos el array principal
	while( $aDato = tep_db_fetch_array( $aDatos ) )
		$aListas[] = $aDato;

	// Incluimos el html
	include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );
?>