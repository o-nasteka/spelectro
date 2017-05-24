<?php if( $aProductos ): ?>
	<div class="ovrflw">
		<div class="titu titu-gris"><?php echo ALSO_PURCHASED_PRODUCTS_TITLE; ?></div>
		<?php 
			foreach( $aProductos as $nCont => $aProducto )
				echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
		?>
	</div>
<?php else: ?>
	<div class="alert alert-info"><?php echo ALSO_PURCHASED_PRODUCTS_NO_FOUND; ?></div>
<?php endif; ?>