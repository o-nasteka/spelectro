<?php
	// Si es una peticon ajax mostramos solo los productos. Esto se usa para la paginaci�n mediante ajax
	if( isAjax() )
	{
		foreach( $aProductos as $nCont => $aProducto )
			echo _product( array( 'SIZE_DESCRIPTION' => 250 ) );

		echo $aPaginador->ajax();

		return;
	}
?>


<div class="page-header">
  <h1>
	<span class="tit"><?php echo $sTitular; ?></span>
	<?php /*<a class="<?php echo (!empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'chng-vsta-hrzt' : 'chng-vsta-vrtl'); ?>" href="javascript:void(0);" id="chng-vsta"><?php echo ENTRY_CHNG_VST; ?></a>*/ ?>
  </h1>
</div>


<?php if( $nProductosTotal > 0 ): ?>
<div class="Products grid-list">
	<?php foreach( $aProductos as $nCont => $aProducto ): ?>
		<?php echo _product( array( 'SIZE_DESCRIPTION' => 250, 'STOCK' => true  ) ); ?>
	<?php endforeach; ?>
</div>
	<?php if( tep_db_prepare_input( $_GET['numero'] ) != '*' ):  ?>
		<div class="pgnc">
			<?php echo $aPaginador->display_links( MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params( array('page', 'info', 'x', 'y' ) ) ); ?>
		</div>
	<?php else: ?>
		<?php echo $aPaginador->ajax(); ?>
	<?php endif; ?>
<?php else: ?>
	<div class="alert alert-info">No existen productos que correspondan con el filtro seleccionado.</div>
<?php endif; ?>
