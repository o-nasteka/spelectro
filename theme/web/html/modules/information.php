<h1 class="pageHeading"><?php echo $title; ?></h1>
<?php if( $articles_menu != ''): ?>
    <h4><?php echo $articles_menu; ?></h4>
<?php endif; ?>

<div class="information_contenido fced">
    <?php echo $page_description; ?>
</div>

<div class="botonera"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></div>