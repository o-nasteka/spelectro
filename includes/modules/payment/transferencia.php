<?php
/*
  $Id: transferencia.php, 10/16/2004
  
  O download do módulo deposito/transferência bancária
   pode ser efetuado em http://www.phpmania.org

  Copyright (c) 2004 PHPmania.org <phpmania@mail.com>

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
  Copyright (c) 2003 osCommerce
   osCommerce 2.2 Milestone 2 BR Por PHPmania.org

*/

  class transferencia {
    var $code, $title, $description, $enabled;

// class constructor
    function __construct() {

      global $order;
      $this->code = 'transferencia';
      $this->title = MODULE_PAYMENT_TRANSFERENCIA_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_TRANSFERENCIA_TEXT_DESCRIPTION;
      $this->email_footer =MODULE_PAYMENT_TRANSFERENCIA_TEXT_CONFIRMATION .
							"\n\nDatos para Transferencia Bancaria:\n" .
							MODULE_PAYMENT_TRANSFERENCIA_TITULAR . "\n" .
							"<strong>Banco:</strong> " . MODULE_PAYMENT_TRANSFERENCIA_BANCO . "\n" .
							"<strong>Número de Cuenta:</strong> " . MODULE_PAYMENT_TRANSFERENCIA_CC . "\n\n";
      $this->sort_order = MODULE_PAYMENT_TRANSFERENCIA_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_TRANSFERENCIA_STATUS == 'True') ? true : false);

      if ((int)MODULE_PAYMENT_TRANSFERENCIA_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_TRANSFERENCIA_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_TRANSFERENCIA_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_PAYMENT_NZ_BANK_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = tep_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
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
      return array('id' => $this->code,
                   'module' => $this->title);
    }

    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      return array('title' => MODULE_PAYMENT_TRANSFERENCIA_TEXT_CONFIRMATION . "\n<br /><strong>\nDatos para Transferencia Bancaria:</strong><br />\n" . MODULE_PAYMENT_TRANSFERENCIA_TITULAR . "\n" . "<br /><strong>Banco:</strong> " . MODULE_PAYMENT_TRANSFERENCIA_BANCO . "\n" . "<br /><strong>Cuenta Corriente:</strong> " . MODULE_PAYMENT_TRANSFERENCIA_CC . "\n");
    }

    function process_button() {
      return false;
    }

    function before_process() {
      return false;
    }

    function after_process() {
      return false;
    }

    function get_error() {
      return false;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_TRANSFERENCIA_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Activar módulo Transferencia', 'MODULE_PAYMENT_TRANSFERENCIA_STATUS', 'True', '', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mensaje (Opciones de pago)', 'MODULE_PAYMENT_TRANSFERENCIA_TEXT_SELECTION', 'Depósito/Transferencia Bancária (nombre del banco aquí)', 'Texto a ser exibido para el cliente de las opciones de pago:', '6', '4', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Mensaje (Instrucciones para el Cliente)', 'MODULE_PAYMENT_TRANSFERENCIA_TEXT_CONFIRMATION', 'Su pedido será enviado cuando sea confirmado el pago. Para agilizar el processo, envíe el comprovante por fax: (xx)xxxx-xxxx o email: info@sudominio.com.', 'Texto a ser exibido para el cliente en la confirmación de compra', '6', '5', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Titular de la Cuenta Bancaria', 'MODULE_PAYMENT_TRANSFERENCIA_TITULAR', 'Denox.', 'Titular de Cuenta Bancaria', '6', '3', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Nombre del Banco', 'MODULE_PAYMENT_TRANSFERENCIA_BANCO', 'Su Banco', 'Nombre del Banco', '6', '4', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Agencia', 'MODULE_PAYMENT_TRANSFERENCIA_AGENCIA', 'xxxx-x', 'Agencia', '6', '5', now())");
	  tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Cuenta Corriente', 'MODULE_PAYMENT_TRANSFERENCIA_CC', 'xxxxx-x', 'Cuenta Corriente', '6', '2', now())");
	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('Status de pedidos', 'MODULE_PAYMENT_TRANSFERENCIA_ORDER_STATUS_ID', '0', 'Actualiza el estado de los pedidos efetuados por este módulo de pago para este valor.', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
	   tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Orden de exibición.', 'MODULE_PAYMENT_TRANSFERENCIA_SORT_ORDER', '0', 'Orden de exibición', '6', '0', now())");


   }


    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_TRANSFERENCIA_STATUS', 'MODULE_PAYMENT_TRANSFERENCIA_TEXT_SELECTION', 'MODULE_PAYMENT_TRANSFERENCIA_TEXT_CONFIRMATION', 'MODULE_PAYMENT_TRANSFERENCIA_TITULAR', 'MODULE_PAYMENT_TRANSFERENCIA_BANCO', 'MODULE_PAYMENT_TRANSFERENCIA_AGENCIA', 'MODULE_PAYMENT_TRANSFERENCIA_CC', 'MODULE_PAYMENT_TRANSFERENCIA_ORDER_STATUS_ID', 'MODULE_PAYMENT_TRANSFERENCIA_SORT_ORDER');

    }
  }
?>
