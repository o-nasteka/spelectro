<?php
  require('includes/application_top.php');
  define('CHEKOUT_ONE_DENOX', 'false');



 if (CHEKOUT_ONE_DENOX == 'false')
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));

  if ($cart->count_contents() < 1) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<script language="javascript" src="checkout/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="checkout/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
<script src="checkout/jquery.livequery.min.js" type="text/javascript"></script>
<script src="checkout/funciones.js" type="text/javascript"></script>
<?php require(DIR_THEME. 'scripts/scripts.php'); ?>
<link href="checkout/checkout.css" rel="stylesheet" type="text/css" />
</head>


<?php require(DIR_THEME. 'html/header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<div class="contenido_checkout">
    <noscript>
        <div class="no_javascript">
              <p><strong>Ha ocurrido un error</strong>, para continuar con el proceso de pago <a href="<?php echo tep_href_link(FILENAME_CHECKOUT_SHIPPING) ?>">pulse aqu�</a>
              </p>
              <p class="explicacion">Su navegador no soporta javascript, por eso le llevamos a una p&aacute;gina adaptada a su navegador</p>
        </div>
    </noscript>
    <div class="desvanecer"></div>
<div class="checkout">
    	<div class="progreso_contenedor">
        	<div class="progreso"></div>
        </div>
        <div class="mensajes">
        	Mensajes
        </div>
        <div class="contenedor">
            <div class="login box"></div>
            <div class="facturacion box"></div>
            <div class="shipping box"></div>
            <div class="payment box"></div>
            <?php /*?><div class="session box"></div><?php */?>
        </div>
    </div>
    <div class="cargando">Actualizando datos...</div>
    <div class="checkout columna_checkout">
        <div class="confirmation"></div>
    </div>
</div>
<?php require(DIR_THEME. 'html/footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>