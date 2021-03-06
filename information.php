<?php
    require('includes/application_top.php');
    require_once( DIR_WS_FUNCTIONS . 'information.php' );

    // Added for information pages
    if( !isset($_GET['info_id']) || !tep_not_null($_GET['info_id']) || !is_numeric($_GET['info_id']) )
    {
        $title = ($languages_id == 3 ? 'Nuestra información' : 'Information');
        $breadcrumb->add($INFO_TITLE, tep_href_link(FILENAME_INFORMATION, 'info_id=' . $_GET['info_id'], 'NONSSL'));
        $page_description = '<ul>' . tep_information_show_category(1) . '</ul>';
    }
    else
    {
        $info_id = intval($_GET['info_id']);
        $information_query = tep_db_query("SELECT information_title, information_description FROM " . TABLE_INFORMATION . " WHERE visible='1' AND information_id='" . $info_id . "' and language_id='" . (int)$languages_id ."'");
        $information = tep_db_fetch_array($information_query);
        $title = stripslashes($information['information_title']);
        $page_description = stripslashes($information['information_description']);

        // Added as noticed by infopages module
        if (!preg_match("/([\<])([^\>]{1,})*([\>])/i", $page_description))
            $page_description = str_replace("\r\n", "<br />\r\n", $page_description);

        $breadcrumb->add($title, tep_href_link(FILENAME_INFORMATION, 'info_id=' . $_GET['info_id'], 'NONSSL'));
    }

    require(DIR_THEME. 'html/header.php');
    require(DIR_THEME. 'html/column_left.php');

    include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );

    require(DIR_THEME. 'html/column_right.php');
    require(DIR_THEME. 'html/footer.php');;

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>