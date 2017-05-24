<?php
    if( !empty( $_GET['c'] ) && ($_GET['c'] == 'chng-vsta-vrtl' || $_GET['c'] == 'chng-vsta-hrzt' ) )
    {
        include( 'includes/application_top.php' );
        $vista = $_GET['c'];
        tep_session_register( "vista" );
    }
?>