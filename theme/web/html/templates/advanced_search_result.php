<?php if( ! isAjax() ): ?>

<div class="page-header">
  <h1>
	<?php echo ADVANCED_SEARCH_TITLE; ?>
  </h1>
</div>

	<?php if( $sTags != ''): ?>
		 
			 
		</div>
		
		
	<?php endif; ?>
<?php include( DIR_THEME_ROOT . 'html/partial/_product_listing.php' ); ?>
	<?php
		if( $nProductosTotal == 0 )
			echo $messageStack->show( array( 'class' => ' alert alert-danger', 'text' => ERROR_NO_FOUND ) );
	?>
			
<?php endif; ?>

 