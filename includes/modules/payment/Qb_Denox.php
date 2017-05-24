<?php
/*
  Módulo de págo de la pasarela 4B 
  Author:
  Denox
  http://www.Denox.es
  
  Copyright (c) 2008 Denox
  Released under the GNU General Public License  
*/

class Qb_Denox {
	var $code, $title, $description, $enabled;
	
	function __construct() {
		global $order;		
		$this->code        = 'Qb_Denox';
		$this->title       = MODULE_PAYMENT_4B_TEXT_TITLE;
		$this->description = MODULE_PAYMENT_4B_TEXT_DESCRIPTION;
		$this->sort_order  = MODULE_PAYMENT_4B_SORT_ORDER;
		$this->enabled     = ((MODULE_PAYMENT_4B_STATUS == 'True') ? true : false);
		$this->debug = true;
		if(MODULE_PAYMENT_4B_URL_TYPE=='Real'){
				$this->form_action_url = "https://sis.redsys.es/sis/realizarPago";
		}else{
				$this->form_action_url = "https://tpv2.4b.es/simulador/teargral.exe";
		}
		
		if ((int)MODULE_PAYMENT_4B_ORDER_STATUS_ID > 0) {
			$this->order_status = MODULE_PAYMENT_4B_ORDER_STATUS_ID;
		}
		if (is_object($order)) $this->update_status();		
	}
	
// class methods	
	function trace($log){
		if(!$this->debug)
			return;
		$fp = fopen (DIR_FS_CATALOG . '/includes/modules/payment/4b.log', "a+");
		fwrite($fp,date("Y-m-d H:i:s")." - ".$log."\n");
		fclose($fp);
	}
	
	function update_status() {
		global $order, $db;

		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_4B_ZONE > 0) ) {
			$check_flag = false;
			$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_4B_ZONE . "' and zone_country_id = '" . $order->billing['country']['id'] . "' order by zone_id");
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
	}
	
    function javascript_validation() {
      return false;
    }

   function selection() {
     return array(
				'id' => $this->code,
				'module' => $this->title,
				'fields' => array(array('title' => MODULE_PAYMENT_4B_DESCRIPCION_PUBLICA, 'field' => '')));
   }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
			return false;
    }

	function process_button() {
		global $order,$db;			
      switch ($language){
        case "french":
          $Idioma="fr";
          break;          
        case "german":
          $Idioma="de";
          break;          
        case "english":
          $Idioma="en";
          break;
        case "catalan":
          $Idioma="ca";
          break;					
        default:
          $Idioma="es";
      }
			$gv_query= "insert into Qb_Denox (pedido) values ('".urlencode(serialize($order))."')";
      $gv = tep_db_query($gv_query);
      $Qb_order_ref = tep_db_insert_id();
			
		  $process_button_string .= tep_draw_hidden_field('order',$Qb_order_ref);
		  $process_button_string .= tep_draw_hidden_field('id_comercio',MODULE_PAYMENT_4B_CLAVE_DE_COMERCIO); 
		  $process_button_string .= tep_draw_hidden_field('Idioma',$Idioma);	  			
			return $process_button_string;
    }

    function before_process() {
			global $order, $db;
      $Qb_order_ref=$_REQUEST['pszPurchorderNum'];
      $aaa = "select resultado, deserror from Qb_Denox where id='".$Qb_order_ref."'";
			$this->trace( $aaa);
      $rowq = tep_db_query($aaa);
      while ($rows=tep_db_fetch_array($rowq)){
			 $this->trace("$Qb_order_ref  $rows[resultado] ");
       $deserror=$rows[deserror];
       if ($Qb_order_ref!="" && $rows[resultado]==0)
       {
    	 //The order has been succesfully paid
      	 return false;    		
       }
      }
      //Payment unsuccesful
      tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'error_message=' . urlencode("Hubo un error procesando el pago, por favor, intente de nuevo o contacte con el comercio (ERROR: ".$deserror.")"), 'SSL', true, false));    		
      exit;
		}
		
    function after_process() {
      global $insert_id;
    }

    function get_error() {

      $error = array('title' => MODULE_PAYMENT_4B_TEXT_ERROR,
                     'error' => stripslashes(urldecode($_GET['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_4B_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
			tep_db_query("CREATE TABLE if not exists Qb_Denox (".
				 "id INT (12) UNSIGNED NOT NULL AUTO_INCREMENT,".
				 "pedido TEXT NOT NULL,".
	       "resultado INT DEFAULT '666',".
	       "pszTxnID VARCHAR( 64 ) ,".
				 "pszTxnDate VARCHAR( 32 ) ,".			 
				 "authcode TEXT,".
	       "deserror VARCHAR( 255 ) DEFAULT 'No se ha obtenido notificación del pago desde e-tpv.com',".
				 "PRIMARY KEY(id), UNIQUE(id), INDEX(id)) ");

			//limpiamos la tabla de pedidos, la siguiente línea se puede comentar si se desea mayor persistencia
			tep_db_query("delete from Qb_Denox");	
			
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Activar módulo', 'MODULE_PAYMENT_4B_STATUS', 'True', 'Quiere activar este módulo?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Entorno Pasat 4B', 'MODULE_PAYMENT_4B_URL_TYPE', 'Real', 'No olvide poner el entorno en \"Real\" para comenzar a vender!!', '6', '1', 'tep_cfg_select_option(array(\'Real\', \'Pruebas\'), ', now())");			
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Preferencia ordinal al mostrar.', 'MODULE_PAYMENT_4B_SORT_ORDER', '0', 'Preferencia ordinal en la lista de formas de pago. Un número menor indica mayor preferencia. ', '6', '0' , now())");
			tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Clave de Comercio 4B', 'MODULE_PAYMENT_4B_CLAVE_DE_COMERCIO', '', 'La clave de comercio proporcionada por el sistema 4B (es necesaria para que funcione).', '6', '0', now())");
			// tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Idioma', 'MODULE_PAYMENT_4B_IDIOMA', 'es', 'Indique un código de idioma de dos letras. El sistema 4B soporta los siguientes idiomas: es (Castellano), ca (Catalán), en (Inglés), fr (Francés), de (Alemán)', '6', '1', now())");
	      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Estado de los pedidos', 'MODULE_PAYMENT_4B_ORDER_STATUS_ID', '0', 'Los pedidos pagados por este método, se pondrán a este estado.', '6', '12', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
 	      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Zona de pagos', 'MODULE_PAYMENT_4B_ZONE', '0', 'Si se selecciona una zona, este módulo solo estará disponible para esa zona.', '6', '4', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
			
    }

	function remove() {
		tep_db_query("drop table if exists Qb_Denox");
		tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
	}

	function keys() {
		return array(
			'MODULE_PAYMENT_4B_STATUS',
			'MODULE_PAYMENT_4B_SORT_ORDER',
			'MODULE_PAYMENT_4B_CLAVE_DE_COMERCIO',
			'MODULE_PAYMENT_4B_ORDER_STATUS_ID',
			'MODULE_PAYMENT_4B_URL_TYPE',
			'MODULE_PAYMENT_4B_ZONE');
	}
		
  function normalizar ($precio){
		//convertimos un precio "XX.XX" a "XXXX"
		//si no hay . añadimos dos ceros al final
		$precio2 = "";
		if (!strpos($precio,".")){
			$precio2 = $precio . "00";
		} else {
			$pos_punto = strpos($precio,".");
			$decimales = substr($precio,1+$pos_punto);
		    $num_decimales = strlen($decimales);
			$precio2 = substr($precio,0,$pos_punto) . 
			( $num_decimales < 2 ? ( $num_decimales==0 ? "00":
			$decimales . "0") : substr($decimales,0,2) );
		}
		return $precio2;
  }  

  function escapar ($str){
		return str_replace("'", "\\'", $str);
	}		
	
	function show_order(){
		//código que devuelve el desglose de la compra al sistema passat
		if (isset($_REQUEST['order']) /*&& isset($_REQUEST['store'])*/){	
			$this->trace($_REQUEST['order'] . ".\n-\n" . $_REQUEST['store'] . ".\n---");

			//guardamos las variables que pasa el sistema passat		
			$order = $_REQUEST['order'];
			$store = $_REQUEST['store'];
	   
	   	//leemos la clave de comercio guardada
			$this->trace(MODULE_PAYMENT_4B_CLAVE_DE_COMERCIO . ":" . $order . ":" . $store);
		
			//comprobaciones de seguridad e integridad:
					 
			//comprobamos el código de comercio		
			if ($store != MODULE_PAYMENT_4B_CLAVE_DE_COMERCIO){
				$this->trace("Clave de comercio erronea");
				//return;
			}
		
			//todo ok
			$pedido_a = tep_db_fetch_array(tep_db_query("select pedido from Qb_Denox where id = '$order' limit 1"));
            $pedido_s = urldecode($pedido_a['pedido']);
			require_once(DIR_WS_CLASSES.'order.php');
			$pedido = unserialize($pedido_s);
			$total = $pedido->info['total'] * $pedido->info['currency_value'];
			//this->trace("total del pedido: $total objeto: $pedido .\nserie: ". $pedido_s);

			//el precio iso es M (money) + el código del euro (978) + el precio normalizado
			//(ver funcion normalizar)
		
			$precioiso = "M978" . $this->normalizar($total);

			$productos = count($pedido->products);
		
			$this->trace("el precio es $precioiso y hay $productos productos en cesta");
				
			echo $precioiso . "\n";
			$this->trace(">>" . $precioiso);
			echo $productos . "\n";
			$this->trace(">>" . $productos);

			foreach ($pedido->products as $producto){
				$id_producto = "";
				$arrid = tep_db_query("select products_id from products_description where products_name = '" . $this->escapar($producto['name']) . "' limit 1");
				$id_producto = $arrid['products_id'];
				if ($id_producto == "") $id_producto = "VOID";
				echo "T". $id_producto . "\n";
				$this->trace(">>" . "T". $id_producto);
				echo $producto['name'] . "\n";
				$this->trace(">>" . $producto['name']);
				echo $producto['qty'] . "\n";
				$this->trace(">>" . $producto['qty']);
				echo $this->normalizar($producto['final_price']) . "\n";		   		   		   
				$this->trace(">>" . $this->normalizar($producto['final_price']));
			}	  		
		}
	}
	
	function save_result(){
			$this->trace("Desde: ".$_SERVER['REMOTE_ADDR']." pide:".$_SERVER['REQUEST_URI'].":\n" .		
			$_REQUEST['result'] . "\n" .
			$_REQUEST['pszPurchorderNum'] . "\n" .
			$_REQUEST['pszTxnId'] . "\n" .			
			$_REQUEST['pszTxnDate'] . "\n" .
			$_REQUEST['tipotrans'] . "\n" .
			$_REQUEST['store'] . "\n" .
			$_REQUEST['coderror'] . "\n" .
			$_REQUEST['deserror']);
			$sql = "update Qb_Denox set resultado = '".$_REQUEST['result']."', pszTxnID='".$_REQUEST['pszTxnDate']."', deserror='".$_REQUEST['deserror']."' where id='".$_REQUEST['pszPurchorderNum']."'";
      tep_db_query($sql);   
	}
}
?>
