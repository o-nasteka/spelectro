<?php
/*
  $Id: contrib_tracker.php,v .9 2007/01/08 11:25:32 lildog Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
_________________________________________________________________

Contribution Tracker Functions for osC Admin
By Admin of www.silvermoon-jewelry.com
Based on:
		Admin_notes: Original Code By: Robert Hellemans of www.RuddlesMills.com
		RSS News for OSC
These are LIVE SHOPS - So please, no TEST accounts etc...
We will report you to your ISP if you abuse our websites!
*/
	function vWritePageToFile($sHTMLpage, $sTxtfile ) {
		$sh=curl_init($sHTMLpage);
		$hFile=FOpen($sTxtfile, 'w');
		curl_setopt($sh, CURLOPT_FILE, $hFile);
		curl_setopt($sh, CURLOPT_HEADER, 0);
		curl_exec($sh);
		$aCURLinfo = curl_getInfo($sh);
		curl_close($sh);
		FClose($hFile);
	}

  function tep_set_contrib_query_status($contr_id, $status,$contr_last_modified) {
// IF THERE IS NO LAST MODIFIED DATE USE NOW ELSE USE THE $contr_last_modified (LAST TIME THE CONTRIB APPEARED IN THE RSS FEED)
    if ($contr_last_modified == NULL){
      $last_update_date= strftime($format,time());
    }else{
      $last_update_date= $contr_last_modified;
    }

    if ($status == '0') {
      return tep_db_query("update " . TABLE_CONTRIB_TRACKER . " set status = '0', date_status_change = NULL where contr_id = '" . $contr_id . "'");
    } elseif ($status == '1') {
      return tep_db_query("update " . TABLE_CONTRIB_TRACKER . " set status = '1', date_status_change =  now(), last_update='" .$contr_last_modified. "' where contr_id = '" . $contr_id . "'");
    } elseif ($status == '2') {
      return tep_db_query("update " . TABLE_CONTRIB_TRACKER . " set status = '3', date_status_change =  now(), last_update='" .$contr_last_modified. "' where contr_id = '" . $contr_id . "'");
    } else {
      return -1;
    }
  }

?>