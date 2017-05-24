<?php
/*
  $Id: password_forgotten.php,v 1.6 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Entrar');
define('NAVBAR_TITLE_2', 'Recuperação de Palavra Passe');
define('HEADING_TITLE', 'Recuperação de Palavra Passe!');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>NOTA:</b></font> O Email especificado não foi encontrado nos nossos registos. Por favor, tente de novo.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Nova Password');
define('EMAIL_PASSWORD_REMINDER_BODY', 'A sua nova password para a loja \'' . STORE_NAME . '\' é:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', 'Uma nova password foi enviada para o seu email');
?>