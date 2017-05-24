<?php
	// Variables
	$customer_group_id = getCustomerGroupId();

	// Consulta con los productos
	$sSql = 'select ' . SQL_SELECT . ' p.products_quantity, p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price
			 from ' . TABLE_PRODUCTS . ' p
			 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on (p.products_id = pd.products_id)
			 inner join ' . TABLE_SPECIALS . ' s on (s.products_id = p.products_id)
			 ' . SQL_FROM . '
			 where p.products_status = 1 and pd.language_id = ' . (int)$languages_id . ' and s.status = 1 and s.customers_group_id = ' . (int)$customer_group_id . '
			 order by s.specials_date_added DESC
			 limit 12';

	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];

	// Mostramos productos
	if( count( $aProductos ) > 0 )
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );
?>