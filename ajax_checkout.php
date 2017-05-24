<?php 
header('Content-Type: text/html; charset=iso-8859-1'); 
require('includes/application_top.php'); 
switch ($_GET['f']) {
	case 'usuarios':
		$archivo='usuarios.php';
	break;
	case 'direccion':
		$archivo='actualizar_direccion.php';
	break;
	case 'shipping':
		$archivo='checkout_shipping.php';
	break;
	case 'payment':
		$archivo='checkout_payment.php';
	break;
	case 'session':
		$archivo='session.php';
	break;
	case 'progreso':
		$archivo='progreso.php';
	break;
	case 'confirmation':
		$archivo='checkout_confirmation.php';
	break;
	case 'email':
		$archivo='comprobar_email.php';
	break;
	case 'listado':
		$archivo='listado_paises.php';
	break;
	case 'cantidad':
		$archivo='cambiar_cantidad.php';
	break;
	case 'login':
		$archivo='login.php';
	break;
	case 'create':
		$archivo='create_account.php';
	break;
	case 'facturacion':
		$archivo='direccion_facturacion.php';
	break;
	case 'anular_factura':
		$archivo='anular_facturacion.php';
	break;
	default:
		$archivo='error.php';
	break;
}
include('checkout/'.$archivo);
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
