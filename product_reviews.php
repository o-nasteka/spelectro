<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2012 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (!tep_db_num_rows($product_info_query)) {
    tep_redirect(tep_href_link(FILENAME_REVIEWS));
  } else {
    $product_info = tep_db_fetch_array($product_info_query);
// BOF Separate Pricing Per Customer
// global variable (session) $sppc_customer_group_id -> local variable customer_group_id
    if (isset($_SESSION['sppc_customer_group_id']) && $_SESSION['sppc_customer_group_id'] != '0') {
      $customer_group_id = $_SESSION['sppc_customer_group_id'];
    } else {
      $customer_group_id = '0';
    }

    if ($customer_group_id !='0') {
      $customer_group_price_query = tep_db_query("select customers_group_price from " . TABLE_PRODUCTS_GROUPS . " where products_id = '" . (int)$_GET['products_id'] . "' and customers_group_id =  '" . $customer_group_id . "'");
        if ($customer_group_price = tep_db_fetch_array($customer_group_price_query)) {
          $product_info['products_price'] = $customer_group_price['customers_group_price'];
        }
    }
// EOF Separate Pricing Per Customer
  }

  if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
    $products_price = '<s>' . $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s> <span class="productSpecialPrice">' . $currencies->display_price($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</span>';
  } else {
    $products_price = $currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id']));
  }

  if (tep_not_null($product_info['products_model'])) {
    $products_name = $product_info['products_name'] . ' <span class="smallText">' . $product_info['products_model'] . '</span>';
	$products_name_solo = $product_info['products_name'];
  } else {
    $products_name = $product_info['products_name'];
	$products_name_solo = $product_info['products_name'];
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_REVIEWS);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_PRODUCT_REVIEWS, tep_get_all_get_params()));
?>


<?php require(DIR_THEME. 'html/header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<!-- left_navigation //-->
<?php require(DIR_THEME. 'html/column_left.php'); ?>
<!-- left_navigation_eof //-->

    <?php
        $products_rating_2 = getRatingAndNumReviews( (int)$_GET['products_id'] );
        $products_rating_2=$products_rating_2['RATING'];
        $products_reviews_2=getNumReviews((int)$_GET['products_id']);
    ?>
    <?php 
	if (($products_rating_2>0) and ($products_reviews_2>0)) {
	?>
    <div itemscope itemtype="http://data-vocabulary.org/Product" class="product_reviews escondido">
        <div class="hreview-aggregate">
            <span class="item">
                <span class="fn"><?php echo $products_name ?></span>
            </span>
            <span class="rating">
                <span class="average"><?php echo $products_rating_2 ?></span> de
            </span>
            sobre un total de
            <span class="votes"><?php echo $products_reviews_2 ?></span> puntuaciones
            <span class="count"><?php echo $products_reviews_2 ?></span> opiniones de usuarios
        </div>
    </div>  
    <?php }?>  
<!-- body_text //-->
            <h1 class="pageHeading" valign="top"><?php echo $products_name_solo; ?> <strong class="precio"><?php echo $products_price; ?></strong></h1>

<!-- // Points/Rewards Module V2.1rc2a bof //-->
<?php
   if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
?>
<p class="informacion">
	<?php echo sprintf(REVIEW_HELP_LINK, $currencies->format(tep_calc_shopping_pvalue(USE_POINTS_FOR_REVIEWS)), '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=13', 'NONSSL') . '" title="' . BOX_INFORMATION_MY_POINTS_HELP . '">' . BOX_INFORMATION_MY_POINTS_HELP . '</a>'); ?>
</p>
<?php
  }
?>
<!-- // Points/Rewards Module V2.1rc2a eof //-->
<?php
//*** <Reviews Mod>
  $reviews_query_raw = "select r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$product_info['products_id'] . "' and r.reviews_id = rd.reviews_id and rd.languages_id = '" . (int)$languages_id . "' and r.approved = '1' order by r.reviews_id desc";
//*** </Reviews Mod>
  $reviews_split = new splitPageResults($reviews_query_raw, MAX_DISPLAY_NEW_REVIEWS);

  if ($reviews_split->number_of_rows > 0) {
    if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
?>
<div class="paginacion">
    <p><?php echo $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></p>
</div>
<?php
    }

    $reviews_query = tep_db_query($reviews_split->sql_query);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
?>
    <div class="comentario">
        <p><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $product_info['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '"><u><strong>' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</strong></u></a>'; ?></p>
        <p><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, tep_date_long($reviews['date_added'])); ?></p>
        
        <p class="informacion"><?php echo tep_break_string(tep_output_string_protected($reviews['reviews_text']), 60, '-<br />') . ((strlen($reviews['reviews_text']) >= 100) ? '..' : '') . '<br /><br /><i>' . sprintf(TEXT_REVIEW_RATING, tep_image(DIR_WS_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating'])) . '</i>'; ?>
        </p>
    </div>
<?php
    }
?>
<?php
  } else {
?>
<div class="alert alert-info"><?php echo TEXT_NO_REVIEWS; ?></div>
<?php
  }

  if (($reviews_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
?>

<div class="paginacion">
    <p><?php echo $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></p>
</div>
<?php
  }
?>
<div class="botonera">
<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params()) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?> <?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?>

<?php
  echo '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' . tep_image_button('button_in_cart.gif', IMAGE_BUTTON_IN_CART) . '</a>';
?>
</div>

<!-- right_navigation //-->
<?php require(DIR_THEME. 'html/column_right.php'); ?>
<!-- right_navigation_eof //-->
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_THEME. 'html/footer.php'); ?>
<!-- footer_eof //-->

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
