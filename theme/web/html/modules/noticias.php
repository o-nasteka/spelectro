<h1 class="pageHeading">Noticias</h1>

<?php if( $sId ): ?>
	<span id="ntca-fcha"> <?php echo $aNoticia['date']; ?> -</span>
	<h3 id="ntca-tile"><?php echo $aNoticia['titulo']; ?></h3>
	<div id="ntca-dscp"><?php echo $aNoticia['noticia']; ?></div>
<?php else: ?>
	<?php while( $aNoticia = tep_db_fetch_array( $aNoticias ) ): ?>
		<span class="ntca-fcha"> <?php echo $aNoticia['date']; ?> - </span>
		<a class="ntca-tile" href="<?php echo getSlug( truncate( $aNoticia['titulo'], array( 'SIZE' => 50 ) ) ) . '-n-' . $aNoticia['id'] . '.html' ?>"><?php echo $aNoticia['titulo']; ?></a>
		<div class="ntca-dscp"><?php echo truncate( $aNoticia['noticia'], array( 'SIZE' => 250 ) ); ?></div>
	<?php endwhile; ?>
	<div class="pgnc"><?php echo $sPaginacion; ?></div>
<?php endif; ?>