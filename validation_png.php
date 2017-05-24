<?php
/*
  $Id: validation_png.php,v 2.8.2 2008/08/25 18:44:27 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

// include necessary pre-amble

$bitmap_font_size = 5;// 1 to 5

  error_reporting(0);
	require_once('includes/define.php');
	require_once('includes/configure.php');
	define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
	require_once(DIR_WS_INCLUDES . 'filenames.php');
	require_once(DIR_WS_INCLUDES . 'database_tables.php');
	require_once(DIR_WS_FUNCTIONS . 'database.php');
	tep_db_connect() or die('Unable to connect to database server!');

	$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
	while ($configuration = tep_db_fetch_array($configuration_query)) {
		define($configuration['cfgKey'], $configuration['cfgValue']);
	}
// End - include

// Derived from the original contribution by AlexStudio
// Note to potential users of this code ...
//
// Remember this is released under the _GPL_ and is subject
// to that licence. Do not incorporate this within software 
// released or distributed in any way under a licence other
// than the GPL. We will be watching ... ;)

// Do we have an id? No, then just exit
if(empty($_GET['rsid']))
{
  header('Content-Type: image/png');
  header('Cache-control: no-cache, no-store');
  $im = @imagecreatetruecolor(250, 60)
      or die("Cannot Initialize new GD image stream");
	$colour = imagecolorallocate($im, 255, 0, 0);
	imagefill($im, 0, 0, $colour);		
	$text_color = imagecolorallocate($im, 0, 0, 0);
	imagestring($im, 5, 5, 5,  "Generation Error:", $text_color);
	imagestring($im, 5, 50, 22,  "Missing SID", $text_color);
	imagestring($im, 5, 20, 40,  "Please Refresh browser", $text_color);
	imagepng($im);
	imagedestroy($im);
  exit;
}

$s_id = tep_db_output($_GET['rsid']);

	function urand($min = NULL, $max = NULL){
        static $alreadyGenerated = array();
				$full = $max + abs($min);
        $range = ($min || $max) ? ($max - $min) + 1 : NULL; 
        do{
            $randValue = ($range) ? rand($min, $max) : rand();
            $key = $randValue + $full;
            if(count($rangeList) == $range) unset($alreadyGenerated);
            if($range) $rangeList[$key] = $randValue;
        }while($alreadyGenerated[$key] == $randValue + $full);
        unset($rangeList);
        $alreadyGenerated[$key] = $randValue + $full;
				return $randValue;
    } 
// Try and grab reg_key for this id and session

$check_anti_robotreg_query = tep_db_query("select reg_key from anti_robotreg where session_id = '$s_id'");
$new_query_for_reg_key = tep_db_fetch_array($check_anti_robotreg_query);

$code = $new_query_for_reg_key['reg_key'];
$ttf = (file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'fonts/'. ANTI_ROBOT_IMAGE_TTF) && ANTI_ROBOT_IMAGE_USE_TTF=='true') ;
$whitespace = (ANTI_ROBOT_IMAGE_WHITESPACE != 0 ? ANTI_ROBOT_IMAGE_WHITESPACE : 6);
$font_size = (ANTI_ROBOT_IMAGE_FONT_SIZE != 0 ? ANTI_ROBOT_IMAGE_FONT_SIZE : 20);
$image_hieght = (ANTI_ROBOT_IMAGE_HEIGHT != 0 ? ANTI_ROBOT_IMAGE_HEIGHT : 50);
$image_width = (ANTI_ROBOT_IMAGE_WIDTH != 0 ? ANTI_ROBOT_IMAGE_WIDTH : 260);
$top_margin = (ANTI_ROBOT_IMAGE_TOP_MARGIN != 0 ? ANTI_ROBOT_IMAGE_TOP_MARGIN : 6);
$total_code_width = ($ttf) ? 0 : ((imagefontwidth($font_size)+$whitespace-1) * strlen($code));
$max_code_height = ($ttf) ? 0 : imagefontheight($font_size);
if ($ttf) {
  $angle = (ANTI_ROBOT_FONT_ANGLE != 0 ? ANTI_ROBOT_FONT_ANGLE : 14);$angle_char[]=array();
	for ($i=0; $i < strlen($code); $i++) {
    $angle_char[$i] = urand(-$angle, $angle);
    $char_bbox = imagettfbbox($font_size, $angle_char[$i], DIR_FS_CATALOG . DIR_WS_IMAGES . 'fonts/'. ANTI_ROBOT_IMAGE_TTF, $code[$i]);
	$width_char[$i] = max($char_bbox[2], $char_bbox[4]) - min($char_bbox[0], $char_bbox[6]);
    $total_code_width += $width_char[$i] + $whitespace-1;
    $max_code_height = max($max_code_height, max($char_bbox[1],$char_bbox[3]) -  max($char_bbox[5], $char_bbox[7]));
  }
}

$height = ($image_hieght > $max_code_height + $top_margin) ? $image_hieght : $max_code_height + $top_margin;
$width = ($image_width > $total_code_width) ? $image_width : $total_code_width;

$bgc = hexdec(ANTI_ROBOT_IMAGE_BACKGROUND_COLOR != 0 ? ANTI_ROBOT_IMAGE_BACKGROUND_COLOR : 'FFFFFF');
$tc = hexdec(ANTI_ROBOT_IMAGE_TEXT_COLOR != 0 ? ANTI_ROBOT_IMAGE_TEXT_COLOR : '000000');
$image = @imagecreatetruecolor($total_code_width, $max_code_height + $top_margin);
imagefilledrectangle($image, 0, 0, $width, $height, $bgc);
$bg_color = imagecolorallocate($image, ($bgc >> 16) & 0xFF, ($bgc >> 8) & 0xFF, $bgc & 0xFF);
$fg_color = imagecolorallocate($image, ($tc >> 16) & 0xFF, ($tc >> 8) & 0xFF, $tc & 0xFF);

$pos_x = urand(0,$whitespace);
$top_margin = (int)($top_margin/2);
for ($i=0; $i < strlen($code); $i++) {
  $wspace = urand(0,$whitespace);
  if ($ttf) {
	      imagettftext($image, $font_size, $angle_char[$i], $pos_x, $max_code_height + urand(-$top_margin, $top_margin), $fg_color, DIR_FS_CATALOG . DIR_WS_IMAGES . 'fonts/' . ANTI_ROBOT_IMAGE_TTF, $code[$i]);
 } else {
      imagechar($image, $bitmap_font_size, $pos_x, rand(-$top_margin, $top_margin), $code[$i], $fg_color); 
 }
  $pos_x += ($ttf) ? $width_char[$i] + $wspace: imagefontwidth($font_size) + $wspace;
	$width -= ($whitespace - $wspace);
}

$resized_image = @imagecreatetruecolor($width, $height);
if (($image_hieght != 0) || ($image_width != 0))
    imagecopyresized($resized_image, $image, 0, 0, 0, 0, ($image_width) ? $image_width : $width, ($image_hieght) ? $image_hieght : $height, $total_code_width, $max_code_height + $top_margin);
else
    $resized_image = $image;

if (ANTI_ROBOT_IMAGE_FILTER_GREYSCALE=='true')
    image_greyscale($resized_image);
if (ANTI_ROBOT_IMAGE_FILTER_NOISE=='true')
	image_noise($resized_image); 
if (ANTI_ROBOT_IMAGE_FILTER_SCATTER=='true')
    image_scatter($resized_image);
if (ANTI_ROBOT_IMAGE_FILTER_INTERLACE=='true')
    image_interlace($resized_image, $fg_color, $bg_color);		


header('Content-Type: image/png');
header('cache-control: no-store, no-cache, must-revalidate');
imagepng($resized_image);
imagedestroy($image);
imagedestroy($resized_image);
exit;

function image_noise (&$image) {
   $imagex = imagesx($image);
   $imagey = imagesy($image);

   for ($x = 0; $x < $imagex; ++$x) {
      for ($y = 0; $y < $imagey; ++$y) {
         if (rand(0,1)) {
            $rgb = imagecolorat($image, $x, $y);
            $red = ($rgb >> 16) & 0xFF;
            $green = ($rgb >> 8) & 0xFF;
            $blue = $rgb & 0xFF;
            $modifier = rand(-128,128);
            $red += $modifier;
            $green += $modifier;
            $blue += $modifier;

            if ($red > 255) $red = 255;
            if ($green > 255) $green = 255;
            if ($blue > 255) $blue = 255;
            if ($red < 0) $red = 0;
            if ($green < 0) $green = 0;
            if ($blue < 0) $blue = 0;

            $newcol = imagecolorallocate($image, $red, $green, $blue);
            imagesetpixel($image, $x, $y, $newcol);
         }
      }
   }
}

function image_scatter(&$image) {
   $imagex = imagesx($image);
   $imagey = imagesy($image);

   for ($x = 0; $x < $imagex; ++$x) {
      for ($y = 0; $y < $imagey; ++$y) {
         $distx = rand(-1, 1);
         $disty = rand(-1, 1);

         if ($x + $distx >= $imagex) continue;
         if ($x + $distx < 0) continue;
         if ($y + $disty >= $imagey) continue;
         if ($y + $disty < 0) continue;

         $oldcol = imagecolorat($image, $x, $y);
         $newcol = imagecolorat($image, $x + $distx, $y + $disty);
         imagesetpixel($image, $x, $y, $newcol);
         imagesetpixel($image, $x + $distx, $y + $disty, $oldcol);
      }
   }
}

   function image_interlace (&$image, $fg=0, $bg=255) {
      $imagex = imagesx($image);
      $imagey = imagesy($image);

      $fg_red = ($fg >> 16) & 0xFF;
      $fg_green = ($fg >> 8) & 0xFF;
      $fg_blue = $fg & 0xFF;
      $bg_red = ($bg >> 16) & 0xFF;
      $bg_green = ($bg >> 8) & 0xFF;
      $bg_blue = $bg & 0xFF;
	  $red = ($fg_red+$bg_red)/2;
	  $green = ($fg_green+$bg_green)/2;
	  $blue = ($fg_blue+$bg_blue)/2;

      $band = imagecolorallocate($image, $red, $green, $blue);

      for ($y = 0; $y < $imagey; $y+=2) {
            for ($x = 0; $x < $imagex; ++$x) {
               imagesetpixel($image, $x, $y, $band);
            }
      }
   }

function image_greyscale (&$image) {
   $imagex = imagesx($image);
   $imagey = imagesy($image);
   for ($x = 0; $x <$imagex; ++$x) {
      for ($y = 0; $y <$imagey; ++$y) {
         $rgb = imagecolorat($image, $x, $y);
         $red = ($rgb >> 16) & 0xFF;
         $green = ($rgb >> 8) & 0xFF;
         $blue = $rgb & 0xFF;
         $grey = (int)(($red+$green+$blue)/3);
         $newcol = imagecolorallocate($image, $grey, $grey, $grey);
         imagesetpixel($image, $x, $y, $newcol);
      }
   }
}

?>