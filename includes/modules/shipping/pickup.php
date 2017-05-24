<?php
/*
  $Id: pickup.php,v 1.05 2003/02/18 03:37:00 harley_vb Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  class pickup {
    var $code, $title, $description, $icon, $enabled;

// class constructor
    function __construct() {
      global $order;

      $this->code = 'pickup';
      $this->title = MODULE_SHIPPING_PICKUP_TEXT_TITLE;
      $this->description = MODULE_SHIPPING_PICKUP_TEXT_DESCRIPTION;
      $this->sort_order = MODULE_SHIPPING_PICKUP_SORT_ORDER;
      $this->icon = '';
      $this->tax_class = MODULE_SHIPPING_PICKUP_TAX_CLASS;
      $this->enabled = ((MODULE_SHIPPING_PICKUP_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_PICKUP_ZONE > 0) ) {
        $check_flag = false;
        $check_query = tep_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_PICKUP_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
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

// class methods
    function quote($method = '') {
      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_PICKUP_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_PICKUP_TEXT_WAY,
                                                     'cost' => MODULE_SHIPPING_PICKUP_COST)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = tep_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (tep_not_null($this->icon)) $this->quotes['icon'] = tep_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_PICKUP_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable PickUp Shipping', 'MODULE_SHIPPING_PICKUP_STATUS', 'True', 'Do you want to offer pickup rate shipping?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Steuersatz', 'MODULE_SHIPPING_PICKUP_TAX_CLASS', '0', 'Wählen Sie den MwSt.-Satz für diese Versandart aus.', '6', '0', 'tep_get_tax_class_title', 'tep_cfg_pull_down_tax_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Versand Zone', 'MODULE_SHIPPING_PICKUP_ZONE', '0', 'Wenn Sie eine Zone auswählen, wird diese Versandart nur in dieser Zone angeboten.', '6', '0', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Reihenfolge der Anzeige', 'MODULE_SHIPPING_PICKUP_SORT_ORDER', '0', 'Niedrigste wird zuerst angezeigt.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('PickUp Cost', 'MODULE_SHIPPING_PICKUP_COST', '0.00', 'What is the Shipping cost? The Handling fee will also be added.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_PICKUP_STATUS', 'MODULE_SHIPPING_PICKUP_COST', 'MODULE_SHIPPING_PICKUP_TAX_CLASS', 'MODULE_SHIPPING_PICKUP_ZONE', 'MODULE_SHIPPING_PICKUP_SORT_ORDER');
    }
  }
?>