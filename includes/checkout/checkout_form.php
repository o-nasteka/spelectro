<div class="checkout_form">
  <?php echo tep_draw_form('checkout', tep_href_link(FILENAME_CHECKOUT, '', $request_type), 'post','id=onePageCheckoutForm') . tep_draw_hidden_field('action', 'process'); ?>   
    <div id="pageContentContainer"  style="display:none;">                               
          <div class="row">
						<div class="col-sm-4">
							<?php include(DIR_WS_INCLUDES . 'checkout/checkout_cart.php'); ?>
            </div>
						<div class="col-sm-4">  
              <?php  
              
                // billing address
                
                ob_start();
                include(DIR_WS_INCLUDES . 'checkout/billing_address.php'); 
                $billingAddress_string = ob_get_contents();
                ob_end_clean();
    
                echo $billingAddress_string;    

								// shipping address
								
                ob_start();
                include(DIR_WS_INCLUDES . 'checkout/shipping_address.php'); 
                $shippingAddress = ob_get_contents();
                ob_end_clean();
    
                echo $shippingAddress;    
              ?> 
            </div> 
            <div class="col-sm-4 shippingMethods">  
				      <?php
				        if ($onepage['shippingEnabled'] === true and tep_count_shipping_modules() > 0) {
				      ?>
				          <div class="form-group">
				            <!-- SHIPPING METHOD -->  
					          <?php                               
					            if (isset($_SESSION['customer_id'])){
					              ob_start();
					              include(DIR_WS_INCLUDES . 'checkout/shipping_method.php');
					              $shippingMethod = ob_get_contents();
					              ob_end_clean();
					            }
					            $shippingMethod = '<div id="noShippingAddress" class="main noAddress" style="font-size:12px;' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '"></div><div id="shippingMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $shippingMethod . '</div>';
					            echo $shippingMethod;
					          ?>
				          </div>
				    <?php  } ?>
					        <div class="form-group">
					          <!-- PAYMENT METHOD -->
					          <?php
					            if (isset($_SESSION['customer_id'])){
					              ob_start();
					              include(DIR_WS_INCLUDES . 'checkout/payment_method.php');
					              $paymentMethod = ob_get_contents();
					              ob_end_clean();
					            }
					            $paymentMethod = '<div id="noPaymentAddress" class="main noAddress" style="font-size:12px;' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '"></div><div id="paymentMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $paymentMethod . '</div>';
					            echo $paymentMethod;
					          ?>    
					        </div> 
									 
					        <div class="form-group">
					        	<?php echo tep_draw_textarea_field('comments', 'soft', '40', '3', $comments, 'class="checkout_inputs form-control" placeholder="'.ENTRY_COMMENT.'"'); ?>
					        </div>  
            </div> 
          </div>          

        <table border="0" width="100%" cellspacing="1" cellpadding="2">
          <tr id="checkoutYesScript" style="display:none;">
            <td id="checkoutMessage"></td>
            <td align="center">
              <?php if(ONEPAGE_CHECKOUT_LOADER_POPUP == 'False'){ ?>
                <div id="ajaxMessages" style="display:none;"></div>
              <?php } 
              
              if(MIN_ORDER<$cart->show_total()){?>
              <div id="checkoutButtonContainer">
                <span class="btn btn-default" id="checkoutButton" formUrl="<?php tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type); ?>" style="cursor: pointer"><?php echo IMAGE_BUTTON_CHECKOUT;?></span>
                <input type="hidden" name="formUrl" id="formUrl" value="">
              </div>


              <?php 
              }
							// raid ------ минимальный заказ!!!---------------- //	
                if(MIN_ORDER>$cart->show_total()*$currencies->currencies[$currency]['value']) echo '<input type="hidden" id="minsum" value="'.MIN_ORDER.'" />';
	            // raid ------ минимальный заказ!!!---------------- //	
							?>
              <div id="checkoutButtonContainer_minimal" style="display:none;color:#D40000;">
                <div class="right" style="opacity:0.3;"></div>
                <div class="right" style="padding:12px 20px 0 0;"><?php echo TEXT_MIN_SUM; ?>: <b>
                <span id="minimal_sum"></span></b> <?php echo ($currencies->currencies[$currency]['symbol_left']?$currencies->currencies[$currency]['symbol_left']:$currencies->currencies[$currency]['symbol_right']); ?></div>
                <div class="clear"></div>
              </div>

              <div id="paymentHiddenFields" style="display:none;"></div>
            </td>
          </tr>
          <tr id="checkoutNoScript">
            <td>
              <td>
                <?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>to update/view your order.'; ?>
              </td>
              <td align="right">
                <button class="btn" type="submit"><?php echo IMAGE_BUTTON_UPDATE; ?></button>
                <?php //echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?>
              </td>
        
            </td>
          </tr>
        </table>
    </div>

  </form> 
</div><!-- /checkout_form -->
