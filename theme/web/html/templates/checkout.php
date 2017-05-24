<link rel="stylesheet" href="<?php echo DIR_THEME.'css/checkout.css'; ?>">

<div class="checkout">
    <?php 
    // Если юзер НЕ авторизированый
    if(!isset($_SESSION['customer_id'])) { ?>
                      
          <div id="checkout_new-user" class="tab-pane fade in active">
            <?php include(DIR_WS_INCLUDES . 'checkout/checkout_form.php'); ?>
          </div>
          <div id="checkout_authorization" class="tab-pane fade">
            <div class="row">            
              <div class="checkout_userlogin_form col-md-6 col-sm-6">
                <?php echo tep_draw_form('login', tep_href_link(FILENAME_LOGIN, 'action=process&from='.$_SERVER['REQUEST_URI'], 'SSL')); ?>
                  <div class="form-group">
                    <?php echo tep_draw_input_field('email_address','',' class="form-control" placeholder="'.ENTRY_EMAIL_ADDRESS.'"','text','',false); ?>
                  </div>

                  <div class="form-group">
                    <?php echo tep_draw_password_field('password','',' class="form-control" placeholder="'.ENTRY_PASSWORD.'"','password','',false); ?>
                  </div>

                  <div class="form-group"><button class="btn btn-success" type="submit"><?php echo IMAGE_LOGIN;?></button>&nbsp;&nbsp;&nbsp;
                  <?php echo '<a  href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?>
                  </div>
                </form>
              </div>
            </div>
          </div>

    <?php 
			} else { // Если юзер АВТОРИЗИРОВАНЫЙ
      	include(DIR_WS_INCLUDES . 'checkout/checkout_form.php');
    	} 
		?>
</div>

