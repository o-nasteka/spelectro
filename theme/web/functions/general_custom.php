<?php

	// Preparamos el menu estatico
	$aInformacion = array();
	// Introducimos las ID de información que no queremos mostrar
	$aInformacionDenegadas = array();

	// Obtenemos las pagins de informacion
    $aQuery = tep_db_query( 'SELECT information_id, information_title, parent_id
							 FROM information
							 WHERE visible = 1 and language_id=' . (int)$languages_id . ' and information_group_id = 1
							 ' . (count($aInformacionDenegadas) > 0 ? ' and information_id not in(' . implode( ',', $aInformacionDenegadas ) . ')' : '') . '
							 ORDER BY sort_order' );

    // Creamos el menu de información
    while( $aInfo = tep_db_fetch_array( $aQuery ) )
            $aInformacion[] = array( 'FILE' => 'information.php?info_id=' . $aInfo['information_id'], 'CLASS' => 'sbmu-' . $aInfo['information_id'], 'TITLE' => $aInfo['information_title'] );

	// Creamos el menu estatico
    $aMenuEstatico = array(
        array( 'FILE' => FILENAME_DEFAULT, 'CLASS' => 'bt-ini', 'TITLE' => ENTRY_HOME ),
        array( 'FILE' => FILENAME_PRODUCTS_NEW, 'CLASS' => 'bt-nvds', 'TITLE' => ENTRY_NEWS ),
        array( 'FILE' => FILENAME_SPECIALS, 'CLASS' => 'bt-ofrts', 'TITLE' => ENTRY_OFFERS ),
        array( 'FILE' => FILENAME_INFORMATION, 'CLASS' => 'bt-info-'.$languages_id.'', 'TITLE' => ENTRY_INFORMATION, 'MENU' => $aInformacion ),
        //array( 'FILE' => FILENAME_INFORMATION, 'CLASS' => 'bt-info', 'TITLE' => 'Información' ),
        array( 'FILE' => FILENAME_CONTACT_US, 'CLASS' => 'bt-cntc', 'TITLE' => ENTRY_CONTACT )
    );

	// Preparamos los filtros
	// Si estamos filtrando un fabricante mostramos las categorias de ese fabricante
	if( isset( $_GET['manufacturers_id'] ) )
	{
		$aFiltro = array( '-1' => array( 'TEXT' => ($languages_id == 3 ? 'Todas las categorias' : 'All categories'), 'ACTION' => '' ) );

		$sFiltroSql = 'select distinct c.categories_id as id, cd.categories_name as name
					   from ' . TABLE_PRODUCTS . ' p
					   inner join ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c on (p.products_id = p2c.products_id)
					   inner join ' . TABLE_CATEGORIES . ' c on (p2c.categories_id = c.categories_id)
					   inner join ' . TABLE_CATEGORIES_DESCRIPTION . ' cd on (p2c.categories_id = cd.categories_id)
					   where p.products_status = 1 and cd.language_id = ' . $languages_id . ' and p.manufacturers_id = ' . $_GET['manufacturers_id'] . '
					   order by cd.categories_name';
	}
	// Si no mostramos los fabricantes filtrados por la categoria
	elseif( $current_category_id )
	{
		$aFiltro = array( '-1' => array( 'TEXT' => ($languages_id == 3 ? 'Todos los fabricantes' : 'All manufacturers'), 'ACTION' => '' ) );

		$sFiltroSql = 'select distinct m.manufacturers_id as id, m.manufacturers_name as name
					   from ' . TABLE_PRODUCTS . ' p
					   inner join ' . TABLE_PRODUCTS_TO_CATEGORIES . ' p2c on (p.products_id = p2c.products_id)
					   inner join ' . TABLE_MANUFACTURERS . ' m on (p.manufacturers_id = m.manufacturers_id)
					   where p.products_status = 1 and p2c.categories_id = ' . $current_category_id . '
					   order by m.manufacturers_name';
	}
	// Si no mostramos todos los fabricantes
	else
	{
		$aFiltro = array( '-1' => array( 'TEXT' => ($languages_id == 3 ? 'Todos los fabricantes' : 'All manufacturers'), 'ACTION' => '' ) );

		$sFiltroSql = 'select manufacturers_id as id, manufacturers_name as name
					   from manufacturers
					   order by manufacturers_name asc';
	}


	// Obtenemos todos los fabricantes o categorias
	$aDatos = tep_db_query( $sFiltroSql );

	// Filtro
	while( $aDato = tep_db_fetch_array( $aDatos ) )
		$aFiltro[$aDato['id']] = array( 'TEXT' => $aDato['name'], 'ACTION' => (isset( $_GET['manufacturers_id'] ) ? 'p2c.categories_id = ' : 'p.manufacturers_id = ') . $aDato['id'] );

	// Filtro ordenar
	$aFiltroOrdenar = array( '-1' => array( 'TEXT' => ($languages_id == 3 ? 'Por defecto' : 'By default') ),
							 '1' => array( 'TEXT' => ($languages_id == 3 ? 'Titulo ascendente' : 'Title up' ), 'ACTION' => 'pd.products_name asc' ),
							 '2' => array( 'TEXT' => ($languages_id == 3 ? 'Titulo descendente' : 'Title descending' ), 'ACTION' => 'pd.products_name desc' ),
							 '3' => array( 'TEXT' => ($languages_id == 3 ? 'Precio descendente' : 'Price descending' ), 'ACTION' => 'final_price desc' ),
							 '4' => array( 'TEXT' => ($languages_id == 3 ? 'Precio ascendente' : 'Price up' ), 'ACTION' => 'final_price asc' ) );

	// Filtro numero
	$aFiltroNumero = array( '-1' => array( 'TEXT' => ($languages_id == 3 ? 'Por defecto' : 'By default') ),
							'9' => array( 'TEXT' => '9' ),
							'18' => array( 'TEXT' => '18' ),
							'36' => array( 'TEXT' => '36' ),
							'45' => array( 'TEXT' => '45' ),
							'*' => array( 'TEXT' => 'Todos' ) );

	/**
	 * Nombre: _getFiltro
	 * Informacion: Modulo para mostrar formulario de filtro
	 * Argumentos:
	 *	   - FILTRO: Texto para la opcion filtro [Filtro|string]
	 *	   - ORDENAR: Texto para la opcion ordenar [Ordenar|string]
	 *	   - NUMERO: Texto para la opcion numero [Nº Articulos|string]
	 *	   - VISTA: Mostrar o no el icono de vista dentro del filtro [false|boolean]
	 *	   - SHOW: Mostrar o no el html resultante [false|true]
	**/
	function _getFiltro_custom($aArgumentos = array())
	{

		// Variables
		global $aFiltro, $aFiltroOrdenar, $aFiltroNumero;
		$sFiltro = (empty( $aArgumentos['FILTRO'] ) ? FILTRO_FILTRO : $aArgumentos['FILTRO'] );
		$sOrdenar = (empty( $aArgumentos['ORDENAR'] ) ? FILTRO_ORDENAR : $aArgumentos['ORDENAR'] );
		$nNumero = (empty( $aArgumentos['NUMERO'] ) ? FILTRO_NUMERO : $aArgumentos['NUMERO'] );
		$bVista = (empty( $aArgumentos['VISTA'] ) ? false : $aArgumentos['VISTA'] );
		$aExtra = (empty( $aArgumentos['EXTRA'] ) ? false : $aArgumentos['EXTRA'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sPostFiltro = tep_db_prepare_input( $_GET['filtro'] );
		$sPostOrden = tep_db_prepare_input( $_GET['orden'] );
		$sPostNumero = tep_db_prepare_input( $_GET['numero'] );
		$sHtml = '';
		$sMethod = 'get';

		// Comprobamos si existe el formulario de filtros
		if( isset( $aFiltro ) ||  isset( $aFiltroOrdenar ) || $aFiltroNumero )
		{
			// Si existen campos por post el filtro sera post
			if( count($_POST) > 0 )
			{
				$sMethod = 'post';
				$sPostFiltro = tep_db_prepare_input( $_POST['filtro'] );
				$sPostOrden = tep_db_prepare_input( $_POST['orden'] );
				$sPostNumero = tep_db_prepare_input( $_POST['numero'] );
			}

			$sHtml .= '<form id="fltr" action="' . $_SERVER['PHP_SELF'] . '" method="' . $sMethod . '" class="fltr">';

			// Si tenemos contenido extra que deseamos mostrar arriba
			if( $aExtra && $aExtra['POSITION'] == 'top' )
				$sHtml .= $aExtra['HTML'];


			// Comprobamos si contiene filtro
			if( isset( $aFiltro ) )
			{
				$sHtml .= '<label>' . $sFiltro . '</label>';
				$sHtml .= '<select onchange="this.form.submit();" name="filtro" id="filtro">';
				// Recorremos los filtros para mostrarlo
				foreach( $aFiltro as $key => $value )
					$sHtml .= '<option ' . ($sPostFiltro == $key ? 'selected="selected"' : '') . ' value="' . $key . '">' . $value['TEXT'] . '</option>';
				$sHtml .= '</select>';
			}

			// Comprobamos si contiene orden
			if( isset( $aFiltroOrdenar ) )
			{
				$sHtml .= '<label>' . $sOrdenar . '</label>';
				$sHtml .= '<select onchange="this.form.submit();" name="orden" id="orden">';
				// Recorremos los order para mostrarlo
				foreach( $aFiltroOrdenar as $key => $value )
					$sHtml .= '<option ' . ($sPostOrden == $key ? 'selected="selected"' : '') . ' value="' . $key . '">' . $value['TEXT'] . '</option>';
				$sHtml .= '</select>';
			}

			// Comprobamos si contiene numero
			if( isset( $aFiltroNumero ) )
			{
				$sHtml .= '<label>' . $nNumero . '</label>';
				$sHtml .= '<select name="numero" id="numero">';
				// Recorremos los numeros para mostrarlo
				foreach( $aFiltroNumero as $key => $value )
					$sHtml .= '<option ' . ($sPostNumero == $key ? 'selected="selected"' : '') . ' value="' . $key . '">' . $value['TEXT'] . '</option>';
				$sHtml .= '</select>';
			}

			// Comprobamos si debemos añadir el icono de cambiar vista
			if( $bVista )
				$sHtml .= '<a class="'. (!empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'chng-vsta-hrzt' : 'chng-vsta-vrtl') . '" href="javascript:void(0);" id="chng-vsta">'. ENTRY_CHNG_VST .'</a>';

			// Recorremos todos los campos get
			foreach( $_GET as $key => $value )
				if( !in_array( $key, array( 'filtro', 'orden', 'numero', 'dxfilter' ) ) )
					$sHtml .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';

			// Recorremos todos los campos post
			foreach( $_POST as $key => $value )
				if( !in_array( $key, array( 'filtro', 'orden', 'numero', 'dxfilter' ) ) )
					$sHtml .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';

			$sHtml .= '<input type="hidden" name="dxfilter" value="1" />';

			// Si tenemos contenido extra que deseamos mostrar abajo
			if( $aExtra && $aExtra['POSITION'] == 'bottom' )
				$sHtml .= $aExtra['HTML'];

			$sHtml .= '</form>';

			// Retornamos o mostramos el html resultante
			if( !$bShow )
				return $sHtml;
		}

		echo $sHtml;
	}

	/**
	 * Nombre: _getSearchForm
	 * Informacion: Modulo para mostrar el formulario de busqueda comunmente usado en la cabecera
	 * Argumentos:
	 *	   - VALUE_SUBMIT: Valor del campo submit [Buscar|string]
	 *	   - SHOW: Mostrar o no el html resultante [false|boolean]
	**/
	function _getSearchForm_custom($aArgumentos = array())
	{

		// Variables
	//	global $languages_id;
		$sHtml = '';
		$sHtml = "<script languaje=\"JavaScript\">var buscarLang = '".ENTRY_SEARCH."';</script>";
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sValueSubmit = (empty( $aArgumentos['VALUE_SUBMIT'] ) ? 'Buscar' : $aArgumentos['VALUE_SUBMIT'] );

        $sHtml .= tep_draw_form( 'search', tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, '', 'SSL', false ), 'get', 'id="form-srch" class="navbar-form" onsubmit="return  fnCheckAdvanceSearch(this);"' );
		$sHtml .= '<div class="input-group">';
		$sHtml .= tep_draw_hidden_field( 'description', '1' );
		$sHtml .= tep_draw_hidden_field( 'auto', '1' );
		$sHtml .= tep_draw_input_field( 'buscar', '', 'class="form-control"' );
		$sHtml .= tep_hide_session_id();
		$sHtml .= '<div class="input-group-btn"><button type="submit" class="btn btn-default btn-icon"><i class="icon-search"></i></button></div>';
		$sHtml .= '</div>';
        $sHtml .= '</form>';

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}

		/**
	 * Nombre: _getListaNoticia
	 * Informacion: Modulo para mostrar una lista de noticias
	 * Argumentos:
	 *	   - MAX: Maximo de noticias a mostrar [5|int]
	 *	   - ORDER: Orden de la lista de noticias mediante la fecha [desc|string]
	 *	   - SIZE_TITULO: Tamaño del titulo [false|int]
	 *	   - SIZE_NOTICIA: Tamaño de la noticia [false|int]
	 *	   - SHOW_TITLE: Mostrar titulo de la noticia [true|boolean]
	 *	   - SHOW_DATE: Mostrar fecha de la noticia [true|boolean]
	 *	   - SIZE_MAS: Numero de veces para mostrar enlace hacia "leer mas noticias" [0|int]
	 *	   - SHOW: Mostrar o no el html resultante [false|boolean]
	**/
	function _getListaNoticia_custom($aArgumentos = array())
	{
		global $languages_id;

		// Variables
		$nMax = (empty( $aArgumentos['MAX'] ) ? 5 : $aArgumentos['MAX'] );
		$sOrder = (empty( $aArgumentos['ORDER'] ) ? 'desc' : $aArgumentos['ORDER'] );
		$nSizeTitulo = (empty( $aArgumentos['SIZE_TITULO'] ) ? false : $aArgumentos['SIZE_TITULO'] );
		$nSizeNoticia = (empty( $aArgumentos['SIZE_NOTICIA'] ) ? false : $aArgumentos['SIZE_NOTICIA'] );
		$bShowTitle = (key_exists( 'SHOW_TITLE', $aArgumentos ) ? $aArgumentos['SHOW_TITLE'] : true);
		$bShowDate = (key_exists( 'SHOW_DATE', $aArgumentos ) ? $aArgumentos['SHOW_DATE'] : true);
		$nSizeMas = (key_exists( 'SIZE_MAS', $aArgumentos ) ? $aArgumentos['SIZE_MAS'] : 0);
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sHtml = '';
		global $languages_id;

		// Consultamos las noticias
		$aDatos = tep_db_query( 'select id, date_format(date,"%d/%m/%Y") as date, noticia, titulo
								 from noticias
								 order by date ' . $sOrder . ' limit ' . $nMax );

		// Si hemos obtenido noticias
		if( tep_db_num_rows( $aDatos ) > 0 )
		{
			$sHtml .= '<div id="box-ntcs">';
			$sHtml .= '<a id="ntcs-ttl-'.$languages_id.'" href="' . tep_href_link( 'noticias.php' ) . '" title="Ver todas las noticias"></a>';

			// Mostramos enlaces de ver mas noticias
			for( $nCont = 1; $nCont <= $nSizeMas; $nCont++ )
				$sHtml .= '<a id="box-ntcs-mas-'.$languages_id . $nCont . '" href="' . tep_href_link( 'noticias.php' ) . '" title="Ver todas las noticias"></a>';

			$sHtml .= '<ul>';

			while( $aDato = tep_db_fetch_array( $aDatos ) )
			{
				$sHtml .= '<li>
					<div>' . ($bShowDate ? $aDato['date'] . ' - ' : '') . ($bShowTitle ? ($nSizeTitulo ? truncate( $aDato['titulo'], array( 'SIZE' => $nSizeTitulo ) ) : $aDato['titulo']) : '') . '</div>
					<span>' . ($nSizeTitulo ? truncate( $aDato['noticia'], array( 'SIZE' => $nSizeNoticia, 'CLEAR' => true ) ) : $aDato['noticia']) . '
					<a href="' . getSlug( truncate( $aDato['titulo'], array( 'SIZE' => 50 ) ) ) . '-n-' . $aDato['id'] . '.html" title="Leer noticia completa">'.ENTRY_READ_MORE.'</a></span>
				</li>';
			}

			$sHtml .= '</ul><a id="box-ntcs-mas-'.$languages_id.'" href="noticias.php" title="Ver todas las noticias"></a> </div>';
		}

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}

	/**
	 * Nombre: _getLoginFormHeader
	 * Informacion: Modulo para mostrar el formulario de login comunmente usado en la cabecera
	 * Argumentos:
	 *	   - RECORDAR: Mostrar anchor o no hacia password_forgotten.php [false|boolean]
	 *	   - REGISTRO: Mostrar anchor o no hacia create_account.php [false|boolean]
	 *	   - VALUE_EMAIL: Valor del campo email [E-mail|string]
	 *	   - VALUE_PASSWORD: Valor del campo password [*****|string]
	 *	   - VALUE_SUBMIT: Valor del campo submit [Entrar|string]
	 *	   - SHOW: Mostrar o no el html resultante [false|boolean]
	**/
	function _getLoginFormHeader_custom($aArgumentos = array())
	{

		// Variables
		$bRecordar = (empty( $aArgumentos['RECORDAR'] ) ? false : true );
		$bRegistro = (empty( $aArgumentos['REGISTRO'] ) ? false : true );
		$sValueEmail = (empty( $aArgumentos['VALUE_EMAIL'] ) ? 'E-mail' : $aArgumentos['VALUE_EMAIL'] );
		$sValuePassword = (empty( $aArgumentos['VALUE_PASSWORD'] ) ? '*****' : $aArgumentos['VALUE_PASSWORD'] );
		$sValueSubmit = (empty( $aArgumentos['VALUE_SUBMIT'] ) ? 'Entrar' : $aArgumentos['VALUE_SUBMIT'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sHtml = '';
		global $languages_id;

                $sHtml .= '  <form id="form-lgin-'.$languages_id.'" name="login" method="post" action="login.php?action=process">
                                 <div class="col-md-12">
                                     <div class="form-group">
                                         <input type="text" placeholder="' . $sValueEmail . '" name="email_address" class="form-control"/>
                                     </div>	
                                     <div class="form-group">			
                                         <input type="password" placeholder="' . $sValuePassword . '" name="password" class="form-control"/>
                                     </div>	 
                                     <div class="text-center">    			
                                         <input type="submit" value="' . $sValueSubmit . '" id="form-lgin-sbmt" class="btn btn-default" />
                                     </div>';
		// Comprobamos si debemos pintar el anchor de recordar
		if( $bRecordar )
                        $sHtml .= '  <div class="text-center"><a class="" href="' . tep_href_link( 'password_forgotten.php' ) . '" title="Recordar contraseña">He olvidado mi contraseña</a></div>';

		// Comprobamos si debemos pintar el anchor de registro
		if( $bRegistro )
                        $sHtml .= '  <div class="text-center"> <a class="btn btn-default" href="' . tep_href_link( 'create_account.php' ) . '" title="Registrarse">Crear cuenta</a></div>';
		
		
                $sHtml .= '      </div>';
                $sHtml .= '  </form>';

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}

	/**
	 * Nombre: _getMenuLoginUser
	 * Informacion: Modulo para mostrar el menu de login comunmente usado en la cabecera
	 * Argumentos:
	 *	   - BIENVENIDA: Muestra el mensaje de bienvenida [nombre de usuario|string]
	 *	   - MENU: Array con el menu que deseamos que aparezca [array( array('TEXT' => 'Mis pedidos', 'HREF' => tep_href_link( FILENAME_ACCOUNT_HISTORY ) ),array('TEXT' => 'Novedades', 'HREF' => tep_href_link( FILENAME_PRODUCTS_NEW ) ),array('TEXT' => 'Mi cuenta', 'HREF' => tep_href_link( FILENAME_ACCOUNT ) ),array('TEXT' => 'Desconectar', 'HREF' => tep_href_link( FILENAME_LOGOFF )))|array]
	 *	   - SHOW: Mostrar o no el html resultante [false|boolean]
	**/
	function _getMenuLoginUser_custom($aArgumentos = array())
	{

		// Variables
		$sHtml = '';
		$sBienvenida = (empty( $aArgumentos['BIENVENIDA'] ) ? tep_customer_greeting() . ' - ' : $aArgumentos['BIENVENIDA'] );
		$sBienvenida = (empty( $aArgumentos['BIENVENIDA'] ) ? tep_customer_greeting() . '' : $aArgumentos['BIENVENIDA'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$aMenus = (empty( $aArgumentos['MENU'] ) ? array( array('TEXT' => HEADER_TITLE_MY_ACCOUNT, 'HREF' => tep_href_link( FILENAME_ACCOUNT ) ),
														  array('TEXT' => HEADER_TITLE_ACCOUNT_HISTORY, 'HREF' => tep_href_link( FILENAME_ACCOUNT_HISTORY ) ),
														  array('TEXT' => HEADER_TITLE_LOGOFF, 'HREF' => tep_href_link( FILENAME_LOGOFF ))
														  ): $aArgumentos['MENU'] );

		$sHtml .= '<div id="lgin-menu"><a>' . $sBienvenida . '</a>';
 
		// Recorremos el menu predefinido o dado por el usuario
		foreach( $aMenus as $aMenu )
			 $sHtml .= '<a href="' . $aMenu['HREF'] . '">' . $aMenu['TEXT'] . '</a>';

		//$sHtml = substr( $sHtml, 0, -3 );

		$sHtml .= '</div>';

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}	
	
	
	
	
	function _getMenuLoginUser_custom_mobile($aArgumentos = array())
	{

		// Variables
		$sHtml = '';
		$sBienvenida = (empty( $aArgumentos['BIENVENIDA'] ) ? tep_customer_greeting() . ' <br/> ' : $aArgumentos['BIENVENIDA'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$aMenus = (empty( $aArgumentos['MENU'] ) ? array( array('TEXT' => HEADER_TITLE_MY_ACCOUNT, 'HREF' => tep_href_link( FILENAME_ACCOUNT ) ),
														  array('TEXT' => HEADER_TITLE_ACCOUNT_HISTORY, 'HREF' => tep_href_link( FILENAME_ACCOUNT_HISTORY ) ),
														  array('TEXT' => HEADER_TITLE_LOGOFF, 'HREF' => tep_href_link( FILENAME_LOGOFF ))
														  ): $aArgumentos['MENU'] );

		$sHtml .= '<div id="lgin-menu">' . $sBienvenida;

		// Recorremos el menu predefinido o dado por el usuario
		foreach( $aMenus as $aMenu )
			 $sHtml .= '<a href="' . $aMenu['HREF'] . '">' . $aMenu['TEXT'] . '</a> - ';

		$sHtml = substr( $sHtml, 0, -3 );

		$sHtml .= '</div>';

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}
?>
