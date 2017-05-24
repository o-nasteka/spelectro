<div class="box-small box-small-idma">
    <span>IDIOMAS</span>
	<?php while( list( $key, $value ) = each( $aDatos ) ): ?>
		<a id="<?php echo getSlug( $value['name'] ) . ($value['directory'] == $language ? '-actv' : ''); ?>" href="<?php echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type); ?>">
			<?php echo $value['name']; ?>
		</a>
	<?php endwhile; ?>
</div>