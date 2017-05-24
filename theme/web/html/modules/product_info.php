<?php 
	$aProductoInformacion = getInformacionProducto( $aProducto, array( 'MAS_INFO' => true ) );

    echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'));
 ?>

<div class="fich">
    <div class="<?php echo $aProductoInformacion['CLASS_ENVIO'] . ' ' . $aProductoInformacion['CLASS_OFERTA']; ?> prdct-stock" itemscope itemtype="http://data-vocabulary.org/Product">
        <div class="fich-prdct-izqd">
            <?php echo ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : ''); ?>
            <?php echo ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : ''); ?>

            <?php if( tep_not_null( $aProducto['products_image'] ) ): ?>
                <a href="<?php echo tep_href_link(DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'] ); ?>" rel="frky[g1]" title="<?php echo $aProducto['products_name']; ?>">
                    <?php echo tep_image(DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'], $aProducto['products_name'], 272, 272, 'itemprop="image"') ?>
                </a>
            <?php endif; ?>

            <?php echo $mopics_output ?>
        </div>

        <div class="fich-prdct-drch">
            <h1 class="prdct-title" id="prdt-info-ttle"><?php echo $aProducto['products_name']; ?></h1>
            <p class="prdct-dscp" itemprop="description"><?php echo truncate( $aProducto['products_description'], array( 'SIZE' => 350 ) ); ?></p>

            <div class="prdct-star-cnmt">
                <img src="<?php echo DIR_THEME . 'images/custom/s' . $aProductoInformacion['RATING'] ?>.jpg" alt="Puntuacion" />
                <div class="prdct-cnmt">
                    <span><?php echo $aProductoInformacion['PUNTUACION'] . '/5'; ?></span>
                    <?php echo $aProductoInformacion['NUMERO_COMENTARIO']; ?> comentario(s) / <a id="prdt-info-comt" href="<?php echo tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()); ?>"><?php echo TEXT_YOUR_COMMENT; ?></?php></a>
                </div>
            </div>

            <div id="prdct-info-scal">
                <!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style ">
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					<a class="addthis_button_compact"></a>
					<a class="addthis_counter addthis_bubble_style"></a>
				</div>
                <!-- AddThis Button END -->
            </div>

            <div class="prdct-cant">
                <span><?php echo TEXT_BUY; ?> <input type="text" name="cart_quantity" id="cart_quantity" value="1" /> <?php echo TEXT_UNITS; ?></span>
            </div>

            <div class="prdct-prco-<?php echo $languages_id; ?>" itemprop="price" content="<?php echo str_replace( '€', '', $aProductoInformacion['PRECIO'] ); ?>">
                <meta itemprop="currency" content="EUR" />
                <s><?php echo $aProductoInformacion['PRECIO_ANTERIOR']; ?></s>

                <input value="" type="submit" class="prdct-cmpr" title="Comprar <?php echo $aProducto['products_name']; ?>"/>

                <div class="prco-<?php echo $languages_id; ?> prco-b">
                    <?php echo getPrecioImagen( $aProductoInformacion ); ?>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="<?php echo $aProducto['products_id']; ?>" name="products_id">
	<?php echo $sHtmlAtributos;  ?>
</div>
</form>

<div class="tab">
    <div class="tab-top">
        <a rel="tbInfo" href="javascript: void(0);" class="actv">Informacion</a>
		<a rel="tb-rel" href="javascript: void(0);">Productos relacionados</a>
		<a rel="tb-cmtr" href="javascript: void(0);">Comentarios</a>
    </div>
	<div class="fced" id="tbInfo-0">
		<?php echo $aProducto['products_description']; ?>
	</div>

	<div id="tb-rel-0" style="display: none;">
		<?php include( DIR_WS_MODULES . 'also_purchased_products.php' ); ?>
	</div>

	<div id="tb-cmtr-0" style="display: none;">
		<?php include(DIR_WS_MODULES . 'comentarios.php');?>
	</div>
</div>