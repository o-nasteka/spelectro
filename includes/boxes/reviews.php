<?php

	// Consulta con todos los comentarios
	$aDatos = "select r.reviews_id, r.reviews_rating, p.products_id, p.products_image, pd.products_name, rd.reviews_text 
			from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd 
			where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and r.approved = '1'";

	// Si nos encontramos en un producto mostramos sus comentarios
	if( isset( $_GET['products_id'] ) )
		$aDatos .= " and p.products_id = '" . (int)$_GET['products_id'] . "'";

	$aDatos .= " order by r.reviews_id desc limit " . MAX_RANDOM_SELECT_REVIEWS;
	
	$aDatos = tep_db_query( $aDatos );

	// Incluimos el html
	include( DIR_THEME_ROOT . 'html/boxes/' . basename(__FILE__) );

	// Liberamos
	unset( $aDatos );
?>