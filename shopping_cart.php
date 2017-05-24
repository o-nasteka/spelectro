<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  if ($cart->count_contents() > 0) {
    include(DIR_WS_CLASSES . 'payment.php');
    $payment_modules = new payment;
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));
?>

<?php require(DIR_THEME. 'html/header.php'); ?>


<!-- BOE: ERSD.net AJAX Shopping Cart -->
		<script language="JavaScript" type="text/javascript">
			var sendReq = getXmlHttpRequestObject();
			var receiveReq = getXmlHttpRequestObject();
			var receiveReqInfoBox = getXmlHttpRequestObject();
			var file = 'getCart.php';
			var loc = 'span_cart';
			var tmp = '&nbsp;<img src=theme/<?php echo THEME; ?>/images/general/loading_sc.gif alt=loading>&nbsp;<?php echo MESSAGE_WAIT; ?><br/>';
			//Function for initializating the page.
			function startCart(key,file,sid,loc,tmp) {
				//Start Showing Cart.
				getCartText('',file,sid,loc,tmp);
			}		
			//Gets the browser specific XmlHttpRequest Object
			function getXmlHttpRequestObject() {
				if (window.XMLHttpRequest) {
					<?php define('SHOW_NON_AJAX_CART',false); ?>
					return new XMLHttpRequest();
				} else if(window.ActiveXObject) {
				<?php define('SHOW_NON_AJAX_CART',false); ?>
					return new ActiveXObject("Microsoft.XMLHTTP");
				} else {
					document.getElementById('p_status').innerHTML = 'Status: Cound not create XmlHttpRequest Object.  Consider upgrading your browser.';
					<?php define('SHOW_NON_AJAX_CART',true); ?>
				}
			}
			
			//Gets the current cart
			function getCartText(key,file,sid,loc,tmp) {
				var url=file+"?"+sid+"&"+key;
				if (sid && key) {
					  url=file+"?"+sid+"&"+key;
				} else {
					if (sid) {
				    	url=file+"?"+sid;
				  	} else {
				    	if (key) {
				      		url=file+"?"+key;
				    	} else {
				      		url=file;
				    	}
				  	}
				}
				
				if (tmp) {getObject(loc).innerHTML = tmp;}
				
				if (receiveReq.readyState == 4 || receiveReq.readyState == 0) {
					receiveReq.open("GET", url+'&Cart=1', true);
					receiveReq.onreadystatechange = handleReceiveCart; 
					receiveReq.send(null);
				}			
			}
			
			//Gets the current cart info box
			function getCartInfoBoxText(key,file,sid,loc,tmp) {
				var url=file+"?"+sid+"&"+key;
				if (sid && key) {
					  url=file+"?"+sid+"&"+key;
				} else {
					if (sid) {
				    	url=file+"?"+sid;
				  	} else {
				    	if (key) {
				      		url=file+"?"+key;
				    	} else {
				      		url=file;
				    	}
				  	}
				}
				
				if (tmp) {getObject(loc).innerHTML = tmp;}
				
				if (receiveReqInfoBox.readyState == 4 || receiveReqInfoBox.readyState == 0) {
					receiveReqInfoBox.open("GET", url+'&Cart=1', true);
					receiveReqInfoBox.onreadystatechange = handleReceiveCartInfoBox; 
					receiveReqInfoBox.send(null);
				}			
			}
						
			//send change of stock to Cart.
			function sendCartChangeQty(key,file,sid,loc,tmp,iElementId,product_id,qty) {

    			if (typeof iElementId == "string" && iElementId.length > 0) {
        			var element = document.getElementById(iElementId);
        			if (element) {
            			//alert("name=" + element.name + " - id=" + element.id);
						//return;
        			} else {
            			document.getElementById('p_status').innerHTML = 'Status: Could not find the requested element.';
						return;
        			}
    			}
									
				 //Verify entered quantity is number
				if (isNaN(element.value)) {
				document.getElementById('p_status').innerHTML = 'Debes de introducir un nï¿½mero con la Cantidad que deseas.';
				getCartText(key,file,sid,loc,tmp);
				return;
				}
				else {
				document.getElementById('p_status').innerHTML = '';
				}					
				// check if Quantity drops below 0
				// check if Quantity drops below 0
				if (Number(element.value) + Number(qty) <= 0) {
				//Call function to delete item
				sendCartRemoveItem(key,file,sid,loc,tmp,iElementId,product_id);
				return;
				}													
				var url=file+"?"+sid+"&"+key;
				if (sid && key) {
					  url=file+"?"+sid+"&"+key;
				} else {
					if (sid) {
				    	url=file+"?"+sid;
				  	} else {
				    	if (key) {
				      		url=file+"?"+key;
				    	} else {
				      		url=file;
				    	}
				  	}
				}
				
				if (tmp) {getObject(loc).innerHTML = tmp;}			

				if (sendReq.readyState == 4 || sendReq.readyState == 0) {
					sendReq.open("POST", url+'&Cart=1', true);
					sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
					sendReq.onreadystatechange = handleSendCart; 
					
					var param = 'quantity=' + (Number(element.value) + Number(qty));
					param += '&products_id=' + product_id;
					sendReq.send(param);
				}							
			}

			//send change of stock to Cart.
			function sendCartRemoveItem(key,file,sid,loc,tmp,iElementId,product_id) {

    			if (typeof iElementId == "string" && iElementId.length > 0) {
        			var element = document.getElementById(iElementId);
        			if (element) {
            			//alert("name=" + element.name + " - id=" + element.id);
						//return;
        			} else {
            			document.getElementById('p_status').innerHTML = 'Status: Could not find the requested element.';
						return;
        			}
    			}
					
				// check user wants to remove item
				//if (element.value != '') {
				// Are you sure you want to remove this item
				var fRet;
				fRet = confirm('Â¿EstÃ¡s seguro de querer eliminar este producto de la cesta de la compra?');
				//alert(fRet);
				if (fRet == false) {
				getCartText(key,file,sid,loc,tmp);
				return;
				}													
				var url=file+"?"+sid+"&"+key;
				if (sid && key) {
					  url=file+"?"+sid+"&"+key;
				} else {
					if (sid) {
				    	url=file+"?"+sid;
				  	} else {
				    	if (key) {
				      		url=file+"?"+key;
				    	} else {
				      		url=file;
				    	}
				  	}
				}
				
				if (tmp) {getObject(loc).innerHTML = tmp;}			

				if (sendReq.readyState == 4 || sendReq.readyState == 0) {
					sendReq.open("POST", url+'&Cart=1', true);
					sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
					sendReq.onreadystatechange = handleSendCartRemoveItem; 
					
					var param = '&products_id=' + product_id;
					param += '&cart_delete=Yes'; 
					sendReq.send(param);
				}							
			}
				
			//When our stock change has been sent, update our page.
			function handleSendCart() {
			if (sendReq.readyState == 4 && sendReq.status == 200){
			getCartText('','getCart.php','<?php echo tep_session_name().'='.tep_session_id(); ?>','span_cart',tmp);
			getCartInfoBoxText('','getCartBox.php','<?php echo tep_session_name().'='.tep_session_id(); ?>','span_cart_box',tmp);
			}
			}			
			//Function for handling the return of Cart text
			function handleReceiveCart() {
				//Check to see if the XmlHttpRequests state is finished.
				if (receiveReq.readyState == 4) {
					//Set the contents of our span element to the result of the asyncronous call.
					document.getElementById('span_cart').innerHTML = receiveReq.responseText;
				}
			}

			//Function for handling the return of Cart Info Box text
			function handleReceiveCartInfoBox() {
				//Check to see if the XmlHttpRequests state is finished.
				if (receiveReqInfoBox.readyState == 4) {
					//Set the contents of our span element to the result of the asyncronous call.
					document.getElementById('span_cart_box').innerHTML = receiveReqInfoBox.responseText;
				}
			}
			
			//When our stock change has been sent, update our page.
			function handleSendCartRemoveItem() {
			if (sendReq.readyState == 4 && sendReq.status == 200){
			getCartText('','getCart.php','<?php echo tep_session_name().'='.tep_session_id(); ?>',loc,tmp);
			getCartInfoBoxText('','getCartBox.php','<?php echo tep_session_name().'='.tep_session_id(); ?>','span_cart_box',tmp);
			}
			}			
			function getObject(name) { 
			   var ns4 = (document.layers) ? true : false; 
			   var w3c = (document.getElementById) ? true : false; 
			   var ie4 = (document.all) ? true : false; 

			   if (ns4) return eval('document.' + name); 
			   if (w3c) return document.getElementById(name); 
			   if (ie4) return eval('document.all.' + name); 
			   return false; 
			}			
		</script>
<!-- EOE: ERSD.net AJAX Shopping Cart -->
<script type="text/javascript"> 
on = "<?php echo TEXT_SHOPPINGCART_UPDATE; ?>"; 
off = " "; 
function advisecustomer(advise_status) { 
document.cart_quantity.advise.value = advise_status; 
} 
window.onload = function(){ startCart('','getCart.php','<?php echo tep_session_name().'='.tep_session_id(); ?>'); };
</script>


<?php require(DIR_THEME. 'html/column_left.php'); ?>

<?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

            
<?php
  if ($cart->count_contents() > 0) {
?>
<table class="table table-responsive table-condensed" border="0" style="width: 100%;">
      <tr>
        <td style="border:0px;">
<!-- BOE: ERSD.net AJAX Shopping Cart -->	
		<p id="p_status"></p>
		<!-- used to display the results of the asyncronous request -->
		<span id="span_cart" class="span_cart"></span>
<!-- EOE: ERSD.net AJAX Shopping Cart -->
<?php
if (SHOW_NON_AJAX_CART == true) {
    $info_box_contents = array();
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_REMOVE);

    $info_box_contents[0][] = array('params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_PRODUCTS);

    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_QUANTITY);

    $info_box_contents[0][] = array('align' => 'right',
                                    'params' => 'class="productListing-heading"',
                                    'text' => TABLE_HEADING_TOTAL);

    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, popt.products_options_track_stock, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
		  $products[$i][$option]['track_stock'] = $attributes_values['products_options_track_stock'];
        }
      }
    }

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (($i/2) == floor($i/2)) {
        $info_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $info_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($info_box_contents) - 1;

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data" valign="top"',
                                             'text' => tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']));

      $products_name = '<table class="table table-default">' .
                       '  <tr>' .
                       '    <td class="productListing-data" align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
                       '    <td class="productListing-data" valign="top"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><strong>' . $products[$i]['name'] . '</strong></a>';

      if (STOCK_CHECK == 'true') {
          if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
          $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity'], $products[$i]['attributes']); 
        }else{
          $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        }
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
          $products_name .= '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_name .= '    </td>' .
                        '  </tr>' .
                        '</table>';

      $info_box_contents[$cur_row][] = array('params' => 'class="productListing-data"',
                                             'text' => $products_name);

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="productListing-data" valign="top"',
                                             'text' => tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']));

      $info_box_contents[$cur_row][] = array('align' => 'right',
                                             'params' => 'class="productListing-data" valign="top"',
                                             'text' => '<strong>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</strong>');
    }

    new productListingBox($info_box_contents);
	echo '
        </td>
      </tr>

      <tr>
  	  	<td><input type="text" name="advise" size="30" maxlength="100" style="width:340px; font-size:10px; background-color:white;color:red;font-weight:bold;border:0px;" value="" /></td>
        <td align="right" class="main"><strong>' . SUB_TITLE_SUB_TOTAL . $currencies->format($cart->show_total()) . '</strong></td>
      </tr>';
	
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>

    <div class="alert alert-warning"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></div>

<?php
      } else {
?>

    <div class="alert alert-danger"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></div>

<?php
      }
    }
    }
    if ($messageStack->size('cart_notice') > 0) {
?>
        <?php echo $messageStack->output('cart_notice'); ?>
<?php
      }
// EOF QPBPP for SPPC
?>
<?php
	$back = sizeof($navigation->path)-2;
	if (isset($navigation->path[$back])) {
		if ($navigation->path[$back]['get']['products_id'] > 0) {
		$cat_query = tep_db_query("SELECT categories_id as id FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE products_id = " . (int)$navigation->path[$back]['get']['products_id'] . " LIMIT 1");
		
			if (tep_db_num_rows($cat_query)) {
				$cat = tep_db_fetch_array($cat_query);
			
				$navigation->path[$back]['page'] = 'index.php';
				$navigation->path[$back]['get'] = array('cPath' => $cat['id']);
			}
		}
	$boton_continuar= tep_draw_button(IMAGE_BUTTON_CONTINUE_SHOPPING, 'icon-arrow-right2', tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']), 'btn btn-default pull-right');

	}
?>
      <tr>
        <td style="border:0px;"><table border="0" style="border:0px; width:100%">
              <tr style="border:0px;">
                <td style="border:0px;"><?php if (SHOW_NON_AJAX_CART == true) { echo tep_image_submit('button_update_cart.gif', IMAGE_BUTTON_UPDATE_CART, 'onclick="advisecustomer(off)"');}; ?></td>
                <td align="right" class="main" style="border:0px;"><?php echo $boton_continuar; ?> 
                <?php echo tep_draw_button(IMAGE_BUTTON_CHECKOUT, 'icon-arrow-right2', tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'), 'btn btn-default pull-right'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();

    if (!empty($initialize_checkout_methods)) {
?>
  <div class="clearfix"></div>
  <p class="text-right"><?php echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?></p>

<?php
      reset($initialize_checkout_methods);
      while (list(, $value) = each($initialize_checkout_methods)) {
?>
      
      <tr>
  <p class="text-right"><?php echo $value; ?></p>
  
      
<?php
      }
    }
  } else {
?>
      <tr>
        <td align="center" class="main"><div class="mensaje"><?php echo TEXT_CART_EMPTY; ?></div></td>
      </tr>
      
      <tr>
      <td>
    <?php echo TEXT_CART_EMPTY; ?>
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', tep_href_link(FILENAME_DEFAULT), 'btn btn-default pull-right'); ?>
</td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
<tr>
        <td></form></td>
      </tr>    
    </table>
<?php require(DIR_THEME. 'html/column_right.php');
  require(DIR_THEME. 'html/footer.php');; 
  require(DIR_WS_INCLUDES . 'application_bottom.php'); 
?>