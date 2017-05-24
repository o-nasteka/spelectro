<?php
	$aFiles = array_diff( scandir( dirname(__FILE__) . '/../../theme/web/js/' ), array( '.', '..' ) );
	array_walk( $aFiles, create_function('&$item', '$item = "../../theme/web/js/" . $item;') );

	return array( 'js' => $aFiles,
		'css' => array( '../../theme/web/css/style.css', '../../theme/web/css/base.css' )
	);