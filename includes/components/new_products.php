<?php
	// Consulta con los productos
	if( !isset( $new_products_category_id ) || $new_products_category_id == '0' )
	{
		$sSql = 'select ' . SQL_SELECT . ' p.products_id, p.products_image, p.products_tax_class_id, p.products_price, pd.products_name, p.products_quantity
				 from ' . TABLE_PRODUCTS . ' p 
				 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on (p.products_id = pd.products_id)
				 ' . SQL_FROM . '
				 where p.products_status = 1 and pd.language_id = ' . (int)$languages_id . ' 
				 order by p.products_date_added desc 
				 limit ' . MAX_DISPLAY_NEW_PRODUCTS;
	}
	else
	{
		$sSql = 'select ' . SQL_SELECT . ' distinct p.products_id, p.products_image, p.products_tax_class_id, p.products_price, pd.products_name, p.products_quantity
				 from ' . TABLE_PRODUCTS . ' p
				 inner join ' . TABLE_PRODUCTS_DESCRIPTION . ' pd on(p.products_id = pd.products_id)
				 left join ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c on pd.products_id = p2c.products_id 
				 left join ' . TABLE_CATEGORIES . ' c 
				 ' . SQL_FROM . '
				 using(categories_id) 
				 where c.parent_id = ' . (int)$new_products_category_id . ' and p.products_status = 1 and pd.language_id = ' . (int)$languages_id . '
				 order by p.products_date_added desc
				 limit ' . MAX_DISPLAY_NEW_PRODUCTS;
	}

	// Obtenemos los productos cambiando el precio segun tipo de cliente
	$aAux = changePriceCustomer( $sSql, array( 'PAGINAR' => false ) );
	$aProductos = $aAux['PRODUCTOS'];
	$nProductosTotal = count( $aProductos );	

	// Mostramos productos
	if( $nProductosTotal > 0 )
		include( DIR_THEME. 'html/components/' . basename(__FILE__) );

	unset( $sSql, $aAux, $aProductos, $nProductosTotal );
?>