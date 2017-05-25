<?php
	echo '<h3><i class="fa fa-truck checkout-ico" aria-hidden="true"></i>'.TABLE_HEADING_SHIPPING_METHOD.'</h3>';
	
  $quotes = $shipping_modules->quote();

  if ( !tep_session_is_registered('shipping') || ( tep_session_is_registered('shipping') && ($shipping == false) && (tep_count_shipping_modules() > 1) ) ){
	  if (tep_session_is_registered('shipping')) tep_session_unregister('shipping');
	  tep_session_register('shipping');
	  $shipping = $shipping_modules->cheapest();
  }
?>
<?php
	if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
?>
	<div class="row">
	  <div class="col-sm-12"><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></div>
	</div>
<?php
	} elseif ($free_shipping == false) {
?>
	<div class="row">
	  <div class="col-sm-12"><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></div>
	</div>
<?php } ?>

<?php if ($free_shipping == true) {
?>
	<div class="row">
	  <div class="col-sm-8"><?php echo FREE_SHIPPING_TITLE; ?></div>
	  <div class="col-sm-4"><?php echo $quotes[$i]['icon']; ?></div>
	</div>
	<div class="row">
	  <div class="col-sm-12"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></div>
	</div>

<?php } else { ?>

	<?php
		for ($i=0, $n=sizeof($quotes); $i<$n; $i++) {
			if (isset($quotes[$i]['error'])) {
	?>
		<div class="row"><div class="col-sm-12"><?php echo $quotes[$i]['error']; ?></div></div>
	<?php
			} else {
			  for ($j=0, $n2=sizeof($quotes[$i]['methods']); $j<$n2; $j++) {
			  	if($i==0) $checked = true;
		  		else $checked = false;
		  		
		  		$radio_val = $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'];
	?>
	  <div class="row moduleRow shippingRow<?php echo ($checked ? ' moduleRowSelected' : '');?>" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $quotes[$i]['methods'][$j]['title']; ?>">
	    <div class="col-xs-9">
				<div class="form-group">
          <?php echo tep_draw_radio_field('shipping', $radio_val, $checked,'id="radio_'.$radio_val.'"'); ?>
					<?php if (isset($quotes[$i]['icon']) && tep_not_null($quotes[$i]['icon'])) { echo $quotes[$i]['icon']; } ?>
          <label for="radio_<?php echo $radio_val;?>"><?php echo $quotes[$i]['module']; ?></label>
				</div>
			</div>
			<div class="col-xs-3 text-right">
				<div class="form-group">
							<?php if ( ($n > 1) || ($n2 > 1) ) { ?>
					<?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?>
					
				<?php } else {
					if ($checked) {
						$shipping_actual_tax = $quotes[$i]['tax'] / 100;
						$shipping_tax = $shipping_actual_tax * $quotes[$i]['methods'][$j]['cost'];
		
						$shipping['cost'] = $quotes[$i]['methods'][$j]['cost'];
						$shipping['shipping_tax_total'] = $shipping_tax;
						if (isset($onepage['info']['shipping_method']['cost'])) {
							$onepage['info']['shipping_method']['cost'] =
							$quotes[$i]['methods'][$j]['cost'];
						$onepage['info']['shipping_method']['shipping_tax_total'] =
							$shipping_tax;
						}
					}
				?>
					<?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], $quotes[$i]['tax'])) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?>
				<?php } ?>
				</div>
			</div>
	
	  </div>
<?php
			  }
			}
		}
	}
?>
