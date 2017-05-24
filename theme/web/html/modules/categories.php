<h1 class="pageHeading"><?php echo $sTitular; ?></h1>

<?php while( $aCategoria = tep_db_fetch_array( $aCategorias ) ): ?>
    <?php
	$sClass = false; ?>
    <div class="ctgr">
        <?php if( $aCategoria['categories_image'] != '' && is_file(DIR_WS_IMAGES . 'categorias/' . $aCategoria['categories_image']) ): ?>
            <a href="<?php echo tep_href_link( FILENAME_DEFAULT, tep_get_path( $aCategoria['categories_id'] ) ); ?>">
                <?php echo tep_image(DIR_WS_IMAGES . $aCategoria['categories_image'], $aCategoria['categories_name'], 120, 120 ); ?>
            </a>
	<?php else: ?>
            <?php $sClass = true; ?>
        <a class="ctgr-txto<?php echo ($sClass ? ' ctgr-sin-img' : ''); ?>" href="<?php echo tep_href_link( FILENAME_DEFAULT, tep_get_path( $aCategoria['categories_id'] ) ); ?>">
            <?php echo $aCategoria['categories_name']; ?>
        </a>
	<?php endif; ?>
    </div>
<?php endwhile; ?> 