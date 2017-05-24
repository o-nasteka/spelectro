<div class="cntd-prdct">
	<div class="titu titu-ofer"></div>
	<?php 
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
	?>
</div>