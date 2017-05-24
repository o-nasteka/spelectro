<?php
    require('includes/application_top.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_REVIEW_NOTICE);

    $breadcrumb->add(NAVBAR_TITLE);

    require(DIR_THEME. 'html/header.php');
    require(DIR_THEME. 'html/column_left.php');
?>

<h1 class="pageHeading" align="center"><?php echo HEADING_TITLE; ?></h1>
<?php echo TEXT_MAIN; ?>
<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
    <tr class="infoBoxContents">
        <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php require(DIR_THEME. 'html/column_right.php'); ?>
<?php require(DIR_THEME. 'html/footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
