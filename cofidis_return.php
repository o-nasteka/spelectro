<?
require('includes/application_top.php');
require(DIR_WS_MODULES . 'payment/cofidis.php');
$cofidis=new cofidis();
$cofidis->trace("Desde: ".$_SERVER['REMOTE_ADDR']." pide:".$_SERVER['REQUEST_URI']);
if($_REQUEST['referencia']=='')
	//tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode("Hubo un error procesando el pago, por favor, intente de nuevo o contacte con el comercio"), 'SSL', true, false));
	tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, 'SSL', true, true));
$cofidis->answer($_REQUEST['referencia'],
               $_REQUEST['accept'],
               $_REQUEST['numcuotas']
                );
//tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, 'SSL', true, false));
?>