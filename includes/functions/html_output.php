<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

// BOF Ultimate SEO URLs EDITABLE
// The HTML href link wrapper function
if (SEO_ENABLED == 'true') { //run chemo's code
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
        global $seo_urls;
        global $kill_sid;
        if ( !is_object($seo_urls) ){
	        if ( !class_exists('SEO_URL') ){
	                include_once(DIR_WS_CLASSES . 'seo.class.php');
	        }
	        global $languages_id;
	        $seo_urls = new SEO_URL($languages_id);
        }
        return preg_replace('/&/','&',$seo_urls->href_link($page, $parameters, $connection, $add_session_id));
  }
} else { //run original code

// The HTML href link wrapper function
  function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true) {
    global $request_type, $session_started, $SID;

    if (!tep_not_null($page)) {
      die('<div class="alert alert-danger"><strong>Unable to determine the page link!</strong></div>');
    }

    if ($connection == 'NONSSL') {
      $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == true) {
        $link = HTTPS_SERVER . DIR_WS_HTTPS_CATALOG;
      } else {
        $link = HTTP_SERVER . DIR_WS_HTTP_CATALOG;
      }
    } else {
      die('<div class="alert alert-danger">Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL</strong></div>');
    }
    if ($_GET['language'] && $kill_sid) {
      $l = preg_match('/[&\?/]?language[=/][a-z][a-z]/', $parameters, $m);
      if ($l) {
        $parameters = preg_replace('/[&\?/]?language[=/][a-z][a-z]/', "", $parameters);
        $_GET['language'] = substr($m[0],-2);
      }
      if (tep_not_null($parameters)) {
        $parameters .= "&language=" . $_GET['language'];
      } else {
        $parameters = "language=" . $_GET['language'];
      }
    }

    if (tep_not_null($parameters)) {
      $link .= $page . '?' . tep_output_string($parameters);
      $separator = '&';
    } else {
      $link .= $page;
      $separator = '?';
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

// Add the session ID when moving from different HTTP and HTTPS servers, or when SID is defined
    if ( ($add_session_id == true) && ($session_started == true) && (SESSION_FORCE_COOKIE_USE == 'False') ) {
      if (tep_not_null($SID)) {
        $_sid = $SID;
      } elseif ( ( ($request_type == 'NONSSL') && ($connection == 'SSL') && (ENABLE_SSL == true) ) || ( ($request_type == 'SSL') && ($connection == 'NONSSL') ) ) {
        if (HTTP_COOKIE_DOMAIN != HTTPS_COOKIE_DOMAIN) {
          $_sid = tep_session_name() . '=' . tep_session_id();
        }
      }
    }

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($_sid) && ( !$kill_sid ) ) {
      $link .= $separator . $_sid;
    }

    return $link;
  }
}


// "On the Fly" Auto Thumbnailer using GD Library, servercaching and browsercaching
// Scales product images dynamically, resulting in smaller file sizes, and keeps
// proper image ratio. Used in conjunction with product_thumb.php t/n generator.
function tep_image($src, $alt = '', $width = '', $height = '', $params = '', $bSize = true) {

  
    // Set default image variable and code
    $image = '<img src="' . $src . '"';

    // Don't calculate if the image is set to a "%" width
    if (strstr($width,'%') == false || strstr($height,'%') == false) {
        $dont_calculate = 0;
    } else {
        $dont_calculate = 1;
    }

    // Dont calculate if a pixel image is being passed (hope you dont have pixels for sale)
    if (!strstr($image, 'pixel')) {
        $dont_calculate = 0;
    } else {
        $dont_calculate = 1;
    }
	
	if( ! $bSize )
	{
		$dont_calculate = 1;
		$image = '<img src="product_thumb.php?img=' . $src . '&amp;w=' .
                tep_output_string($width) . '&amp;h=' . tep_output_string($height) . '"';
	}

    // Do we calculate the image size?
    if (CONFIG_CALCULATE_IMAGE_SIZE && !$dont_calculate) {

        // Get the image's information
        if ($image_size = @getimagesize($src)) {

            $ratio = $image_size[1] / $image_size[0];

            // Set the width and height to the proper ratio
            if (!$width && $height) {
                $ratio = $height / $image_size[1];
                $width = intval($image_size[0] * $ratio);
            } elseif ($width && !$height) {
                $ratio = $width / $image_size[0];
                $height = intval($image_size[1] * $ratio);
            } elseif (!$width && !$height) {
                $width = $image_size[0];
                $height = $image_size[1];
            }

            // Scale the image if not the original size
            if ($image_size[0] != $width || $image_size[1] != $height) {
                $rx = $image_size[0] / $width;
                $ry = $image_size[1] / $height;

                if ($rx < $ry) {
                    $width = intval($height / $ratio);
                } else {
                    $height = intval($width * $ratio);
                }

                $image = '<img src="product_thumb.php?img=' . $src . '&amp;w=' .
                tep_output_string($width) . '&amp;h=' . tep_output_string($height) . '"';
            }

        } elseif (IMAGE_REQUIRED == 'false') {
            return '';
        }
    }

    // Add remaining image parameters if they exist
    if ($width) {
        $image .= ' width="' . tep_output_string($width) . '"';
    }

    if ($height) {
        $image .= ' height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($params)) $image .= ' ' . $params;

    $image .= ' alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
        $image .= ' title="' . tep_output_string($alt) . '"';
    }

    $image .= ' />';

    return $image;
}			
				
////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;

    $image_submit .= ' />';

    return $image_submit;
  }

////
// Output a function button in the selected language
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
  }

////
// Output a separator either through whitespace, or with an image
  function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return tep_image(DIR_WS_IMAGES . $image, '', $width, $height);
  }

////
// Output a form
  function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters)) $form .= ' ' . $parameters;

    $form .= '>';

    return $form;
  }

////
// Output a form input field
  function tep_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
   

    $field = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '" ';

    if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $value = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $value = stripslashes($_POST[$name]);
      }
    }

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;
    $field .= 'id="' . tep_output_string($name) . '"';
    $field .= ' />';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Output a form password field
  function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters,false, 'password', false);
  }

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
  function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {

    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) $selection .= ' value="' . tep_output_string($value) . '"';

    if ( ($checked == true) || (isset($_GET[$name]) && is_string($_GET[$name]) && (($_GET[$name] == 'on') || (stripslashes($_GET[$name]) == $value))) || (isset($_POST[$name]) && is_string($_POST[$name]) && (($_POST[$name] == 'on') || (stripslashes($_POST[$name]) == $value))) ) {
      $selection .= ' checked="checked"';
    }

    if (tep_not_null($parameters)) $selection .= ' ' . $parameters;

    $selection .= ' />';

    return $selection;
  }

////
// Output a form checkbox field
  function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
  }

////
// Output a form radio field
  function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
  }

////
// Output a form textarea field
// The $wrap parameter is no longer used in the core xhtml template
  function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {

    $field = '<textarea name="' . tep_output_string($name) . '" id="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= '>';

    if ( ($reinsert_value == true) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $field .= tep_output_string_protected(stripslashes($_GET[$name]));
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $field .= tep_output_string_protected(stripslashes($_POST[$name]));
      }
    } elseif (tep_not_null($text)) {
      $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
  }

////
// Output a form hidden field
  function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
      $field .= ' value="' . tep_output_string($value) . '"';
    } elseif ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) {
      if ( (isset($_GET[$name]) && is_string($_GET[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($_GET[$name])) . '"';
      } elseif ( (isset($_POST[$name]) && is_string($_POST[$name])) ) {
        $field .= ' value="' . tep_output_string(stripslashes($_POST[$name])) . '"';
      }
    }

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' />';

    return $field;
  }

////
// Hide form elements
  function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
      return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
  }

////
// Output a form pull down menu
  function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters)) $field .= ' ' . $parameters;

    $field .= ' class="form-control">';

    if (empty($default) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
      if (isset($_GET[$name]) && is_string($_GET[$name])) {
        $default = stripslashes($_GET[$name]);
      } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
        $default = stripslashes($_POST[$name]);
      }
    }

    for ($i=0, $n=sizeof($values); $i<$n; $i++) {
      $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
      if ($default == $values[$i]['id']) {
        $field .= ' selected="selected"';
      }

      $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

////
// Creates a pull-down list of countries
  function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();

    for ($i=0, $n=sizeof($countries); $i<$n; $i++) {
      $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
  }

////
// Output a Button Modified responsive-oscommerce.com
  function tep_draw_button($title = null, $icon = null, $link = null, $class = null, $params = null) {

    $types = array('submit', 'button', 'reset');

    if ( !isset($params['type']) ) {
      $params['type'] = 'submit';
    }

    if ( !in_array($params['type'], $types) ) {
      $params['type'] = 'submit';
    }

    if ( ($params['type'] == 'submit') && isset($link) ) {
      $params['type'] = 'button';
    }

    if (!isset($class)) {
     $class = '';
    }


    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '<a class="' . $class . '" href="' . $link . '"';

      if ( isset($params['newwindow']) ) {
        $button .= ' target="_blank"';
      }
    } else {
      $button .= '<button class="' . $class . '" type="' . tep_output_string($params['type']) . '"';
    }

    if ( isset($params['params']) ) {
      $button .= ' ' . $params['params'];
    }

    $button .= '>';
      if ( isset($icon) ) {
        $button .= '<i class="' . $icon . '"></i>';
      }  
    $button .=  $title;

    if ( ($params['type'] == 'button') && isset($link) ) {
      $button .= '</a>';
    } else {
      $button .= '</button>';
    }

    return $button;
  }
  
?>