<?php
echo '<h3><span class="glyphicon glyphicon-user checkout-ico" aria-hidden="true"></span>' . TABLE_HEADING_BILLING_ADDRESS . '</h3>';
if (!tep_session_is_registered('customer_id')) echo '<a href="#" data-toggle="modal" data-target="#login-modal" class="check-isregistred">Are you already registred?</a> ';
if (tep_session_is_registered('customer_id')) echo '<a href="address_book.php" class="btn btn-default abook">Dirección</a>';
?>
<div id="billingAddress"><?php
    if (tep_session_is_registered('customer_id') && ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False') {
        echo tep_address_label($customer_id, $billto, true, ' ', '<br>');

        $address_query = tep_db_query("select entry_firstname as firstname, entry_NIF, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
        $address = tep_db_fetch_array($address_query);

        echo '<input type="hidden" name="billing_firstname" value="' . $address['firstname'] . '" />';
        echo '<input type="hidden" name="billing_lastname" value="' . $address['lastname'] . '" />';
//   echo '<input type="hidden" name="billing_email_address" value="'.$address['firstname'].'" />';
//   echo '<input type="hidden" name="billing_telephone" value="'.$address['firstname'].'" />';
        echo '<input type="hidden" name="billing_country" value="' . $address['country_id'] . '" />';
        echo '<input type="hidden" name="billing_nif" value="' . $address['entry_NIF'] . '" />';
        echo '<input type="hidden" name="billing_city" value="' . $address['city'] . '" />';  
        echo '<input type="hidden" name="billing_zone_id" value="' . $address['zone_id'] . '" />';        
        echo '<input type="hidden" name="billing_street_address" value="' . $address['street_address'] . '" />';

    } else {
        if (tep_session_is_registered('onepage')) {
            $billingAddress = $onepage['billing'];
            $customerAddress = $onepage['customer'];
        }
            echo '<div class="check-bl">';
              echo '<div class="form-group check-name">'.
                  tep_draw_input_field('billing_firstname', (isset($billingAddress) ? $billingAddress['firstname'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_FIRST_NAME . '"')
                  .'</div>';
              echo '<div class="form-group check-name2">'.
                  tep_draw_input_field('billing_lastname', (isset($billingAddress) ? $billingAddress['lastname'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_LAST_NAME . '"')
                  .'</div>';
            echo '</div>';
        if(!tep_session_is_registered('customer_id')) {
            echo '<div class="form-group check-email">'.tep_draw_input_field('billing_email_address', (isset($customerAddress) ? $customerAddress['email_address'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_EMAIL_ADDRESS . '"').'</div>';
            echo '<div class="form-group" id="email_error" style="color:#DD703E;"></div>';
        }
        ?>


        <div class="checkout-panel">
            <ul class="nav nav-tabs">
                <li><a href="#shipping" data-toggle="tab">Billing Address</a></li>
                <li><a href="#billing" data-toggle="tab">Shipping Address</a></li>
            </ul>
        </div>
        <div class="checkout-address">
            <div class="tab-content">
                <div class="tab-pane active" id="shipping">
                    <?php
                    echo '<div class="form-group">'.tep_draw_input_field('billing_nif', (isset($billingAddress) ? $billingAddress['nif'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_NIF . '"').'</div>';

                    echo '<div class="form-group">'.tep_draw_input_field('billing_company', (isset($billingAddress) ? $billingAddress['company'] : ''), 'class="checkout_inputs form-control" placeholder="' . ENTRY_COMPANY . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('billing_cif', (isset($billingAddress) ? $billingAddress['cif'] : ''), 'class="checkout_inputs form-control" placeholder="' . ENTRY_CIF . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('billing_street_address', (isset($billingAddress) ? $billingAddress['street_address'] : ''), 'class="required checkout_inputs form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('billing_zipcode', (isset($billingAddress) ? $billingAddress['postcode'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_POST_CODE . '"').'</div>';
                    echo '<div class="form-group">'.tep_get_country_list('billing_country', (isset($billingAddress) && tep_not_null($billingAddress['country_id']) ? $billingAddress['country_id'] : STORE_COUNTRY), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_COUNTRY . '"').'</div>';
                    echo '<div class="form-group fixed-height">'.ajax_get_zones_html(DEFAULT_COUNTRY,(isset($billingAddress) ? $billingAddress['zone_id'] : ''),false,'billing_zone_id').'</div>';   
                                    
                    echo '<div class="form-group">'.tep_draw_input_field('billing_city', (isset($billingAddress) ? $billingAddress['city'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_CITY . '"').'</div>';
                    //echo '<div class="form-group">'.tep_draw_input_field('billing_suburb', (isset($billingAddress) ? $billingAddress['suburb'] : ''), 'class="required checkout_inputs form-control" placeholder="' . ENTRY_SUBURB . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('billing_telephone', (isset($customerAddress) ? $customerAddress['telephone'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_TELEPHONE . '"').'</div>';


                    if(!tep_session_is_registered('customer_id')) {
                        echo '<div class="form-group" style="display:none">
                  '.tep_draw_password_field('password', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="checkout_inputs required form-control" maxlength="40" ' : 'maxlength="40" ') . 'id="bg_register_pass"').'
                  '.tep_draw_password_field('confirmation', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="checkout_inputs required form-control" ' : '') . 'maxlength="40" id="bg_register_pass" style="width:140px;"').'
                  <div id="pstrength_password"></div>
                </div>';

                        echo '<div class="newsletter">
                  '.tep_draw_checkbox_field('billing_newsletter', '1', (isset($customerAddress) && $customerAddress['newsletter'] == '1' ? true : true), 'id="billing_newsletter"').'
                  <label for="billing_newsletter">'.ENTRY_NEWSLETTER.'</label>
                </div>';
                    }

                    } ?>
                </div>
                <div class="tab-pane" id="billing">
                    <input type="checkbox" name="diffShipping" id="diffShipping" value="1">
                    <label for="diffShipping"><?php echo TEXT_DIFFERENT_SHIPPING; ?></label>
                    <?php
                    echo '<div class="form-group">'.tep_draw_input_field('shipping_nif', (isset($billingAddress) ? $billingAddress['nif'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_NIF . '"').'</div>';

                    echo '<div class="form-group">'.tep_draw_input_field('shipping_company', (isset($billingAddress) ? $billingAddress['company'] : ''), 'class="checkout_inputs form-control" placeholder="' . ENTRY_COMPANY . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('shipping_cif', (isset($billingAddress) ? $billingAddress['cif'] : ''), 'class="checkout_inputs form-control" placeholder="' . ENTRY_CIF . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('shipping_street_address', (isset($billingAddress) ? $billingAddress['street_address'] : ''), 'class="required checkout_inputs form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('shipping_zipcode', (isset($billingAddress) ? $billingAddress['postcode'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_POST_CODE . '"').'</div>';
                    echo '<div class="form-group">'.tep_get_country_list('shipping_country', (isset($billingAddress) && tep_not_null($billingAddress['country_id']) ? $billingAddress['country_id'] : STORE_COUNTRY), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_COUNTRY . '"').'</div>';
                    echo '<div class="form-group fixed-height">'.ajax_get_zones_html(DEFAULT_COUNTRY,(isset($billingAddress) ? $billingAddress['zone_id'] : ''),false,'shipping_zone_id').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('shipping_city', (isset($billingAddress) ? $billingAddress['city'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_CITY . '"').'</div>';
                    //echo '<div class="form-group">'.tep_draw_input_field('billing_suburb', (isset($billingAddress) ? $billingAddress['suburb'] : ''), 'class="required checkout_inputs form-control" placeholder="' . ENTRY_SUBURB . '"').'</div>';
                    echo '<div class="form-group">'.tep_draw_input_field('shipping_telephone', (isset($customerAddress) ? $customerAddress['telephone'] : ''), 'class="checkout_inputs required form-control" placeholder="' . ENTRY_TELEPHONE . '"').'</div>';


                    if(!tep_session_is_registered('customer_id')) {
                        echo '<div class="form-group" style="display:none">
                  '.tep_draw_password_field('password', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="checkout_inputs required form-control" maxlength="40" ' : 'maxlength="40" ') . 'id="bg_register_pass"').'
                  '.tep_draw_password_field('confirmation', '', 'autocomplete="off" ' . (ONEPAGE_ACCOUNT_CREATE == 'required' ? 'class="checkout_inputs required form-control" ' : '') . 'maxlength="40" id="bg_register_pass" style="width:140px;"').'
                  <div id="pstrength_password"></div>
                </div>';

                    } ?>
                </div>
            </div>
        </div>
    
</div>