<div id="cmtr-hdng">Qué opinan de <span><?php echo $aProducto['products_name']; ?></span></div>

<?php while( $aComentario = tep_db_fetch_array( $aComentarios ) ): ?>
	<div class="cmtr">
		<span><?php echo $aComentario['customers_name']; ?> <small><?php echo $aComentario['date_added']; ?></small></span>
		<div class="cmtr-txt">
			<div class="cmtr-ratg cr<?php echo $aComentario['reviews_rating']; ?>"></div>
			<?php echo $aComentario['reviews_text']; ?>
		</div>
	</div>
<?php endwhile; ?>


<div id="cmtr-wrte">
	<div id="cmtr-wrte-bg"></div>
	<?php if( !tep_session_is_registered( 'customer_id' ) ): ?>
		<div class="msje msje-wrng"><div class="msje-icon"></div><?php echo sprintf(TEXT_COMMENTS_LOGIN, '<a href="'. tep_href_link( 'login.php' ) .'" title="Contacto">', '</a>'); ?></div>
	<?php else: ?>
		<div id="cmtr-wrte-ajax"></div>
	<?php endif; ?>

	<p id="cmtr-wrte-info"><?php echo sprintf(TEXT_COMMENTS_DESCRIPT, '<a href="'. tep_href_link( 'contact_us.php' ) .'" title="Contacto">'); ?></a>.</p>
	<form id="cmtr-form" name="form" method="post" <?php echo $sFormulario; ?>>
		<p>
			<label for="reviews_text"><?php echo TEXT_COMMENTS_YOU_COMMENT; ?></label>
			<textarea name="reviews_text" id="reviews_text" cols="45" rows="10"></textarea>
		</p>
		<div id="cmtr-wrte-load"></div>
		<div id="cmtr-wrte-ratg">
			<label for="reviews_text"><?php echo TEXT_COMMENTS_YOU_POINT; ?></label>
			<div class="cmtr-ratg"></div>
		</div>
		<p>
			<input type="submit" name="enviar" id="cmtr-send" value="Enviar"/>
		</p>
		<input type="hidden" id="rating" name="rating" />
		<input type="hidden" id="product_id" name="product_id" value="<?php echo $_GET['products_id']; ?>" />
	</form>
</div>