<div id="container_white">
<h3 class="heading_title_grey"><span>LOGIN</span></h3>

<FORM id="form_login_mobile" name="login" action="http://spainelectro2.com/login.php?action=process" method="post">

<div class="row">


<?php
  if ($messageStack->size('login') > 0) {
?>
<div class="alert alert-info"><?php echo $messageStack->output('login'); ?></div>
<?php
  }
?>








<div class="col-xs-12 col-md-12 col-sm-12 ol-lg-12">
<h3><?php echo ENTRY_EMAIL_ADDRESS; ?></h3>


<?php echo tep_draw_input_field('email_address'); ?>

</div>







<div class="col-xs-12 col-md-12 col-sm-12 ol-lg-12">

<h3><?php echo ENTRY_PASSWORD; ?></h3>


<?php echo tep_draw_password_field('password'); ?>

</div>




<div class="col-xs-12 col-md-12 col-sm-12 ol-lg-12">

	
	
	
<input type="submit" class="btn_submit_mobile" value="ENTRAR" />
<br/>
	<br/>
 <?php if((ALLOW_AUTOLOGON != 'false') && ($cookies_on == true)) { ?>
    	<p class="campo">
		<?php echo tep_draw_checkbox_field('remember_me','on', (($password == '') ? false : true)) . '&nbsp;' . ENTRY_REMEMBER_ME; ?>
		</p>
		<br/>
		<a href="password_forgotten.php" title="Recuperar Contrase?a"><?php echo TEXT_PASSWORD_FORGOTTEN; ?></a>
		</p>
    <?php } ?>
</div>




 







</div>
</FORM>





</div>