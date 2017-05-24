<?php
/*
  $Id: checkout_success.php,v 1.11 2002/11/01 04:27:01 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Saída');
define('NAVBAR_TITLE_2', 'Encomenda Confirmada!');

define('HEADING_TITLE', 'O seu pedido foi concluído!');

define('TEXT_SUCCESS', 'O seu pedido vai ser processado o mais rapidamente possível! Deverá chegará ao destino dentro de 2 a 5 dias úteis.');
define('TEXT_NOTIFY_PRODUCTS', 'Desejo ser notificado de alterações aos artigos seguintes:');
define('TEXT_SEE_ORDERS', 'Pode analisar o histórico das suas encomendas clicando em <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Minha Conta\'</a> e clicando em <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'Histórico\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '"Por favor contacte-nos para quaisquer dúvidas que possam surgir.</a>');
define('TEXT_THANKS_FOR_SHOPPING', 'Obrigado!');

define('TABLE_HEADING_COMMENTS', 'Insira um comentário para a encomenda efectuada');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Data de Validade');
define('TABLE_HEADING_DOWNLOAD_COUNT', 'Nº Max. de downloads');
define('HEADING_DOWNLOAD', 'Faça o download os seus artigos aqui:');
define('FOOTER_DOWNLOAD', 'Também pode fazer o download dos seus artigos em \'%s\'');
?>