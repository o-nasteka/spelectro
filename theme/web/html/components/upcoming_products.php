<div class="ovrflw">
	<div class="titu titu-gris"><?php echo UPCOMING_PRODUCTS_TITLE; ?></div>
	<?php 
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
	?>
</div>