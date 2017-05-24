<?php
	
	include( 'includes/application_top.php' );
	include( DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH );

	////print_r($conn);
	
	// Variables
	 $sBuscar = strtolower( mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['buscar'] ) ) );
	$sDescription = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['description'] ) );
	$sPrecioDesde = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['precio_desde'] ) );
	$sPrecioHasta = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['precio_hasta'] ) );
	$sCategoria = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['categoria'] ) );
	$sCategoriaHijos = null;
	$sFabricante = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['fabricante'] ) );
	$sOrden = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['order'] ) );
	$sAuto = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['auto'] ) );
	$sPrecioAnterior = mysqli_real_escape_string($conn, tep_db_prepare_input( $_GET['precio_anterior'] ) );
	$sTags = '';
	$nPrecioMax = 0;
	$nPrecioMin = 0;
	$aOrders = null;

	// Obtenemos el filtro de los precios
	if( $sPrecioAnterior == '' )
	{
		$sPrecioDesde = '';
		$sPrecioHasta = '';
	}
	else
	{
		$sPrecioAnterior = explode( '_', $sPrecioAnterior );
		$sPrecioDesde = $sPrecioAnterior[0];
		$sPrecioHasta = $sPrecioAnterior[1];
	}
	
	$sPrecioDesdePosion = $sPrecioDesde;
	$sPrecioHastaPosion = $sPrecioHasta;
	
	// Si el precio desde contiene valor y no es numerico retornamos
	if( $sPrecioDesde != '' && !is_numeric( $sPrecioDesde ) )
		tep_redirect( tep_href_link( FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'SSL', true, false ) );

	// Si el precio hasta contiene valor y no es numerico retornamos
	if( $sPrecioHasta != '' && !is_numeric( $sPrecioHasta ) )
		tep_redirect( tep_href_link( FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'SSL', true, false ) );

	// Si contenemos precio desde y hasta, comprobamos que desde no sea mayor que hasta
	if( $sPrecioDesde != '' && $sPrecioHasta != '' && $sPrecioDesde > $sPrecioHasta )
		tep_redirect( tep_href_link( FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'SSL', true, false ) );
		
	// Si no nos envian nada a buscar retornamos al formulario de busqueda
	if( $sBuscar == '' && $sPrecioHasta == '' && $sPrecioDesde == '' )
	{
		$messageStack->addSession( 'error_search', ERROR_AT_LEAST_ONE_INPUT );
		tep_redirect( tep_href_link( FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'SSL', true, false ) );
	}	

	// Creamos el array de order
	$aOrders = array( 
		array( 'id' => '1', 'text' => ADVANCED_SEARCH_FILTRO_ORDENAR_POR_NINGUNO ),
		array( 'id' => '2', 'text' => ADVANCED_SEARCH_FILTRO_ORDENAR_POR_NOMBRE_ASC ),
		array( 'id' => '3', 'text' => ADVANCED_SEARCH_FILTRO_ORDENAR_POR_NOMBRE_DESC ),
		array( 'id' => '4', 'text' => ADVANCED_SEARCH_FILTRO_ORDENAR_POR_PRECIO_ASC ),
		array( 'id' => '5', 'text' => ADVANCED_SEARCH_FILTRO_ORDENAR_POR_PRECIO_DESC ),		
	);
	
	// Breadcrumb
	$breadcrumb->add( ADVANCED_SEARCH_BREADCRUMB, tep_href_link( FILENAME_ADVANCED_SEARCH ) );
	$breadcrumb->add( ADVANCED_SEARCH_SUB_BREADCRUMB, tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(), 'SSL', true, false ) );
	
	// Cabecera y columna, si es una peticon ajax no mostramos
	if( ! isAjax() )
	{
		ob_start();
		include( DIR_THEME. 'html/header.php' );
		include( DIR_THEME. 'html/column_left.php' );
	}
	
	// Variables
	$aCategorias = array();
	$aFabricantes = array();
	$aPrecios = array();
	$aProductos = array();
	$sFinalPrice = '';

	// Variables SQL
	$sSelect = '';
	$sJoins = '';
	$sWhere = '';
	$sWherePrecio = '';
	$sOrder = '';

	// Construimos los campos select
	$sSelect = ' p.products_id, p.products_price, p.products_tax_class_id, pd.products_name, p.products_quantity, p.products_image, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price';

	// Construimos los joins
	$sJoins = 'products p inner join products_description pd on (p.products_id = pd.products_id) ';
	$sJoins .= 'left outer join products_to_categories pc on (p.products_id = pc.products_id) ';
	$sJoins .= 'left outer join categories c on (pc.categories_id = c.categories_id) ';
	$sJoins .= 'left outer join categories_description cd on (c.categories_id = cd.categories_id and cd.language_id = ' . (int)$languages_id . ') ';
	$sJoins .= 'left join ' . TABLE_SPECIALS_RETAIL_PRICES . ' s on (p.products_id = s.products_id and s.status = 1) ';

	// Construimos el where //

	// Dividimos la palabra enviada
	$aAux = preg_split( "/[\s]|[,]|[.]|[-]/", $sBuscar, -1, PREG_SPLIT_NO_EMPTY );
	$aBusquedasPlural = array();
	$aBusquedasSingular = array();

	// Formateamos las palabras de la búsqueda
	foreach( $aAux as $aBusqueda )
	{
		// Eliminamos las palabras demasiado cortas (pronombres, etc)
		if( strlen( $aBusqueda ) > 2 )
		{
			// Si es un número vale igual para singular y plural
			if( is_numeric( $aBusqueda ) )
			{
				// Añadimos en singular y plural
				$aBusquedasPlural[] = $aBusqueda;
				$aBusquedasSingular[] = $aBusqueda;

				continue;
			}

			// Comprobamos si la palabra está en plural (si termina en -s, -es, -ces)
			if( preg_match( '/s$/', $aBusqueda ) || preg_match( '/es$/', $aBusqueda ) || preg_match( '/ces$/', $aBusqueda ) )
			{
				// Añadimos al array plural
				$aBusquedasPlural[] = $aBusqueda;

				// Obtenemos su singular
				if( preg_match( '/ces$/', $aBusqueda ) )
					$aBusquedasSingular[] = preg_replace( '/ces$/', 'z', $aBusqueda );
				else if( preg_match( '/es$/', $aBusqueda ) )
					$aBusquedasSingular[] = preg_replace( '/es$/', '', $aBusqueda );
				else if( preg_match( '/s$/', $aBusqueda ) )
					$aBusquedasSingular[] = preg_replace( '/s$/', '', $aBusqueda );
			}
			// Si la palabra está en singular
			else
			{
				// Añadimos al array singular
				$aBusquedasSingular[] = $aBusqueda;

				// Obtenemos su plural
				if( preg_match( '/[a]$|[e]$|[o]$/', $aBusqueda ) )
					$aBusquedasPlural[] = $aBusqueda . 's';
				else if( preg_match( '/[z]$/', $aBusqueda ) )
					$aBusquedasPlural[] = preg_replace( '/z$/', 'ces', $aBusqueda );
				else
					$aBusquedasPlural[] = $aBusqueda . 'es';
			}
		}
	}

	// Nombre del producto
	$sWhere = '(LCASE( pd.products_name ) like "%' . $sBuscar . '%"';
	$sWhere .= ' or LCASE( pd.products_name ) like "%' . implode( $aAux, '%' ) . '%"';
	if( count( $aBusquedasPlural ) > 0 )
		$sWhere .= ' or LCASE( pd.products_name ) like "%' . implode( $aBusquedasPlural, '%' ) . '%"';
	if( count( $aBusquedasSingular ) > 0 )
		$sWhere .= ' or LCASE( pd.products_name ) like "%' . implode( $aBusquedasSingular, '%' ) . '%"';

	// Componemos las combinaciones
	while( $nCont < count( $aBusquedasPlural ) )
	{
		$aAuxs = $aBusquedasPlural;
		$aAuxs[$nCont] = $aBusquedasSingular[$nCont];
		$sWhere .= ' or LCASE( pd.products_name ) like "%' . implode( $aAuxs, '%' ) . '%"';
		++$nCont;
	}
	$nCont = 0;
	while( $nCont < count( $aBusquedasSingular ) )
	{
		$aAuxs = $aBusquedasSingular;
		$aAuxs[$nCont] = $aBusquedasPlural[$nCont];
		$sWhere .= ' or LCASE( pd.products_name ) like "%' . implode( $aAuxs, '%' ) . '%"';
		++$nCont;
	}

	// Descripcion del producto
	if( $sDescription == 1 )
		$sWhere .= ' or LCASE( pd.products_description ) like "%' . $sBuscar . '%"';
		
	$sWhere .= ' or LCASE( p.product_ean ) like "%' . $sBuscar . '%"';
			
	$sWhere .= ') and p.products_status = 1 ';

	// Construimos el order
	switch( $sOrden )
	{
		case 1:
			$sOrder = ' order by final_price asc';
		break;

		case 2:
			$sOrder = ' order by pd.products_name asc';
		break;
		
		case 3:
			$sOrder = ' order by pd.products_name desc';
		break;
		
		case 4:
			$sOrder = ' order by final_price asc';
		break;
		
		case 5:
			$sOrder = ' order by final_price desc';
		break;

		default:
			$sOrden = 4; 
			$sOrder = ' order by final_price asc';
		break;
	}

	// Si hay que mostrar el IVA, obtenemos el precio total con IVA
	if( DISPLAY_PRICE_WITH_TAX == 'true' )
	{
		if( ! tep_session_is_registered( 'customer_country_id' ) )
		{
			$customer_country_id = STORE_COUNTRY;
			$customer_zone_id = STORE_ZONE;
		}

		$sFinalPrice = 'round(((IF(s.status, s.specials_new_products_price, p.products_price) * tr.tax_rate) / 100) + IF(s.status, s.specials_new_products_price, p.products_price), 2)';
		$sJoins .= "left join " . TABLE_TAX_RATES . " tr on p.products_tax_class_id = tr.tax_class_id left join " . TABLE_ZONES_TO_GEO_ZONES . " gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "') ";
	}
	// Si no hay que mostrar IVA, mostramos el campo final
	else
		$sFinalPrice = 'round( IF(s.status, s.specials_new_products_price, p.products_price), 2)';
	$sSelect .= ', ' . $sFinalPrice . ' as final_price';

	// Precio desde
	if( $sPrecioDesde != '' )
		$sWherePrecio .= 'and ' . $sFinalPrice . ' >= ' . $sPrecioDesde . ' ';

	// Precio hasta
	if( $sPrecioHasta != '' )
		$sWherePrecio .= 'and ' . $sFinalPrice . ' <= ' . $sPrecioHasta . ' ';

	// Categoría
	if( $sCategoria != '' )
	{
		// Obtenemos las categorias hijo
		$sCategoriaHijos = getIdCategoriasHijasRecursivoByIdCategoriaPadre( $sCategoria );

		// Construimos el where
		$sWhere .= ($sCategoriaHijos == '' ? 'and pc.categories_id = ' . $sCategoria : 'and pc.categories_id in (' . $sCategoria . ', ' . $sCategoriaHijos . ')') . ' ';
	}

	// Fabricante
	if( $sFabricante != '' )
		$sWhere .= 'and p.manufacturers_id = ' . $sFabricante . ' ';

	// Añadimos filtro de idioma
	$sWhere .= 'and pd.language_id = ' . (int)$languages_id . ' ';

	/* Fin */

	// Construimos la consulta SQL
	$sSql = 'select ' . $sSelect . ' from ' . $sJoins . ' where ' . $sWhere . $sWherePrecio . ' group by p.products_id ' . $sOrder;
	
	// Obtenemos el paginador y los productos
	$aAux = changePriceCustomer( $sSql, array( 'AJAX' => true, 'COUNT_KEY' => 'p.products_id' ) );	
	$aProductos = $aAux['PRODUCTOS'];
	$aPaginador = $aAux['PAGE_PRODUCTOS'];
	$nProductosTotal = count( $aProductos );


	if( $sAuto == 1 && $aPaginador->number_of_rows == 1 )
	{
		tep_redirect( tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProductos[0]['products_id'] ) );
		exit();
	}

	// Consulta para obtener todas las categorias de los productos que se mostraran
	$aCategorias = tep_db_query( 'select c.categories_id as idcategoria, cdp.categories_name as categoria_padre, cd.categories_name as categoria, count(DISTINCT p.products_id) as cantidad 
								  from ' . $sJoins . ' left outer join categories cp on (c.parent_id = cp.categories_id) left outer join categories_description cdp on (cp.categories_id = cdp.categories_id and cdp.language_id = ' . (int)$languages_id . ') 
								  where ' . $sWhere . $sWherePrecio . ' 
								  group by pc.categories_id having categoria IS NOT NULL
								  ORDER BY cdp.categories_name, cd.categories_name' );
								  
	// Consulta para obtener todos los fabricantes de los productos que se mostraran
	$aFabricantes = tep_db_query( 'select p.manufacturers_id, m.manufacturers_name, m.manufacturers_image, count(p.products_id) as cantidad
								   from ' . $sJoins . ' left outer join manufacturers m on (p.manufacturers_id = m.manufacturers_id) 
								   where ' . $sWhere . $sWherePrecio . '
								   group by p.manufacturers_id having manufacturers_name IS NOT NULL
								   ORDER BY m.manufacturers_name' );

	// Consulta para obtener el precio maximo de los productos que se estan mostrando
	$aPrecios = tep_db_query( 'select max(' . $sFinalPrice . ') as maximo, min(' . $sFinalPrice . ') as minimo
							   from ' . $sJoins . ' left outer join manufacturers m on (p.manufacturers_id = m.manufacturers_id) 
							   where ' . $sWhere );
	$aPrecios = tep_db_fetch_array( $aPrecios );
	$nPrecioMin = floor( $aPrecios['minimo'] );
	$nPrecioMax = ceil( $aPrecios['maximo'] );

	// Si nos hemos pasado con los rangos de precio posicionamos en su maximo y minimo
	if( $sPrecioDesdePosion < $nPrecioMin || $sPrecioDesdePosion == '' )
		$sPrecioDesdePosion = $nPrecioMin;

	if( $sPrecioHastaPosion > $nPrecioMax || $sPrecioHastaPosion == '' )
		$sPrecioHastaPosion = $nPrecioMax;

	if( $sPrecioDesde < $nPrecioMin )
		$sPrecioDesde = $nPrecioMin;

	if( $sPrecioHasta > $nPrecioMax )
		$sPrecioHasta = $nPrecioMax;
		
	// Construimos los tags precios
	if( $sPrecioDesde != '' && $sPrecioHasta != '' && (($sPrecioDesde >= $nPrecioMin && $sPrecioHasta <= $nPrecioMax) || ($sPrecioDesde >= $nPrecioMin && $sPrecioHasta <= $nPrecioMax) ) )
		$sTags .= '<a rel="precio" href="' . tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(array('precio_desde', 'precio_hasta', 'precio_anterior')), 'SSL', true, false ) . '">Precio desde ' . $sPrecioDesde . ' € hasta ' . $sPrecioHasta . ' €</a>';
	
	// Construimos los tags categorias y comprobamos si existe
	if( $sCategoria )
	{
		// Consultamos la categoria
		$aDatos = tep_db_query( 'select categories_name from categories_description where categories_id = ' . $sCategoria . ' and language_id = ' . (int)$languages_id );
		
		// Comprobamos si existe
		if( tep_db_num_rows( $aDatos ) == 0 )
		{
			$messageStack->addSession( 'error_search', ERROR_AT_LEAST_ONE_INPUT );
			tep_redirect( tep_href_link( FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'SSL', true, false ) );
		}

		$aDatos = tep_db_fetch_array( $aDatos );
		$sTags .= '<a rel="categoria" href="' . tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(array('categoria')), 'SSL', true, false ) . '">' . $aDatos['categories_name'] . '</a>';
	}
	
	// Construimos los tags fabricantes y comprobamos si existe
	if( $sFabricante )
	{
		// Consultamos la categoria
		$aDatos = tep_db_query( 'select manufacturers_name from manufacturers where manufacturers_id = ' . $sFabricante );
		
		// Comprobamos si existe
		if( tep_db_num_rows( $aDatos ) == 0 )
		{
			$messageStack->addSession( 'error_search', ERROR_AT_LEAST_ONE_INPUT );
			tep_redirect( tep_href_link( FILENAME_ADVANCED_SEARCH, tep_get_all_get_params(), 'SSL', true, false ) );
		}
			
		$aDatos = tep_db_fetch_array( $aDatos );
		$sTags .= '<a rel="fabricante" href="' . tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(array('fabricante')), 'SSL', true, false ) . '">' . $aDatos['manufacturers_name'] . '</a>';
	}
	
	// Construimos los tags de orden
	/*if( $sOrden && $sOrden != 1 )
		$sTags .= '<a rel="orden" href="' . tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, tep_get_all_get_params(array('order')), 'SSL', true, false ) . '">' . $aOrders[$sOrden - 1]['text'] . '</a>';*/
	
	// Theme
	// Si existe la variable $bTemplateForm, mostramos solo el fomurlario de busqueda, esto se utiliza cuando se realiza la peticion ajax para cargar el formulario de búsqueda
	if( isset( $bTemplateForm ) )
		include( DIR_THEME_ROOT . 'html/templates/advanced_search_ajax.php' );
	else
		include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );

	// Pie y columna, si es una peticon ajax no mostramos
	if( ! isAjax() )
	{
		include( DIR_THEME. 'html/column_right.php' );
		include( DIR_THEME. 'html/footer.php' );
		include( DIR_WS_INCLUDES . 'application_bottom.php' );
	}
	
?>