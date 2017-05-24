<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  if (!isset($process)) $process = false;
?>

  <div>
    <span class="pull-right"><?php echo FORM_REQUIRED_INFORMATION; ?></span>
    <h2><?php echo NEW_ADDRESS_TITLE; ?></h2>
  </div>

  <div class="form-box">

<?php
  if (ACCOUNT_GENDER == 'true') {
    $male = $female = false;
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
      $female = !$male;
    } elseif (isset($entry['entry_gender'])) {
      $male = ($entry['entry_gender'] == 'm') ? true : false;
      $female = !$male;
    }
?>

      <div class="form-group">
        <div class="required">
          <label class="radio-inline"><div class="choice"><span><?php echo tep_draw_radio_field('gender', 'm', $male) . '</span></div>' . MALE . '</label><label class="radio-inline"><div class="choice"><span>' . tep_draw_radio_field('gender', 'f', $female) . '</span></div>' . FEMALE .'</label>'; ?>
        </div>
      </div>	

<?php
  }
?>
      <div class="form-group">
        <div class="required">
          <?php echo tep_draw_input_field('firstname', (isset($entry['entry_firstname']) ? $entry['entry_firstname'] : ''), 'class="form-control" placeholder="' . ENTRY_FIRST_NAME . '"'); ?>
       </div>
      </div>

      <div class="form-group">
        <div class="required">
          <?php echo tep_draw_input_field('lastname', (isset($entry['entry_lastname']) ? $entry['entry_lastname'] : ''), 'class="form-control" placeholder="' . ENTRY_LAST_NAME . '"'); ?>
        </div>
      </div>
 
  <!--NIF start-->
  <?php
  if (ACCOUNT_NIF == 'true') {
  	?>
      <div class="form-group">
      <?php if (ACCOUNT_NIF_REQ == 'true') { ?> 
      <div class="required"> <?php } ?>
        <?php echo tep_draw_input_field('nif', $entry['entry_nif'], 'class="form-control" placeholder="' . ENTRY_NIF . '"'); ?>
        <?php if (ACCOUNT_NIF_REQ == 'true') { ?> 
      </div> <?php } ?>   
      </div>
  <?php
    }
   
  if (ACCOUNT_COMPANY == 'true') {
?>
      <div class="form-group">
        <div class="required">
          <?php echo tep_draw_input_field('company', (isset($entry['entry_company']) ? $entry['entry_company'] : ''), 'class="form-control" placeholder="' . ENTRY_COMPANY . '"'); ?>
        </div>
      </div>
<?php
  }
?>
      <div class="form-group">
        <div class="required">
          <?php echo tep_draw_input_field('street_address', (isset($entry['entry_street_address']) ? $entry['entry_street_address'] : ''), 'class="form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"'); ?>
        </div>
      </div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
      <div class="form-group">
        <?php echo tep_draw_input_field('suburb', (isset($entry['entry_suburb']) ? $entry['entry_suburb'] : ''), 'class="form-control" placeholder="' . ENTRY_SUBURB . '"'); ?>
      </div>
<?php
  }
?>
      <div class="form-group">
        <div class="required">
          <?php echo tep_draw_input_field('postcode', (isset($entry['entry_postcode']) ? $entry['entry_postcode'] : ''), 'class="form-control" placeholder="' . ENTRY_POST_CODE . '"'); ?>
        </div>
      </div>
      
      <div class="form-group">
        <div class="required">
          <?php echo tep_draw_input_field('city', (isset($entry['entry_city']) ? $entry['entry_city'] : ''), 'class="form-control" placeholder="' . ENTRY_CITY . '"'); ?>
        </div>
      </div> 
<?php
  if (ACCOUNT_STATE == 'true') {
?>

      <div class="form-group">
          <div class="required">
                <span id="states">
				<?php
				// +Country-State Selector
				echo ajax_get_zones_html($entry['entry_country_id'],($entry['entry_zone_id'] == 0 ? $entry['entry_state'] : $entry['entry_zone_id']), false);
				// -Country-State Selector
				?>
				</span>
        </div>
      </div>

          
<?php
  }
?>
      <div class="form-group">
       <?php // echo ENTRY_COUNTRY; ?>
        <div class="required">
			<?php // +Country-State Selector ?>
            <?php echo tep_get_country_list('country', $entry['entry_country_id'],'onChange="getStates(this.value,\'states\');"'); ?>
            <?php // -Country-State Selector ?>
        </div>
      </div>					  

<?php
  if ((isset($_GET['edit']) && ($customer_default_address_id != $_GET['edit'])) || (isset($_GET['edit']) == false) ) {
?>
<p class="campo"><label for="primary"><?php echo SET_AS_PRIMARY ?></label> <?php echo tep_draw_checkbox_field('primary', 'on', false, 'id="primary"') ; ?></p>
<?php
  }
?>
  </div>
