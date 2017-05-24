<div id="nvds" class="infoBoxContainer">
	<a href="<?php echo tep_href_link( 'products_new.php' ); ?>" title="Novedades" class="titleBox infoBoxHeading">Novedades</a>
    <div class="box-slde">
        <a id="nvds-drch" class="box-slde-drch-<?php echo $languages_id; ?>" href="javascript:void(0);"><i class="icon-chevron-right"></i></a>
        <a id="nvds-izqd" class="box-slde-izqd-<?php echo $languages_id; ?>" href="javascript:void(0);"><i class="icon-chevron-left"></i></a>
        <div class="box-slde-cntd">
            <div id="nvds-sldr" class="box-slde-slde">
	            <?php foreach( $aProductos as $nCont => $aProducto ): ?>
					<?php echo _product_slide_box(); ?>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
