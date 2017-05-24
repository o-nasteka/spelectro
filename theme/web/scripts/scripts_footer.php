<?php  
	echo '<script src="//ajax.googleapis.com/ajax/libs/mootools/1.4.5/mootools-yui-compressed.js" type="text/javascript"></script>';     
	echo '<script type="text/javascript">
		var dx_cookie_name = "' . cookie_control_nombre . '";
		var dx_slider_ficha = "' . SLIDER_FICHA_PRODUCTO . '";
		var dx_slider_home = "' . SLIDER_HOME . '";
	</script>';


	
    if( PRODUCCION )
        echo '<script src="'. DIR_WS_INCLUDES . 'min/?g=js" type="text/javascript"></script>';
	    
	 
    else
    {
		$aFiles = array_diff( scandir( dirname(__FILE__) . '/../js/' ), array( '.', '..' ) );

		foreach( $aFiles as $aFile )
			echo '<script src="theme/' . THEME . '/js/' . $aFile . '" type="text/javascript"></script>';

		unset( $aFiles, $aFile );
    }
?>    
