<div class="ovrflw">
	<div class="titu titu-gris">CLIENTES QUE HAN COMPRADO ESTE PRODUCTO TAMBIEN HAN COMPRADO...</div>
	<?php 
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false ) );
	?>
</div>