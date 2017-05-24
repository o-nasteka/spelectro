<?php
/*
  $Id: basket.php,v 2.0 2008/09/01 23:28:24 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Author : Antonio Ibarrola
  email  : antonio_ibarrola_cerda@hotmail.com
  Online Store : www.topgun.com.mx
*/

  require('includes/application_top.php');

// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
  if ($session_started == false) { 
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
  } 

// screen parameter is used to screen wide (W)-Best view 1600x1200 or thiny (T)-Best view 1024x768
  if (!isset($screen)) {
       $screen='W';
  } else {
       $screen = strtoupper($screen) ;
       if (!$screen=='T') {$screen='W';}
  } 

// check basket password 
  if (empty($psw) or $psw <> BASKET_PASSWORD) {
      $messageStack->add('login', TEXT_BASKET_PASSWORD_ERROR) ;
      tep_redirect(tep_href_link(FILENAME_DEFAULT)) ;
  } else {
      tep_session_register('psw');
      tep_session_register('screen');
  }


  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_BASKET);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');

  $cart->reset();

  $basket_customers_id  = array() ;
  $basket_date_added    = array() ;
  $basket_last_login    = array() ;
  $basket_name          = array() ;
  $basket_email_address = array() ;
  $basket_total_cart    = array() ;


  if (isset($action) && $action=='delete') {
     tep_db_query("DELETE from " . TABLE_CUSTOMERS_BASKET . " where customers_id=" . $cID);
     tep_db_query("DELETE from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id=" . $cID);
     unset($cID) ;
     unset($action) ;
  }

  $error = false;
  if (isset($action) && $action=='login') {
    unset($action) ;
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($cID) . "'");
    if (!tep_db_num_rows($check_customer_query)) {
        $error = true;
    } else {
        $check_customer = tep_db_fetch_array($check_customer_query);
        if (SESSION_RECREATE == 'True') {
          tep_session_recreate();
        }

        $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_customer['customers_id'] . "' and address_book_id = '" . (int)$check_customer['customers_default_address_id'] . "'");
        $check_country = tep_db_fetch_array($check_country_query);

        $customer_id = $check_customer['customers_id'];
        $customer_default_address_id = $check_customer['customers_default_address_id'];
        $customer_first_name = $check_customer['customers_firstname'];
        $customer_country_id = $check_country['entry_country_id'];
        $customer_zone_id = $check_country['entry_zone_id'];
        tep_session_register('customer_id');
        tep_session_register('customer_default_address_id');
        tep_session_register('customer_first_name');
        tep_session_register('customer_country_id');
        tep_session_register('customer_zone_id');
    }
    // restore cart contents
    $cart->restore_contents();
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if ($error == true) {
    $messageStack->add('login', TEXT_BASKET_LOGIN_ERROR);
  }


  $i = -1 ;
  $previus_customer_id = -1 ;
  $sql_basket = tep_db_query("SELECT * from " . TABLE_CUSTOMERS_BASKET . " where 1 order by customers_id desc , customers_basket_date_added");
  while ($BASKET=tep_db_fetch_array($sql_basket)) 
   {
      if ($previus_customer_id <> $BASKET['customers_id']) {
          $previus_customer_id =  $BASKET['customers_id'] ;
          $i++ ; 
          $basket_total_cart[$i] = 0 ;
          $sql_customers = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id =" . $BASKET['customers_id']);
          if ($CUSTOMERS = tep_db_fetch_array($sql_customers)) {
              $basket_name[$i]          = $CUSTOMERS['customers_firstname'] . " " . $CUSTOMERS['customers_lastname'];
              $basket_email_address[$i] = $CUSTOMERS['customers_email_address'] ;
              $sql_customers_info = tep_db_query("select * from " . TABLE_CUSTOMERS_INFO . " where customers_info_id =" . $BASKET['customers_id']);
              if ($CUSTOMERS_INFO = tep_db_fetch_array($sql_customers_info)) {
                  if ($CUSTOMERS_INFO['customers_info_date_of_last_logon'] == NULL) {
                      $basket_last_login[$i] = $CUSTOMERS_INFO['customers_info_date_account_created'] ;
                  } else {
                      $basket_last_login[$i] = $CUSTOMERS_INFO['customers_info_date_of_last_logon'] ;
                  }
              } else {
                  $basket_last_login[$i]    = " " ;
              }
              $customer_id = $CUSTOMERS['customers_id'];
              tep_session_register('customer_id');
              // restore cart contents
              $cart->restore_contents();
              $basket_total_cart[$i] = $cart->show_total(); ;
              $cart->reset();
              tep_session_unregister('customer_id');
          } else {
              $basket_customers_id[$i]  = 0 ;
              $basket_last_login[$i]    = " "                 ;
              $basket_name[$i]          = TEXT_BASKET_CUSTOMER_NO_EXIST ;
              $basket_email_address[$i] = TEXT_BASKET_CUSTOMER_NO_EXIST ;
          }
          $basket_customers_id[$i] = $BASKET['customers_id'] ;
      }

      $raw_date = $BASKET['customers_basket_date_added'] ;
      $year  = substr($raw_date, 0, 4);
      $month = substr($raw_date, 4, 2);
      $day   = substr($raw_date, 6, 2);
      $basket_date_added[$i]   = $year . '-' . $month . '-' . $day . ' 00:00:00' ;
   }

   // ---- Sort for PHP 4.0 or greater
   // ---- sort 1 Customer id Descending (defaut for PHP < 4 ) as it reader from data base
   // ---- sort 2 Name Ascending (defaut for PHP >= 4)
   // ---- sort 3 Email
   // ---- sort 4 Last Access Date
   // ---- sort 5 Shoping Cart Date
   // ---- sort 6 Total Cart
   if (function_exists('array_multisort')) {
      if (!isset($sort)) {$sort='2a';}
      $sort1 = substr($sort, 0, 1);
      $sort2 = substr($sort, 1, 1);
      if ($sort1 < 1 or $sort > 6) {$sort1=2; $sort2='a'; $sort = $sort1 . $sort2 ; }
      if ($sort2 != 'a' and $sort2 != 'd') {$sort1=2; $sort2='a'; $sort = $sort1 . $sort2 ;}

      $href_sort1='1a';
      $href_sort2='2a';
      $href_sort3='3a';
      $href_sort4='4a';
      $href_sort5='5a';
      $href_sort6='6a';
      $head_sort1=' ';
      $head_sort2=' ';
      $head_sort3=' ';
      $head_sort4=' ';
      $head_sort5=' ';
      $head_sort6=' ';
      if ($sort=='1a') {$href_sort1='1d'; $head_sort1='+';}
      if ($sort=='2a') {$href_sort2='2d'; $head_sort2='+';}
      if ($sort=='3a') {$href_sort3='3d'; $head_sort3='+';}
      if ($sort=='4a') {$href_sort4='4d'; $head_sort4='+';}
      if ($sort=='5a') {$href_sort5='5d'; $head_sort5='+';}
      if ($sort=='6a') {$href_sort6='6d'; $head_sort6='+';}
      if ($sort=='1d') {$href_sort1='1a'; $head_sort1='-';}
      if ($sort=='2d') {$href_sort2='2a'; $head_sort2='-';}
      if ($sort=='3d') {$href_sort3='3a'; $head_sort3='-';}
      if ($sort=='4d') {$href_sort4='4a'; $head_sort4='-';}
      if ($sort=='5d') {$href_sort5='5a'; $head_sort5='-';}
      if ($sort=='6d') {$href_sort6='6a'; $head_sort6='-';}

      $basket_name          = array_map('strtolower', $basket_name);
      $basket_name          = array_map('ucwords'   , $basket_name);
      $basket_email_address = array_map('strtolower', $basket_email_address);


      if ($sort1 == 1) { if ($sort2=='a') {array_multisort($basket_customers_id, SORT_ASC , $basket_name, $basket_email_address, $basket_last_login, $basket_date_added, $basket_total_cart );}
      }
      if ($sort1 == 2) { if ($sort2=='d') {array_multisort($basket_name, SORT_DESC, $basket_email_address, $basket_last_login, $basket_date_added, $basket_total_cart, $basket_customers_id );
                         } else {          array_multisort($basket_name, SORT_ASC , $basket_email_address, $basket_last_login, $basket_date_added, $basket_total_cart, $basket_customers_id );}
      }
      if ($sort1 == 3) { if ($sort2=='d') {array_multisort($basket_email_address, SORT_DESC, $basket_name, $basket_last_login, $basket_date_added, $basket_total_cart, $basket_customers_id );
                         } else {          array_multisort($basket_email_address, SORT_ASC , $basket_name, $basket_last_login, $basket_date_added, $basket_total_cart, $basket_customers_id );}
      }
      if ($sort1 == 4) { if ($sort2=='d') {array_multisort($basket_last_login, SORT_DESC, $basket_name, $basket_email_address, $basket_date_added, $basket_total_cart, $basket_customers_id );
                         } else {          array_multisort($basket_last_login, SORT_ASC , $basket_name, $basket_email_address, $basket_date_added, $basket_total_cart, $basket_customers_id );}
      }
      if ($sort1 == 5) { if ($sort2=='d') {array_multisort($basket_date_added, SORT_DESC, $basket_name, $basket_email_address, $basket_last_login, $basket_total_cart, $basket_customers_id );
                         } else {          array_multisort($basket_date_added, SORT_ASC , $basket_name, $basket_email_address, $basket_last_login, $basket_total_cart, $basket_customers_id );}
      }
      if ($sort1 == 6) { if ($sort2=='d') {array_multisort($basket_total_cart, SORT_DESC, $basket_date_added, $basket_last_login, $basket_name, $basket_email_address, $basket_customers_id );
                         } else {          array_multisort($basket_total_cart, SORT_ASC , $basket_date_added, $basket_last_login, $basket_name, $basket_email_address, $basket_customers_id );}
      }
   }

   // prepare to display the cart in shoping box on colum left or header
   if (isset($cID) && $cID <> 0) {
       $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($cID) . "'");
       if (tep_db_num_rows($check_customer_query)) {
           $check_customer = tep_db_fetch_array($check_customer_query);
           $customer_id = $check_customer['customers_id'];
           tep_session_register('customer_id');
           // restore cart contents
           $cart->restore_contents();
       }
    }

?>

<STYLE>
.basketRowOver     { background-color: #D7E9F7; font-family: Verdana, Arial, sans-serif; font-size: 10px; }   
.basketRowSelected { background-color: #80ff80; font-family: Verdana, Arial, sans-serif; font-size: 10px; font-weight : bold; }
</STYLE>

<script language="javascript">
function rowOverEffect(object) {
  if (object.className != 'basketRowSelected') object.className = 'basketRowOver';
}

function rowOutEffect(object) {
  if (object.className != 'basketRowSelected') object.className = 'infoBoxContents';
}
//--></script>





<?php require(DIR_THEME. 'html/header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- left_navigation //-->
<?php require(DIR_THEME. 'html/column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top" cellpadding="1">
      <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">     
        <tr>
           <td class="pageHeading"><?php echo HEADING_BASKET_TITLE; ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
      </table>

      <table border="0" width="100%" height="100%" cellspacing="0" cellpadding="2" class="infoBox">
        <tr class="infoBox" border="0">
           <?php if (function_exists('array_multisort')) { 
                    echo '<td align="center" class="infoBoxHeading"><a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $cID .'&sort=' . $href_sort1 , 'SSL') . '"><FONT COLOR="white">' . '&nbsp' . HEADING_BASKET_CUSTOMER_ID      . $head_sort1 . '</FONT></a></td>' ;
                    echo '<td align="left"   class="infoBoxHeading"><a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $cID .'&sort=' . $href_sort2 , 'SSL') . '"><FONT COLOR="white">' . HEADING_BASKET_CUSTOMER_NAME              . $head_sort2 . '</FONT></a>'      ;
                    if ($screen=='T') { echo '<br />'; } else { echo '</td><td align="left"   class="infoBoxHeading">'; }
                    echo                                           '<a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $cID .'&sort=' . $href_sort3 , 'SSL') . '"><FONT COLOR="white">' . HEADING_BASKET_CUSTOMER_EMAIL             . $head_sort3 . '</FONT></a></td>' ;
                    echo '<td align="center" class="infoBoxHeading"><a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $cID .'&sort=' . $href_sort4 , 'SSL') . '"><FONT COLOR="white">' . HEADING_BASKET_CUSTOMER_LAST_LOGIN_DATE   . $head_sort4 . '</FONT></a></td>' ;  
                    echo '<td align="center" class="infoBoxHeading"><a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $cID .'&sort=' . $href_sort5 , 'SSL') . '"><FONT COLOR="white">' . HEADING_BASKET_CUSTOMER_BASKET_DATE       . $head_sort5 . '</FONT></a></td>' ;                                    
                    echo '<td align="right"  class="infoBoxHeading"><a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $cID .'&sort=' . $href_sort6 , 'SSL') . '"><FONT COLOR="white">' . HEADING_BASKET_CUSTOMER_BASKET_TOTAL_CART . $head_sort6 . '</FONT></a></td>' ;                                    
                    echo '<td align="center" class="infoBoxHeading"><strong>&nbsp</strong></td>' ;
                    echo '<td align="center" class="infoBoxHeading"><strong>&nbsp</strong></td>' ;
                    echo '<td align="center" class="infoBoxHeading"><strong>&nbsp</strong></td>' ;
                 } else { ?>
                    <td align="center" class="infoBoxHeading"><strong><?php echo '&nbsp' . HEADING_BASKET_CUSTOMER_ID;      ?></strong></td>
                    <td align="Left"   class="infoBoxHeading"><strong><?php echo HEADING_BASKET_CUSTOMER_NAME;              ?></strong></td>
                    <?php if ($screen!=='T') {echo '<td align="Left"   class="infoBoxHeading"><strong>' . HEADING_BASKET_CUSTOMER_EMAIL . '</strong></td>' ; } ?>
                    <td align="Center" class="infoBoxHeading"><strong><?php echo HEADING_BASKET_CUSTOMER_LAST_LOGIN_DATE;   ?></strong></td>
                    <td align="Center" class="infoBoxHeading"><strong><?php echo HEADING_BASKET_CUSTOMER_BASKET_DATE;       ?></strong></td>
                    <td align="Right"  class="infoBoxHeading"><strong><?php echo HEADING_BASKET_CUSTOMER_BASKET_TOTAL_CART; ?></strong></td>
                    <td align="center" class="infoBoxHeading"><strong>     &nbsp                                              </strong></td>
                    <td align="center" class="infoBoxHeading"><strong>     &nbsp                                              </strong></td>
                    <td align="center" class="infoBoxHeading"><strong>     &nbsp                                              </strong></td>
           <?php } ?>
        </tr>
            <?php
               $end=$i+1 ;
               $i=0 ;
               while ($i < $end)
                 {
                 ?>
                   <tr border="0" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" <?php if (isset($cID) && $cID == $basket_customers_id[$i]) { echo ' class="basketRowSelected">' ; $email = $basket_email_address[$i] ; } else { echo ' class="infoBoxContents">' ; }?>
                        <td align="center"><?php echo '&nbsp#' . $basket_customers_id[$i] ; ?> </td>
                        <td align="left"  ><?php echo $basket_name[$i]                       ; 
                                                    if ($screen =='T') {echo '<br />';} else {echo '</td><td>';} 
                                                 echo '<A href="mailto:' . $basket_email_address[$i] . '">' . $basket_email_address[$i] . '</A>' ; ?></td>
                        <td align="center"><?php echo tep_date_short($basket_last_login[$i])      ; ?></td>
                        <td align="center"><?php echo tep_date_short($basket_date_added[$i])      ; ?></td>
                        <td align="right"> <?php echo $currencies->format($basket_total_cart[$i]) ; ?></td>
                        <td align="right"> <?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $basket_customers_id[$i] . '&sort=' . $sort , 'SSL') . '">' . TEXT_BASKET_SEE_BASKET . '</a>' ; ?></td>
                        <td align="center"><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $basket_customers_id[$i] . '&sort=' . $sort . '&action=delete', 'SSL') . '">' . TEXT_BASKET_DELETE . '</a>' ; ?></td>
                        <td align="center"><?php echo '<a href="' . tep_href_link(FILENAME_BASKET, 'cID='. $basket_customers_id[$i] . '&sort=' . $sort . '&action=login' , 'SSL') . '">' . TEXT_BASKET_LOGIN  . '</a>' ; ?></td>
                   </tr>
                 <?php
                   $i++ ;
                 }

                 ?>
      </table>
    </td>

<?php
    // prepare to display the cart in shoping box on colum rigth or footer
   if (isset($cID) && $cID <> 0) {
       $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_email_address, customers_default_address_id from " . TABLE_CUSTOMERS . " where customers_id = '" . tep_db_input($cID) . "'");
       if (tep_db_num_rows($check_customer_query)) {
           $check_customer = tep_db_fetch_array($check_customer_query);
           $customer_id = $check_customer['customers_id'];
           tep_session_register('customer_id');
           // restore cart contents
           $cart->restore_contents();
       }
    }
?>



<!-- body_text_eof //-->
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
<!-- right_navigation //-->
<?php require(DIR_THEME. 'html/column_right.php'); ?>
<!-- right_navigation_eof //-->
    </table></td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_THEME. 'html/footer.php'); ?>
<!-- footer_eof //-->
<?php
  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
  tep_session_unregister('comments');
  $cart->reset();
?>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

?>
