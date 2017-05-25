<?php

  class mrw1 {
    var $code, $title, $description, $enabled, $num_zones;

// class constructor
    function __construct() {
	  global $order;
      $this->code = 'mrw1';
      $this->title = MODULE_MRW1_TEXT_TITLE;
      $this->description = MODULE_MRW1_TEXT_TITLE;
      $this->sort_order = MODULE_mrw1_SORT_ORDER;
      $this->icon = 'mrw.png';
      $this->icon = DIR_WS_ICONS . 'mrw.png';
      $this->tax_class = MODULE_mrw1_TAX_CLASS;
      $this->enabled = ((MODULE_mrw1_STATUS == 'True') ? true : false);

      // CONFIGURE ESTE PAR?METRO PARA ESTABLECER EL N?MERO DE ZONAS NECESARIAS
      $this->num_zones = 1;
//INICIO
/*	$dest_zone = 0;
	$aux='0';
      for ($i=1; $i<=$this->num_zones; $i++) {
        $countries_table = constant('MODULE_mrw1_COUNTRIES_' . $i);
        if ( ($this->enabled == true) && ((int)constant('MODULE_mrw1_COUNTRIES_' . $i) > 0) ) {
			$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . $countries_table . "' and (zone_country_id = '" . $order->delivery['country']['id'] . "' or zone_country_id='0') order by zone_id");
			while ($check = tep_db_fetch_array($check_query)) {
			  if ( ($check['zone_id'] < 1) || ($check['zone_id'] == $order->delivery['zone_id']) ) {
			    $dest_zone = $i;
				break;
			  }
			}
		}
	}
if($dest_zone=='0'){
	$this->enabled = false;
}else{
	$this->enabled = true;
}*/
//FIN

	}

// class methods
    function quote($method = '') {
     global $order, $shipping_weight, $shipping_num_boxes;

     $dest_zone = 0;
     $error = false;
	 $zones_weight_cost = 0;
     //si el peso del env?o es menor o igual de 31 Kg intentar realizar el env?o
	 if ($shipping_weight < 100000)
	 { 
      for ($i=1; $i<=$this->num_zones; $i++) {
        $countries_table = constant('MODULE_mrw1_COUNTRIES_' . $i);
        if ( ($this->enabled == true) && ((int)constant('MODULE_mrw1_COUNTRIES_' . $i) > 0) ) {
			$check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . $countries_table . "' and (zone_country_id = '" . $order->delivery['country']['id'] . "' or zone_country_id='0') order by zone_id");
			while ($check = tep_db_fetch_array($check_query)) {
			  if ( ($check['zone_id'] < 1) || ($check['zone_id'] == $order->delivery['zone_id']) ) {
			    $dest_zone = $i;
				break;
			  }
			}
      
      // edited by solomono:
      if($dest_zone==0) $dest_zone = 1; // Spain as default zone
      
	        if ($dest_zone > 0) {
				$shipping = -1;
				$zones_cost = constant('MODULE_mrw1_COST_' . $dest_zone);
				$zones_cost_table = preg_split("/[:,]/" , $zones_cost);
				$size = sizeof($zones_cost_table);
				for ($j=0;$j<$size; $j+=2) {				  
				  if ($shipping_weight < $zones_cost_table[$j])
				  {
					$shipping = 1;					
					//obtener el precio de env?o por kg para esa zona
					$zones_weight_cost = $zones_cost_table[$j+1];
					$shipping_method = MODULE_MRW1_TEXT_DESCRIPTION  . ' ' . $order->delivery['']['title'];
					break;
				  }
				}
				//Fin Forma 2
				if ($shipping == -1) {
				  $shipping_cost = 0;
				  $shipping_method = MODULE_mrw1_UNDEFINED_RATE;
				} else {
				  $shipping_cost = $zones_weight_cost + constant('MODULE_mrw1_HANDLING_' . $dest_zone);
			      break;
				}

            }
		}
       }

       if ($dest_zone == 0) {
        $error = true;
       }

       $this->quotes = array('id' => $this->code,
                            'module' => MODULE_MRW1_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => $shipping_method,
                                                     'cost' => $shipping_cost)));

       if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
       }

       if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title, '80', '40');
	   
	   if ($error == true) $this->quotes['error'] = MODULE_mrw1_INVALID_ZONE;
      }
	  else //el peso es mayor de 31 Kg
	  {
	   $error = true;
	   $this->quotes['module'] = NACEX;
	   $this->quotes['error'] = MODULE_mrw1_OVER_WEIGHT;
	  }      

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_mrw1_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Activar mrw1 ?Pack', 'MODULE_mrw1_STATUS', 'True', '&iquest;Quiere activar el m&oacute;dulo de env&iacute;os mrw1 EuroPack?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tipo Impuesto', 'MODULE_mrw1_TAX_CLASS', '0', 'Utilizar el siguiente tipo de impuesto para aplicar al env&iacute;o..', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Orden Visualizaci&oacute;n', 'MODULE_mrw1_SORT_ORDER', '0', 'El menor se visualiza primero.', '6', '0', now())");
      for ($i = 1; $i <= $this->num_zones; $i++) {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Zona " . $i ."', 'MODULE_mrw1_COUNTRIES_" . $i ."', '0', 'Debe seleccionar una Zona de Impuestos para activar el m&eacute;todo de env&iacute;o sobre esta zona" . $i . ".', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Tabla Env&iacute;os', 'MODULE_mrw1_COST_" . $i ."', '3:13.46,10:15.55,15:17.64,20:21.09,31:24.54', 'Tarifas Env&iacute;o para la zona " . $i . ". Precios basados por grupos de peso. Ejemplo: 3:13.46,10:15.55,... Pedidos con Peso < 3 tienen 13.46 Euros de gastos de env&iacute;o. Pedidos con Peso >= 3 y < 10 tienen 15.55 euros de gastos de env&iacute; para la Zona " . $i . ".', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Zona " . $i ." Handling Fee', 'MODULE_mrw1_HANDLING_" . $i."', '0', 'Handling Fee para esta zona', '6', '0', now())");
      }
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      $keys = array('MODULE_mrw1_STATUS', 'MODULE_mrw1_TAX_CLASS', 'MODULE_mrw1_SORT_ORDER');

      for ($i=1; $i<=$this->num_zones; $i++) {
        $keys[] = 'MODULE_mrw1_COUNTRIES_' . $i;
        $keys[] = 'MODULE_mrw1_COST_' . $i;
        $keys[] = 'MODULE_mrw1_HANDLING_' . $i;
      }

      return $keys;
    }
  }
?>