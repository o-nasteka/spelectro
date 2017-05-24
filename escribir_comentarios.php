<?php
	header ('Content-type: text/html; charset=utf-8');

	require( 'includes/application_top.php' );

	// Funcion que muestra los mensajes
	function showMensaje($sMensaje, $sType)
	{
		// Comprobamos el tipo
		if( ! in_array( $sType , array( 'warning', 'correcto', 'error', 'info' ) ) )
			die( 'No existe el tipo de mensaje' );

		$aMensaje = array( 'warning' => 'wrng', 'correcto' => 'crrt', 'error' => 'eror', 'info' => 'info' );

		echo '<div class="msje msje-' . $aMensaje[$sType] . '"><div class="msje-icon"></div>' . $sMensaje . '</div>';
		
		exit();
	}
	
	// Funcion que limpia las cadenas enviadas por post
	function getCleanerString( $sString )
	{
		$sString = strip_tags( $sString );

		if( get_magic_quotes_gpc() )
			return mysql_real_escape_string( stripslashes( $sString ) );
		else
			return mysql_real_escape_string( $sString );
	}

	// Comprobamos que la peticion sea por AJAX
	if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
	{
		// Comprobamos que estamos logeados, si no paramos
		if( ! tep_session_is_registered( 'customer_id' ) )
			exit();
	
		// Nombre de usuario
		$aUsuarios = tep_db_query( 'SELECT customers_id, customers_firstname, customers_lastname 
									FROM ' . TABLE_CUSTOMERS . '
									WHERE customers_id = ' . (int)$customer_id );
		$aUsuario = tep_db_fetch_array( $aUsuarios );
		$sNombre = $aUsuario['customers_firstname'];

		// Producto ID
		$nProductoID = getCleanerString( $_POST['product_id'] );

		// Comentario
		$sComentario = getCleanerString( $_POST['reviews_text'] );
		
		// Puntos
		$nPuntos = getCleanerString( $_POST['rating'] );
		
		// Comprobamos si el producto existe
		$aProductos = tep_db_query( 'SELECT products_id 
									 FROM ' . TABLE_PRODUCTS . '
									 WHERE products_id = ' . (int)$nProductoID );

		// Si el producto no existe paramos
		if( tep_db_num_rows( $aProductos ) == 0 )
			exit();
		
		// Comprobamos los puntos se encuentre en 1 y 5
		if( $nPuntos <= 0 || $nPuntos > 5 )
			showMensaje( TEXT_COMMENTS_ERROR_POINT, 'error' );
			
		// Comprobamos el comentario
		if( $sComentario == '' )
			showMensaje( TEXT_COMMENTS_ERROR_COMMENT, 'error' );
		
		// Si todo esta correcto introducimos el comentario
		tep_db_query( 'INSERT INTO reviews (products_id, customers_id, customers_name, reviews_rating, date_added, approved) 
					   VALUES (' . $nProductoID . ', ' . $aUsuario['customers_id'] . ', "' . $sNombre . '", ' . $nPuntos . ', "' . date( 'Y-m-d H:i:s' ) . '", 0)' );

		tep_db_query( 'INSERT INTO reviews_description (reviews_id, languages_id, reviews_text) 
					   VALUES (' . tep_db_insert_id() . ', ' . $languages_id . ', "' . $sComentario . '")' );

		// Enviamos el mail
		/*$sContenido = '<p>Mensaje dejado por ' . $sNombre . ' (' . $aUsuario['customers_id'] . ') a las ' . date('h:i') . ' del dia ' . date('j m y') . '</p>Mensaje:<br />' . $sComentario );
		$cabeceras = "From: " . STORE_OWNER . "\r\nContent-type: text/html\r\n";
		mail( STORE_OWNER, $asunto, $contenido,$cabeceras);*/

		showMensaje( TEXT_COMMENTS_SUCCESS, 'correcto' );
	}
	else
	{
		tep_redirect( 'index.php' );
	}
?>