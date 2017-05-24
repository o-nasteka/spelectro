<?php
    // Variables
    $aBanners = tep_db_query( 'SELECT * FROM banners_destacados ORDER BY orden asc' );

    if( $aBanners && mysql_num_rows( $aBanners ) != 0 )
        include( DIR_THEME. 'html/components/' . basename(__FILE__) );


    unset( $aBanners );
?>