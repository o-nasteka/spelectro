<?php include( DIR_THEME_ROOT . 'html/partial/_product_listing.php' ); ?>

<div class="page-header">
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
 