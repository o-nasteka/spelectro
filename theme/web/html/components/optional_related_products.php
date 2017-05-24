<div class="ovrflw">
	<div class="titu titu-gris"><?php echo HEADING_TITLE_OPTIONAL_RELATED_PRODUCTS; ?></div>
	<?php 
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
	?>
</div>