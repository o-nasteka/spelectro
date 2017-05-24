<?php
$i=1;
foreach( $aListas as $aLista )
		{
			if( $aLista['parent_id'] == 0 )
			{
				//echo '<li id="ctgr-top" '.$aLista['categories_image_small_'.$languages_id].')"><a href="' . tep_href_link( FILENAME_CATEGORIES, 'cPath=' . $aLista['categories_id'] ) . '" title="' . $aLista['categories_name'] . '">' . $aLista['categories_name'] . '</a></li>';
				//echo getSub( $aListas, $aLista['categories_id'], $aLista['categories_id'] . '_', 1 );
			
			?>
			
		<div class="titlemobilewrapper cat_<?php echo $i; ?>">
		
		 <a>
		<span class="titlemobile">
		<?php echo $aLista['categories_name']; ?>
		</span>
		</a>
		
		</div>
		
			
			<?php
			
	echo getSubMobile( $aListas, $aLista['categories_id'], $aLista['categories_id'] . '_', 1 , "sub_cat_".$i);

			
			
			$i++;
			}
			
			
		}
		?>


<div id="banner_home_mobile_wrapper">
<?php

showbanners();

?>
</div>










<?php


function showbanners()
{

if( $banner = tep_banner_exists( 'dynamic', 'izq1' ) )
				echo '<div class="bnr-izqd">' . tep_display_banner( 'static', $banner ) . '</div>';
			if( $banner = tep_banner_exists( 'dynamic', 'izq2' ) )
				echo '<div class="bnr-izqd" style="margin: 0 0 15px -3px;">' . tep_display_banner( 'static', $banner ) . '</div>';
			if( $banner = tep_banner_exists( 'dynamic', 'izq3' ) )
				echo '<div class="bnr-izqd" style="margin: 0 0 15px -3px;">' . tep_display_banner( 'static', $banner ) . '</div>';


}


function getSubMobile($aListas, $nParent, $sCat, $nSpaces , $subitemclass)
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
					
						$sHTML .= '<ul>';						
						$bUL = true;
					}

					// Agregamos a la lista
					//$sHTML .= '<li class="' . ($_GET['cPath'] == $sCat . $aLista['categories_id'] ? ' ' . actv : '') . '"><a href="' . tep_href_link( FILENAME_CATEGORIES, 'cPath=' . $sCat . $aLista['categories_id'] ) . '" title="' . $aLista['categories_name'] . '" class=""><span>+</span> <h3>' . str_repeat( '&nbsp;', $nSpaces * 2 ) . $aLista['categories_name'] . '</h3></a></li>';
					$link_=tep_href_link( FILENAME_CATEGORIES, 'cPath=' . $sCat . $aLista['categories_id'] );
					$sHTML .= '<li class=><a href="' .$link_. '" title="' . $aLista['categories_name'] . '" class=""><h3>'  . $aLista['categories_name'] . '</h3></a></li>';
					
					
					// Buscamos hijos
					//$sHTML .= getSub( $aListas, $aLista['categories_id'], $sCat . $aLista['categories_id'] . '_', $nSpaces + 1 );
				}
			}

			
			if( $bUL )
				$sHTML .= '</ul>';

			return "<div class='subitem_wrapper $subitemclass'>$sHTML</div>";
		}
		?>