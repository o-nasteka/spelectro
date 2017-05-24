<div id="mrcs">
	<a id="mrcs-left" href="javascript: void(0);"></a>
	<div id="mrcs-slde" class="slideshow-thumbnails">
		<ul>
			<?php while( $aDato = mysql_fetch_array( $aDatos ) ): ?>
				<li class="mrcs-slde-cntd">
					<span></span>
					<a href="<?php echo tep_href_link( FILENAME_DEFAULT, 'manufacturers_id= ' . $aDato['manufacturers_id'] ); ?>" title="<?php echo $aDato['manufacturers_name']; ?>">
						<?php echo tep_image( 'images/fabricantes/' . $aDato['manufacturers_image'], $aDato['manufacturers_name'], 114, 46 ); ?>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
	</div>
	<a id="mrcs-drch" href="javascript: void(0);"></a>
</div>