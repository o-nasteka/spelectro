<?php
	// Si es una peticon ajax mostramos solo los productos. Esto se usa para la paginación mediante ajax
	if( isAjax() )
	{
		foreach( $aProductos as $nCont => $aProducto )
			echo _product();

		echo $aPaginador->ajax();

		return;
	}

?>
<div class="page-header">
<h1><?php echo $sTitular; ?></h1>
<a class="pull-right <?php echo (!empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'chng-vsta-hrzt' : 'chng-vsta-vrtl'); ?>" href="javascript:void(0);" id="chng-vsta"><?php echo ENTRY_CHNG_VST; ?></a>
</div>
<?php // Mostrar categorias o no cuando existen tambien productos, configurable desde el admin ?>
<div class="grid-list">
<?php if( $nProductosTotal == 0 || OSCDENOX_SUBCATEGORIA_PRODUCTOS_SHOW == 'true' ): ?>
	<?php while( $aCategoria = tep_db_fetch_array( $aCategorias ) ): ?>
		<div class="product-item grid-item-list">
		<a href="<?php echo tep_href_link( FILENAME_CATEGORIES, tep_get_path( $aCategoria['categories_id'] ) ); ?>" title="<?php echo $aCategoria['categories_name']; ?>">
			<div class="product-thumb">
				<?php if( $aCategoria['categories_image'] != '' && is_file(DIR_WS_IMAGES . 'categorias/' . $aCategoria['categories_image']) ): ?>
					<?php echo tep_image(DIR_WS_IMAGES . 'categorias/' . $aCategoria['categories_image'], $aCategoria['categories_name'], 179, 169, '', false); ?>
				<?php else: ?>
					<img src="theme/web/images/custom/sin-cat.png" width="179" height="169" class="img-responsive"/>
				<?php endif; ?>
			</div>
			<h5 class="product-name"><?php echo $aCategoria['categories_name']; ?></h5>
		</a>
		</div>
	<?php endwhile; ?>
<?php endif; ?>
</div>
<?php if( $nProductosTotal > 0 ): ?>
	<?php
		// Mostrar filtro, configurable desde el admin
		if( OSCDENOX_SUBCATEGORIA_PRODUCTOS_FILTRO == 'true' )
			echo _getFiltro();
	?>
<div class="Products"> 
	<?php foreach( $aProductos as $nCont => $aProducto ): ?>
		<?php echo _product( array( 'SIZE_DESCRIPTION' => 250, 'STOCK' => true ) ); ?>
	<?php endforeach; ?>
</div>
	<?php // Mostrar paginador ?>
	<?php if( OSCDENOX_SUBCATEGORIA_PRODUCTOS_PAGINADOR  == 'true' ): ?>
		<?php if( tep_db_prepare_input( $_GET['numero'] ) != '*' ):  ?>
			<div class="pgnc">
				<?php echo $aPaginador->display_links( MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params( array('page', 'info', 'x', 'y' ) ) ); ?>
			</div>
		<?php else: ?>
			<?php echo $aPaginador->ajax(); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php elseif( $nCategoriasTotal == 0 ): ?>
	<div class="alert alert-danger"><?php echo FILTRO_NO_EXISTEN; ?></div>
<?php endif; ?>
