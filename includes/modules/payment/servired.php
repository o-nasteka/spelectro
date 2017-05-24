<?php
/********************************************************************

	TPV para Servired
	=========================

	27-09-2009 Versión 4.1 por A. Miguel (www.ibercomp.com)
	
	- Añadido algo de documentación al código
  - Sacado todos los textos para facilitar la internacionalizacion (español, inglés y alemán).
  - Añadido soporte para libra esterlina (euro y dolar ya estaban).
  - Añadido apaño para que funcionen las transferencias por servired
  - Añadido para que se pueda deshabilitar un único cliente para las pruebas
  - Añadido la posibilidad de seleccionar de el administrador que el cliente pueda pagar con tarjeta o transferencia
    (probablemente las domiciliaciones tampoco funcionen correctamente, pero no lo veo en una tienda on line).

	12-06-2008 Revisado por Rucolote 

	28-09-2007 Version 3.0
	
	- Campo Terminal configurable por el admin
	- Ahora funciona correctamente la notificacion online (No se pierden pedidos)
	
------------------------------------------------------------------------	

	4-12-2006 Última edición por Jordi (atencion_clientes@hotmail.com)
	
	
	- Añadido los nuevos campos necesarios para funcionar 
	- Cambiada la encriptación.
	- Añadidos cambios de albert martin (grácias)
	- Instalacion con la web de Servired por defecto

	En los foros en español de oscommerce podreis encontrar la ayuda que necesiteis
	http://oscommerce.qadram.com/modules.php?name=Forums

**********************************************************************	
	
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Servired Payment Module 
  Copyright (c) 2004 qadram software
  http://www.qadram.com

  versión 4.01 - 27/09/2009
  A. Miguel Zúñiga - http://www.ibercomp.com

  Released under the GNU General Public License
*/


  class servired {
    var $code, $title, $description, $enabled;

    //
    // Clase constructor, aquí se definen las variables miembro iniciales,
    //  el estado del módulo (habilitado/deshabilitado). Se define también
    //  la variable form_action_url que es nuestra url pasarela 
    //  (donde se van a recibir los datos POST).
    // Se incluye también la definición de variables estáticas.
    function __construct() {
      global $order;

      // *** No debe cambiarse este texto bajo ningún concepto
      $this->code = 'servired';
      // ***
      $this->title = MODULE_PAYMENT_SERVIRED_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_SERVIRED_TEXT_DESCRIPTION;
      $this->enabled = ((MODULE_PAYMENT_SERVIRED_STATUS == 'True') ? true : false);      
      $this->sort_order = MODULE_PAYMENT_SERVIRED_SORT_ORDER;

      if ((int)MODULE_PAYMENT_SERVIRED_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_SERVIRED_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();

      $this->form_action_url = MODULE_PAYMENT_SERVIRED_URL;      
    }

    //
    // Aquí se implementa la comprobación de zonas de pago o cualquier otra
    //  cosa que se nos ocurra. Si deseamos que el cliente pueda tener
    //  esta forma de pago $this->enabled=true, en caso contrario =false
    //
    // Esta función es llamada desde el constructor, checkout_confirmation.php, checkout_process.php
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_SERVIRED_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_SERVIRED_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->billing['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
      
      //Mira si solo ha de aceptar pedidos del cliente que disponga el email de prueba
      if ((MODULE_PAYMENT_SERVIRED_ID_SEED == 'qwertyasdf0123456789') && ($order->customer['email_address'] != MODULE_PAYMENT_SERVIRED_MAIL) && (MODULE_PAYMENT_SERVIRED_MAIL_STATUS == 'True')) {
          $this->enabled = false;
      }
      
    }

    //
    // Esta función debe generar el código javascript para que en el navegador
    //  del cliente se comprueben que los datos introducidos son correctos. En
    //  este caso no hay nada definido, pues la introducción de los datos se hace
    //  en el banco.
    //
    // Esta función es llamada desde checkout_payment.php
    function javascript_validation() {
      return false;
    }

    //
    // Esta función simplemente debe devolve un arrego con el código de la forma de pago
    //  (en nuestro caso 'servired') y el título (que eslo que se muestra al cliente).
    //
    // Si fuese necesario también incluiríamos los campos de entrada. (en nuestro
    //  caso no lo es).
    //
    // Esta función es llamada desde checkout_payment.php
    function selection() {
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    //
    // En esta función se puede implementar cualquier la comprobación de cualquier condición
    //  despues de que el cliente haya seleccionado el módulo de pago. En nuestro caso nada.
    //
    // Esta función es llamada desde checkout_payment.php
    function pre_confirmation_check() {
      return false;
    }

    //
    // Se puede implementar cualquier comprobación o accción previo a proceder a la confirmación de pago.
    //  Aquí nada.
    //
    // Esta función es llamada desde checkout_payment.php
    function confirmation() {
      return false;
    }
    
    //
    // Genera el formulario html con los elementos ocultos que se van a enviar con
    //  POST a la pasarela de pago.
    // 
    // Esta función es llada desde checkout_confirmation.php
    function process_button() 
    {
      global $order, $currency, $language;
      

      $ds_merchant_order="1".time();
      
      //Merchant Data
      $ds_merchant_data=tep_session_id();
      
      //Amount
      $total=$order->info['total'];      	
      $ds_merchant_amount = round($total*$order->info['currency_value'],2);
      $ds_merchant_amount = number_format($ds_merchant_amount, 2, '.', '');
      $ds_merchant_amount = preg_replace('/\./', '', $ds_merchant_amount);
      
      
      $ds_merchant_currency = "978";
      
      // Cambio de terminal por el 2
      $ds_merchant_terminal = MODULE_PAYMENT_SERVIRED_TERMINAL;
      
      // Añadidos los dos nuevos campos de tipo de transaccion e idioma por defecto      
	  	$ds_merchant_transactiontype = "0";
      $ds_merchant_consumerlanguage = "1";        

      
      if ($currency=='USD') {						//Dolar Estadounidense
      	$ds_merchant_currency = "840";
      	$ds_merchant_terminal = "3";        	
      }
      elseif ($currency=='GBP') {				//Libra esterlina (Inglaterra)
      	$ds_merchant_currency = "826";
      	$ds_merchant_terminal = "3";        	
			}
    
			//
			// No entiendo muy bien lo que ocurre, por lo que lo comento.
			/*elseif ($currency!='EUR')
      {
      	$total=$order->info['total'];      	
      	$ds_merchant_amount = round($total,2);
      	$ds_merchant_amount = number_format($ds_merchant_amount, 2, '.', '');
      	$ds_merchant_amount = preg_replace('/\./', '', $ds_merchant_amount);      	
      }*/
      
      if ($language=='english') 
      {
      	$ds_merchant_consumerlanguage='2';
      }
			elseif ($language=='deutch') {
      	$ds_merchant_consumerlanguage='5';
      }

			$ds_merchant_paymethods=MODULE_PAYMENT_SERVIRED_PAYMODE;
      $seed=MODULE_PAYMENT_SERVIRED_ID_SEED;
      
      $ds_merchant_code=MODULE_PAYMENT_SERVIRED_ID_COM;     
       
      // Gracias martinm por la idea de la session_name :) 
     	$recuperar_sesion = tep_session_id();  // Recojemos la sesion para recuperarla al volver con la notificacion, genere el pedido y vacie el carrito
			$ds_merchant_merchanturl = tep_href_link(FILENAME_CHECKOUT_PROCESS, session_name().'='.$recuperar_sesion, 'NONSSL',false);
			$ds_merchant_urlok = tep_href_link(FILENAME_CHECKOUT_SUCCESS, session_name().'='.$recuperar_sesion, 'NONSSL',false);	// Como el pedido ya estará generado vamos directo al success
			$ds_merchant_urlko = tep_href_link(FILENAME_CHECKOUT_PROCESS, session_name().'='.$recuperar_sesion, 'NONSSL',false);	// Si ha habido algun error volvemos al process, que nos llevara al pago de nuevo con el error correspondientemente tratado.
 
        
      $sha = new SHA1;
      $message = $ds_merchant_amount.$ds_merchant_order.$ds_merchant_code.$ds_merchant_currency.$ds_merchant_transactiontype.$ds_merchant_merchanturl.$seed;
			
			// Nueva encriptación por sha1			    
      $signature = strtoupper (sha1( $message ));
     
     	$Descripcion = $order->customer['lastname']."', ".$order->customer['firstname']." ('".$order->customer['email_address'].")";
     
     // Se han añadido los nuevos inputs
      $process_button_string = 
			       tep_draw_hidden_field('Ds_Merchant_Amount', $ds_merchant_amount) .
			       tep_draw_hidden_field('Ds_Merchant_Currency', $ds_merchant_currency) .
			       tep_draw_hidden_field('Ds_Merchant_Order', $ds_merchant_order) .
             tep_draw_hidden_field('Ds_Merchant_ProductDescription', $Descripcion) .
             tep_draw_hidden_field('Ds_Merchant_Titular', TITLE) .
			       tep_draw_hidden_field('Ds_Merchant_MerchantCode', $ds_merchant_code) .
			       tep_draw_hidden_field('Ds_Merchant_MerchantURL', $ds_merchant_merchanturl) .
			       tep_draw_hidden_field('Ds_Merchant_UrlOK', $ds_merchant_urlok) .
			       tep_draw_hidden_field('Ds_Merchant_UrlKO', $ds_merchant_urlko) .
			       tep_draw_hidden_field('Ds_Merchant_ConsumerLanguage', $ds_merchant_consumerlanguage) .
             tep_draw_hidden_field('Ds_Merchant_MerchantSignature', $signature).
			       tep_draw_hidden_field('Ds_Merchant_Terminal', $ds_merchant_terminal) .
             tep_draw_hidden_field('Ds_Merchant_TransactionType', $ds_merchant_transactiontype) .
             tep_draw_hidden_field('Ds_Merchant_PayMethods', $ds_merchant_paymethods) .
			       tep_draw_hidden_field('Ds_Merchant_MerchantData', $ds_merchant_data);
     
     return $process_button_string;
    }

    //
    // Aquí es donde se implementa la verificación de pago, es decir donde
    //  se reciben los datos del servidor del banco. Es la función más complicada
    //  de entender.
    //
    // Es llamada desde checkout_process.php (antes de que se finalice el pedido).
    function before_process() 
    {
        global $order;
       
			  $seed=MODULE_PAYMENT_SERVIRED_ID_SEED;
			          	
			  $ds_date=$_REQUEST['Ds_Date'];
			  $ds_hour=$_REQUEST['Ds_Hour'];    	
			  $ds_amount=$_REQUEST['Ds_Amount'];    	
			  $ds_currency=$_REQUEST['Ds_Currency'];    	
			  $ds_order=$_REQUEST['Ds_Order'];    	
			  $ds_merchantcode=$_REQUEST['Ds_MerchantCode'];    	
			  $ds_terminal=$_REQUEST['Ds_Terminal'];    	
			  $ds_signature=$_REQUEST['Ds_Signature'];    	
			  $ds_response=$_REQUEST['Ds_Response'];
			  $ds_transactiontype=$_REQUEST['Ds_TransactionType'];	
			  $ds_securepayment=$_REQUEST['Ds_SecurePayment'];    	
			  $ds_merchantdata=$_REQUEST['Ds_MerchantData'];    	
			    	
			  $sha = new SHA1;
			  $message = $ds_amount.$ds_order.$ds_merchantcode.$ds_currency.$ds_response.$seed;
									
			  $digest1 = $sha->hash_string($message);
			  $signature = strtoupper ($sha->hash_to_string( $digest1 ));      
			      	
			  if ($signature!=$ds_signature)
			  {
			     //El proceso no puede ser completado, error de autenticación
           // OJO: Posiblemente no está habilitado en servired que nos
           //      conteste HTML. Comprobar que está ese modo activado.          

			    $error=MODULE_PAYMENT_SERVIRED_ERROR_SIGN;
			    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error), 'SSL', true, false));   
			  }
			      	
			  $iresponse=(int)$ds_response;

		      	
        //
        // El Ciberpac cuando se hace un pago por transferencia, por
        //  por motivos de seguridad siempre va a urlKO y nos devuelve un error 930
        //  que quiere decir que el pago queda pendiente y el usuario lo realizará manualmente
			  if (($iresponse>=0) && ($iresponse<=99) || $iresponse==930)
			  {
            //Es importante añadir esta información, pues de lo contrario no hay seguridad de que el usuario haya pagado
            // y tener ojo con los programas descargables.
						if ($iresponse==930) {
              $order->info['payment_method']=MODULE_PAYMENT_SERVIRED_TRANSFER_TEXT_TITLE;
						}
           	$recuperar_sesion = tep_session_id();  // Recojemos la sesion para recuperarla al volver con la notificacion, genere el pedido y vacie el carrito	
						echo 'Pedido realizado correctamente '.'<meta http-equiv="Refresh" content="0;url='. tep_href_link(FILENAME_CHECKOUT_SUCCESS, session_name().'='.$recuperar_sesion, 'SSL', true, false).'" />';
			  }
			  else
			  {
          //Aquí van los distintos códigos de error de Servired,
          // Ojo que hay muchos más, consultar manual.
          // sino se ajusta a uno de estos errores se dará error desconocido
          // junto su código.
			  	$errors=array();
			  	$errors[101]=MODULE_PAYMENT_SERVIRED_ERROR_101;
			  	$errors[102]=MODULE_PAYMENT_SERVIRED_ERROR_102;  	
			  	$errors[107]=MODULE_PAYMENT_SERVIRED_ERROR_107;  	
			  	$errors[180]=MODULE_PAYMENT_SERVIRED_ERROR_180;  	
			  	$errors[184]=MODULE_PAYMENT_SERVIRED_ERROR_184;  	
			  	$errors[190]=MODULE_PAYMENT_SERVIRED_ERROR_190;  	
			  	$errors[201]=MODULE_PAYMENT_SERVIRED_ERROR_201;  	
			  	$errors[202]=MODULE_PAYMENT_SERVIRED_ERROR_202;  	
					$errors[290]=MODULE_PAYMENT_SERVIRED_ERROR_290;
					$errors[909]=MODULE_PAYMENT_SERVIRED_ERROR_909;
					$errors[912]=MODULE_PAYMENT_SERVIRED_ERROR_912;
					$errors[913]=MODULE_PAYMENT_SERVIRED_ERROR_913;
					$errors[949]=MODULE_PAYMENT_SERVIRED_ERROR_949;
					$errors[9111]=MODULE_PAYMENT_SERVIRED_ERROR_9111;
					$errors[9093]=MODULE_PAYMENT_SERVIRED_ERROR_9093;
					$errors[9112]=MODULE_PAYMENT_SERVIRED_ERROR_9112;
					
			    //Transacción denegada
			    $error=$errors[$iresponse];
			    if ($error=="") $error=MODULE_PAYMENT_SERVIRED_TEXT_UNKNOW_ERROR." ($iresponse)";
    
						echo $error.'<meta http-equiv="Refresh" content="2;url='. tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode($error), 'SSL', true, false).'" />';
			      die();  
			  }


    }
    
    //
    // Aqui se puede implementar cualquier postproceso del pago/pedido. En este
    //  punto se dispone de una referencia id de pedido creada en osCommerce.
    // Se podría por ejemplo actualizar información en una base de datos propia
    //  de la aplicación del comercio (ej: insertar una orden de trabajo/factura en el programa de facturación).
    //
    function after_process()
    {
			return false;
    }

    //
    // Manejo de errores avanzados. *** Ojo puedo confundirme con get_error()
    //
    // Cuendo en el modulo de pago se produce un error se desea redireccionar al
    //  cliente a la página checkout_payment.php junto con información sobre el 
    //  error.
    //
    // La redirección debería ser algo del tipo:
    //
    //  tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error='.$this->code.'&error='.urlencode('algún error'),'NOSSL',true,false));
    //
    // En este módulo no se utiliza, pues se le indica a la pasarela de pago donde ha de redireccionar
    //  cuando la cosa ha ido bien y donde ha de ir cuando la cosa ha de ir mal (Ds_Merchant_UrlOK y Ds_Merchant_UrlKO).
    //
    // Esta función es llamada desde checkout_payment.php
    function get_error() {
      return false;
    }

    //
    // Función estándar de osCommerce que permite saber si un módulo está o no
    //  instalado. Devuelve true si está instalado.
    //
    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_SERVIRED_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }


    //
    // Función estándar de osCommerce que es llamada desde el administrador para instalar el módulo.
    function install() {

			//Para español
		  define('MODULE_PAYMENT_SERVIRED_STATUS_TITLE','Activar módulo Servired');
	  	define('MODULE_PAYMENT_SERVIRED_STATUS_DESCRIPTION','¿Quiere aceptar pagos usando Servired?');
		  define('MODULE_PAYMENT_SERVIRED_ID_COM_TITLE','ID Comercio');
		  define('MODULE_PAYMENT_SERVIRED_ID_COM_DESCRIPTION','Código de comercio proporcionado por la entidad bancaria');
		  define('MODULE_PAYMENT_SERVIRED_ID_SEED_TITLE','Clave de Encriptación');
		  define('MODULE_PAYMENT_SERVIRED_ID_SEED_DESCRIPTION','Clave de encriptación proporcionada por la entidad bancaria');
		  define('MODULE_PAYMENT_SERVIRED_TERMINAL_TITLE','Terminal');
		  define('MODULE_PAYMENT_SERVIRED_TERMINAL_DESCRIPTION','Numero de terminal: <br> XXXXXXXX-001 = terminal 1 <br>XXXXXXXX-002 = Terminal 2');
		  define('MODULE_PAYMENT_SERVIRED_URL_TITLE','URL de la pasarela de pago');
		  define('MODULE_PAYMENT_SERVIRED_URL_DESCRIPTION','Dirección en internet de la pasarela de pago');
	  	define('MODULE_PAYMENT_SERVIRED_ADMIN_TITLE','URL del Admin de la pasarela');
	  	define('MODULE_PAYMENT_SERVIRED_ADMIN_DESCRIPTION','Dirección en internet del admin del TPV');
		  define('MODULE_PAYMENT_SERVIRED_PAYMODE_TITLE','Forma de pago');
		  define('MODULE_PAYMENT_SERVIRED_PAYMODE_DESCRIPTION', 'Forma de pago del TPV. T=Tarjeta, R=Transferencia y D=Domiciliación.');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_STATUS_TITLE','Email para fase de pruebas');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_STATUS_DESCRIPTION','¿Quiere admitir solo email fase de pruebas?');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_TITLE','Mail de pruebas');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_DEFAULT', 'tuemail@dominio.com');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_DESCRIPTION','Mail para fase de pruebas');
		  define('MODULE_PAYMENT_SERVIRED_SORT_ORDER_TITLE','Orden de aparicion.');
		  define('MODULE_PAYMENT_SERVIRED_SORT_ORDER_DESCRIPTION','Orden de aparicion. Número menor es mostrado antes que los mayores.');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ZONE_TITLE','Zona de pago');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ZONE_DESCRIPTION','Si selecciona una zona, este módulo sólo estará disponible en esa zona.');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ORDER_STATUS_ID_TITLE','Estado del pedido');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ORDER_STATUS_ID_DESCRIPTION','Seleccione el estado del pedido un vez procesado con este módulo');

			//For english uncomment
			/*
		  define('MODULE_PAYMENT_SERVIRED_STATUS_TITLE','Activate Servired Module');
  		define('MODULE_PAYMENT_SERVIRED_STATUS_DESCRIPTION','Do you want accept Servired payment?');
		  define('MODULE_PAYMENT_SERVIRED_ID_COM_TITLE','Commerce ID');
		  define('MODULE_PAYMENT_SERVIRED_ID_COM_DESCRIPTION','Identification code supply by bank entity');
		  define('MODULE_PAYMENT_SERVIRED_ID_SEED_TITLE','Encription key');
		  define('MODULE_PAYMENT_SERVIRED_ID_SEED_DESCRIPTION','Encription key supply fron bank entity');
		  define('MODULE_PAYMENT_SERVIRED_TERMINAL_TITLE','Terminal');
		  define('MODULE_PAYMENT_SERVIRED_TERMINAL_DESCRIPTION','Terminal number: <br> XXXXXXXX-001 = terminal 1 <br>XXXXXXXX-002 = Terminal 2');
		  define('MODULE_PAYMENT_SERVIRED_URL_TITLE','payment gate URL ');
		  define('MODULE_PAYMENT_SERVIRED_URL_DESCRIPTION','Internet address of payment gate');
		  define('MODULE_PAYMENT_SERVIRED_ADMIN_TITLE','Admin payment gate URL');
		  define('MODULE_PAYMENT_SERVIRED_ADMIN_DESCRIPTION','Internet address of TPVV admin');
		  define('MODULE_PAYMENT_SERVIRED_PAYMODE_TITLE','Payment allowed');
		  define('MODULE_PAYMENT_SERVIRED_PAYMODE_DESCRIPTION', 'TPVV payment allowed. T=Credit Card, R=Bank transfer y D=Domiciliation.');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_STATUS_TITLE','Email for test period');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_STATUS_DESCRIPTION','Only accept test email user?');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_TITLE','User test email');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_DEFAULT', 'yourmail@domain.com');
		  define('MODULE_PAYMENT_SERVIRED_MAIL_DESCRIPTION','Mail for test phase');
		  define('MODULE_PAYMENT_SERVIRED_SORT_ORDER_TITLE','Appearance order.');
		  define('MODULE_PAYMENT_SERVIRED_SORT_ORDER_DESCRIPTION','Appearance. Small numbers goes first.');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ZONE_TITLE','Payment zone');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ZONE_DESCRIPTION','If you select a zone, this module only will be available there.');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ORDER_STATUS_ID_TITLE','Order status');
		  define('MODULE_PAYMENT_SERVIRED_SERVIRED_ORDER_STATUS_ID_DESCRIPTION','Select status after module process order');
		*/

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('" . MODULE_PAYMENT_SERVIRED_STATUS_TITLE . "', 'MODULE_PAYMENT_SERVIRED_STATUS', 'True', '" . MODULE_PAYMENT_SERVIRED_STATUS_DESCRIPTION . "', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_ID_COM_TITLE."', 'MODULE_PAYMENT_SERVIRED_ID_COM', '', '".MODULE_PAYMENT_SERVIRED_ID_COM_DESCRIPTION."', '6', '1', now())");

      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_ID_SEED_TITLE."', 'MODULE_PAYMENT_SERVIRED_ID_SEED', 'qwertyasdf0123456789', '".MODULE_PAYMENT_SERVIRED_ID_SEED_DESCRIPTION."', '6', '2', now())");      
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_TERMINAL_TITLE."', 'MODULE_PAYMENT_SERVIRED_TERMINAL', '1', '".MODULE_PAYMENT_SERVIRED_TERMINAL_DESCRIPTION."', '6', '3', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_URL_TITLE."', 'MODULE_PAYMENT_SERVIRED_URL', 'https://sis-t.sermepa.es:25443/sis/realizarPago', '".MODULE_PAYMENT_SERVIRED_URL_DESCRIPTION."', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_ADMIN_TITLE."', 'MODULE_PAYMENT_SERVIRED_ADMIN', 'https://sis-t.sermepa.es:25443/canales/lacaixa', '".MODULE_PAYMENT_SERVIRED_ADMIN_DESCRIPTION."', '6', '5', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_PAYMODE_TITLE."', 'MODULE_PAYMENT_SERVIRED_PAYMODE', 'TR', '".MODULE_PAYMENT_SERVIRED_PAYMODE_DESCRIPTION."', '6', '6', now())");				               
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('".MODULE_PAYMENT_SERVIRED_MAIL_STATUS_TITLE."', 'MODULE_PAYMENT_SERVIRED_MAIL_STATUS', 'True', '".MODULE_PAYMENT_SERVIRED_MAIL_STATUS_DESCRIPTION."', '6', '7', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_MAIL_TITLE."', 'MODULE_PAYMENT_SERVIRED_MAIL', '".MODULE_PAYMENT_SERVIRED_MAIL_DEFAULT."', '".MODULE_PAYMENT_SERVIRED_MAIL_DESCRIPTION."', '6', '8', now())");				               
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('".MODULE_PAYMENT_SERVIRED_SORT_ORDER_TITLE."', 'MODULE_PAYMENT_SERVIRED_SORT_ORDER', '0', '".MODULE_PAYMENT_SERVIRED_SORT_ORDER_DESCRIPTION."', '6', '9', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('".MODULE_PAYMENT_SERVIRED_SERVIRED_ZONE_TITLE."', 'MODULE_PAYMENT_SERVIRED_ZONE', '0', '".MODULE_PAYMENT_SERVIRED_SERVIRED_ZONE_DESCRIPTION."', '6', '10', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('".MODULE_PAYMENT_SERVIRED_SERVIRED_ORDER_STATUS_ID_TITLE."', 'MODULE_PAYMENT_SERVIRED_ORDER_STATUS_ID', '0', '".MODULE_PAYMENT_SERVIRED_SERVIRED_ORDER_STATUS_ID_DESCRIPTION."', '6', '11', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
		}

    //
    // Función estándar de osCommerce que es llamada desde el administrador para desinstalar un módulo.
    // Básicamente lo que hace es borrar de la tabla de configuración las claves de este módulo
    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

		//
    // Función que devuelve un array con todas las claves utilizadas por este módulo.
    function keys() {
      return array
      (
      'MODULE_PAYMENT_SERVIRED_STATUS',
      'MODULE_PAYMENT_SERVIRED_ID_COM',
      'MODULE_PAYMENT_SERVIRED_ID_SEED',
      'MODULE_PAYMENT_SERVIRED_TERMINAL',
      'MODULE_PAYMENT_SERVIRED_URL',
      'MODULE_PAYMENT_SERVIRED_ADMIN',
      'MODULE_PAYMENT_SERVIRED_PAYMODE',
      'MODULE_PAYMENT_SERVIRED_MAIL_STATUS',
      'MODULE_PAYMENT_SERVIRED_MAIL',
      'MODULE_PAYMENT_SERVIRED_SORT_ORDER',
      'MODULE_PAYMENT_SERVIRED_ZONE',
      'MODULE_PAYMENT_SERVIRED_ORDER_STATUS_ID'
      );
    }
  }
  
//
// Clase auxiliar proporcionada por el banco que implementa
//  el algoritmo SHA-1 (Secure Hash Algorithm).
class SHA1 {
        var $A, $B, $C, $D, $E;
        var $ta, $tb, $tc, $td, $te;
        var $K0_19, $K20_39, $K40_59, $K60_79;

        var $buffer;
        var $buffsize;
        var $totalsize;

        function SHA () {
                $this->init();
        }

        function init () {
                $this->A = 0x6745 << 16 | 0x2301;
                $this->B = 0xefcd << 16 | 0xab89;
                $this->C = 0x98ba << 16 | 0xdcfe;
                $this->D = 0x1032 << 16 | 0x5476;
                $this->E = 0xc3d2 << 16 | 0xe1f0;
                $this->ta = $this->A;
                $this->tb = $this->B;
                $this->tc = $this->C;
                $this->td = $this->D;
                $this->te = $this->E;
                $this->K0_19 = 0x5a82 << 16 | 0x7999;
                $this->K20_39 = 0x6ed9 << 16 | 0xeba1;
                $this->K40_59 = 0x8f1b << 16 | 0xbcdc;
                $this->K60_79 = 0xca62 << 16 | 0xc1d6;

                $this->buffer = array();
                $this->buffsize = 0;
                $this->totalsize = 0;
        }

        function bytes_to_words( $block ) {
                $nblk = array();
                for( $i=0; $i<16; ++$i) {
                        $index = $i * 4;
                        $nblk[$i] = 0;
                        $nblk[$i] |= ($block[$index] & 0xff) << 24;
                        $nblk[$i] |= ($block[$index+1] & 0xff) << 16;
                        $nblk[$i] |= ($block[$index+2] & 0xff) << 8;
                        $nblk[$i] |= ($block[$index+3] & 0xff);
                }
                return $nblk;
        }

        function pad_block( $block, $size ) {
                $blksize = sizeof( $block );
                $bits = $size * 8;

                $newblock = $block;
                $newblock[] = 0x80;
                while((sizeof($newblock) % 64) != 56) {
                        $newblock[] = 0;
                }
                for ($i=0; $i<8; ++$i) {
                        $newblock[] = ($i<4) ? 0 : ($bits >> ((7-$i)*8)) &0xff;
                }

                return $newblock;
        }

        function circ_shl( $num, $amt ) {
                $leftmask = 0xffff | (0xffff << 16);
                $leftmask <<= 32 - $amt;
                $rightmask = 0xffff | (0xffff << 16);
                $rightmask <<= $amt;
                $rightmask = ~$rightmask;

                $remains = $num & $leftmask;
                $remains >>= 32 - $amt;
                $remains &= $rightmask;

                $res = ($num << $amt) | $remains;

                return $res;
        }

        function f0_19( $x, $y, $z ) {
                return ($x & $y) | (~$x & $z);
        }

        function f20_39( $x, $y, $z ) {
                return ($x ^ $y ^ $z);
        }

        function f40_59( $x, $y, $z ) {
                return ($x & $y) | ($x & $z) | ($y & $z);
        }

        function f60_79( $x, $y, $z ) {
                return $this->f20_39( $x, $y, $z );
        }

        function expand_block( $block ) {
                $nblk = $block;
                for( $i=16; $i<80; ++$i ) {
                        $nblk[$i] = $this->circ_shl(
                                         $nblk[$i-3] ^ $nblk[$i-8] ^ $nblk[$i-14] ^ $nblk[$i-16], 1
                                );
                }

                return $nblk;
        }

        function print_bytes( $bytes ) {
                $len = sizeof( $bytes );
                for( $i=0; $i<$len; ++$i) {
                        $str[] = sprintf(  "%02x", $bytes[$i] );
                }

                print( join(  ", ", $str ) .  "\n" );
        }

        function wordstr( $word ) {
                return sprintf(
                         "%04x%04x", ($word >> 16) & 0xffff, $word & 0xffff
                        );
        }

        function print_words( $words ) {
                $len = sizeof( $words );
                for( $i=0; $i<$len; ++$i) {
                        $str[] = $this->wordstr( $words[$i] );
                }

                print( join(  ", ", $str ) .  "\n" );
        }

        function hash_to_string( $hash ) {
		$astr="";	// albert.martin(a)gmail.com -> si no se inicializa da un notice (tienes que tener el nivel de error reporting alto / E_ALL )
                $len = sizeof( $hash );
                for ($i=0; $i<$len; ++$i) {
                        $astr .= $this->wordstr( $hash[$i] );
                }
                return $astr;
        }

        function add( $a, $b ) {
                $ma = ($a >> 16) & 0xffff;
                $la = ($a) & 0xffff;
                $mb = ($b >> 16) & 0xffff;
                $lb = ($b) & 0xffff;

                $ls = $la + $lb;
                 // Carry
                if ($ls > 0xffff) {
                        $ma += 1;
                        $ls &= 0xffff;
                }

                $ms = $ma + $mb;
                $ms &= 0xffff;

                $result = ($ms << 16) | $ls;
                return $result;
        }

        function process_block( $blk ) {
                $blk = $this->expand_block( $blk );

                for( $i=0; $i<80; ++$i ) {
                        $temp = $this->circ_shl( $this->ta, 5 );
                        if ($i<20) {
                                $f = $this->f0_19( $this->tb, $this->tc,$this->td );
                                $k = $this->K0_19;
                        }
                        elseif ($i<40) {
                                $f = $this->f20_39( $this->tb, $this->tc,$this->td );
                                $k = $this->K20_39;
                        }
                        elseif ($i<60) {
                                $f = $this->f40_59( $this->tb, $this->tc,$this->td );
                                $k = $this->K40_59;
                        }
                        else {
                                $f = $this->f60_79( $this->tb, $this->tc,$this->td );
                                $k = $this->K60_79;
                        }

                        $temp = $this->add( $temp, $f );
                        $temp = $this->add( $temp, $this->te );
                        $temp = $this->add( $temp, $blk[$i] );
                        $temp = $this->add( $temp, $k );

                        $this->te = $this->td;
                        $this->td = $this->tc;
                        $this->tc = $this->circ_shl( $this->tb, 30 );
                        $this->tb = $this->ta;
                        $this->ta = $temp;
                }

                $this->A = $this->add( $this->A, $this->ta );
                $this->B = $this->add( $this->B, $this->tb );
                $this->C = $this->add( $this->C, $this->tc );
                $this->D = $this->add( $this->D, $this->td );
                $this->E = $this->add( $this->E, $this->te );
        }

        function update ( $bytes ) {
                $length = sizeof( $bytes );
                $index = 0;

                while (($length - $index) + $this->buffsize >= 64) {
                        for( $i=$this->buffsize; $i<64; ++$i) {
                                $this->buffer[$i] = $bytes[$index + $i -$this->buffsize];
                        }
                        $this->process_block( $this->bytes_to_words( $this->buffer ) );
                        $index += 64;
                        $this->buffsize = 0;
                }

                $remaining = $length - $index;
                for( $i=0; $i<$remaining; ++$i) {
                        $this->buffer[$this->buffsize + $i] = $bytes[$index+ $i];
                }
                $this->buffsize += $remaining;
                $this->totalsize += $length;
        }

        function finalizar () {
                for( $i=0; $i<$this->buffsize; ++$i) {
                        $last_block[$i] = $this->buffer[$i];
                }
                $this->buffsize = 0;
                $last_block = $this->pad_block( $last_block,$this->totalsize );
                $index = 0;
                $length = sizeof( $last_block );
                while( $index < $length )
                {
                        $block = array();
                        for( $i=0; $i<64; ++$i) {
                                $block[$i] = $last_block[$i + $index];
                        }
                        $this->process_block( $this->bytes_to_words( $block) );
                        $index += 64;
                }

                $result[0] = $this->A;
                $result[1] = $this->B;
                $result[2] = $this->C;
                $result[3] = $this->D;
                $result[4] = $this->E;

                return $result;
        }

        function hash_bytes( $bytes ) {
                $this->init();
                $this->update( $bytes );
                return $this->finalizar();
        }

        function hash_string( $str ) {
                $len = strlen( $str );
                for($i=0; $i<$len; ++$i) {
                        $bytes[] = ord( $str[$i] ) & 0xff;
                }
                return $this->hash_bytes( $bytes );
        }
}  
?>
