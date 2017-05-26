<div class="checkout_form">
  <?php echo tep_draw_form('checkout', tep_href_link(FILENAME_CHECKOUT, '', $request_type), 'post','id=onePageCheckoutForm') . tep_draw_hidden_field('action', 'process'); ?>   
    <div id="pageContentContainer"  style="display:none;">                               
<!--          <div class="row">-->
            <!--  Your Data  -->
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
            <!--  Your Data End -->

            <div class="col-sm-8">
              <!-- Shipping method -->
              <div class="col-sm-6 shippingMethods">
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

              </div>
              <!-- Shipping method End -->

              <!-- Payment Method -->
              <div class="col-sm-6">
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
              </div>
              <!-- Payment Method End -->

              <!--  Order Summary  -->
              <div class="col-sm-12">
                <?php include(DIR_WS_INCLUDES . 'checkout/checkout_cart.php'); ?>

                <!--  Comments    -->
                <div class="form-group">
                  <?php echo tep_draw_textarea_field('comments', 'soft', '40', '3', $comments, 'class="checkout_inputs form-control" placeholder="'.ENTRY_COMMENT.'"'); ?>
                </div>
                <!--  Comments  end  -->
              </div>
              <!--  Order Summary End -->
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
                <span class="btn btn-default disabled" id="checkoutButton" formUrl="<?php tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type); ?>" style="cursor: pointer"><?php echo IMAGE_BUTTON_CHECKOUT;?></span>
                <input type="hidden" name="formUrl" id="formUrl" value=""> <br>
                <input type="checkbox" id="checkBtn" /> Acepto los términos de servicio.
                <a href="#" data-toggle="modal" data-target="#rules-modal">(leer)</a>

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
<!--    </div>-->

  </form> 
</div><!-- /checkout_form -->

</div>

<!-- Login Modal is Already Registred -->
<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="loginmodal-container">
      <h1>Login to Your Account</h1><br>
      <form name="login" action="https://spainelectro.com/login.php?action=process" method="post">
        <input type="email" name="email_address" placeholder="E-mail">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="submit" name="login" class="login loginmodal-submit" value="Login">
        <input type="checkbox" name="remember_me" value="on"> Recordarme
      </form>

      <div class="login-help">
        <a href="/create_account.php">Register</a> - <a href="/password_forgotten.php">Forgot Password</a>
      </div>
    </div>
  </div>
</div>
<!-- Login Modal is Already Registred END -->

<!-- Rules Modal -->
<div class="modal fade" id="rules-modal" tabindex="-1" role="dialog" aria-labelledby="RulesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="RulesModalLabel">Condiciones Generales</h4>
      </div>
      <div class="modal-body">
        <?php include_once('rules.php');?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Rules Modal END -->
