<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
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



<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>


<!-- NEW FILES -->
   <link rel="stylesheet" href="theme/<?php echo THEME ?>/css/jquery-ui.css"/>
		 
		 <link href="ext/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="/theme/web/css/lity.css" rel="stylesheet">
		<!-- jQuery library -->  
           
                <script type="text/javascript" src="ext/jquery/jquery-1.11.1.min.js"></script>
              
              <?php if($_SERVER["REQUEST_URI"]!='/checkout.php') { ?>  
                <script type="text/javascript">
                  // solomono: fix conflicts:
                  jQuery.noConflict();
                </script>
              <?php } ?>
		
    
		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

                <script src="ext/js/plugins.js"></script> 
                <script src="ext/js/initialize.js"></script>


		<link rel="stylesheet" type="text/css" href="<?php echo DIR_THEME.'fontawesome/css/font-awesome.min.css'; ?>"/>
		
		
		<link rel="stylesheet" href="<?php echo DIR_THEME.'css/owl.carousel.min.css'; ?>">
		<link rel="stylesheet" href="<?php echo DIR_THEME.'css/owl.theme.default.min.css'; ?>">

		<!--	<script src="<?php echo DIR_THEME.'js/custom_scripts.js'; ?>"></script>
  	  <script src="<?php echo DIR_THEME.'js/jquery.form.js'; ?>"></script>
    <script src="<?php echo DIR_THEME.'js/popup_cart.js'; ?>"></script>  -->
                                                    


		
		<!-- bxSlider Javascript file -->
    <script src="<?php echo DIR_THEME.'jquery.bxslider/jquery.bxslider.min.js'; ?>"></script>


    <!-- bxSlider CSS file -->
    <link href="<?php echo DIR_THEME.'jquery.bxslider/jquery.bxslider.css'; ?>" rel="stylesheet" />
		
    <link rel="stylesheet" href="theme/<?php echo THEME ?>/css/icons.css"/>
    <link rel="stylesheet" href="theme/<?php echo THEME ?>/css/general-styles.css"/>
    <link rel="stylesheet" href="theme/<?php echo THEME ?>/css/blocks.css"/>
    <link rel="stylesheet" href="theme/<?php echo THEME ?>/css/custom.css"/>
	
	<!--
    <link rel="stylesheet" href="theme/<?php echo THEME ?>/css/jquery.fancybox.min.css"/> 
	--> 

<script src = "<?php echo DIR_THEME.'js/jquery-ui.js'; ?>"></script>
		
		
	<script>
         jQuery(function() {
            jQuery("#tabs").tabs();
         });
      </script>	
		
	</head>

	<body class="bg<?php echo (ONLY_INDEX ? '1' : '2') ?>" <?php echo ($_SERVER['PHP_SELF'] == 'shopping_cart.php' ? ' onLoad=\'javascript:startCart( "", "getCart.php", "' . tep_session_name() . '=' . tep_session_id() . '" ); \'' : ''); ?>>
	
	
	<?php include( DIR_WS_INCLUDES . 'header.php' ); ?>
	
	<!--
	<g:plusone annotation="none" href="http://www.outletsalud.com"></g:plusone>
	-->
	
		
<header id="header-1" class="header-1">
    <nav class="main-nav navbar navbar-default">
        <div class="container">
		
		
		<p id="textoheader"> Lideres en Repuestos y Servicio técnico </p>
		 
		
		
		
		
		    <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
			
			
             
			<button type="button" class="navbar-toggle collapsed btleft">
					<a href="/">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-home fontwhite"></i>
					</a>
                </button> 
				 
				
					
				<button type="button" class="navbar-toggle collapsed btleft" data-toggle="collapse" data-target="#navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-search"></i>
                </button> 
				
			
				
                <a href="<?php echo tep_href_link(FILENAME_DEFAULT) . '">' . tep_image(DIR_WS_IMAGES . 'store_logo.png', STORE_NAME, '','', 'class="brand-img img-responsive"'); ?></a>
				
				
				
					<button type="button" class="navbar-toggle collapsed btright">
					<a href="/account.php">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-user fontwhite"></i>
					</a>
                </button> 
				
				 
				 
						<button type="button" class="navbar-toggle collapsed btright">
					<a href="/shopping_cart.php">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="icon-cart fontwhite"></i>
					</a>
                </button> 
				 
				
            </div>
		     
         
                
          
		     	
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right"><li >	
<?php echo _getSearchForm(); ?></li>
                    <?php include( DIR_WS_BOXES . 'shopping_cart.php' );?>
                                    

				 
									
 				    <li class="user dropdown">
					    <a class="dropdown-toggle" data-toggle="dropdown">
						    <?php echo HEADER_TITLE_MY_ACCOUNT; ?>
						    <i class="caret"></i>
					    </a>
						     
					    <ul class="dropdown-menu dropdown-menu-right icons-right">
					        <li>
	<?php
		// Si estamos logeados mostramos el menu de usuario
		if( tep_session_is_registered( 'customer_id') ) {
			echo _getMenuLoginUser();
						
		// Si no estamos logeados mostramos el formulario de login
	    } else {
			echo _getLoginFormHeader( array( 'REGISTRO' => true, 'RECORDAR' => true ) );
	    }
			?>
                            </li>
                        </ul>
                    </li>                        		
 	            </ul>
            </div>
            <!-- /.navbar-collapse -->			
        </div>
    </nav>
</header>



<?php
$urlpaginaa_=getCurrentURL2();
	?>

<?php
$tpl = '2';

if ($tpl == 1) {
  $bcontent = 'col-xs-12';
} elseif ($tpl == 2) {
  $cleft = 'col-xs-12 col-md-3 col-md-pull-9';
  $bcontent = 'col-xs-12 col-md-9 col-md-push-3';
} elseif ($tpl == 3) {
  $cleft = 'col-xs-12 col-md-2 col-md-pull-8';
  $cright = 'col-xs-12 col-md-2';
  $bcontent = 'col-xs-12 col-md-8 col-md-push-2';
}
?>

<div id="bodyContent" class="container">
  <div class="row">
