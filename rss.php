<?php
/*
  $Id: rss.php,v 2.0 2007/05/12 19:30:06 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/*
 * Include the application_top.php script
 */
require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_RSS);

$navigation->remove_current_page();

// If the language is not specified
  $lang_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES . " where languages_id = '" . (int)$languages_id . "'");

// Recover the code (fr, en, etc) and the id (1, 2, etc) of the current language
if (tep_db_num_rows($lang_query)) {
  $lang_a = tep_db_fetch_array($lang_query);
    $lang_code = $lang_a['code'];
}

define(RSS_STRIP_HTML_TAGS,false);
// If the default of your catalog is not what you want in your RSS feed, then
// please change this three constants:
// Enter an appropriate title for your website
define(RSS_TITLE, STORE_NAME);
// Enter your main shopping cart link
define(WEBLINK, HTTP_SERVER);
// Enter a description of your shopping cart
define(DESCRIPTION, TITLE);
/////////////////////////////////////////////////////////////
//That's it.  No More Editing (Unless you renamed DB tables or need to switch
//to SEO links (Apache Rewrite URL)
/////////////////////////////////////////////////////////////

$store_name = STORE_NAME;
$rss_title = RSS_TITLE;
$weblink = WEBLINK;
$description = DESCRIPTION;
$email_address = STORE_OWNER_EMAIL_ADDRESS;

$subtitle = '';

function replace_problem_characters($text) {
    $in[] = '@&(amp|#038);@i'; $out[] = '&';
    $in[] = '@&(#036);@i'; $out[] = '$';
    $in[] = '@&(quot);@i'; $out[] = '"';
    $in[] = '@&(#039);@i'; $out[] = '\'';
    $in[] = '@&(nbsp|#160);@i'; $out[] = ' ';
    $in[] = '@&(hellip|#8230);@i'; $out[] = '...';
    $in[] = '@&(copy|#169);@i'; $out[] = '(c)';
    $in[] = '@&(trade|#129);@i'; $out[] = '(tm)';
    $in[] = '@&(lt|#60);@i'; $out[] = '<';
    $in[] = '@&(gt|#62);@i'; $out[] = '>';
    $in[] = '@&(laquo);@i'; $out[] = '�';
    $in[] = '@&(raquo);@i'; $out[] = '�';
    $in[] = '@&(deg);@i'; $out[] = '�';
    $in[] = '@&(mdash);@i'; $out[] = '�';
    $in[] = '@&(reg);@i'; $out[] = '�';
	$in[] = '@&(�);@i'; $out[] = '-';
    $text = preg_replace($in, $out, $text);
	return $text;
}

function strip_html_tags($str) {
// $document should contain an HTML document.
// This will remove HTML tags, javascript sections
// and white space. It will also convert some
// common HTML entities to their text equivalent.

	$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
					 "'<[/!]*?[^<>]*?>'si",          // Strip out HTML tags
					 //"'([rn])[s]+'",                // Strip out white space
					 "'&(quot|#34);'i",                // Replace HTML entities
					 "'&(amp|#38);'i",
					 "'&(lt|#60);'i",
					 "'&(gt|#62);'i",
					 "'&(nbsp|#160);'i",
					 "'&(iexcl|#161);'i",
					 "'&(cent|#162);'i",
					 "'&(pound|#163);'i",
					 "'&(copy|#169);'i",
					 "'&#(d+);'e");                    // evaluate as php
	
	$replace = array ("",
					 "",
					 //"\1",
					 "\"",
					 "&",
					 "<",
					 ">",
					 " ",
					 chr(161),
					 chr(162),
					 chr(163),
					 chr(169),
					 "chr(\1)");
	
	return preg_replace($search, $replace, $str);
}


  if(!function_exists('eval_rss_urls')) {
    function eval_rss_urls($string) {
			// rewrite all local urls to absolute urls
		return preg_replace_callback("/(href|src|action)=[\"']{0,1}(\.{0,2}[\\|\/]{1})(.*?)[\"']{0,1}( .*?){0,1}>/is","rewrite_rss_local_urls",$string);
		//"/(href|src|action)=[\"']{0,1}(\.{2}[\\|\/]{1})(.*?)[\"']{0,1}( .*?){0,1}>/is"
	}
  } 

  	function rewrite_rss_local_urls($string) {

	$repl_text = $string[0];
	$repl_by = HTTP_SERVER.'\\';
	$repl_len = 0;
	
	$index_pos = strpos  ( $repl_text , '..');
	if ($index_pos > -1) {
		$repl_len = 2;	
	} else {
	 	$index_pos = strpos  ( $repl_text , '\"\\');	
		if ($index_pos > -1) {
			$repl_len = 0;	
			$index_pos+=1;
		}
	}
	if ($index_pos > -1) {
		substr_replace($repl_text, $repl_by, $index_pos,2 );	
	}
		ob_start();
			echo $repl_text;
			$return = ob_get_contents();
		   ob_end_clean();
		return $return;
	}
	
// to strip html or not to strip html tags
if (isset($_GET['html'])) {
	if ($_GET['html']=='false') {
		$strip_html_tags = true;	
	} else {
		$strip_html_tags = false;	
	}
} else {
	$strip_html_tags = RSS_STRIP_HTML_TAGS;
}

// show the products of a specified manufacturer

    if (isset($_GET['manufacturers_id'])) {

      if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {

// We are asked to show only a specific category

        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_price, p.products_image, p.products_date_added, pd.products_name, pd.products_description,
               m.manufacturers_name, cd.categories_name, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
               ON p2c.categories_id = cd.categories_id where p.products_status = '1' and p.products_to_rss='1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "' 
			   GROUP BY p.products_id
        ORDER BY p.products_id DESC
        LIMIT ". (int)MAX_RSS_ARTICLES."";

      } else {

// We show them all

        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_price, p.products_image, p.products_date_added, pd.products_name, pd.products_description,
               m.manufacturers_name, cd.categories_name, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
               ON p2c.categories_id = cd.categories_id  where p.products_status = '1' and p.products_to_rss='1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id']  . "' 
			   GROUP BY p.products_id
        ORDER BY p.products_id DESC
        LIMIT ". (int)MAX_RSS_ARTICLES."";


      }
	  
	  $subtitle = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
      $subtitle = tep_db_fetch_array($subtitle);
      $subtitle = $subtitle['manufacturers_name'];
  	  $rss_title = BOX_INFORMATION_RSS_MANUFACTURER;
    } else if (tep_not_null($current_category_id)) {

// show the products in a given categorie

      if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
// We are asked to show only specific catgeory
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_price, p.products_image, p.products_date_added, pd.products_name, pd.products_description,
               m.manufacturers_name, cd.categories_name, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_MANUFACTURERS . " m, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
               ON p2c.categories_id = cd.categories_id where p.products_status = '1' and p.products_to_rss='1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id  . "' 
			   GROUP BY p.products_id
        ORDER BY p.products_id DESC
        LIMIT ". (int)MAX_RSS_ARTICLES."";

      } else {
// We show them all
        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_price, p.products_image, p.products_date_added, pd.products_name, pd.products_description,
               m.manufacturers_name, cd.categories_name, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
               ON p2c.categories_id = cd.categories_id where p.products_status = '1' and p.products_to_rss='1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' 
			   and p2c.categories_id = '" . (int)$current_category_id  . "' 
			   GROUP BY p.products_id
        ORDER BY p.products_id DESC
        LIMIT ". (int)MAX_RSS_ARTICLES."";
      }

    	$subtitle= tep_db_query("select cd.categories_name, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
      $subtitle = tep_db_fetch_array($subtitle);
      $subtitle = $subtitle['categories_name'];
   	  $rss_title = BOX_INFORMATION_RSS_CATEGORY;

    } else {
	
		// show realy al products
	        $listing_sql = "select " . $select_column_list . " p.products_id, p.manufacturers_id, p.products_model, p.products_price, p.products_image, p.products_date_added, pd.products_name, pd.products_description,
               m.manufacturers_name, cd.categories_name, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd
               ON p2c.categories_id = cd.categories_id where p.products_status = '1' and p.products_to_rss='1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "'  
			   GROUP BY p.products_id
        ORDER BY p.products_id DESC
        LIMIT ". (int)MAX_RSS_ARTICLES."";
		
		$rss_title = BOX_INFORMATION_RSS;
	}
//	echo $listing_sql;
// Execute SQL query and get result
$query = tep_db_query($listing_sql);

	/*
	 * If there are returned rows...
	 * Basic sanity check 
	 */
	if ( tep_db_num_rows($query) > 0 ){

if ($subtitle != '') $rss_title .= ' - '.$subtitle;

// Encoding to UTF-8
$store_name =  utf8_encode (replace_problem_characters($store_name));
$rss_title =  utf8_encode (replace_problem_characters($rss_title));
$weblink =  utf8_encode ($weblink);
$description =  utf8_encode (replace_problem_characters($description));
$email_address =  utf8_encode ($email_address);

	// SQL header(Last-Modified)
$last_modified = '';
$sql = "SELECT MAX(products_date_added) as mPla, MAX(products_last_modified) as mPlm FROM products WHERE products_to_rss = '1' ORDER BY products_id DESC LIMIT 1";
  $sql_result = tep_db_query($sql);
    $row = tep_db_fetch_array($sql_result);

	if ($row['mPlm'] != '') { $last_modified = $row['mPlm']; }
	else { $last_modified = $row['mpla']; }

  $headers = getallheaders(); 
  $refresh = 1; // refresca por defecto 
  $etag = md5($last_modified);

  if(isset($headers["If-Modified-Since"])) { // Verificamos fecha enviada por el lector RSS
  	$since = strtotime($headers["If-Modified-Since"]); 
	if($since >= strtotime($last_modified)) { $refresh = 0; }
  } 

  if(isset($headers["If-None-Match"])) { // Verificamos el ETag
  	if(strcmp($headers["If-None-Match"], $etag) == 0) {
	  $refresh = 0;
	} else {
	  $refresh = 1;
	}
  }

  if($refresh == 0) {
    header("HTTP/1.1 304 Not changed");
    // La primera l�nea de los headers debe ser el status
    // sino el Netcrap se l�a y da "No Data"
    ob_end_clean(); // Descartamos los contenidos del b�fer de salida en cola y lo deshabilitamos
  }


// Begin sending of the data
header('Content-Type: application/rss+xml; charset=UTF-8');
//header("Last-Modified: " . tep_date_raw($last_modified));
header('Last-Modified: ' .gmdate("D, d M Y G:i:s", strtotime($last_modified)). ' GMT');
//header('Last-Modified: ' .gmdate("D, d M Y G:i:s T",strtotime($last_modified)) . ' GMT');
header("ETag: " . md5($etag));
echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
echo '<?xml-stylesheet href="http://www.w3.org/2000/08/w3c-synd/style.css" type="text/css"?>' . "\n";
echo '<!-- RSS for ' . $store_name . ', generated on ' . gmdate("D, d M Y G:i:s", strtotime($last_modified)) . ' GMT'. ' -->' . "\n";
?>
<rss version="2.0"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:ecommerce="http://shopping.discovery.com/erss/"
  xmlns:media="http://search.yahoo.com/mrss/">
<channel>
<title><?php echo utf8_encode($rss_title); ?></title>
<link><?php echo $weblink;?></link>
<description><?php echo utf8_encode($description); ?></description>
<atom:link href="<?php echo tep_href_link(FILENAME_RSS,urlencode($_SERVER['QUERY_STRING']),'NONSSL',false ); ?>" rel="self" type="application/rss+xml" />
<webMaster><?php echo $email_address; ?></webMaster>
<language><?php echo $lang_code; ?></language>
<lastBuildDate><?php echo gmdate("D, d M Y G:i:s", strtotime($last_modified)). ' GMT'; ?></lastBuildDate>
<image>
  <url><?php echo $weblink . DIR_WS_CATALOG.'/images/rss_logo.jpg';?></url>
  <title><?php echo utf8_encode($rss_title); ?></title>
  <link><?php echo $weblink;?></link>
  <description><?php echo utf8_encode($description); ?></description>
</image>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<?php
// Format results by row
while( $row = tep_db_fetch_array($query) ){
  $id = $row['products_id'];

  // RSS Links for Ultimate SEO (Gareth Houston 10 May 2005)
  $link = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $id, 'NONSSL', false);

  $model = $row['products_model'];
  $image = $row['products_image'];
  $added = date(r,strtotime($row['products_date_added']));

  // Setting and cleaning the data
  $name = $row['products_name'];
  $desc = eval_rss_urls($row['products_description']);

  // Encoding to UTF-8  
  if ($strip_html_tags ==true) {
  	$name = strip_html_tags($name);
  	$desc = strip_html_tags($desc);
  }
  
  $name = utf8_encode(replace_problem_characters($name));
  $desc = utf8_encode(replace_problem_characters($desc));
  $link = utf8_encode($link);

  $manufacturer = $row['manufacturers_name'];
  $manufacturer = utf8_encode ($manufacturer);
  $price = tep_add_tax($row['products_price'], tep_get_tax_rate($row['products_tax_class_id']));
  if( $discount = tep_get_products_special_price($id) ) {
    $offer = tep_add_tax($discount, tep_get_tax_rate($row['products_tax_class_id']));
  } else {
    $offer = '';
  }

  $cat_name = $row['categories_name'];

  // Encoding to UTF-8
  $cat_name = utf8_encode (replace_problem_characters(strip_html_tags($cat_name)));

  // Setting the URLs to the images and buttons
  $relative_image_url = tep_image(DIR_WS_IMAGES . '/productos/'.$image, $name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'style="float: left; margin: 0px 8px 8px 0px;"');
  $relative_image_url = str_replace('">', '', $relative_image_url);
  $relative_image_url = str_replace('<img src="', '', $relative_image_url);
  $image_url = HTTP_SERVER . DIR_WS_CATALOG . $relative_image_url;

  $relative_buy_url = tep_image_button('button_shopping_cart.gif', IMAGE_BUTTON_IN_CART, 'style="margin: 0px;"');
  $relative_buy_url = str_replace('">', '', $relative_buy_url);
  $relative_buy_url = str_replace('<img src="', '', $relative_buy_url);
  $buy_url = HTTP_SERVER . DIR_WS_CATALOG . $relative_buy_url;

  $relative_button_url = tep_image_button('button_more_info.gif', IMAGE_BUTTON_MORE_INFO, 'style="margin: 0px;"');
  $relative_button_url = str_replace('">', '', $relative_button_url);
  $relative_button_url = str_replace('<img src="', '', $relative_button_url);
  $button_url = HTTP_SERVER . DIR_WS_CATALOG . $relative_button_url;


  // http://www.w3.org/TR/REC-xml/#dt-chardata
  // The ampersand character (&) and the left angle bracket (<) MUST NOT appear in their literal form
  $name = str_replace('&','&amp;',$name);
  $desc = str_replace('&','&amp;',$desc);
  $link = str_replace('&','&amp;',$link);
  $cat_name = str_replace('&','&amp;',$cat_name);

  $name = str_replace('<','&lt;',$name);
  $desc = str_replace('<','&lt;',$desc);
  $link = str_replace('<','&lt;',$link);
  $cat_name = str_replace('<','&lt;',$cat_name);

  $name = str_replace('>','&gt;',$name);
  $desc = str_replace('>','&gt;',$desc);
  $link = str_replace('>','&gt;',$link);
  $cat_name = str_replace('>','&gt;',$cat_name);
  
  
  $buy_link = tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $id);
  // Encoding to UTF-8
  $buy_link = utf8_encode ($buy_link);
  $buy_link = str_replace('&','&amp;',$buy_link);
  $buy_link = str_replace('<','&lt;',$buy_link);
  $buy_link = str_replace('>','&gt;',$buy_link);

  // Writing the output
  echo '<item>' . "\n";
  echo '  <title>' . $name . '</title>' . "\n";
  echo '  <category>' . $cat_name . '</category>' . "\n";
  echo '  <link>' . $link . '</link>' . "\n";
  echo '  <description>'; // . "\n";
  if ($ecommerce=='' && $image != '') {
    echo '<![CDATA[<a href="' . $link . '"><img src="' . $image_url . '</a>]]>';
  }
  echo $desc;
  if ($ecommerce=='') {
    echo '<![CDATA[<br /><br /><a href="' .$buy_link. '"><img src="' . $buy_url . '</a>&nbsp;]]>';
    echo '<![CDATA[<a href="' . $link . '"><img src="' . $button_url . '</a>]]>' . "\n";
  }
  echo '</description>' . "\n";
  echo '  <guid>' . $link . '</guid>' . "\n";
  echo '  <pubDate>' . gmdate("D, d M Y G:i:s", strtotime($added)). ' GMT' . '</pubDate>' . "\n";
  if($ecommerce!='') {
    echo '  <media:thumbnail url="' . $image_url . '">' . $image_url . '</media:thumbnail>' . "\n";
    echo '  <ecommerce:SKU>' . $id . '</ecommerce:SKU>' . "\n";
    echo '  <ecommerce:listPrice currency="' . DEFAULT_CURRENCY . '">' . $price . '</ecommerce:listPrice>' . "\n";
    if ($offer) {
      echo '  <ecommerce:offerPrice currency="' . DEFAULT_CURRENCY . '">' . $offer . '</ecommerce:offerPrice>' . "\n";
    }
    echo '  <ecommerce:manufacturer>' . $manufacturer . '</ecommerce:manufacturer>' . "\n";
  }
  echo '</item>' . "\n";
}
}

echo '</channel>' . "\n";
echo '</rss>' . "\n";

	/*
	 * Include the application_bottom.php script 
	 */
	include_once('includes/application_bottom.php');
?>