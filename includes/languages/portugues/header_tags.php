<?php

// Define aqui la direccion de tu correo electronico que debe aparecer en todas las paginas
define('HEAD_REPLY_TAG_ALL',STORE_OWNER_EMAIL_ADDRESS);

// Para todas las paginas no definidas o que se deja en blanco, y para los productos no definidos
// Esto sera incluido a menos que modifique en cada seccion de abajo a OFF ( '0' )
// ElHEAD_TITLE_TAG_ALL sera incluido DESPUES del especificado para cada pagina
// El HEAD_DESC_TAG_ALL sera incluido DESPUES del especificado para cada pagina
// El HEAD_KEY_TAG_ALL sera incluido DESPUES del especificado para cada pagina
define('HEAD_TITLE_TAG_ALL','Tienda Videoconsolas - Chips, Flasheo de Consolas, Repuestos Wii, Videoconsolas,Videjuegos, PSP, NDS, Play Station, PS3');
define('HEAD_DESC_TAG_ALL','El catalogo mas amplio de accesorios para consolas y videojuegos con los mejores precios de España, mas de 10.000 clientes satisfechos.');
define('HEAD_KEY_TAG_ALL','chips, flasheo, consolas, tienda, flasheo de consolas, repuestos, wii,repuestos wii, videoconsolas, videojuegos, video, psp, nds, play station, ps3, wii, dstt, r4, wiikey2, drivekey,pantalla tactil, edge, tienda online, tienda, online, videojuegos');

// ETIQUETAS DEFINES PARA CADA PAGINA INDIVIDUAL / SECCION
// incluimos aqui ademas el de contribuciones que no vienen instaladas con osCommerce, por si las instala en un futuro.
// allprods.php
define('HTTA_ALLPRODS_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ALLPRODS_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ALLPRODS_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ALLPRODS', '');
define('HEAD_DESC_TAG_ALLPRODS','');
define('HEAD_KEY_TAG_ALLPRODS','');

// index.php
define('HTTA_DEFAULT_ON','0'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HTTA_CAT_DEFAULT_ON', '0'); //Include HEADE_TITLE_DEFAULT in CATEGORY DISPLAY
define('HEAD_TITLE_TAG_DEFAULT', 'Tienda Videoconsolas - Chips, Flasheo de Consolas, Repuestos Wii, Videoconsolas,Videjuegos, PSP, NDS, Play Station, PS3 - Inicio');
define('HEAD_DESC_TAG_DEFAULT','El catalogo mas amplio de accesorios para consolas y videojuegos con los mejores precios de España, mas de 10.000 clientes satisfechos.');
define('HEAD_KEY_TAG_DEFAULT','chips, flasheo, consolas, tienda, flasheo de consolas, repuestos, wii,repuestos wii, videoconsolas, videojuegos, video, psp, nds, play station, ps3, wii, dstt, r4, wiikey2, drivekey,pantalla tactil, edge, tienda online, tienda, online, videojuegos');

// product_info.php - si se deja en blanco en la tabla de products_description se usaran estos valores
define('HTTA_PRODUCT_INFO_ON','0');
define('HTKA_PRODUCT_INFO_ON','0');
define('HTDA_PRODUCT_INFO_ON','0');
define('HTTA_CAT_PRODUCT_DEFAULT_ON', '0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','');
define('HEAD_DESC_TAG_PRODUCT_INFO','');
define('HEAD_KEY_TAG_PRODUCT_INFO','');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','0');
define('HTKA_WHATS_NEW_ON','0');
define('HTDA_WHATS_NEW_ON','0');
define('HEAD_TITLE_TAG_WHATS_NEW','Novedades En e-nuc.com');
define('HEAD_DESC_TAG_WHATS_NEW','El catalogo mas amplio de accesorios para consolas y videojuegos con los mejores precios de España, mas de 10.000 clientes satisfechos.');
define('HEAD_KEY_TAG_WHATS_NEW','chips, flasheo, consolas, tienda, flasheo de consolas, repuestos, wii,repuestos wii, videoconsolas, videojuegos, video, psp, nds, play station, ps3, wii, dstt, r4, wiikey2, drivekey,pantalla tactil, edge, tienda online, tienda, online, videojuegos');

// specials.php
// Si deja en blanco HEAD_KEY_TAG_SPECIALS, creara los keywords desde el nombre del producto de todos los productos en oferta
define('HTTA_SPECIALS_ON','0');
define('HTKA_SPECIALS_ON','0');
define('HTDA_SPECIALS_ON','0');
define('HEAD_TITLE_TAG_SPECIALS','Las Mejores Ofertas en consolas en E-nuc.com.');
define('HEAD_DESC_TAG_SPECIALS','Nuestras Mejores Ofertas en Armarios Rack, Cables HDMI, SAI�s, Cables VGA, Cables DVI, Conmutadores HDMI, Extensores, Fibra �ptica...');
define('HEAD_KEY_TAG_SPECIALS','chips, flasheo, consolas, tienda, flasheo de consolas, repuestos, wii,repuestos wii, videoconsolas, videojuegos, video, psp, nds, play station, ps3, wii, dstt, r4, wiikey2, drivekey,pantalla tactil, edge, tienda online, tienda, online, videojuegos');

// product_reviews_info.php y product_reviews.php - si los deja en blanco en la descripcion estos valores seran los que se usen
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','');

// .php
define('HTTA__ON','1');
define('HTDA__ON','1');
define('HTKA__ON','1');
define('HEAD_TITLE_TAG_','');
define('HEAD_DESC_TAG_','');
define('HEAD_KEY_TAG_','');


// distribuidores.php
define('HTTA_DISTRIBUIDORES_ON','1');
define('HTDA_DISTRIBUIDORES_ON','1');
define('HTKA_DISTRIBUIDORES_ON','1');
define('HEAD_TITLE_TAG_DISTRIBUIDORES','');
define('HEAD_DESC_TAG_DISTRIBUIDORES','');
define('HEAD_KEY_TAG_DISTRIBUIDORES','');
// helpdesk.php
define('HTTA_HELPDESK_ON','1');
define('HTDA_HELPDESK_ON','1');
define('HTKA_HELPDESK_ON','1');
define('HEAD_TITLE_TAG_HELPDESK','');
define('HEAD_DESC_TAG_HELPDESK','');
define('HEAD_KEY_TAG_HELPDESK','');
?>
