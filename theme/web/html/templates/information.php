  <div class="page-header">
    <h1><?php echo $title; ?></h1>
  </div>  
<?php if( $articles_menu != ''): ?>
    <h2><?php echo $articles_menu; ?></h2>
<?php endif; ?>

 <p>
    <?php echo $page_description; ?>
</p>
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', tep_href_link(FILENAME_DEFAULT), 'btn btn-default pull-right'); ?>
