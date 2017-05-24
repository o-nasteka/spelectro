<?php
	include( 'includes/application_top.php' );
	include( DIR_WS_LANGUAGES . $language . '/' . basename(__FILE__) );

	// Variables
	$aFabricantes = null;
	
	// breadcrumb
	$breadcrumb->add( NAVBAR_TITLE, tep_href_link( basename(__FILE__) ) );

	// Obtenemos los fabricantes
	$aFabricantes = tep_db_query( 'select manufacturers_name, manufacturers_id, manufacturers_image 
								   from manufacturers
								   order by manufacturers_name' );

	// Cabecera y columna
	include(DIR_THEME. 'html/header.php');
	include(DIR_THEME. 'html/column_left.php');

	// Theme
	include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );
	
	// Liberamos
	unset( $aFabricantes );

	// Columna y pie
	include( DIR_THEME. 'html/column_right.php' );
	include( DIR_THEME. 'html/footer.php' );
	include( DIR_WS_INCLUDES . 'application_bottom.php' );
?>