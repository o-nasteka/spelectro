<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Servired Payment Module 
  Copyright (c) 2004 qadram software
  http://www.qadram.com
  
  versión 4.01 - 27/09/2009
  A. Miguel Zúñiga - http://www.ibercomp.com

  Released under the GNU General Public License
*/

 	$medidas_popup = "'width=760,height=600'";
 
  define('MODULE_PAYMENT_SERVIRED_TEXT_TITLE', 'Tarjeta de crédito');
  define('MODULE_PAYMENT_SERVIRED_TRANSFER_TEXT_TITLE', 'Transferencia');
  define('MODULE_PAYMENT_SERVIRED_TEXT_DESCRIPTION', '<font color=red>Admin TPV: </font><a href="' . MODULE_PAYMENT_SERVIRED_ADMIN . '" target="_blank" onClick="window.open(this.href, this.target, ' . $medidas_popup . '); return false;"> aquí</a>');
  define('MODULE_PAYMENT_SERVIRED_TEXT_ERROR_MESSAGE','Error en el proceso');
  define('MODULE_PAYMENT_SERVIRED_TEXT_CANCEL','Cancelado el proceso');	

  define('MODULE_PAYMENT_SERVIRED_TEXT_UNKNOW_ERROR','Error desconocido ');

  define('MODULE_PAYMENT_SERVIRED_ERROR_SIGN','Las firmas no coinciden: error de autenticación');

  define('MODULE_PAYMENT_SERVIRED_ERROR_101','Tarjeta caducada');
  define('MODULE_PAYMENT_SERVIRED_ERROR_102','Tarjeta bloqueada por el banco emisor');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_107','Orden de contactar con el banco emisor de la tarjeta');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_180','Tarjeta no soportada por el sistema');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_184','Autenticación del titular de la tarjeta fallida');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_190','Denegada por el banco emisor de la tarjeta por diversos motivos');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_201','Tarjeta caducada. Orden de retirar la tarjeta');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_202','Tarjeta bloqueada por el banco emisor. Orden de retirar la tarjeta');  	
  define('MODULE_PAYMENT_SERVIRED_ERROR_290','Denegada por diversos motivos. Orden de retirar la tarjeta');
  define('MODULE_PAYMENT_SERVIRED_ERROR_909','Error de sistema');
  define('MODULE_PAYMENT_SERVIRED_ERROR_912','Centro resolutor no disponible');
  define('MODULE_PAYMENT_SERVIRED_ERROR_913','Recibido mensaje duplicado');
  define('MODULE_PAYMENT_SERVIRED_ERROR_949','Fecha de caducidad de la tarjeta errónea');
  define('MODULE_PAYMENT_SERVIRED_ERROR_9111','Banco emisor de la tarjeta no responde');
  define('MODULE_PAYMENT_SERVIRED_ERROR_9093','Número de tarjeta inexistente');
  define('MODULE_PAYMENT_SERVIRED_ERROR_9112','Número de tarjeta inexistente');
?>
