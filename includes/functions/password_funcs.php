<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

////
// This function validates a plain text password with a
// encrpyted password
  function tep_validate_password($plain, $encrypted) {
// HMCS: Begin Autologon	******************************************************************

    global $_COOKIE;
	
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    if (tep_not_null($_COOKIE['password']) && tep_not_null($encrypted)) {   //Autologon
      if ($_COOKIE['password'] == $encrypted) {
        return true;
      }
    }

// HMCS: End Autologon		******************************************************************

    return false;
  }

////
// This function makes a new password from a plaintext password. 
  function tep_encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
?>
