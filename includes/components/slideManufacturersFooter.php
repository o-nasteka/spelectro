<?php
	// Variables
	$nLimit = 0;

	// Consultamos los fabricantes
	$aDatos = tep_db_query( 'select manufacturers_id, manufacturers_name, manufacturers_image, manufacturers_name 
							 from manufacturers 
							 order by orden' . ($nLimit > 0 ? ' limit ' . $nLimit : '') );

	if( tep_db_num_rows( $aDatos ) > 0 )
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );
?>