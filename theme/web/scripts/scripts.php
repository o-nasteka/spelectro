<?php if( PRODUCCION == '1' ): ?>
    <link rel="stylesheet" type="text/css" href="<?php echo DIR_WS_INCLUDES ?>min/?g=css"/>
<?php else: ?>
    <link rel="stylesheet" type="text/css" href="theme/<?php echo THEME ?>/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="theme/<?php echo THEME ?>/css/base.css"/>
    <link rel="stylesheet" type="text/css" href="theme/<?php echo THEME ?>/css/fonts.css"/>
<?php endif; ?>
