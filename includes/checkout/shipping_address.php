<!--<input type="checkbox" name="diffShipping" id="diffShipping" value="1">
<label for="diffShipping"><?php echo TEXT_DIFFERENT_SHIPPING; ?></label> -->

<div id="shippingAddress">
<?php
	echo '<h3>'.TABLE_HEADING_SHIPPING_ADDRESS.'</h3>';

 if (tep_session_is_registered('customer_id') && ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False'){
	 if((int)$sendto<1)	 	$sendto = $billto;
	 echo tep_address_label($customer_id, $sendto, true, ' ', '<br>');
 } else {
	 if (tep_session_is_registered('onepage')){
		 $shippingAddress = $onepage['delivery'];
   }
?>
<table class="checkout_form_info" border="0" cellspacing="0" cellpadding="0" >
  <tr>
  	<td>
    	<div class="form-group clearfix">
    	<?php echo tep_draw_input_field('shipping_firstname', (isset($shippingAddress) ? $shippingAddress['firstname'] : ''), 'class="checkout_inputs required form-control" placeholder="'.ENTRY_FIRST_NAME.'"'); ?>
    	</div>

        <?php if(ACCOUNT_LAST_NAME == 'true') {?>

      <div class="form-group clearfix">
      <?php echo tep_draw_input_field('shipping_lastname', (isset($shippingAddress) ? $shippingAddress['lastname'] : ''), 'class="checkout_inputs required form-control" placeholder="'.ENTRY_LAST_NAME.'"'); ?>
      </div>
        <?php }?>
    </td>
  </tr>
<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
  <tr>
	  <td>
      <div class="form-group clearfix">
      <?php echo tep_draw_input_field('shipping_company', (isset($shippingAddress) ? $shippingAddress['company'] : ''), 'class="checkout_inputs form-control" placeholder="'.ENTRY_COMPANY.'"'); ?>
      </div>
	  </td>
  </tr>
<?php
  }
  if (ACCOUNT_COUNTRY == 'true') {
?>
  <tr>
	  <td>
  	  <div class="form-group clearfix">
  	  <?php echo tep_get_country_list('shipping_country', (isset($shippingAddress) && tep_not_null($shippingAddress['country_id']) ? $shippingAddress['country_id'] : STORE_COUNTRY), 'class="checkout_inputs required form-control" placeholder="'.ENTRY_COUNTRY.'"'); ?>
  	  </div>
	  </td>
  </tr>
<?php
  }
if (ACCOUNT_STREET_ADDRESS == 'true') {
    ?>
    <tr>
        <td>
            <div class="form-group">
                <?php echo tep_draw_input_field('shipping_street_address', (isset($shippingAddress) ? $shippingAddress['street_address'] : ''), 'class="required checkout_inputs form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"'); ?>
            </div>
        </td>
    </tr>
    <?php
}
    if (ACCOUNT_CITY == 'true') {
    ?>
  <tr>
	  <td>
  	  <div class="form-group clearfix">
  	  <?php echo tep_draw_input_field('shipping_city', (isset($shippingAddress) ? $shippingAddress['city'] : ''), 'class="checkout_inputs required form-control" placeholder="'.ENTRY_CITY.'"'); ?>
  	  </div>
	  </td>
  </tr>
<?php }
if(ACCOUNT_SUBURB == 'true') { ?>
    <tr>
        <td>
            <div class="form-group">
                <?php echo tep_draw_input_field('shipping_suburb', (isset($shippingAddress) ? $shippingAddress['suburb'] : ''), 'class="required checkout_inputs form-control" placeholder="' . ENTRY_SUBURB . '"'); ?>
                <?php //echo tep_draw_input_field('billing_suburb', (isset($billingAddress) ? $billingAddress['suburb'] : ''), 'class="required checkout_inputs form-control" placeholder="'.ENTRY_SUBURB.'"'); ?>
            </div>
        </td>
    </tr>
<?php
}
//  if(ONEPAGE_ZIP_BELOW == 'True'){
if(ACCOUNT_POSTCODE == 'True'){
?>
  <tr>
	  <td>
      <div class="form-group clearfix">
      <?php echo tep_draw_input_field('shipping_zipcode', (isset($shippingAddress) ? $shippingAddress['postcode'] : ''), 'class="checkout_inputs required form-control" placeholder="'.ENTRY_POST_CODE.'"'); ?>
      </div>
	  </td>
  </tr>
<?php                                               
  }
?>


</table>
<?php 
}
?></div>