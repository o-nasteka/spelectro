<!--<div id="ctgr-drch">
	<div class="ctgr-drch-ttl"></div>
	<ul>
	<?php
		/*foreach( $aListas as $aLista )
			echo '<li style="background-image:url(images/categorias/'.$aLista['IMAGEN'].')"><a ' . ($aLista['ACTIVO'] ? 'class="actv"' : '') . ' title="' . $aLista['TEXT'] . '" href="' . $aLista['HREF'] . '">' . $aLista['TEXT'] . '</a></li>';
*/	?>
	</ul>
</div>-->
<div class="infoBoxContainer" id="categories_tree">
  <div class="infoBoxHeading"><?php echo BOX_HEADING_CATEGORIES; ?></div>
    <div class="pgnc">
  
  
	<ul id="ctgrs">
	<?php
		// Variables
		$sHref = '';
 
		foreach( $aListas as $aLista )
		{
			if( $aLista['parent_id'] == 0 )
			{
				echo '<li id="ctgr-top" '.$aLista['categories_image_small_'.$languages_id].')"><a href="' . tep_href_link( FILENAME_CATEGORIES, 'cPath=' . $aLista['categories_id'] ) . '" title="' . $aLista['categories_name'] . '">' . $aLista['categories_name'] . '</a></li>';
				echo getSub( $aListas, $aLista['categories_id'], $aLista['categories_id'] . '_', 1 );
			}
		}

		function getSub($aListas, $nParent, $sCat, $nSpaces)
		{
			// Variables
			$sHTML = '';
			$sHref = '';
			$bUL = false;

			// Recorremos los elementos
			foreach( $aListas as $aLista )
			{
				// Si el padre coincide
				if( $aLista['parent_id'] == $nParent )
				{
					if( ! $bUL )
					{
						$sHTML .= '<li class="subcat-cntd">';
						$sHTML .= '<ul>';
						$bUL = true;
					}

					// Agregamos a la lista
					$sHTML .= '<li class="ctgr-subcat ' . ($_GET['cPath'] == $sCat . $aLista['categories_id'] ? ' ' . actv : '') . '"><a href="' . tep_href_link( FILENAME_CATEGORIES, 'cPath=' . $sCat . $aLista['categories_id'] ) . '" title="' . $aLista['categories_name'] . '" class=""><span>+</span> <h3>' . str_repeat( '&nbsp;', $nSpaces * 2 ) . $aLista['categories_name'] . '</h3></a></li>';
					// Buscamos hijos
					$sHTML .= getSub( $aListas, $aLista['categories_id'], $sCat . $aLista['categories_id'] . '_', $nSpaces + 1 );
				}
			}

			if( $bUL )
				$sHTML .= '</ul>';

			return $sHTML;
		}
	?>
	</ul>
	
	
	
	
	
	
	
  </div>	
</div>


