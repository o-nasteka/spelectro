<?php
	echo '<h3><span class="glyphicon glyphicon-shopping-cart checkout-ico"></span>'.TABLE_HEADING_PAYMENT_METHOD.'</h3>';

  $selection = $payment_modules->selection();

  if (tep_session_is_registered('onepage')){
	  $paymentMethod = $onepage['info']['payment_method'];
  } else {
		$paymentMethod = ONEPAGE_DEFAULT_PAYMENT;
	}

  if (sizeof($selection) > 1) {
?>
	<div class="row">
	  <div class="col-sm-12 checkout_b_section"><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></div>
	</div>
<?php
  } else {
?>
	<div class="row">
	  <div class="col-sm-12"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></div>
	</div>
<?php
  }
?> 

<?php
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
?>
	<div class="row moduleRow paymentRow<?php echo ($selection[$i]['id'] == $paymentMethod ? ' moduleRowSelected' : '');?>">
    <div class="col-md-12">
	    <div class="form-group">
	    	<?php
			     if (sizeof($selection) > 1) {
			       echo tep_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $paymentMethod ? true : ($i=='0' ? true : false)),'id="radio_'.$selection[$i]['id'].'"');
			     } else {
			       echo tep_draw_hidden_field('payment', $selection[$i]['id'],true,'id="radio_'.$selection[$i]['id'].'"');
			     }
	    	?>
	    	<label for="radio_<?php echo $selection[$i]['id'];?>"><?php echo $selection[$i]['module']; ?></label>
	    </div>
  	</div>
  </div>

<?php
	if (isset($selection[$i]['error'])) {
?>
	<div class="row">
	  <div class="col-sm-12"><?php echo $selection[$i]['error']; ?></div>
	</div>
<?php
		} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields']) && ($selection[$i]['id'] == $paymentMethod)) {
			for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
	<div class="row">
	  <div class="col-xs-6"><?php echo $selection[$i]['fields'][$j]['title']; ?></div>
	  <div class="col-xs-6"><?php echo $selection[$i]['fields'][$j]['field']; ?></div>
	</div>
<?php
			}
		}
  }
?>
