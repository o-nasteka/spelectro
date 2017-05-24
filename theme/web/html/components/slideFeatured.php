<div class="dstc-fix"></div>
<div id="dstc">
	<div class="box-slde">
		<a id="dstc-drch" class="box-slde-drch-<?php echo $languages_id ?>" href="javascript:void(0);"></a>
		<a id="dstc-izqd" class="box-slde-izqd-<?php echo $languages_id ?>" href="javascript:void(0);"></a>
		<div class="box-slde-cntd">
			<div id="dstc-sldr" class="box-slde-slde">
			<?php while( $aProducto = tep_db_fetch_array( $aDatos ) ): ?>
					<?php $aProductoInformacion = getInformacionProducto( $aProducto ); ?>
			    <div class="prdct-slde <?php echo $aProductoInformacion['CLASS_ENVIO'] . ' ' . $aProductoInformacion['CLASS_OFERTA']; ?>">
				<?php 
					// Iconos envio y oferta
					echo ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : ''); 
					echo ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : '');
				?>
		    	    <a class="prdct-img" href="<?php echo tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ); ?>" title="<?php echo $aProductoInformacion['TITLE']; ?>"><?php echo tep_image(DIR_WS_IMAGES . 'productos/' .$aProducto['products_image'], $aProductoInformacion['TITLE'], 250, 250, false); ?></a>
		    	    <h3><a class="prdct-title" href="<?php echo tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ); ?>" title="<?php echo $aProductoInformacion['TITLE']; ?>"><b><?php echo $aProducto['products_name']; ?></b></a></h3>
		    	    <div class="prdct-dscp">
		    	    	<?php echo truncate( $aProducto['products_description'], array( 'SIZE' => '120', 'CLEAR' => true ) ); ?>
		    	    </div>
	    	    	<div class="prdct-prco-<?php echo $languages_id ?>">
						<div class="prco-<?php echo $languages_id ?>">
							<?php echo getPrecioImagen( $aProductoInformacion ) . ' <s>' . $aProductoInformacion['PRECIO_ANTERIOR'] . '</s>'; ?>	
						</div>
					</div>
		    	    <a class="prdct-cmpr-<?php echo $languages_id ?>" title="Comprar <?php echo $aProductoInformacion['TITLE']; ?>" href="<?php echo tep_href_link( FILENAME_DEFAULT, tep_get_all_get_params( array('action') ) . 'action=buy_now&products_id=' . $aProducto["products_id"] ); ?>">Comprar <?php echo $aProducto['products_name']; ?></a>
		    	    <div class="prdct-iva"><?php echo ENTRY_TAX_INCL; ?></div>
                	<div id="icon-stock-<?php echo $languages_id; ?>" title="Quedan <?php echo $aProducto['products_quantity'];?> productos" class="prdct-icon-stock <?php echo $aProductoInformacion['CLASS_STOCK']; ?>"></div>
			    </div>
			<?php endwhile; ?>
			</div>
			<div class="box-slde-pgnd" id="dstc-pgnd">
			</div>
		</div>
	</div>
</div>