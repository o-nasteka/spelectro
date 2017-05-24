<?php
	require( DIR_WS_LANGUAGES . $language . '/also_purchased_products.php' );

	// Variables
	$aDatos = null;
	$list_of_order_ids = null;
	$sub_orders = null;
	$aAux = null;
	$aProductos = null;
	
	// Obtenemos los 100 pedidos ultimos de este producto
	$aDatos = tep_db_query( 'select op.orders_id 
							 from orders_products op
							 inner join orders o on (op.orders_id = o.orders_id)
							 where products_id = ' . $_GET['products_id'] . ' and  DATE_SUB(CURDATE(), INTERVAL 720 DAY) <= o.date_purchased
							 order by o.date_purchased desc limit 100' ); 

	// Si hemos obtenido resultados
	if( tep_db_num_rows( $aDatos ) > 0 ) 
	{
		while( $sub_orders = tep_db_fetch_array( $aDatos ) )
			$list_of_order_ids[] = $sub_orders['orders_id'];

		$sSql = 'select ' . SQL_SELECT . ' p.products_id, p.products_tax_class_id, p.products_quantity, p.products_image, p.products_price, pd.products_id, pd.products_name
				 from ' . TABLE_ORDERS_PRODUCTS . ' opb, '
				 . TABLE_ORDERS . ' o, '
				 . TABLE_PRODUCTS . ' p, '
				 . TABLE_PRODUCTS_DESCRIPTION . ' pd
				 ' . SQL_FROM . '
				 where opb.products_id != ' . (int)$_GET['products_id'] . ' and opb.products_id = p.products_id and pd.products_id = p.products_id and opb.orders_id = o.orders_id and o.orders_id in (' . implode(',', $list_of_order_ids) . ') and p.products_status = 1
				 group by p.products_id
				 order by o.date_purchased desc
				 limit ' . MAX_DISPLAY_ALSO_PURCHASED;

		// Obtenemos los productos cambiando el precio segun tipo de cliente
		$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
		$aProductos = $aAux['PRODUCTOS'];
	}
	
	// Mostramos productos
	include( DIR_THEME. 'html/components/' . basename(__FILE__) );
?>