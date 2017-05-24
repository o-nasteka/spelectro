<?php

$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define('UHE_TEXT_DEAR', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado');        
define('UHE_MESSAGE_GREETING', 'Obrigado por confiar em nós com sua compra.<br />Abaixo, está a informação de seu pedido.'); 

define('UHE_TEXT_ORDER_NUMBER', 'Número de Ordem:');
define('UHE_TEXT_INVOICE_URL', 'Detalhes da Ordem:');
define('UHE_TEXT_DATE_ORDERED', 'Data do Pedido:');
define('UHE_TEXT_COMMENTS', 'Comentários:');

define('UHE_TEXT_ORDER_CONTENTS', 'Ordem do conteúdo');
define('UHE_TEXT_PRODUCTS_ARTICLE', 'Produto');
define('UHE_TEXT_PRODUCTS_MODEL', 'Modelo');
define('UHE_TEXT_PRODUCTS_PRICE', 'Preço&nbsp;');
define('UHE_TEXT_PRODUCTS_QTY', 'Quantidade');
define('UHE_TEXT_PRODUCTS_TOTAL', 'Total&nbsp;');

define('UHE_TEXT_PAYMENT_METHOD', 'Método de Pagamento');

define('UHE_TEXT_FINAL', 'Você receberá um e-mail assim que o estado do pedido for alterado para processado ou enviado.');

define('UHE_TEXT_BILLING_ADDRESS', 'Endereço de Cobrança');
define('UHE_TEXT_DELIVERY_ADDRESS', 'Endereço de Entrega');

define('TEXT_PAYMENT_INFORMATION', 'Por favor, note que o seu pedido será enviado assim que o pagamento for confirmado. Para agilizar o processo, você pode notificar-nos do seu pagamento, enviando o comprovante da transferência bancária por e-mail para: info@outletsalud.com
<br><strong>
Dados para Transferência Bancária:</strong><br>
Outlet de Salud y Belleza S.L.
<br><strong>Banco:</strong> Banco Santander
<br><strong>Conta Corrente:</strong> 0049 4421 17 2310005285');
?>