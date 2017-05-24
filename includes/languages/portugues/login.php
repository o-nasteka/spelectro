<?php
/*
  $Id: login.php,v 1.11 2002/06/03 13:19:42 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($_GET['origin'] == FILENAME_CHECKOUT_PAYMENT) {
  define('NAVBAR_TITLE', 'Encomendar');
  define('HEADING_TITLE', 'Encomendar online é simples!.');
  define('TEXT_STEP_BY_STEP', 'Basta seguir passo a passo o processo.');
} else {
  define('NAVBAR_TITLE', 'Entrar');
  define('HEADING_TITLE', 'Bemvindo, insira o seu login');
  define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_NEW_CUSTOMER', 'Novo Cliente');
define('TEXT_NEW_CUSTOMER', 'Registar como Novo Cliente.'); 
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Ao criar uma nova Conta Pessoal na loja ' . STORE_NAME . ' poderá comprar mais rapidamente, comódamente e guardar um histórico das suas encomendas anteriores.');

define('HEADING_RETURNING_CUSTOMER', 'Cliente Habitual');
define('TEXT_RETURNING_CUSTOMER', 'Entrar como Cliente já Existente.'); 
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_REMEMBER_ME', 'Lembrar');

define('TEXT_PASSWORD_FORGOTTEN', '<div align=center>Recuperar Password</div>');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERRO:</b></font> E-Mail ou Palavra Passe incorrectos.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>NOTA:</b></font> A sua "<a href="javascript:session_win();">Lista de Visitante</a>" será adicionada à sua "<a href="javascript:session_win();">Lista de Membro</a>" depois de se ter validado.');
?>
