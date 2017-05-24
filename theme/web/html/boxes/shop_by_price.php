<div class="box b2">
    <div class="box-top">POR PRECIO</div>
    <ul class="box-lsta box-cntd">
		<?php for( $nCont = 0; $nCont < sizeof( $price_ranges ); $nCont++ ): ?>
			<li><a href="<?php echo tep_href_link( FILENAME_SHOP_BY_PRICE, 'range=' . $nCont , 'NONSSL'); ?> "><?php echo $price_ranges[$nCont]; ?></a></li>
		<?php endfor; ?>
    </ul>
    <div class="box-fotr"></div>
</div>