<?php
$logo = HTTP_SERVER . DIR_WS_CATALOG . DIR_THEME .'logo-trans.png';
$url = HTTP_SERVER . DIR_WS_CATALOG ;

define('UHE_SUBJECT', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Bem-vindo à ' . STORE_NAME);
define('UHE_GREET_MR', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado %s,');
define('UHE_GREET_MS', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimada %s,');
define('UHE_GREET_NONE', '<a href="'.$url.'"><img src="'.$logo.'" alt="'.$logo.'" border="0"></a><br />Estimado %s,');
define('UHE_WELCOME', 'Bem-vindo à <strong>' . STORE_NAME . '</strong>.' . "\n\n");
define('UHE_TEXT', 'Agora você pode desfrutar dos <strong>serviços</strong> que oferecemos. Alguns desses serviços são:' . "\n\n" . '<li><strong>Carrinho Permanente</strong> - Qualquer produtos adicionados ao seu carrinho de linha permanecem lá até você removê-lo, ou até fazer a compra.' . "\n" . '<li><strong>Agenda</strong> - Nós podemos entregar seus produtos para outro endereço que não seja a seu! Isto é perfeito para enviar presentes de aniversário direto para o aniversário da pessoa.' . "\n" . '<li><strong>Histórico de Pedidos</strong> - Ver o seu histórico de compras que você fez com a gente.' . "\n" . '<li><strong>Comentarios</strong> - Compartilhar suas opiniões sobre os produtos com nossos outros clientes.' . "\n\n");
define('UHE_CONTACT', 'Para qualquer questões sobre nossos serviços, por favor escreva para: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('UHE_WARNING', '<strong>Nota:</strong> Essa mensagem foi enviado porque uma pessoa se registrou com essa mesma direção. Se você ainda não se inscreveu como cliente, por favor, contacte con ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");

define('EMAIL_SUBJECT', 'Bem-vindo à Outletsalud.com');


?>
