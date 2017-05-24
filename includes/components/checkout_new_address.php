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
<!-- // +Country-State Selector -->
		<div id="indicator"><?php //echo tep_image(DIR_WS_IMAGES . 'indicator.gif'); ?></div>			
<!-- // -Country-State Selector -->
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
      <label class="col-sm-2 form-control-label"><?php echo ENTRY_GENDER; ?></label>
      <div class="col-sm-10"><?php echo tep_draw_radio_field('gender', 'm', $male) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('gender', 'f', $female) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . (tep_not_null(ENTRY_GENDER_TEXT) ? '<span class="inputRequirement">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></div>
    </div>
<?php
  }
?>
    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('firstname', '', 'class="form-control" placeholder="' . ENTRY_FIRST_NAME . '"'); ?>
      </div>
    </div>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('lastname', '', 'class="form-control" placeholder="' . ENTRY_LAST_NAME . '"'); ?>
      </div>
    </div>

          </tr>
<!--NIF start-->
<?php
  if (ACCOUNT_NIF == 'true') {
?>

              <div class="form-group">
                <div class="required">
                  <?php echo tep_draw_input_field('nif', '', 'class="form-control" placeholder="' . ENTRY_NIF . '"'); ?>
                </div>
              </div>
<?php
  }
  if (ACCOUNT_COMPANY == 'true') {
?>
    <div class="form-group">
      <?php echo tep_draw_input_field('company' , '', 'class="form-control" placeholder="' . ENTRY_COMPANY . '"'); ?>
    </div>

<?php
  }
?>
    <div class="form-group">
      <div class="required">
         <?php echo tep_draw_input_field('street_address', '', 'class="form-control" placeholder="' . ENTRY_STREET_ADDRESS . '"'); ?>
      </div>
    </div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<p class="campo"><label for="suburb"><?php echo ENTRY_SUBURB; ?></label><?php echo tep_draw_input_field('suburb') . '&nbsp;' . (tep_not_null(ENTRY_SUBURB_TEXT) ? '<span class="inputRequirement">' . ENTRY_SUBURB_TEXT . '</span>': ''); ?></p>
<?php
  }
?>
    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('postcode', '', 'class="form-control" placeholder="' . ENTRY_POST_CODE . '"'); ?>
      </div>
    </div>

			<?php // +Country-State Selector ?>
            <?php echo tep_get_country_list('country', 195,'onChange="getStates(this.value,\'states\');"') . '&nbsp;' . (tep_not_null(ENTRY_COUNTRY_TEXT) ? '<span class="inputRequirement">' . ENTRY_COUNTRY_TEXT . '</span>': ''); ?>
	    <?php // -Country-State Selector ?>
          
<?php
  if (ACCOUNT_STATE == 'true') {
?>
                <div class="form-group">
                  <div class="required">
<span id="states">
				<?php
				// +Country-State Selector
				echo ajax_get_zones_html($country,'',false);
				// -Country-State Selector
				?>
				</span>
                </div>
              </div>
<?php
  }
?>

    <div class="form-group">
      <div class="required">
        <?php echo tep_draw_input_field('city' , '', 'class="form-control" placeholder="' . ENTRY_CITY . '"'); ?>
      </div>
    </div>    
</div>
