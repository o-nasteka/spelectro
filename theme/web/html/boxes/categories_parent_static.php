<ul class="box-lsta box">
	<?php
		while( $aCategoria = tep_db_fetch_array( $aDatos ) )
			echo '<li><a ' . ($aCategoriaPadreActual == $aCategoria['categories_id'] ? 'class="actv"' : '') . ' href="' . tep_href_link( FILENAME_DEFAULT, 'cPath=' . $aCategoria['categories_id'] ) . '">• ' . $aCategoria['categories_name'] . '</a></li>';
	?>
</ul>