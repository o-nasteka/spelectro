<?php
/*
  $Id: tax.php,v 1.1 2003/11/17 16:56:11 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Mofified by: Chemo
  Purpose: MS2 backward compatibility
  Contact: osc_tax@bdcconcepts.com
  Updated by: xmanflash 27/08/06: Fixed getTaxRate() - was returning 0 when shop user logged in.
  adapted for Separate Pricing Per Customer (addon 716) version 4.2 2008/07/20 JanZ
*/

  class osC_Tax {
    var $tax_rates;

// class constructor
    function __construct() {
      $this->tax_rates = array();
    }

// class methods
    function getTaxRate($class_id, $country_id = -1, $zone_id = -1) {
    global $customer_zone_id, $customer_country_id;  //HW: ADDED TO FIX PROBLEM WITH ZERO TAX BEING RETURNED WHEN USER LOGGED IN.
    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if (!tep_session_is_registered('customer_id')) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $customer_country_id;
        $zone_id = $customer_zone_id;
      }
    }

      if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['rate']) == false) {
// BOF Separate Pricing Per Customer
        if (!isset($_SESSION['sppc_customer_group_tax_exempt'])) {
          $customer_group_tax_exempt = '0';
        } else {
         $customer_group_tax_exempt = $_SESSION['sppc_customer_group_tax_exempt'];
        }

       if ($customer_group_tax_exempt == '1') {
// is customer is tax exempted we can set the tax rate at zero, no query necessary
         $tax_rate = 0;
         $this->tax_rates[$class_id][$country_id][$zone_id]['rate'] = $tax_rate;
       } else {
// customer can still be exempted for certain taxes
       if (isset($_SESSION['sppc_customer_specific_taxes_exempt']) && tep_not_null($_SESSION['sppc_customer_specific_taxes_exempt']) ) {
         $additional_for_specific_taxes = "AND tax_rates_id NOT IN ( ". $_SESSION['sppc_customer_specific_taxes_exempt'] ." )";   
       } else {
         $additional_for_specific_taxes = '';
       }
        $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' " . $additional_for_specific_taxes . " group by tr.tax_priority");

        if (tep_db_num_rows($tax_query)) {
          $tax_multiplier = 1.0;
          while ($tax = tep_db_fetch_array($tax_query)) {
            $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
          }

          $tax_rate = ($tax_multiplier - 1.0) * 100;
        } else {
          $tax_rate = 0;
        }

        $this->tax_rates[$class_id][$country_id][$zone_id]['rate'] = $tax_rate;
        } // end if/else ($customer_group_tax_exempt == '1')
      } // end if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['rate']) == false)
// EOF Separate Pricing Per Customer
      return $this->tax_rates[$class_id][$country_id][$zone_id]['rate'];
    }

    function getTaxRateDescription($class_id, $country_id, $zone_id) {
      if (isset($this->tax_rates[$class_id][$country_id][$zone_id]['description']) == false) {
        $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int)$country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int)$zone_id . "') and tr.tax_class_id = '" . (int)$class_id . "' order by tr.tax_priority");
        if (tep_db_num_rows($tax_query)) {
          $tax_description = '';

          while ($tax = tep_db_fetch_array($tax_query)) {
            $tax_description .= $tax['tax_description'] . ' + ';
          }

          $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = substr($tax_description, 0, -3);
        } else {
          $this->tax_rates[$class_id][$country_id][$zone_id]['description'] = TEXT_UNKNOWN_TAX_RATE;
        }
      }

      return $this->tax_rates[$class_id][$country_id][$zone_id]['description'];
    }
  }
?>
