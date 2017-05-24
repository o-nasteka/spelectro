<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
	
	v1.7
*/


// disable Gzip compression
  define('GZIP_COMPRESSION', 'false');
  define('GZIP_LEVEL', '0');	
  define('STRICT_ERROR_REPORTING', true);

  require('includes/application_top.php');

	// zencart, xtcommerce
	if (!defined('DIR_WS_HTTP_CATALOG')) define('DIR_WS_HTTP_CATALOG', DIR_WS_CATALOG);	
	
	define('TEXT_EMAIL_ALREADY_SEND', 'was already sent: ');
	define('TEXT_EMAIL_SEND', 'successfully sent: ');

	require_once(DIR_FS_CATALOG. 'mailhive/common/functions/compatibility.php');	
  require(DIR_FS_CATALOG . 'mailhive/common/classes/mailhive.php');
	
  $mailHive= new mailHive;
	
	$mailhive_token = MAILBEEZ_MAILHIVE_TOKEN;
	
	// call external module action e.g. block
	if ( isset($_GET['ma']) ) {
		$module_action = $_GET['ma'];
		$module = (isset($_GET['m'])) ? $_GET['m'] : false;
		$module_params = (isset($_GET['mp'])) ? $_GET['mp'] : false;
		$result = $mailHive->moduleAction($module, 'external_' .$module_action, $module_params);
	}
	//	
	
	
	
	if (isset($_GET[$mailhive_token]) ) {
		$mpaction = $_GET[$mailhive_token];
	} elseif (isset($_POST[$mailhive_token]) ) {
		$mpaction = $_POST[$mailhive_token];
	}
	
	$output_plain = false;
	
	if ($mpaction == 'view') {
		$output_plain = true;
	}
	
	if ($output_plain == false) {
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<script type="text/javascript">
  <!-- Begin Hidden JavaScript
  function scrolldown() {
   var a=document.anchors.length;
   var b=document.anchors[a-1];
   var y=b.offsetTop;
   window.scrollTo(0,y+120);
  }
  // End hidden JavaScript -->
  </script>   
<style>
body { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
.s { font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #99ff00; }
.w { font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #ffb3b5; }
.rn { width: 30px; float: left; font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #e6e6e6; }
.r { margin-left: 30px; position: relative; margin-bottom: 3px; font-family: Verdana, Arial, sans-serif; font-size: 10px; background-color: #e9e9e9; }
.pageHeading { font-family: Arial, Verdana, sans-serif; font-size: 24px; font-weight: bold; padding-top: 10px; padding-bottom: 7px; }

</style>
<div class="pageHeading">MailBeez - Mode: <?php echo MAILBEEZ_MAILHIVE_MODE ?> (platform: <?php echo MH_PLATFORM; ?>)</div>
<!-- body //-->
<hr size="1" noshade>

<?php 
	}

	if (MAILBEEZ_MAILHIVE_STATUS == 'False') {
	
?>
	MailHive inactive - please activate MailHive and MailBeez in Basic Configuration
<?php
	
	} else {
	
		if ($mpaction == 'test') {
	?>
			<blockquote><br><br>
			<?php echo mh_draw_form('test', mh_href_link(FILENAME_HIVE, '', 'NONSSL'), 'post', '') . mh_draw_hidden_field($mailhive_token, 'sendTest') . mh_draw_hidden_field('module', $_GET['module']); ?>
			<?php echo mh_draw_input_field('email', MAILBEEZ_MAILHIVE_EMAIL_COPY) ?>
			<input type="Submit" value="Send Test-Mail">
			</form>
			<br>
			<br>
			<?php if (MAILBEEZ_MAILHIVE_COPY == 'True') { echo 'Send Copy to: ' . MAILBEEZ_MAILHIVE_EMAIL_COPY; } ?>
			</blockquote>
	<?php
		} elseif ($mpaction == 'sendTest') {
			$mailHive->sendTest($_POST['email'], $_POST['module']);
			
		} elseif ($mpaction == 'listAudience') {
			$result = $mailHive->listAudience($_GET['module']);
			
		} elseif ($mpaction == 'view') {
			$out = $mailHive->viewMail($_GET['module'], $_GET['format']);	
			if ($_GET['format'] == 'txt') {
				echo '<pre>';
			}
			echo $out[$_GET['module']];
			
		} elseif ($mpaction == 'run') {
			$result = $mailHive->run($_GET['module']);
			
		} elseif ($mpaction == 'runconfirm') {
			?>
			<blockquote><br><br>
			<button type="button" onclick="window.location.href='<?php echo mh_href_link(FILENAME_HIVE, $mailhive_token . '=run&module=' . $_GET['module'] , 'NONSSL') ?>'">RUN NOW - Mode:<?php echo MAILBEEZ_MAILHIVE_MODE ?></button>
			
			<br>
			<br>
			<br>
			Send Copy: <?php echo MAILBEEZ_MAILHIVE_COPY ?><br>
			<?php if (MAILBEEZ_MAILHIVE_COPY == 'True') { echo 'to: ' . MAILBEEZ_MAILHIVE_EMAIL_COPY; } ?>
			</blockquote>
			<?php

		} elseif ($mpaction == '') {
			// 
		} 
?>
<?php
	if ($output_plain == false) {
 ?>
		<?php echo str_repeat(" ", 4096); ?>
</body>
</html>
<?php
		}
	}
	require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>