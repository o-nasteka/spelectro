<div id="cbcr-crrt-<?php echo $languages_id; ?>">
    <a id="cbcr-crrt-anch" href="<?php echo tep_href_link( FILENAME_SHOPPING_CART ); ?>" title="Carrito"><?php echo $nCantidad; ?> <span>producto(s)</span></a>
    <div id="cbcr-crrt-cntd" style="display:none;">
        <ul>
            <?php foreach( $aDatos as $aDato ): ?>
                <li>
                    <span><?php echo $aDato['quantity'] ?>x</span>
                    <a href="<?php echo tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aDato['id'] ); ?>" title="<?php echo $aDato['name'] ?>"><?php echo $aDato['name'] ?></a>
                    <a class="cbcr-crrt-dlte" title="Quitar producto" href="<?php echo tep_href_link( 'borrar_carrito.php', 'pId=' . $aDato['id'] ); ?>">x</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <div id="cbcr-crrt-ttal">
            <b>TOTAL:</b>
            <span><?php echo $nTotal; ?></span>
        </div>
        <a id="cbcr-crrt-rlza" href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING) ?>">Realizar pedido</a>
    </div>
</div>