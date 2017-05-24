<?php
    require('includes/application_top.php');
    require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US );

	// Breadcrumb
	$breadcrumb->add( NAVBAR_TITLE, tep_href_link( FILENAME_CONTACT_US ) );
	
	// Si nos han enviado el formulario
	if( isset( $_GET['action'] ) && $_GET['action'] == 'send' )
	{
		// Variables
		$sNombre = tep_db_prepare_input( $_POST['name'] );
		$sEmail = tep_db_prepare_input( $_POST['email'] );
		$sAsunto = tep_db_prepare_input( $_POST['subject'] );
		$sConsulta = tep_db_prepare_input( $_POST['enquiry'] );
		$bError = false;

		// Reseteamos
		$messageStack->reset();
		
		//if(RECAPTCHA_ENABLE == 'true')
	//{
			//ReCaptcha Google Variables
			$userIP = $_SERVER["REMOTE_ADDR"];
			$recaptchaResponse = $_POST['g-recaptcha-response'];
			//$secretKey = RECAPTCHA_PRIVATE_KEY;
			$secretKey = '6LfUCAsUAAAAAFGndIuImin37Phtad9HTEWYDT2Z';
			// Validamos el captcha
			$verifyCaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}&remoteip={$userIP}");
			
			if(!strstr($verifyCaptcha, "true")){
				$messageStack->add( 'contact_error', ERROR_CAPTCHA );
				$bError = true;
			}
		//}

		// Comprobamos el nombre
		if( $sNombre == '' )
		{
			$messageStack->add( 'contact_error', ERROR_NOMBRE );
			$bError = true;
		}

		// Comprobamos el email
		if( $sEmail == '' || ! tep_validate_email( $sEmail ) )
		{
			$messageStack->add( 'contact_error', ERROR_EMAIL );
			$bError = true;
		}

		// Comprobamos la consulta
		if( $sConsulta == '' )
		{
			$messageStack->add( 'contact_error', ERROR_CONSULTA );
			$bError = true;
		}

		// Si no existen errores enviamos el email
		if( !$bError )
		{
			// Creamos el contenido del email
			$sContenido = 'Nombre: ' . $sNombre  . '<br/>';	
			$sContenido .= 'Email: ' . $sEmail . '<br/>';
			$sContenido .= 'Asunto: ' . $sAsunto . '<br/>';
			$sContenido .= 'Consulta: ' . $sConsulta . '<br/>';

			// Enviamos el email a la tienda
			tep_mail( STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EXTRA_SUBJECT_STOREOWNER . ' ' . $sAsunto, $sContenido, $sNombre, $sEmail );

			// Si hemos pedido copia
			if( $_POST['send_copy_customer'] ){
			
				// Creamos el contenido del email
				$sContenido = FORM_NOMBRE .' ' . $sNombre  . '<br/>';	
				$sContenido .= FORM_EMAIL .' ' . $sEmail . '<br/>';
				$sContenido .= FORM_ASUNTO .' ' . $sAsunto . '<br/>';
				$sContenido .= FORM_CONSULTA .' ' . $sConsulta . '<br/>';
			
				tep_mail( $sNombre, $sEmail, EXTRA_SUBJECT_CUSTOMER . ' ' . $sAsunto, $sContenido, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS );
			}

			// Redireccionamos
			$messageStack->add_session( 'contact_correcto', CORRECTO );
			tep_redirect( tep_href_link( FILENAME_CONTACT_US ) );
		}
	}
	// Si estamos logeados obtenemos el nombre y email
	elseif( tep_session_is_registered('customer_id') )
	{
		$aDatos = tep_db_query( 'select customers_firstname, customers_lastname, customers_email_address
								 from ' . TABLE_CUSTOMERS . '
								 where customers_id = ' . (int)$customer_id );
		$aDatos = tep_db_fetch_array( $aDatos );
		$sNombre = $aDatos['customers_firstname'] . ' ' . $aDatos['customers_lastname'];
		$sEmail = $aDatos['customers_email_address'];
	}

	require(DIR_THEME. 'html/header.php');
	require(DIR_THEME. 'html/column_left.php');
?>

  <div class="page-header">
    <h1><?php echo HEADING_TITLE; ?></h1>
  </div>

	<?php echo tep_draw_form( 'contact_us', tep_href_link( FILENAME_CONTACT_US, 'action=send' ) ); ?>
		<?php //if(RECAPTCHA_ENABLE == 'true'): ?>
			<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php //endif; ?>
		<?php if( $messageStack->size( 'contact_error' ) > 0 ): ?>
				<?php echo str_replace( chr(10), '<br/>', $messageStack->output( 'contact_error' ) ); ?>
		<?php endif; ?>
		
		<?php if( $messageStack->size( 'contact_correcto' ) > 0 ): ?>
				<?php echo str_replace( chr(10), '<br/>', $messageStack->output( 'contact_correcto' ) ); ?>
		<?php endif; ?>
	 
  <div class="col-xs-12 col-md-3">	 
	 
		<div class="form-group">
			<label for="name"><?php echo FORM_NOMBRE; ?></label>
			<?php echo tep_draw_input_field( 'name', $sNombre,'class="form-control"' ); ?>
		</div>

		<div class="form-group">
			<label for="email"><?php echo FORM_EMAIL; ?></label>
			<?php echo tep_draw_input_field( 'email', $sEmail,'class="form-control"' ); ?>
		</div>
		
		<div class="form-group">
			<label for="subject"><?php echo FORM_ASUNTO; ?></label>
			<?php echo tep_draw_input_field( 'subject', $sAsunto,'class="form-control"' ); ?>
		</div>
  </div>
		
    <div class="col-xs-12 col-md-9">
		
		
      <div class="form-group">                
			<label for="enquiry"><?php echo FORM_CONSULTA; ?></label>
			<?php echo tep_draw_textarea_field( 'enquiry', 'soft', 50, 15, tep_sanitize_string( $sConsulta, '', false ),'class="form-control"'); ?>
      </div>

		<p class="campo" style="position: relative; left: -6px; width: 265px;">
			<?php echo tep_draw_checkbox_field( 'send_copy_customer', '1', false, 'style="width: 20px !important;"'); ?>
			<label for="send_copy_customer" style="width: auto;"><?php echo FORM_COPIA; ?></label>
		</p>
		<?php //if(RECAPTCHA_ENABLE == 'true'): ?>
			<div class="g-recaptcha" data-sitekey="<?php //echo RECAPTCHA_SITE_KEY; ?>6LfUCAsUAAAAAPTV4qDk_jG-c61M4xda_Pwiiej6"></div>
		<?php //endif; ?>
		
      <span class="right button-right space-top">
        <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'icon-arrow-right2', null, 'btn btn-default pull-right'); ?>
      </span>
  
	</div>	
	</form>

<?php
	require( DIR_THEME. 'html/column_right.php' );
	require( DIR_THEME. 'html/footer.php' );
	require( DIR_WS_INCLUDES . 'application_bottom.php' );
?>