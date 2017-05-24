<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="es-ES">
		<head>
		<?php getHeader(); ?>
		<?php	
function getCurrentURL2()
{
    $currentURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $currentURL .= $_SERVER["SERVER_NAME"];
 
    if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
    {
        $currentURL .= ":".$_SERVER["SERVER_PORT"];
    } 
 
        $currentURL .= $_SERVER["REQUEST_URI"];
    return $currentURL;
}
?>
		<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1">


		
		
				<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<link rel="stylesheet" type="text/css" href="<?php echo DIR_THEME.'fontawesome/css/font-awesome.min.css'; ?>"/>
		
		
		<link rel="stylesheet" href="<?php echo DIR_THEME.'css/owl.carousel.min.css'; ?>">
		<link rel="stylesheet" href="<?php echo DIR_THEME.'css/owl.theme.default.min.css'; ?>">
		
		<script src="<?php echo DIR_THEME.'js/custom_scripts.js'; ?>"></script>
		
		
		<link rel="stylesheet" type="text/css" href="<?php echo DIR_THEME.'css/stylemobile.css'; ?>"/>

		
		<!-- bxSlider Javascript file -->
<script src="<?php echo DIR_THEME.'jquery.bxslider/jquery.bxslider.min.js'; ?>"></script>

<!-- bxSlider CSS file -->
<link href="<?php echo DIR_THEME.'jquery.bxslider/jquery.bxslider.css'; ?>" rel="stylesheet" />
		


		
		
	</head>

	<body class="bg<?php echo (ONLY_INDEX ? '1' : '2') ?>" <?php echo ($_SERVER['PHP_SELF'] == 'shopping_cart.php' ? ' onLoad=\'javascript:startCart( "", "getCart.php", "' . tep_session_name() . '=' . tep_session_id() . '" ); \'' : ''); ?>>
	
	
	
	
	
	<?php include( DIR_WS_INCLUDES . 'header.php' ); ?>
	
	<!--
	<g:plusone annotation="none" href="http://www.outletsalud.com"></g:plusone>
	-->
	
	
	
	
	
	
<div class="preHeader">

	<?php
		// Si estamos logeados mostramos el menu de usuario
		if( tep_session_is_registered( 'customer_id') )
			echo _getMenuLoginUser();
			
		// Si no estamos logeados mostramos el formulario de login
		else
			echo _getLoginFormHeader( array( 'REGISTRO' => true, 'RECORDAR' => true ) );
	?>
</div>





	
<div class="preHeaderMobile">


<div class="topinfo">
<?php
if( tep_session_is_registered('customer_id') )
{

	echo _getMenuLoginUserMobile();

}
else
{
?>
<a  id="btn_aceso">HACER LOGIN</a>
<?php
}
?>
<!--
<a href="http://www.spainelectro.com/contact_us.php">CONTACTO</a> 
-->

</div>

<div class="menumobile">
 
 <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2  togglemenu">

  <span>
  
 <i  class="fa fa-bars btnmenu" aria-hidden="true"></i>
 <i class="fa fa-times btnmenu" aria-hidden="true"></i>

 </span>
 </div>
 
 
 <div class="col-xs-8 col-md-8col-sm-8 col-lg-8 logo-container">
 
 <a href="http://www.spainelectro.com/">
<img src="<?php echo DIR_THEME."/images/mobile/logo.png"; ?>" />

</a>

 </div>

 
 <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2 user-container">
<span>
 <i id="btn_search_mobile" class="fa fa-search" aria-hidden="true"></i>
</span>
</div>


</div>




<div class="menu-items-wrapper">
<ul>



<?php echo _getMenuEstatico_mobile(); ?>

</ul>
</div>

</div>















<div id="login_mobile_window">

<div class="titulo">

<span>
LOGIN
</span>

<i id="btn_close_login_mobile" class="fa fa-times" aria-hidden="true" >
</i>

</div>




<div id="container_form_login">


 <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">

<form name="login" action="login.php?action=process"method="post" class="form_login">

<input type="text" value="E-mail" class="email_address" name="email_address" placeholder="E-mail" />
<br/> 
<input type="password" value="****" class="password" name="password" placeholder="Contraseña" />
<br/>
<i class="forgotpassword"><a href="">Olvidó su contraseña</a></i>
<input type="submit" value="Entrar" id="" class="buttonmobile">
</form>


</div>

 <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">

 
  <a href="">
 <div class="button_crear_cuenta_mobile">
CREAR CUENTA
 </div>
</a>
 
 
 
 </div>




</div>




</div>





<div id="search_mobile_window">
<div class="titulo">

<span>
BUSCAR
</span>

<i id="btn_close_search_mobile" class="fa fa-times" aria-hidden="true" >
</i>

</div>



<form action="">

<input type="text" value="" />


<input class="btn_search" type="submit" value="BUSCAR" />

</form>





</div>


<div id="slidermobile">
<?php

if(ONLY_INDEX == 'null'){
			include( DIR_WS_COMPONENTS . 'slideFeaturedMobile.php' );
			
			
}
?>
</div>




<?php

if(ONLY_INDEX == 'null'){
			
			
			
			
?>
	<div class="container_banner_mobile">

	 <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 centrar_texto">

	 <?php 
	 
	 if( $banner = tep_banner_exists( 'dynamic', 'header-top' ) )
				 echo '<span class="">' . tep_display_banner( 'static', $banner ) . '</span><br/>';
 	 
	 ?>
	 
	 </div>
	
	
	 <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6 centrar_texto">

	<?php
	
	if( $banner = tep_banner_exists( 'dynamic', 'contacto-t' ) )
				echo '<span class="">' . tep_display_banner( 'static', $banner ) . '</span>';
	
	?>
	 
	 </div>

	</div>
 <br/>
	<div class="mobile_only">
	<?php
	require( DIR_WS_BOXES . "categories_tree_mobile.php");
	?>
	</div>
	
		
	
	

<?php	



		
}
?>

<div id="cbcr">
	<a id="cbcr-logo-<?php echo $languages_id; ?>" href="index.php" title="<?php echo TITLE ?>"><?php echo TITLE ?></a>
	<?php
		if( $banner = tep_banner_exists( 'dynamic', 'cabecera' ) )
			echo '<div class="bnr-cbcr-pq">' . tep_display_banner( 'static', $banner ) . '</div>';
	?>
	<a href="contact_us.php" title="cont&aacute;ctanos" id="cbcr-tlef-<?php echo $languages_id; ?>"></a>
	<?php include( DIR_WS_BOXES . 'shopping_cart.php' );?>
	<ul id="cbcr-menu-cntd-<?php echo $languages_id; ?>"><?php echo _getMenuEstatico(); ?></ul>
	
	<div id="srch-crto-<?php echo $languages_id; ?>">
		<?php echo _getSearchForm(); ?>
	</div>
	
	<?php
		/*if( $banner = tep_banner_exists( 'dynamic', 'lateralizq' ) )
			echo '<div class="bnr-float-izqd">' . tep_display_banner( 'static', $banner ) . '</div>';*/
	?>
	<?php
		/*if( $banner = tep_banner_exists( 'dynamic', 'lateraldch' ) )
			echo '<div class="bnr-float-drch">' . tep_display_banner( 'static', $banner ) . '</div>';*/
	?>
	<?php
		if(ONLY_INDEX == 'null'){
			include( DIR_WS_COMPONENTS . 'slideFeatured.php' );
			if( $banner = tep_banner_exists( 'dynamic', 'header-top' ) )
				echo '<span class="bnr-top">' . tep_display_banner( 'static', $banner ) . '</span>';
			if( $banner = tep_banner_exists( 'dynamic', 'contacto-t' ) )
				echo '<span class="cnt-top">' . tep_display_banner( 'static', $banner ) . '</span>';

			//include( DIR_WS_BOXES . 'social.php' );
		}
	?>
	<div style="clear: both;"></div>
</div>





<div class="mobile_only">
<?php
$urlpaginaa_=getCurrentURL2();

if (strpos($urlpaginaa_, 'account.php') !== false) {

   
   include(DIR_THEME."html/templates/account_mobile.php");
	

}



	?>
</div>



<div id="web-cntd">
