<div class="ovrflw">
	<div class="titu titu-gris"><?php echo $sTitular; ?></div>
	<?php 
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
	?>
</div>