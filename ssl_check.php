<?php
    require('includes/application_top.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SSL_CHECK);
    $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SSL_CHECK));

    require(DIR_THEME. 'html/header.php');
    require(DIR_THEME. 'html/column_left.php');
?>

<table border="0" width="100%" cellspacing="3" cellpadding="3">
    <tr>
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="main">
                        <table border="0" width="40%" cellspacing="0" cellpadding="0" align="right">
                            <tr>
                                <td><?php new InfoBoxHeading(array(array('text' => BOX_INFORMATION_HEADING))); ?></td>
                            </tr>
                            <tr>
                                <td><?php new infoBox(array(array('text' => BOX_INFORMATION))); ?></td>
                            </tr>
                        </table>
                        <?php echo TEXT_INFORMATION; ?>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_LOGIN) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php require(DIR_THEME. 'html/column_right.php'); ?>
<?php require(DIR_THEME. 'html/footer.php'); ?>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>