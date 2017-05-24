
<div id="web-drch" class="<?php echo $bcontent ?>">
	<?php
		if(ONLY_INDEX == 'null'){
			// Noticias
			echo _getListaNoticia( array(
				'SIZE_TITULO' => 90,
				'SIZE_NOTICIA' => 150,
				'MAX' => 1
			) );
		}
	?>
	
	<?php   if((basename($PHP_SELF) == FILENAME_DEFAULT && $cPath == '') && !isset($_GET['manufacturers_id']) ) {  
   } else {?>
	<div class="breadcrumb-line" xmlns:v="http://rdf.data-vocabulary.org/#">
	    <ul class="breadcrumb">
            <?php echo $breadcrumb->trail(''); ?>
        </ul>
	</div>
	<?php } ?>
	