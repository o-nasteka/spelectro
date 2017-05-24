<?php
	/*****************************\
	|* INICIO FUNCIONES CLIENTES *|
	\*****************************/

	/**
	 * Funcion que devuelve el ID del cliente con el nos hemos identificado, por defecto 0 cliente normal
	**/
	function getCustomerGroupId()
	{
		if( isset( $_SESSION['sppc_customer_group_id'] ) && $_SESSION['sppc_customer_group_id'] != '0' )
			return $_SESSION['sppc_customer_group_id'];
		else
			return '0';
	}

	/**************************\
	|* FIN FUNCIONES CLIENTES *|
	\**************************/
	
	
	

	/*******************************\
	|* INICIO FUNCIONES CATEGORIAS *|
	\*******************************/
	
	/**
	 * Devuelve todas las id hijas recursivamente desde una categoria padre
	 * Argumentos:
	 *     - @param int $nIdParentPrincipal, Categoria desde donde quieres obtener las demas categorias recursivamente
	**/
	function getIdCategoriasHijasRecursivoByIdCategoriaPadre($nIdParentPrincipal)
	{
		// Funcion recursiva
		function _getIdCategoriasHijasRecursivoByIdCategoriaPadre($aCategorias, $nIdParent)
		{
			$sIds = '';

			foreach( $aCategorias as $key => $value )
			{
				if( $value == $nIdParent )
				{
					$sIds .= $key . ', '; 
					$sIds .= _getIdCategoriasHijasRecursivoByIdCategoriaPadre($aCategorias, $key);
					
				}
			}
			
			return $sIds;
		}
	
		// Obtenemos todas las categorias para no realizar muchas consultas
		$aDatos = tep_db_query( 'select categories_id, parent_id from categories order by parent_id desc' );
		$aCategorias = array();

		while( $aDato = tep_db_fetch_array( $aDatos ) )
			$aCategorias[$aDato['categories_id']] = $aDato['parent_id'];

		return substr( _getIdCategoriasHijasRecursivoByIdCategoriaPadre($aCategorias, $nIdParentPrincipal), 0, -2);
	}

	
	/**
	 * Obtenemos la categoria pasada como argumento
	 * Argumentos:
	 *     - @param int $nId, Id categoria que deseamos
	 */
	function getCategoriaByIdCategoria($nId)
	{
		// Variables
		global $languages_id;
		$aCategorias = null;


		$aCategorias = tep_db_query( 'SELECT ctgr.categories_id, ctgr.categories_image, desp.categories_name, ctgr.categories_image, ctgr.parent_id
									  FROM ' . TABLE_CATEGORIES . ' ctgr
									  INNER JOIN ' . TABLE_CATEGORIES_DESCRIPTION . ' desp ON(ctgr.categories_id = desp.categories_id)
									  WHERE desp.language_id = ' . (int)$languages_id . ' AND ctgr.categories_id = ' . $nId );

		return (tep_db_num_rows( $aCategorias ) ? tep_db_fetch_array( $aCategorias ) : false);
	}
	
	
	/**
	 * Pasas como parametro un id de un producto y obtenemos el ID y el nombre de su categoria
	 *
	 * @global int $languages_id
	 * @param int $nId
	 * @return array $aSalida
	 */
	function getCategoriaProducto($nId)
	{
		// Variables
		global $languages_id;
		$nId = tep_get_product_path( $nId );

		$aCategorias = tep_db_query( 'SELECT c.categories_id, cd.categories_name 
									  FROM ' . TABLE_CATEGORIES . ' c, ' . TABLE_CATEGORIES_DESCRIPTION . ' cd
									  WHERE c.categories_id = ' . (int)$nId . ' and c.categories_id = cd.categories_id and cd.language_id = ' . (int)$languages_id );

		while( $aCategoria = tep_db_fetch_array( $aCategorias ) )
		{
			$aSalida['nombre'] = $aCategoria['categories_name'];
			$aSalida['id'] = $aCategoria['categories_id'];
		}

		return $aSalida;
	}

	/****************************\
	|* FIN FUNCIONES CATEGORIAS *|
	\****************************/
	
	

	/****************************\
	|* INICIO FUNCIONES PRECIOS *|
	\****************************/

	function getMaxPriceProduct($bRedondear)
	{
		// Obtenemos el maximo precio que existe en la web con IVA
		if( DISPLAY_PRICE_WITH_TAX == 'true' )
		{
			if( ! tep_session_is_registered( 'customer_country_id' ) )
			{
				$customer_country_id = STORE_COUNTRY;
				$customer_zone_id = STORE_ZONE;
			}

			$sSql = 'select max( round(((IF(s.status, s.specials_new_products_price, p.products_price) * tr.tax_rate) / 100) + IF(s.status, s.specials_new_products_price, p.products_price), 2) ) as maximo from products p left outer join specials s on (p.products_id = s.products_id and s.status = 1) left outer join ' . TABLE_TAX_RATES . ' tr on (p.products_tax_class_id = tr.tax_class_id) left outer join ' . TABLE_ZONES_TO_GEO_ZONES . ' gz on (tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = "0" or gz.zone_country_id = "' . (int)$customer_country_id . '") and (gz.zone_id is null or gz.zone_id = "0" or gz.zone_id = "' . (int)$customer_zone_id . '")) where p.products_status = 1;';
		}
		// Obtenemos el maximo precio que existe en la web sin IVA
		else
			$sSql = 'select max( round( IF(s.status, s.specials_new_products_price, p.products_price), 2) ) as maximo from products p left outer join specials s on (p.products_id = s.products_id and s.status = 1) where p.products_status = 1;';

		// Lanzamos la consulta para obtener el máximo
		$aMax = tep_db_query( $sSql );

		// Si no hay productos retornamos 0
		if( tep_db_num_rows( $aMax ) == 0 )
			return 0;

		// Obtenemos el máximo
		$aMax = tep_db_fetch_array( $aMax );

		// Comprobamos si deseamos redondear
		if( $bRedondear )
			return ceil( $aMax['maximo'] );
		
		// Retornamos el máximo
		return $aMax['maximo'];
	}
	
	/*************************\
	|* FIN FUNCIONES PRECIOS *|
	\*************************/
	
	

	/******************************\
	|* INICIO FUNCIONES PRODUCTOS *|
	\******************************/

	/**
	 * Pasamos un array con la información del producto para obtener un array con la clase de envio, la clase de oferta, el precio, precio anterior si estuviese en oferta, numero de comentarios, el rating y la puntuacion
	 * Argumentos:
	 *     - @param array $aProducto, Array con los datos del producto
	 * Opciones
	 *     - MAS_INFO: Si deseamos más información obtenemos el numero de comentarios y el rating [false|true]
	 */
	function getInformacionProducto($aProducto, $aArgumentos = array())
	{
		// Variables
		global $currencies;
		$bMasInfo = (empty( $aArgumentos['MAS_INFO'] ) ? false : true );
		$sClassEnvio     = '';
		$sClassOferta    = '';
		$sClassStock     = '';
		$nPrecio         = null;
		$nPrecioAnterior = null;
		$aMasInfo        = array( 'NUMERO_COMENTARIO' => null, 'RANTING' => null, 'PUNTUACION' => null );
		$nCustomerGroupId = getCustomerGroupId();
		$aPrecio = array();
		$aPrecioAnterior = array();

		// Comprobamos si no somos cliente final 0
		if( $nCustomerGroupId > 0 )
		{
			// Obtenemos el precio por grupo de cliente
			$aDatos = tep_db_query( 'select customers_group_price 
									 from ' . TABLE_PRODUCTS_GROUPS . ' 
									 where products_id = ' . $aProducto['products_id'].' and customers_group_id = '.$nCustomerGroupId  );

			// Si contemos un nuevo precio, modificamos el precio actual
			if( tep_db_num_rows( $aDatos ) > 0 )
			{
				$aDato = tep_db_fetch_array( $aDatos );
				$aProducto['products_price'] = $aDato['customers_group_price'];
			}
		}

		// Obtenemos el stock
		if( $aProducto['products_quantity'] <= 0 ) // Rojo sin productos
			$sClassStock = 'prdct-icon-stockR';
		else // Verde muchos productos
			$sClassStock = 'prdct-icon-stockV';

		// Obtenemos si el envio es gratuito o no
		$sClassEnvio = (getProductFreeShipping( $aProducto['products_id'] ) > 0 ? 'prdct-envo' : '');

		// Obtenemos el precio en oferta, si no esta en oferta devuelve 0
		$nPrecio = tep_get_products_special_price( $aProducto['products_id'] );

		// Si hemos obtenido precio en oferta
		if( $nPrecio > 0 )
		{
			$sClassOferta = 'prdct-ofrt';
			$nPrecio = $currencies->display_price( $nPrecio, tep_get_tax_rate( $aProducto['products_tax_class_id'] ) );
			$nPrecioAnterior = $currencies->display_price( $aProducto['products_price'], tep_get_tax_rate( $aProducto['products_tax_class_id'] ) );
		}
		else
			$nPrecio = $currencies->display_price( $aProducto['products_price'], tep_get_tax_rate( $aProducto['products_tax_class_id'] ) );

		// Separamos el precio en un array
		$aPrecio = explode( ',', $nPrecio );
		$aPrecioAnterior = explode( ',', $nPrecioAnterior );	
			
		// Si deseamos más información obtenemos el numero de comentarios y el rating
		if( $bMasInfo )
			$aMasInfo = getRatingAndNumReviews( $aProducto['products_id'] );

		return array(
			'TITLE' => tep_output_string( $aProducto['products_name'], array( '"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;', '&' => '&amp;' ) ),
			'CLASS_ENVIO' => $sClassEnvio,
			'CLASS_OFERTA' => $sClassOferta,
			'CLASS_STOCK' => $sClassStock,
			'PRECIO' => $nPrecio,
			'PRECIO_ANTERIOR' => $nPrecioAnterior,
			'ARRAY_PRECIO' => $aPrecio,
			'ARRAY_PRECIO_ANTERIOR' => $aPrecioAnterior,
			'NUMERO_COMENTARIO' => $aMasInfo['NUMERO_COMENTARIO'],
			'RATING' => $aMasInfo['RATING'],
			'PUNTUACION' => $aMasInfo['PUNTUACION'],
		);
	}


	/**
	 * Devuelve la descripcion de un producto pasado como argumento
	 *
	 * @global $languages_id $languages_id
	 * @param int $product_id
	 * @param $languages_id $language
	 * @return string
	 */
	function getDescriptionProductById($product_id, $language = '')
	{
		global $languages_id;

		if (empty($language)) $language = $languages_id;

		$product_query = tep_db_query("select products_description from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$product_id . "' and language_id = '" . (int)$language . "'");
		$product = tep_db_fetch_array($product_query);

		return $product['products_description'];
	}
	

	/**
	 * Obtenemos si el producto pasado como argumento contiene envio gratuito, devuelve 0 o 1
	 * Argumentos:
	 *     - @param int $nId, Id del producto a comprobar
	 */
	function getProductFreeShipping($nId)
	{
		$aProductos = tep_db_query( 'SELECT products_free_shipping FROM ' . TABLE_PRODUCTS . ' WHERE products_id = ' . (int)$nId );
		$aProducto = tep_db_fetch_array( $aProductos );

		return $aProducto['products_free_shipping'];
	}


	/**
	 * Obtenemos el numero de comentarios y la puntuacion que tiene
	 * Argumentos:
	 *	   - @param int $nId, Id del producto a comprobar
	 *	   - @param int $nMax, Maximo de puntos que puede obtener, por defecto 5
	 */
	function getRatingAndNumReviews($nId, $nMax = 5)
	{
		global $languages_id;

		// Sumamos todos los puntos de los comentarios
		$aRating = tep_db_fetch_array( tep_db_query( 'select sum(r.reviews_rating) as TOTAL
													  from ' . TABLE_REVIEWS . ' r, ' . TABLE_PRODUCTS . ' p, ' . TABLE_REVIEWS_DESCRIPTION . ' rd
													  where p.products_status = 1 AND r.approved = 1 AND r.products_id = p.products_id AND r.reviews_id = rd.reviews_id AND rd.languages_id = ' . $languages_id . ' AND p.products_id = ' . $nId ) );

		// Obtenemos el numero de comentarios que tiene el producto
		$nNumReviews = getNumReviews( $nId );

		// Obtenemos el numero maximo que podemos obtener
		$nMaxRating = $nMax * $nNumReviews;

		// Obtenemos la puntuacion
		$nPuntuacion = (int)($nMaxRating ? ($aRating['TOTAL'] * $nMax) / $nMaxRating : 0);

		return array(
			'NUMERO_COMENTARIO' => $nNumReviews,
			'RATING' => (int)$nPuntuacion,
			'PUNTUACION' => $nPuntuacion
		);
	}


	/**
	 * Obtenemos la cantidad de comentarios que tiene aprobado un producto
	 * Argumentos:
	 *	   - @param int $nId, Id del producto a comprobar
	 */
	function getNumReviews($nId)
	{
		global $languages_id;

		$aReview = tep_db_query( 'SELECT count(*) AS COUNT 
								  FROM ' . TABLE_REVIEWS . ' r, ' . TABLE_REVIEWS_DESCRIPTION . ' rd
								  WHERE r.products_id = ' . $nId . ' AND r.reviews_id = rd.reviews_id and approved = 1 AND rd.languages_id = ' . $languages_id );

		$aReview = tep_db_fetch_array( $aReview );

		return $aReview['COUNT'];
	}


	/**
	 * Devuelve la tabla de rappels de un producto
	 */
	function getTableRappels($nId)
	{
		// Variables
		global $currencies, $customer_group_id;
		$aDatos = null;
		$aAux = array();

		// Realizamos la consulta para obtener los rappels
		$aDatos = tep_db_query( 'select pb.products_price, pb.products_qty, p.products_tax_class_id
								 from products_price_break pb
								 inner join products p ON (pb.products_id = p.products_id)
								 where pb.customers_group_id = ' . $customer_group_id . ' and p.products_id = ' . $nId . '
								 order by pb.products_qty asc' );
								 
		$aDatos2 = tep_db_query( 'select pb.products_price, pb.products_qty, p.products_tax_class_id
								 from products_price_break pb
								 inner join products p ON (pb.products_id = p.products_id)
								 where pb.customers_group_id = ' . $customer_group_id . ' and p.products_id = ' . $nId . '
								 order by pb.products_qty asc' );		
	
	// Si hemos obtenido rappels -> Comento array (Isra)
		/* if( mysql_num_rows( $aDatos ) )
		{
			while( $aDato = tep_db_fetch_array( $aDatos ) )
				$aAux[] = array( 'CANTIDAD' => $aDato['products_qty'], 'PRECIO' => $currencies->display_price( $aDato['products_price'], tep_get_tax_rate( $aDato['products_tax_class_id'] ) ) );
		}
		
		
		*/
		if( mysqli_num_rows( $aDatos ) )
		{
			while( $aDato = tep_db_fetch_array( $aDatos ) )
				$aAux[] = array( 'CANTIDAD' => $aDato['products_qty'], 'PRECIO' => $currencies->display_price( $aDato['products_price'], tep_get_tax_rate( $aDato['products_tax_class_id'] ) ) );
		}
		
		
		
		
		

		return $aAux;
	}
	
	/*	// Si hemos obtenido rappels	
	    if( mysql_num_rows( $aDatos ) )
    	{
        	$sHtml .= '<table id="fich-tbl"><tr><td>Descuento x cantidades</td>';
        while( $aDato = tep_db_fetch_array( $aDatos ) )
            $sHtml .= '<td>' . $aDato['products_qty'] . ' o más</td>';
            
            $sHtml .= '</tr><tr class="tr-claro"><td>Precio Unidad</td>';
        while( $aDato2 = tep_db_fetch_array( $aDatos2 ) )
            $sHtml .= '<td>' . $currencies->display_price( $aDato2['products_price'], tep_get_tax_rate( $aDato2['products_tax_class_id'] ) ) . '</td>';
        	$sHtml .= '</tr></table>';
    	}

    	return $sHtml;
	}*/

	
	/*<table id="fich-tbl">
            	<tr>
            		<td>Descuento x cantidades</td>
            		<td>2 - 4</td>
            		<td>5 - 8</td>
            		<td>+ 8</td>
            	</tr>
            	<tr class="tr-claro">
            		<td>19,99€</td>
            		<td>19,99€</td>
            		<td>19,99€</td>
            		<td>19,99€</td>
            	</tr>
            </table>*/
	
	/**
	 * Obtenemos los productos de la consulta SQL paginados y cambiados los precios segun el tipo de cliente
	 * Argumentos:
	 *	   - @param string $sSql, SQL con los productos
	 * Opciones:
	 *     - PAGINAR: Paginar o no el resultado. [1|0]
	 *     - COUNT_KEY: Cuando realiza la paginacion por que campo realiza el count para saber los productos que tiene. [*|string]
	 */
	function changePriceCustomer($sSql, $aArgumentos = array())
	{
		// Variables
		$customer_group_id = getCustomerGroupId();
		$aProductos = array();
		$sNumero = tep_db_prepare_input( $_GET['numero'] );
		$sCountKey = (!isset( $aArgumentos['COUNT_KEY'] ) ? '*' : $aArgumentos['COUNT_KEY'] );
		$bPaginar = (!isset( $aArgumentos['PAGINAR'] ) ? true : $aArgumentos['PAGINAR'] );
		$bAjax = (!isset( $aArgumentos['AJAX'] ) ? false : $aArgumentos['AJAX'] );
		$sIds = '';
	
		// Si contenemos datos por post el filtro se ha realizado por ese metodo
		if( count( $_POST ) > 0 )
			$sNumero = tep_db_prepare_input( $_POST['numero'] );
	
		if( $sNumero == '*' || $bAjax )
			$sNumero = OSCDENOX_CANTIDAD_PRODUCTOS_LISTADO_AJAX;
	
		// Comprobamos si vamos a paginar el resultado
		if( $bPaginar )
		{
			// Comprobamos si no tenemos numero maximo para mostrar
			if( ! $sNumero || $sNumero == '-1' )
				$sNumero = OSCDENOX_CANTIDAD_PRODUCTOS_LISTADO;
			
			// Paginamos la consulta
			$aDatosSplit = new splitPageResults( $sSql, $sNumero, $sCountKey );
			
			// Modificamos el SQL
			$sSql = $aDatosSplit->sql_query;
		}

		$aDatos = tep_db_query( $sSql );
		$no_of_products_new = tep_db_num_rows( $aDatos );

		// Si hemos obtenido datos
		if( $no_of_products_new > 0 )
		{
			while( $aDato = tep_db_fetch_array( $aDatos ) )
			{
				$aProductos[] = $aDato;
				$sIds .= 'products_id = ' . $aDato['products_id'] . ' or ';
			}

			// Si el tipo de cliente no es cliente final cambiamos precios
			if( $customer_group_id != 0 )
			{
				// Quitamos el "or" sobrante
				$select_list_of_prdct_ids = substr($sIds, 0, -4);
				
				// Consultamos los precios de los productos para el tipo de cliente
				$pg_query = tep_db_query( 'select pg.products_id, customers_group_price as price
										   from ' . TABLE_PRODUCTS_GROUPS . ' pg 
										   where (' . $select_list_of_prdct_ids . ') and pg.customers_group_id = ' . $customer_group_id );

				// Obtenemos los nuevos precios
				while( $pg_array = tep_db_fetch_array( $pg_query ) )
					$new_prices[] = array ('products_id' => $pg_array['products_id'], 'products_price' => $pg_array['price'], 'specials_new_products_price' => '');

				// Cambiamos los precios
				for( $x = 0; $x < $no_of_products_new; $x++ )
				{
					if( !empty($new_prices) )
					{
						for( $i = 0; $i < count($new_prices); $i++ )
						{
							if( $aProductos[$x]['products_id'] == $new_prices[$i]['products_id'] )
								$aProductos[$x]['products_price'] = $new_prices[$i]['products_price'];
						}
					}
				}
				
				// Por ultimo obtenemos los precios en oferta para los produtos
				$specials_query = tep_db_query("select s.products_id, specials_new_products_price from " . TABLE_SPECIALS . " s  where (".$select_list_of_prdct_ids.") and status = '1' and s.customers_group_id = '" .$customer_group_id. "'");

				while( $specials_array = tep_db_fetch_array( $specials_query ) )
					$new_s_prices[] = array ('products_id' => $specials_array['products_id'], 'products_price' => '', 'specials_new_products_price' => $specials_array['specials_new_products_price']);
					
				// Si el producto se encuentra en oferta cambiamos el precio
				if( !empty( $new_s_prices ) )
				{
					for( $x = 0; $x < $no_of_products_new; $x++ )
					{
						for( $i = 0; $i < count( $new_s_prices ); $i++ )
						{
							if( $aProductos[$x]['products_id'] == $new_s_prices[$i]['products_id'] )
								$aProductos[$x]['specials_new_products_price'] = $new_s_prices[$i]['specials_new_products_price'];
						}
					}
				}
			}
		}

		return array( 'PRODUCTOS' => $aProductos, 'PAGE_PRODUCTOS' => $aDatosSplit );
	}

	/***************************\
	|* FIN FUNCIONES PRODUCTOS *|
	\***************************/
	
	
	

	/***************************\
	|* INICIO FUNCIONES VARIAS *|
	\***************************/

	/**
	 * Obtenemos si la peticion es AJAX
	 */
	function isAjax()
	{
	   return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	/**
	 * Obtenemos los productos de la consulta SQL filtrados
	 * Argumentos:
	 *	   - @param string $sSql, SQL con los productos
	 */
	function changeFilter(&$sSql, $aArgumentos = array())
	{
		// Variables
		global $aFiltro, $aFiltroOrdenar;
		$sFiltro = tep_db_prepare_input( $_GET['filtro'] );
		$sOrden = tep_db_prepare_input( $_GET['orden'] );

		// Si contenemos datos por post el filtro se ha realizado por ese metodo
		if( count( $_POST ) > 0 )
		{
			$sFiltro = tep_db_prepare_input( $_POST['filtro'] );
			$sOrden = tep_db_prepare_input( $_POST['orden'] );
		}
				
		// Comprobamos si existe el filtro
		if( $sFiltro && $sFiltro != '-1' && key_exists( $sFiltro, $aFiltro ) )
		{
			// Modificamos el where
			$sSql = str_replace( 'where ', 'where ' . $aFiltro[$sFiltro]['ACTION'] . ' and ', $sSql );
		}

		// Comprobamos si existe el ordenar
		if( $sOrden && $sOrden != '-1' && key_exists( $sOrden, $aFiltroOrdenar ) )
		{
			// Modificamos el order by
			$sSql = preg_replace( '/order by (.+)$/i', 'order by ' . $aFiltroOrdenar[$sOrden]['ACTION'], $sSql );
		}
	}
	
	
	/**
	 * Funcion que devuelve el numero aleatorio
	**/
	function random()
	{
		list( $usec, $sec ) = explode( ' ', microtime() );
		srand( (float) $sec + ((float) $usec * 100000) );
		return rand();
	}
	

	/**
	 * Devuelve una cadena convertida a slug, convirtiendo los espacios al caracter deseado
	 *
	 * @param string $sTexto
	 * @param string $sSeparator
	 * @return string
	 */
	function getSlug($sTexto, $sSeparator = '-')
	{
		// Convertimos los caracteres especiales
		$sTexto = utf8_decode( $sTexto );
		$sTexto = htmlentities( $sTexto );
		$sTexto = preg_replace( '/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $sTexto );
		$sTexto = html_entity_decode( $sTexto );

		// Pasamos a minusculas
		$sTexto = strtolower( $sTexto );

		// Convertimos los caracteres no permitidos a espacios
		$sTexto = preg_replace('/\W/', ' ', $sTexto);

		// Reemplazamos los espacios por el separador
		$sTexto = preg_replace( '/\ +/', $sSeparator, $sTexto );

		// Hacemos un trim para quitar espacios sobrantes
		$sTexto = trim( $sTexto, $sSeparator );

		return $sTexto;
	}
	
	
	/*
	 * Muestra el precio del producto en imagenes
	 * @param array $aProductoInformacion
	 */
	function getPrecioImagen($aProductoInformacion)
	{
		// Variables
		$sPrecio  = str_replace( '&euro;', '', $aProductoInformacion['PRECIO'] );
		$sHtml    = '';
		$aPrecios = explode( ',', $sPrecio );
		$sClass   = array( 'entr', 'dcml' );
		$nTotal   = 0;

		// Si no hemos obtenido bien el precio retornamos
		if( count($aPrecios) < 2 )
			return $aProductoInformacion['PRECIO'];

		// Recorremos los enteros y los decimales
		foreach( $aPrecios as $nCont => $aPrecio )
		{
			// Obtenemos cuantos numeros tiene
			$nTotal = strlen( $aPrecio ) - 1;

			// Recorremos los numeros enteros y los numeros decimales
			for( $nPos = 0; $nPos <= $nTotal; $nPos++ )
				$sHtml .= '<span class="' . $sClass[$nCont] . $aPrecio[$nPos] . '">' . $aPrecio[$nPos] . '</span>';

			// Si nos encontramos en los enteros ($nCont == 0) y tenemos decimales (count( $aPrecios ) > 0) añadimos la cama
			if( $nCont == 0 && count( $aPrecios ) > 0 )
				$sHtml .= '<span class="coma">,</span>';
		}

		return $sHtml . '<span class="euro">€</span>';
	}
	
	
	function getPrecioTexto($aProductoInformacion)
	{
		// Variables
		/*$sPrecio  = str_replace( '&euro;', '', $aProductoInformacion['PRECIO'] );
		$sHtml    = '';
		$aPrecios = explode( ',', $sPrecio );
		$sClass   = array( 'entr', 'dcml' );
		$nTotal   = 0;

		// Si no hemos obtenido bien el precio retornamos
		if( count($aPrecios) < 2 )
			return $aProductoInformacion['PRECIO'];

		// Recorremos los enteros y los decimales
		foreach( $aPrecios as $nCont => $aPrecio )
		{
			// Obtenemos cuantos numeros tiene
			$nTotal = strlen( $aPrecio ) - 1;

			// Recorremos los numeros enteros y los numeros decimales
			for( $nPos = 0; $nPos <= $nTotal; $nPos++ )
				$sHtml .= '<span class="' . $sClass[$nCont] . $aPrecio[$nPos] . '">' . $aPrecio[$nPos] . '</span>';

			// Si nos encontramos en los enteros ($nCont == 0) y tenemos decimales (count( $aPrecios ) > 0) añadimos la cama
			if( $nCont == 0 && count( $aPrecios ) > 0 )
				$sHtml .= '<span class="coma">,</span>';
		}
*/
		//return $sHtml . '<span class="euro">€</span>';
		return 233;
	}
	
	

	/**
	 * Acorta un string segun un tamaño considerando que no corte las palabras finales al añadir los puntos suspensivos
	 * Argumentos:
	 *	   - @param string $text, Texto a cortar
	 * Opciones:
	 *     - SIZE: Cantidad de caracteres antes de cortar [100|int]
	 *     - END: Cuando corte mostrar al final [...|string]
	 *     - EXACT: No cortar palabras [true|false]
	 *     - HTML: No cortar html [false|true]
	**/
	function truncate($text, $aArgumentos = array())
	{
		$length = (empty( $aArgumentos['SIZE'] ) ? 100 : $aArgumentos['SIZE'] );
		$ending = (empty( $aArgumentos['END'] ) ? '...' : $aArgumentos['END'] );
		$exact = (empty( $aArgumentos['EXACT'] ) ? true : $aArgumentos['EXACT'] );
		$considerHtml = (empty( $aArgumentos['HTML'] ) ? false : $aArgumentos['HTML'] );
		$bClear = (empty( $aArgumentos['CLEAR'] ) ? false : $aArgumentos['CLEAR'] );

		// UTF-8
		mb_internal_encoding("UTF-8");
		
		// Comprobamos si debemos limpiar la cadena
		if( $bClear )
		{
			$caracters_no_permitidos = array('"',"'");
			# paso los caracteres entities tipo &aacute; $gt;etc a sus respectivos html
			$s = html_entity_decode($text,ENT_COMPAT,'UTF-8');
			# quito todas las etiquetas html y php
			$s = strip_tags($s);	
			$s = str_replace( array( chr(13), chr(10) ), array( '', '', '' ), $s );
			# elimino los caracters como comillas dobles y simples
			$text = str_replace($caracters_no_permitidos,"",$s);
		}
	
		if (is_array($ending))
		{
			extract($ending);
		}
		if ($considerHtml) {
			if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			$totalLength = mb_strlen($ending);
			$openTags = array();
			$truncate = '';
			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
			foreach ($tags as $tag) {
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
						array_unshift($openTags, $tag[2]);
					} else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
						$pos = array_search($closeTag[1], $openTags);
						if ($pos !== false) {
							array_splice($openTags, $pos, 1);
						}
					}
				}
				$truncate .= $tag[1];

				$contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $tag[3]));
				if ($contentLength + $totalLength > $length) {
					$left = $length - $totalLength;
					$entitiesLength = 0;
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
						foreach ($entities[0] as $entity) {
							if ($entity[1] + 1 - $entitiesLength <= $left) {
								$left--;
								$entitiesLength += mb_strlen($entity[0]);
							} else {
								break;
							}
						}
					}

					$truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
					break;
				} else {
					$truncate .= $tag[3];
					$totalLength += $contentLength;
				}
				if ($totalLength >= $length) {
					break;
				}
			}

		} else {
			$text = strip_tags($text);
			if (mb_strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = mb_substr($text, 0, $length - strlen($ending));
			}
		}
		if (!$exact) {
			$spacepos = mb_strrpos($truncate, ' ');
			if (isset($spacepos)) {
				if ($considerHtml) {
					$bits = mb_substr($truncate, $spacepos);
					preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
					if (!empty($droppedTags)) {
						foreach ($droppedTags as $closingTag) {
							if (!in_array($closingTag[1], $openTags)) {
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}
				$truncate = mb_substr($truncate, 0, $spacepos);
			}
		}

		$truncate .= $ending;

		if ($considerHtml) {
			foreach ($openTags as $tag) {
				$truncate .= '';
			}
		}

		return $truncate;
	}

	
	/**
	 * Pinta los metatags necesarios en la cabecera
	 */
	function getHeader()
	{
		global $lng;

		echo '<meta http-equiv="Content-Type" content="text/html; charset=' . CHARSET . '" />'."\n";
		echo '<meta name="language" content="es" />'."\n";
		metatags();
		echo '<link rel="canonical" href="' . CanonicalUrl() . '" />'."\n";
		echo '<base href="' . (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . '" />'."\n";

		include( DIR_THEME. 'scripts/scripts.php' );

		if( !isset( $lng ) || ( isset( $lng ) && !is_object( $lng ) ) )
		{
			include_once( DIR_WS_CLASSES . 'language.php' );
			$lng = new language;
		}

		reset( $lng->catalog_languages );

		while (list($key, $value) = each($lng->catalog_languages))
			echo '<link rel="alternate" type="application/rss+xml" title="' . STORE_NAME . ' - ' . BOX_INFORMATION_RSS . '" href="' . FILENAME_RSS . '?language=' . $key . '" />'."\n";
	}
	
	
	/**
	 * Obtenemos la url actual donde nos enconramos
	 *
	 * @return string $sUrl
	 */
	function getCurrentUrl()
	{
		// Variables
		$sUrl = 'http';

		if( $_SERVER["HTTPS"] == "on" )
			$sUrl .= "s";

		$sUrl .= "://";
	 
		if( $_SERVER["SERVER_PORT"] != "80" )
			$sUrl .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		else
			$sUrl .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

		return $sUrl;
	}

	
	/**
	 * Comprueba si existe el id en la tabla
	 *
	 * - @param string $sTable, Tabla a comprobar
	 * - @param string $sCampo, Campo a comprobar
	 * - @param int $nId, Valor del campo a comprobar
	 * - @param int $sWhere, Consulta estra para la sentencia where
	 *	 
	 * @return bool
	 */
	function checkId($sTable, $sCampo, $nId, $sWhere = '')
	{
		// Comprobamos que sea un ID valido
		if( ! is_int( (int)$nId ) )
			return false;
	
		// Comprobamos si existe
		$aDatos = tep_db_query( 'select ' . $sCampo . ' from ' . $sTable . ' where ' . $sCampo . ' = ' . $nId . ' ' . $sWhere );

		// Si existe
		if( tep_db_num_rows( $aDatos ) )
			return true;

		return false;
	}
	
	/**
	 * Obtener arbol de categorías
	 *	 
	 * @return bool
	 */
	function getCategoriasHijasByIdCategoria($nIdParentPrincipal)
	{
		// Obtenemos todas las categorias para no realizar muchas consultas
		$aDatos = tep_db_query( 'select c.categories_id, c.parent_id, c.categories_id, cd.categories_name 
								 from categories c
								 inner join categories_description cd on(cd.categories_id = c.categories_id)
								 order by sort_order, cd.categories_name asc' );
		$aCategorias = array();
		$aCategoriasAux = array();

		function tep_get_path_array_revursivo($nIdCurrent, $aCategorias)
		{
			$cPath = $nIdCurrent . '_';

			// obtenemos quien es el padre
			if( $aCategorias[$nIdCurrent]['parent_id'] != 0 )
				$cPath .= tep_get_path_array_revursivo( $aCategorias[$nIdCurrent]['parent_id'], $aCategorias );

			return $cPath;
		}
		
		function tep_get_path_array($nIdCurrent, $aCategorias)
		{
			return 'cPath=' . implode( '_', array_reverse( explode( '_', substr( tep_get_path_array_revursivo($nIdCurrent, $aCategorias), 0, -1 ) ) ) );
		}
			
		function getCategoriasHijasByIdCategoriaAux( $aCategorias, $nParent, &$aCategoriasAux )
		{		
			foreach( $aCategorias as $key => $value )
			{
				if( $value['parent_id'] == $nParent )
				{
					$aCategoriasAux[$key]['categories_name'] = $value['categories_name'];
					$aCategoriasAux[$key]['categories_id'] = $value['categories_id'];
					$aCategoriasAux[$key]['parent_id'] = $value['parent_id'];
					$aCategoriasAux[$key]['href'] = tep_href_link( FILENAME_DEFAULT, tep_get_path_array( $value['categories_id'], $aCategorias ) );
					$aCategoriasAux[$key]['subcategorias'] = array();
					
					getCategoriasHijasByIdCategoriaAux( $aCategorias, $key, $aCategoriasAux[$key]['subcategorias'] );
				}
			}
		}
		
		while( $aDato = tep_db_fetch_array( $aDatos ) )
		{
			$aCategorias[$aDato['categories_id']] = $aDato;
			
			if( $aDato['parent_id'] == $nIdParentPrincipal )
				$aCategoriasAux[$aDato['categories_id']] = array();
		}
		
		getCategoriasHijasByIdCategoriaAux( $aCategorias, $nIdParentPrincipal, $aCategoriasAux );
		
		return $aCategoriasAux;
	}
	/************************\
	|* FIN FUNCIONES VARIAS *|
	\************************/