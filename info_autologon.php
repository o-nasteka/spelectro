<?php
/*
  $Id: info_autologon.php,v 1.01 2002/10/08 12:00:00

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2002 HMCservices
  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  require(DIR_WS_LANGUAGES . $language . '/' . "info_autologon.php");
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="theme/<?php echo THEME ?>/stylesheet.css">
</head>

<p class="main"><strong><?php echo HEADING_TITLE; ?></strong><br /><?php echo tep_draw_separator(); ?></p>
<p class="main"><strong><i><?php echo SUB_HEADING_TITLE_1; ?></i></strong><br /><?php printf(SUB_HEADING_TEXT_1, STORE_NAME); ?></p>
<p class="main"><strong><i><?php echo SUB_HEADING_TITLE_2; ?></i></strong><br /><?php printf(SUB_HEADING_TEXT_2, STORE_NAME); ?></p>
<p class="main"><strong><i><?php echo SUB_HEADING_TITLE_3; ?></i></strong><br /><?php printf(SUB_HEADING_TEXT_3, STORE_NAME); ?></p>
<p align="right" class="main"><a href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></p>
</body>
</html>
<?php
  require("includes/counter.php");
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
