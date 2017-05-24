<?php
	// Variables
	$nRandom = random();
		
	// Consulta para obtener los productos destacados
	$aDatos = tep_db_query( 'select ' . SQL_SELECT . ' p.products_id, p.products_image, p.products_quantity, p.products_tax_class_id, p.products_price, pd.products_name, pd.products_description
							 from products p 
							 inner join products_description pd on(pd.products_id = p.products_id)
							 left join featured f on p.products_id = f.products_id
							 ' . SQL_FROM . '
							 where p.products_status = 1 and f.status = 1 and pd.language_id = ' . (int)$languages_id . '
							 order by rand(' . $nRandom . ') DESC 
							 limit 10' );

	// Si hemos obtenido datos incluimos el html
	if( tep_db_num_rows( $aDatos ) > 0 )
		include( DIR_THEME_ROOT . 'html/components/slideFeatured.php' );
?>