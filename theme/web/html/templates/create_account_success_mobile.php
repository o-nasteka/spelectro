<div id="container_white">

<h3 class="heading_account_created"><span><?php echo HEADING_TITLE; ?></span></h3>

<div style="account_created_mobile">

<p class="account_text_created"><?php echo TEXT_ACCOUNT_CREATED; ?></p>
              </tr>
<!-- Points/Rewards Module V2.1rc2a bof-->
<?php 
   if ((USE_POINTS_SYSTEM == 'true') && (NEW_SIGNUP_POINT_AMOUNT > 0)) {
?>
<p class="informacion"><?php echo sprintf(TEXT_WELCOME_POINTS_TITLE, '<a href="' . tep_href_link(FILENAME_MY_POINTS, '', 'SSL') . '" title="' . TEXT_POINTS_BALANCE . '">' . TEXT_POINTS_BALANCE . '</a>', number_format(NEW_SIGNUP_POINT_AMOUNT,POINTS_DECIMAL_PLACES), $currencies->format(tep_calc_shopping_pvalue(NEW_SIGNUP_POINT_AMOUNT))); ?>.</p>
<p class="informacion"><?php echo sprintf(TEXT_WELCOME_POINTS_LINK, '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=13', 'NONSSL') . '" title="' . BOX_INFORMATION_MY_POINTS_HELP . '">' . BOX_INFORMATION_MY_POINTS_HELP . '</a>'); ?></p>
<?php
   }
?>               
<!-- Points/Rewards Module V2.1rc2a eof-->
<div style="margin:20px;">
<div class="botonera" style="text-align:center;"> 
<?php echo '<a href="' . $origin_href . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?>
</div>

</div>
</div>

</div>