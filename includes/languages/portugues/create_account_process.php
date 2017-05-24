<?php
/*
  $Id: create_account_process.php,v 1.13 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Conta Pessoal');
define('NAVBAR_TITLE_2', 'Criar');
define('HEADING_TITLE', 'Criar Conta Pessoal');

define('EMAIL_SUBJECT', 'Bem vindo a ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Exmo. Sr. ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Exma. Sra. ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Caro ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'A <b>' . STORE_NAME . '</b>' . " agradece a sua preferência.\n\n");
define('EMAIL_TEXT', 'Pode agora gozar dos serviços que temos para lhe oferecer. Estes incluem:' . "\n\n" . '<li><b>Carrinho de Compras permanente</b> - TODOS os artigos que adicionar ao carrinho de compras permanecerão até que conclua a sua encomenda ou retire os artigos da lista.' . "\n" . '<li><b>Agenda/Livro de moradas</b> - Podemos entregar/enviar os artigos para outra morada que não a sua! Isto é perfeito para enviar presentes a uma amigo ou familiar.' . "\n" . '<li><b>Histórico de Encomendas</b> - Veja o histórico das suas encomendas feitas na nossa loja.' . "\n\n");
define('EMAIL_CONTACT', 'Para ajuda ou dúvidas, é favor contactar-nos pelo email ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Nota:</b> Este e-mail foi-nos fornecido por um dos nossos clientes. Caso não tenha efectuado nenhuma inscrição na nossa loja, por favor, contacte-nos para: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
?>
