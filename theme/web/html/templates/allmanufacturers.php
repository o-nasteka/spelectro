<h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>
<div id="box-prdct">
<?php
	// Si existen fabricantes
	if( tep_db_num_rows( $aFabricantes ) > 0 )
	{
		$nCont = 0;

		while( $aFabricante = tep_db_fetch_array($aFabricantes) )
		{
			$sClass = false;
			$nCont++;

			// Si es multiplo de 3 añadimos la clase last
			if( $nCont % 3 == 0 )
				$sClass = 'last';

			echo '<a class="fbct ' . $sClass . '" href="' . tep_href_link( FILENAME_DEFAULT, 'manufacturers_id=' . $aFabricante['manufacturers_id'] ) . '" title="Comprar '. $aFabricante['manufacturers_name'] . '">
				' . tep_image(DIR_WS_IMAGES . 'fabricantes/' . $aFabricante['manufacturers_image'], $aFabricante['manufacturers_name'], 133, 79, '', false) . '
			</a>';
		}
	}
	else
		echo '<div class="alert alert-info">' . ALLMANUFACTURERS_NO_FOUND . '</div>';
?>
</div>