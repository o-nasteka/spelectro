<?php
    // Configuración backend
    if( strstr( dirname( $_SERVER['SCRIPT_NAME'] ), '_admin' ) )
    {
        define( 'HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] );
        define( 'HTTP_CATALOG_SERVER', 'http://' . $_SERVER['HTTP_HOST'] );
        define( 'HTTPS_CATALOG_SERVER', 'http://' . $_SERVER['HTTP_HOST'] );
        define( 'ENABLE_SSL_CATALOG', 'false' );
        define( 'DIR_FS_DOCUMENT_ROOT', dirname(__FILE__) . '/../' );
        define( 'DIR_WS_ADMIN', dirname( $_SERVER['SCRIPT_NAME'] ) . '/' );
        define( 'DIR_FS_ADMIN', dirname(__FILE__) . '/../_admin/' );
        define( 'DIR_WS_CATALOG', str_replace( '_admin', '', dirname( $_SERVER['SCRIPT_NAME'] ) ) );
        define( 'DIR_FS_CATALOG', dirname(__FILE__) . '/../' );
        define( 'DIR_WS_IMAGES', 'images/' );
        define( 'DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/' );
        define( 'DIR_WS_CATALOG_IMAGES', '../images/' );
        define( 'DIR_WS_INCLUDES', 'includes/' );
        define( 'DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/' );
        define( 'DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/' );
        define( 'DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/' );
        define( 'DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/' );
        define( 'DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/' );
        define( 'DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/' );
        define( 'DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/' );
        define( 'DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/' );
        define( 'DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/' );
        define( 'DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/' );
        define( 'DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/' );
        define( 'DIR_FS_CATALOG_INCLUDES', DIR_FS_CATALOG . 'includes/' );
        define( 'DIR_FS_CATALOG_CLASSES', DIR_FS_CATALOG_INCLUDES . 'classes/' );
        define( 'DIR_RP_LANGUAGES', DIR_FS_CATALOG_INCLUDES . 'languages/' );
        define( 'DIR_RP_MODULES', DIR_FS_CATALOG_INCLUDES . 'modules/' );
		define( 'THEME', 'theme/web/' );

        // Theme catalogo
        define( 'THEME_CAT', 'web' );
        define( 'DIR_THEME', 'theme/'.THEME_CAT.'/' );
        define( 'DIR_THEME_ROOT', dirname(__FILE__) . '/../' . THEME_CAT );
    }
    // Configuración frontend
    else
    {
        $sDirNameScriptName = dirname( $_SERVER['SCRIPT_NAME'] );
        $sDirNameScriptName .= ($sDirNameScriptName == '/' ? '' : '/');
        $sDirNameScriptName = (strstr( $sDirNameScriptName, 'googlesitemap' ) == 'googlesitemap/' ? '/' : $sDirNameScriptName );
		
        define( 'HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] );
        define( 'HTTPS_SERVER', 'http://' . $_SERVER['HTTP_HOST'] );
        define( 'ENABLE_SSL', false );
        define( 'HTTP_COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );
        define( 'HTTPS_COOKIE_DOMAIN', $_SERVER['HTTP_HOST'] );
        define( 'HTTP_COOKIE_PATH', $sDirNameScriptName );
        define( 'HTTPS_COOKIE_PATH', $sDirNameScriptName );
        define( 'DIR_WS_HTTP_CATALOG', $sDirNameScriptName );
        define( 'DIR_WS_HTTPS_CATALOG', $sDirNameScriptName );
        define( 'DIR_WS_IMAGES', 'images/' );
        define( 'DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/' );
        define( 'DIR_WS_INCLUDES', 'includes/' );
	define( 'DIR_WS_COMPONENTS', DIR_WS_INCLUDES . 'components/' );
        define( 'DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/' );
        define( 'DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/' );
        define( 'DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/' );
        define( 'DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/' );
        define( 'DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/' );
        define( 'BASKET_PASSWORD' , 'DeNoX194A' );
        define( 'DIR_WS_DOWNLOAD_PUBLIC', 'pub/' );
        define( 'DIR_FS_CATALOG', dirname(__FILE__) . '/../' );
        define( 'DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/' );
        define( 'DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/' );

        // Theme
        define( 'THEME', 'web' );
        define( 'DIR_THEME', 'theme/web/' );
        define( 'DIR_THEME_ROOT', dirname(__FILE__) . '/../' . DIR_THEME );
    }
?>