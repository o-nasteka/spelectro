<div class="box b3">
    <div class="box-top"><?php echo BOX_HEADING_BESTSELLERS; ?></div>
    <div class="box-cntd">
        <ul class="lsta-top">
			<?php
				$nCont = 1;
				while( $aProducto = tep_db_fetch_array( $aDatos ) )
				{
					echo '<li ' . ($nCont % 2 == 0 ? 'class="bgg"' : '') . '><i>' . $nCont . '.</i> <a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto['products_id'] ) . '">' . truncate( $aProducto['products_name'], array( 'SIZE' => 21 ) ) . '</a></li>';
					$nCont++;
				}
			?>
        </ul>
    </div>
    <div class="box-fotr"></div>
</div>