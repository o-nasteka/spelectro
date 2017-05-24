<?php 
// Sets the status of a slide
function tep_set_slide_status($slides_id, $status) {
	if ($status == '1') {
		return tep_db_query("update " . TABLE_SLIDES . " set status = '1', date_status_change = now(), date_scheduled = NULL where slides_id = '" . (int)$slides_id . "'");
	} elseif ($status == '0') {
		return tep_db_query("update " . TABLE_SLIDES . " set status = '0', date_status_change = now(), expires_date = NULL where slides_id = '" . (int)$slides_id . "'");
	} else {
		return -1;
	}
}

////
// Auto activate slides
function tep_activate_slides() {
	$slides_query = tep_db_query("select slides_id, date_scheduled from " . TABLE_SLIDES  . " where date_scheduled != ''");
	if (tep_db_num_rows($slides_query)) {
		while ($slides = tep_db_fetch_array($slides_query)) {
			if (date('Y-m-d H:i:s') >= $slides['date_scheduled']) {
				tep_set_slide_status($slides['slides_id'], '1');
			}
		}
	}
}

////
// Auto expire slides
function tep_expire_slides() {
	$slides_query = tep_db_query("select slides_id, expires_date from " . TABLE_SLIDES  . " where status = '1' ");
	if (tep_db_num_rows($slides_query)) {
		while ($slides = tep_db_fetch_array($slides_query)) {
			if (tep_not_null($slides['expires_date'])) {
				if (date('Y-m-d H:i:s') >= $slides['expires_date']) {
					tep_set_slide_status($slides['slides_id'], '0');
				}
			}
		}
	}
}
?>