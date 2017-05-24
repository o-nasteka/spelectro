<div id="asrch-load"><div></div></div>
<a class="asrch-advc-achr" href="javascript:void(0);"><?php echo ADVANCED_SEARCH_FILTRO_CAMBIAR_RESULTADO; ?></a>

<?php if( $sTags != '' ): ?>
	<div class="form-lnea">
		<div class="form-lnea-txt"><?php echo ADVANCED_SEARCH_SUBTITLE_FILTRANDO_POR; ?></div>
	</div>
	<div id="asrch-tags" class="asrch-tags">
		<?php echo $sTags; ?>
	</div>
<?php endif; ?>

<?php echo tep_draw_input_field( 'buscar', '', 'style="display:none;"' ); ?>

<?php if( tep_db_num_rows( $aCategorias ) != 0 ): ?>
	<div class="form-lnea <?php echo ($sTags != '' ? 'form-lnea-sepa' : ''); ?>">
		<div class="form-lnea-txt"><?php echo ADVANCED_SEARCH_SUBTITLE_CATEGORIAS; ?></div>
	</div>
	<div class="asrch-fltr-mas"></div>
	<div id="asrch-fltr-ul-ctgr">
		<ul>
			<?php while( $aCategoria = tep_db_fetch_array( $aCategorias ) ): ?>
				<?php echo '<li><a rel="' . $aCategoria['idcategoria'] . '" href="javascript:void(0);">' . $aCategoria['categoria_padre'] . ' - ' . $aCategoria['categoria'] . ' (' . $aCategoria['cantidad'] . ')</a></li>'; ?>
			<?php endwhile; ?>
		</ul>
	</div>
	<div class="asrch-fltr-mnos"></div>
	<?php echo tep_draw_input_field( 'categoria', '', 'style="display:none;"' ); ?>
<?php endif; ?>
	
	
<?php if( tep_db_num_rows( $aFabricantes ) != 0 ): ?>
	<div class="form-lnea form-lnea-sepa">
		<div class="form-lnea-txt"><?php echo ADVANCED_SEARCH_SUBTITLE_FABRICANTES; ?></div>
	</div>
	<div class="asrch-ovfl">
		<div class="asrch-fltr-mas-vrtl"></div>
		<div id="asrch-fltr-ul-fbct">
			<ul style="top: 0px;">
				<?php while( $aFabricante = tep_db_fetch_array( $aFabricantes ) ): ?>
					<?php echo '<li>
						<a rel="' . $aFabricante['manufacturers_id'] . '" href="javascript:void(0);">
							' . tep_image( DIR_WS_IMAGES . 'fabricantes/' . $aFabricante['manufacturers_image'], $aFabricante['manufacturers_name'], 100, 57, '', false ) . '
							<span>' . truncate( $aFabricante['manufacturers_name'], array( 'SIZE' => 8 ) ) . ' (' . $aFabricante['cantidad'] . ')</span>
						</a>
					</li>'; ?>
				<?php endwhile; ?>
			</ul>
		</div>
		<div class="asrch-fltr-mnos-vrtl"></div>
		<?php echo tep_draw_input_field( 'fabricante', '', 'style="display:none;"' ); ?>
	</div>
<?php endif; ?>

<?php if( $nPrecioMax > 0 && $nPrecioMin != $nPrecioMax ): ?>
	<div class="form-lnea form-lnea-sepa">
		<div class="form-lnea-txt"><?php echo ADVANCED_SEARCH_SUBTITLE_PRECIO_DESDE_HASTA_2; ?></div>
	</div>
	<div class="slde-rnge-price">
		<div class="slde-rnge" id="slde-rnge" rel="<?php echo $nPrecioMin; ?>_<?php echo $nPrecioMax; ?>,<?php echo $sPrecioDesdePosion; ?>_<?php echo $sPrecioHastaPosion; ?>">
			<div id="slde-rnge-bg" class="slde-rnge-bg"></div>
			<div class="slde-rnge-flxa" id="slde-rnge-izqd"></div>
			<div class="slde-rnge-flxa" id="slde-rnge-drch"></div>
		</div>
	</div>
	<div class="slde-rnge-price-inpt">
		Desde:
		<?php echo tep_draw_input_field( 'precio_desde', '', 'autocomplete="off"', false, 'number' ); ?>
		Hasta:
		<?php echo tep_draw_input_field( 'precio_hasta', '', 'autocomplete="off"', false, 'number' );?>
	</div>
	<?php echo tep_draw_input_field( 'precio_anterior', $sPrecioAnterior, 'style="display:none;"' ); ?>
<?php endif; ?>

<div id="asrch-btns">
	<input id="asrch-clse" type="button" class="form-bton" href="javascript:void(0);" value="<?php echo ADVANCED_SEARCH_BOTON_CERRAR; ?>" />
	<input type="submit" class="form-sbmt" value="<?php echo ADVANCED_SEARCH_BOTON_APLICAR; ?>" />
</div>