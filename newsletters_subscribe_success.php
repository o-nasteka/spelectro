<?php
  require('includes/application_top.php');
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_NEWSLETTERS);
  $location = ' &raquo; <a href="' . tep_href_link(FILENAME_NEWSLETTERS_SUBSCRIBE_SUCCESS, '', 'NONSSL') . '" class="headerNavigation">' . NAVBAR_TITLE . '</a>';
?>
<?php require(DIR_THEME. 'content/html/header.php'); ?>
<?php require(DIR_THEME. 'content/html/column_left.php'); ?>
<div align="center">
<h1 class="pageHeading"><span><?php echo utf8_encode(HEADING_TITLE); ?></span></h1>
	<p class="main"><?php echo utf8_encode(TEXT_INFORMATION); ?></p>
	<p align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></p>
</div>     
</form>
<?php require(DIR_THEME. 'content/html/column_right.php'); ?>

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_THEME. 'content/html/footer.php'); ?>
<!-- footer_eof //-->

</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>