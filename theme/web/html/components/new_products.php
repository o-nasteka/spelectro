<div class="Products grid-list" id="new_products_content">
<?php
	foreach( $aProductos as $nCont => $aProducto )
		echo _product( array( 'CLASS' => 'prdct-vrtl', 'VISTA' => false, 'DESCRIPCION' => false, 'STOCK' => true ) );
?>
</div> 
 