<?php
	// Variables
	$aComentarios    = null;
	$sFormulario = '';

	// Obtenemos todos los comentarios del producto
	$aComentarios = tep_db_query( 'SELECT r.customers_name, r.reviews_rating, date_format(r.date_added, "%d/%m/%Y") as date_added, rd.reviews_text
								   FROM reviews r
								   INNER JOIN reviews_description rd ON(rd.reviews_id = r.reviews_id)
								   WHERE approved = 1 and products_id=' . preg_replace( '/{.+$/i', '', $_GET['products_id'] ) . ' ORDER BY r.date_added DESC');

	// Formulario
	if( tep_session_is_registered( 'customer_id' ) )
		$sFormulario = 'action="' . tep_href_link( 'escribir_comentarios.php' ) . '" onsubmit="formComentario(); return false;"';
	else
		$sFormulario = 'action="' . tep_href_link( 'login.php' ) . '"';
	
	// Incluimos el theme
	include( DIR_THEME. 'html/components/' . basename(__FILE__) );
?>