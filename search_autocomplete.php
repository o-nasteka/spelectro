<?php

    require( 'includes/application_top.php' );

    // Variables
    $sSearch = mysqli_real_escape_string( $conn, $_POST['value'] );
    $aProductos = null;
    $aProducto = null;
    $sHtml = '';


    // Consulta de productos
    $aProductos = tep_db_query( 'SELECT p.products_image, p.product_ean, pd.products_name, pd.products_description
                                 FROM products p
                                 INNER JOIN products_description pd on(p.products_id = pd.products_id)
                                 WHERE p.products_status = 1 AND pd.language_id = ' . (int)$languages_id . ' AND pd.products_name LIKE "%' . $sSearch . '%"
								 OR p.product_ean LIKE "%' . $sSearch . '%"
                                 ORDER BY pd.products_name ASC
                                 LIMIT 15' );

    // Comprobamos si hemos obtenido productos
    if( tep_db_num_rows( $aProductos ) > 0 )
    {
        // Recorremos los productos
        while( $aProducto = tep_db_fetch_array( $aProductos ) )
        {
            $sHtml .= '<li>';
            
            $sHtml .= tep_image( DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'], $aProducto['products_name'], 50, 50);

            $sHtml .= '<p><h6>' . $aProducto['products_name'] . '</h6>' . truncate( strip_tags( $aProducto['products_description'] ), 100 ) . '</p>';

            $sHtml .= '</li>';
        }
    }

    // Pintamos el html resultante
    header( "Content-type:text/html; charset=utf-8" );
    echo $sHtml;
?>