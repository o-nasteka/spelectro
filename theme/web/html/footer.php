<?php if( USE_COLUMN_LEFT && ! in_array( basename( $_SERVER['SCRIPT_NAME'] ), $NO_USE_COLUMN_LEFT ) ): ?>
	<div id="web-izqd" class="<?php echo $cleft ?>">
		<?php
			$myColumn = "left";
			include( DIR_WS_INCLUDES . 'columns.php' );
			if( $banner = tep_banner_exists( 'dynamic', 'izq1' ) )
				echo '<div class="infoBoxContainer">' . tep_display_banner( 'static', $banner ) . '</div>';
			if( $banner = tep_banner_exists( 'dynamic', 'izq2' ) )
				echo '<div class="infoBoxContainer">' . tep_display_banner( 'static', $banner ) . '</div>';
			if( $banner = tep_banner_exists( 'dynamic', 'izq3' ) )
				echo '<div class="infoBoxContainer">' . tep_display_banner( 'static', $banner ) . '</div>';
		?>
	</div>
<?php endif; ?>


  </div>
</div>

        <section class="content-block-nopad bg-custom-footer footer-wrap-1-1">
            <div class="container footer-1-1">
                <div class="row">
		            <div class="col-sm-3">
                        <img src="<?php echo DIR_THEME."/images/mobile/logoalpha.png"; ?>" />
               
                        <div class="text-center">
                            <b>Copyright <?php echo date('Y');?> &copy; Spainelectro.com</b><br /><br />
		                    <?php echo TEXT_FOOTER_ADDRESS; ?>
                        </div>      
		            </div>
		            
		            <div class="col-sm-8 col-sm-offset-1">
                        <div class="row">
                          <div class="col-md-4">
                
                              <a href="index.php" title="<?php echo TITLE ?>"><?php echo TITLE ?></a>
                   		      <ul>
					          <?php echo _getMenuEstatico( array( 'SHOW_SUBMENU' => false ) ); ?>
		                      </ul>
		                  </div>
		                  <div class="col-md-8">
		                 	<!--
		                  <a href="contact_us.php">Contuct us</a>
		                  -->
		                  <div class="google-maps">
		                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3044.1733642732643!2d-3.7537956847341576!3d40.27189897938227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd418a99a575e6c7%3A0xa8199125a4284c31!2sSpainelectro.com!5e0!3m2!1spt-PT!2ses!4v1495546154874" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
		                  <div class="google-maps">
		                  </div>
		                  <!--
		                  <div class="col-md-4">

		                  </div>
		                  -->
		               </div>
		            </div>    
	
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->
        </section> 		
       <div class="copyright-bar-2 bg-custom-footer">
            <div class="container text-center">
                <p><?php //echo FOOTER_TEXT_BODY . $oscTemplate->getTitle(); ?></p>
                <p><?php  if ( basename($PHP_SELF) == FILENAME_DEFAULT && $cPath==null && ( !isset($_GET['manufacturers_id']) ) ) { ?> <a href="http://responsive-oscommerce.com" target="_blank">Developed by responsive-oscommerce.com</a> <?php } else { echo 'Developed by responsive-oscommerce.com'; } ?></p>
            </div>
        </div> 		
	
<div id="dx-coki" class="dx-coki-<?php echo cookie_control_view; ?>" style="background-color: <?php echo cookie_control_color_fondo_box; ?>; border-color: <?php echo cookie_control_color_borde_box; ?>;">
	<div id="dx-coki-cntd">
		<div id="dx-coki-clse" style="color: <?php echo cookie_control_color_cierre; ?>;">&#10006;</div>
		<div id="dx-coki-tile" style="color: <?php echo cookie_control_color_titulo; ?>;"><?php echo cookie_control_texto_titulo; ?></div>
		<div id="dx-coki-text" style="color: <?php echo cookie_control_color_texto; ?>;"><?php echo cookie_control_texto_mensaje; ?><a rel="nofollow" title="Politica de cookies" href="<?php echo tep_href_link( 'information.php', 'info_id=' . cookie_control_url ); ?>" style="color: #2b9af0;">Ver Politica de cookies.</a></div>
		<a id="dx-coki-acpt" style="background-color: <?php echo cookie_control_color_fondo_boton; ?>; border-color: <?php echo cookie_control_color_borde_boton; ?>;"><?php echo cookie_control_texto_boton; ?></a>
	</div>
</div>

	<div class="ContactForm Transition" id="ContactForm">
		<?php
		if( tep_session_is_registered('customer_id') )
		{
			$aDatos = tep_db_query( 'select customers_firstname, customers_lastname, customers_email_address
									 from ' . TABLE_CUSTOMERS . '
									 where customers_id = ' . (int)$customer_id );
			$aDatos = tep_db_fetch_array( $aDatos );
			$sNombre = $aDatos['customers_firstname'] . ' ' . $aDatos['customers_lastname'];
			$sEmail = $aDatos['customers_email_address'];
		} ?>
		<?php echo tep_draw_form( 'contact_us', tep_href_link( FILENAME_CONTACT_US, 'action=send' ), 'POST', 'class="Transition" id="contact_us_ajax"' ); ?>
		<a href="javascript:void(0);" id="CloseContactForm"></a>
			<div class="Column">
				<p><strong>Datos personales:</strong></p>
				<p><?php echo tep_draw_input_field( 'name', $sNombre, 'placeholder="Nombre"' ); ?></p>
				<p><?php echo tep_draw_input_field( 'email', $sEmail, 'placeholder="E-mail"' ); ?></p>
				<p><?php echo tep_draw_input_field( 'subject','', 'placeholder="Teléfono"' ); ?></p>
			</div>
			<div class="Column">
				<p><strong>Datos del producto que buscas:</strong></p>
				<p><?php echo tep_draw_input_field( 'enquiry','', 'placeholder="Nombre del producto"'); ?></p>
				<p class="ContactBotonera"><button type="submit" class="Button ButtonGreen" id="enviarContactForm" data-url="<?php echo tep_href_link( FILENAME_CONTACT_US, 'action=send' ); ?>">Enviar</button></p>
			</div>
		</form>
	</div>
 
        <?php include( DIR_THEME. 'scripts/scripts_footer.php' ); ?>
		
		
				<script src="<?php echo DIR_THEME.'js/owl.carousel.min.js'; ?>"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48508208-1', 'auto');
  ga('send', 'pageview');

</script>	

<!-- BEGIN JIVOSITE CODE {literal} -->
<script type='text/javascript'>
(function(){ var widget_id = 'il9Z8idzbF';var d=document;var w=window;function l(){
var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
<!-- {/literal} END JIVOSITE CODE -->	
    </body>
</html>
