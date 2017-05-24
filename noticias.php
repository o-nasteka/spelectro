<?php
	include( 'includes/application_top.php' );

	// Breadcrumb
	$breadcrumb->add( 'Noticias', 'noticias.php' );

	// Variables
	$sId = tep_db_prepare_input( $_GET['id'] );
	$sPaginacion = '';

	// Si hemos obtenido noticia la seleccionamos para mostrarla
	if( $sId )
	{
		// Consultamos
		$aNoticias = tep_db_query( 'select date_format(date,"%d/%m/%Y") as date, noticia, titulo, id
								 from noticias
								 where id = ' . $sId );

		// Si no obtenemos noticia redireccionamos
		if( tep_db_num_rows( $aNoticias ) == 0 )
			tep_redirect( 'noticias.php' );

		// Obtenemos la noticia
		$aNoticia = tep_db_fetch_array( $aNoticias );
		
		// Breadcrumb
		$breadcrumb->add( truncate( $aNoticia['titulo'], array( 'SIZE' => 50 ) ), getSlug( truncate( $aNoticia['titulo'], array( 'SIZE' => 50 ) ) ) . '-n-' . $aNoticia['id'] . '.html' );
	}
	else
	{
		// Obtenemos todas las noticias paginadas
		$aSplitNoticias = new splitPageResults( 'select date_format(date,"%d/%m/%Y") as date, noticia, titulo, id
												 from noticias
												 order by date desc', 10 );

		$aNoticias = tep_db_query( $aSplitNoticias->sql_query );
		
		// Comprobamos la paginacion
		if( $aSplitNoticias->number_of_rows > 0 )
            $sPaginacion = $aSplitNoticias->display_links( MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params( array( 'page', 'info', 'x', 'y' ) ) );
	}

	include( DIR_THEME. 'html/header.php' );
	include( DIR_THEME. 'html/column_left.php' );

	// Incluimos el html
	include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );

	// Liberamos
	unset( $sId, $sPaginacion );
	
	include( DIR_THEME. 'html/column_right.php' );
	include( DIR_THEME. 'html/footer.php');;
	include( DIR_WS_INCLUDES . 'application_bottom.php' );

?>