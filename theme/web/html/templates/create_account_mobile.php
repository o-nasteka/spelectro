<div id="container_white">
<br/>
<h3 class="heading_title_grey">CREAR CUENTA</h3>
<form name="create_account_mobile" id="form_account_mobile" action="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>" method="post" onSubmit="return check_form(create_account_mobile);">
<h4 class="h4mobile">Personal</h4>



<input id="m_fname" name="firstname" value="<?php echo  $_SESSION['customer_first_name']; ?>" type="text" placeholder="<?php echo substr(ENTRY_FIRST_NAME, 0, -1); ?>" />

<input id="m_fname" name="lastname" type="text" placeholder="<?php echo substr(ENTRY_LAST_NAME, 0, -1); ?>" />

<input id="m_fname" name="nif" type="text" placeholder="<?php echo substr(ENTRY_NIF, 0, -1); ?>" />


<input id="m_fname" name="email_address" type="text" placeholder="<?php echo substr(ENTRY_EMAIL_ADDRESS, 0, -1); ?>" />

<input id="m_fname" name="email_address_re" type="text" placeholder="<?php echo substr(ENTRY_REPEAT_EMAIL, 0, -1); ?>" />


<br/>
<br/>
<h4 class="h4mobile"><?php echo CATEGORY_ADDRESS; ?></h4>



<input id="m_fname" name="street_address" type="text" placeholder="<?php echo substr(ENTRY_STREET_ADDRESS, 0, -1); ?>" />

<input id="m_fname" name="postcode" type="text" placeholder="<?php echo substr(ENTRY_POST_CODE, 0, -1); ?>" />



<br/>
<br/>
<h5><strong><?php echo substr(ENTRY_COUNTRY, 0, -1); ?></strong></h5>


<?php // +Country-State Selector ?>
                      <?php echo tep_get_country_list('country',$country,'onChange="getStates(this.value, \'states2\');"'); ?></p>
                      <?php // -Country-State Selector ?>

<h5><strong><?php echo substr(ENTRY_STATE, 0, -1); ?></strong></h5>

<span id="states2">
<?php 
echo ajax_get_zones_html($country,'',false); 
?>
</span>



<input id="m_fname" name="city" type="text" placeholder="<?php echo substr(ENTRY_CITY, 0, -1); ?>" />


<br/>
<br/>
<h5><strong><?php echo CATEGORY_CONTACT; ?></strong></h5>
<input id="m_fname" name="ftelephone" type="text" placeholder="<?php echo substr(ENTRY_TELEPHONE_NUMBER, 0, -1); ?>" />






<br/>
<br/>
<h5><strong><?php echo CATEGORY_PASSWORD; ?></strong></h5>
<input id="m_fname" name="password" type="text" placeholder="<?php echo substr(ENTRY_PASSWORD, 0, -1); ?>" />
<input id="m_fname" name="confirmation" type="text" placeholder="<?php echo substr(ENTRY_PASSWORD_CONFIRMATION, 0, -1); ?>" />

<br/>
<br/>
<input type="submit" value="CONTINUAR" id="btn_c_acc_movile" />





















</form>




























</div>