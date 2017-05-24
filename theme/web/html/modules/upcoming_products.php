<div class="ovrflw">
	<div class="titu titu-gris">PRÓXIMAMENTE</div>
	<?php 
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
	?>
</div>