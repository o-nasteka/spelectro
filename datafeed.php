<?php
/**
 * Google Datafeeder
 * @Autor - www.responsive-oscommerce.com
 * @Script -  v 1.00
**/

ini_set('include_path', dirname( __FILE__ ) . '/../');

require_once ('includes/application_top.php');

//Find the cPath of product
function tep_get_product_path2($products_id) {

	$cPath = "";
	$category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_price > 0 and p.products_id = p2c.products_id order by p2c.categories_id desc");
	if (tep_db_num_rows($category_query))
	{
		$category = tep_db_fetch_array($category_query);
		$categories = array();
		tep_get_parent_categories($categories, $category["categories_id"]);
		$categories = array_reverse($categories);
		$cPath = implode("_", $categories);
		if (tep_not_null($cPath))
		{
			$cPath .= "_";
		}
		$cPath .= $category["categories_id"];
	}
	return $cPath;
}

// We generate the feeds for products
function GenerateNode($data) {
	
		
	$content = "";
	$content .= "	        " . "<entry><g:id>" . $data["pid"] . "</g:id>" . "\n";
	$content .= "	        " . "<g:identifier_exists>no</g:identifier_exists>" . "\n";
	$content .= "	        " . "<g:google_product_category>" . $data["google"] . "</g:google_product_category>" . "\n";
	if (isset($data["manufacturer"]) && $data["manufacturer"] != "")
	{
		$content .= "		" . "<g:brand><![CDATA[" . $data["manufacturer"] . " " . $data["name"] . "]]></g:brand>" . "\n";
	}
	else
	{
		$content .= "		" . "<g:title><![CDATA[" . $data["name"] . "]]></g:title>" . "\n";
	}
	$content .= "		    " . "<g:link><![CDATA[" . $data["url"] . "]]></g:link>" . "\n";
	$content .= "		    " . "<g:image_link><![CDATA[" . $data["image"] . "]]></g:image_link>" . "\n";

	$content .= "		    " . "<g:price><![CDATA[" . $data["price"] . "EUR]]></g:price>" . "\n";
	if (isset($data["manufacturer"]) && $data["manufacturer"] != "")
	{
		$content .= "		" . "<g:brand><![CDATA[" . $data["manufacturer"] . "]]></g:brand>" . "\n";
	}
	if (isset($data["description"]) && $data["description"] != "")
	{
		$content .= "		" . "<g:summary><![CDATA[" . strip_tags($data["description"]) . "]]></g:summary>" . "\n";
	}
	if (isset($data["mpn"]) && $data["mpn"] != "")
	{
		$content .= "		" . "<g:mpn><![CDATA[" . $data["mpn"] . "]]></g:mpn>" . "\n";
	}
	if (isset($data["qty"]) && $data["qty"] > 0)
	{
		$content .= "		" . "<g:availability>in stock</g:availability>" . "\n";
		$content .= "		" . "<g:quantity><![CDATA[" . $data["qty"] . "]]></g:quantity>" . "\n";
	}
	else
	{
		$content .= "		" . "<g:availability>out of stock</g:availability>" . "\n";
	}
	$content .= "		    " . "<g:condition>new</g:condition>" . "\n";
	$content .= "		    " . "<g:country>ES</g:country>" . "\n";
	
	$content .= "		    " . "<g:shipping>" . "\n";
	$content .= "		    " . "<g:service>Standard</g:service>" . "\n";
	$content .= "		    " . "<g:price>6.00 EUR</g:price>" . "\n";
	$content .= "		    " . "</g:shipping>" . "\n";
	
	$content .= "	" . "</entry>" . "\n";
	
	return $content;
}

//error_reporting(E_ALL);
//ini_set("display_errors", 0);
include_once "includes/application_top.php";
$sql_lang = "SELECT languages_id, directory  \r
					FROM " . TABLE_LANGUAGES . " \r
					WHERE code = 'es' OR code = 'es'";
$query_lang = tep_db_query($sql_lang);
$result_lang = tep_db_fetch_array($query_lang);
$language = $result_lang["directory"];
$languages_id = $result_lang["languages_id"];
if (!isset($languages_id) || $languages_id < 1)
{
	$language = "spanish";
	$languages_id = 1;
}
$content = strtolower(file_get_contents(DIR_WS_LANGUAGES . $language . ".php"));
if ($content !== false)
{
	if (!preg_match("/iso-8859-7/", $content) && preg_match("/windows-1253/", $content))
	{
		$enc = "ISO-8859-7";
	}
	else
	{
		$enc = "UTF-8";
	}
}
else
{
	$enc = "UTF-8";
}
header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"" . $enc . "\"?>" . "\n";
echo '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0" version="1">';
echo '<title>spainelectro.com</title>';
echo '<updated>'. date("Y-m-d H:i") . '</updated>';
echo '<lastBuildDate>'. date("Y-m-d H:i") . '</lastBuildDate>';


$sql = "select p.products_id, p.products_model, p.categories_google_id, p.products_quantity, pd.products_name, pd.products_description, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.categories_google_id ='267' and p.products_status = '1' and p.products_price > 0 and p.products_id = pd.products_id and pd.products_name != '' and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
$query = tep_db_query($sql);
if (tep_db_num_rows($query) > 0)
{
	$container = array();
	$number = 0;
	$top = 0;
	while ($result = tep_db_fetch_array($query))
	{
		if ($new_price = tep_get_products_special_price($result["products_id"]))
		{
			$products_price = $currencies->calculate_price($new_price, tep_get_tax_rate($result["products_tax_class_id"]));
		}
		else
		{
			$products_price = $currencies->calculate_price($result["products_price"], tep_get_tax_rate($result["products_tax_class_id"]));
		}
		$pid = $result["products_id"];
		$name = $result["products_name"];
		$google = $result["categories_google_id"];
		$model = $result["products_model"];
		$manufacturer = $result["manufacturers_name"];
		$description = $result["products_description"];
		$qty = $result["products_quantity"];
		$price = $products_price;
		$url = tep_href_link(FILENAME_PRODUCT_INFO, "products_id=" . $result["products_id"], "NONSSL", false);
		$image = tep_href_link(DIR_WS_IMAGES . 'productos/'.$result["products_image"], "", "NONSSL", false);
		$cpat = tep_get_product_path2($result["products_id"]);
		$paths = explode("_", $cpat);
		$cnt = count($paths);
		if ($cnt == 1)
		{
			$categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$paths[0] . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id . "'");
			while ($row = tep_db_fetch_array($categories_query))
			{
				$cid = $paths[0];
				$cname = $row["categories_name"];
				continue;
			}
		}
		else
		{
			$cid = $paths[$cnt - 1];
			$cname = "";
			$x = 0;
			while ($cnt > $x)
			{
				$categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$paths[$x] . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id . "'");
				while ($row = tep_db_fetch_array($categories_query))
				{
					$cname .= $row["categories_name"] . " > ";
					continue;
				}
				$x++;
				continue;
			}
			$cname = substr($cname, 0, 0 - 3);
		}
		$TmUFID = "d2ViaXQuYnovbGljZW5zZS50eHQ=";
		$container = array("pid" => $pid, "name" => $name, "price" => $price, "google" => $google, "mpn" => $model, "manufacturer" => $manufacturer, "description" => $description, "qty" => $qty, "url" => $url, "image" => $image, "cid" => $cid, "cname" => $cname);
		echo generatenode($container);
		continue;
	}
}
echo "" . "\n" . "</feed>" . "\n";

