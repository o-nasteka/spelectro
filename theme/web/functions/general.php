<?php

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
	
	
	function _getLoginFormHeader($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getLoginFormHeader_custom' ) )
			return call_user_func_array( '_getLoginFormHeader_custom', array( $aArgumentos ) );
	
		// Variables
		$bRecordar = (empty( $aArgumentos['RECORDAR'] ) ? false : true );
		$bRegistro = (empty( $aArgumentos['REGISTRO'] ) ? false : true );
		$sValueEmail = (empty( $aArgumentos['VALUE_EMAIL'] ) ? 'E-mail' : $aArgumentos['VALUE_EMAIL'] );
		$sValuePassword = (empty( $aArgumentos['VALUE_PASSWORD'] ) ? '*****' : $aArgumentos['VALUE_PASSWORD'] );
		$sValueSubmit = (empty( $aArgumentos['VALUE_SUBMIT'] ) ? 'Entrar' : $aArgumentos['VALUE_SUBMIT'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sHtml = '';

		$sHtml .= '<form id="form-lgin" name="login" method="post" action="login.php?action=process">
                <input type="text" placeholder="' . $sValueEmail . '" name="email_address" id="form-lgin-name"/>
                <input type="password" placeholder="' . $sValuePassword . '" name="password" id="form-lgin-pass"/>
				<input type="submit" placeholder="' . $sValueSubmit . '" id="form-lgin-sbmt" class="hovr" />';

		// Comprobamos si debemos pintar el anchor de recordar
		if( $bRecordar )
			$sHtml .= '<a href="' . tep_href_link( 'password_forgotten.php' ) . '" title="Recordar contraseña" id="form-lgin-rcdr">Recordar contraseña</a>';

		// Comprobamos si debemos pintar el anchor de registro
		if( $bRegistro )
			$sHtml .= '<a href="' . tep_href_link( 'create_account.php' ) . '" title="Registrarse" id="form-lgin-crat">Registrarse</a>';

        $sHtml .= '</form>';

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
	function _getMenuLoginUser($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getMenuLoginUser_custom' ) )
			return call_user_func_array( '_getMenuLoginUser_custom', array( $aArgumentos ) );

		// Variables
		$sHtml = '';
		$sBienvenida = (empty( $aArgumentos['BIENVENIDA'] ) ? tep_customer_greeting() . ' - ' : $aArgumentos['BIENVENIDA'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$aMenus = (empty( $aArgumentos['MENU'] ) ? array( array('TEXT' => 'Mi cuenta', 'HREF' => tep_href_link( FILENAME_ACCOUNT ) ),
														  array('TEXT' => 'Mis pedidos', 'HREF' => tep_href_link( FILENAME_ACCOUNT_HISTORY ) ), 
														  array('TEXT' => 'Desconectar', 'HREF' => tep_href_link( FILENAME_LOGOFF ))
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


	
	
	
	
	
	
	
	
	function _getMenuLoginUserMobile($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getMenuLoginUser_custom' ) )
			return call_user_func_array( '_getMenuLoginUser_custom_mobile', array( $aArgumentos ) );

		// Variables
		$sHtml = '';
		$sBienvenida = (empty( $aArgumentos['BIENVENIDA'] ) ? tep_customer_greeting() . ' <br/>' : $aArgumentos['BIENVENIDA'] );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$aMenus = (empty( $aArgumentos['MENU'] ) ? array( array('TEXT' => 'Mi cuenta', 'HREF' => tep_href_link( FILENAME_ACCOUNT ) ),
														  array('TEXT' => 'Mis pedidos', 'HREF' => tep_href_link( FILENAME_ACCOUNT_HISTORY ) ), 
														  array('TEXT' => 'Desconectar', 'HREF' => tep_href_link( FILENAME_LOGOFF ))
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


	
	
	
	
	
	
	
	
	
	/**
	 * Nombre: _getMenuEstatico
	 * Informacion: Modulo para mostrar el menu estatico de la web, cuando se encuentra en un modulo añade la clase activo para dar el efecto de seleccionado 
	 * Argumentos:
	 *	   - SHOW_SUBMENU: Muestra o no submenu [true|boolean]
	 *	   - SHOW: Mostrar o no el html resultante [false|boolean]
	 */
	function _getMenuEstatico($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getMenuEstatico_custom' ) )
			return call_user_func_array( '_getMenuEstatico_custom', array( $aArgumentos ) );

		// Variables
		global $aMenuEstatico;
		$bSubmenu = (!isset( $aArgumentos['SHOW_SUBMENU'] ) ? true : false );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sHtml = '';
		$sFile = basename( $_SERVER['SCRIPT_NAME'] );
		$sUrl = null;

		// Recorremos el menu principal
		foreach( $aMenuEstatico as $aMenu )
		{		
			// Comprobamos si la url empieza por http para no llamar a la funcion tep_href_link
			if( preg_match( '/^http/i', $aMenu['FILE'] ) )
				$sUrl = $aMenu['FILE'];
			else
				$sUrl = tep_href_link( $aMenu['FILE'] );
		
			$sHtml .= '<li' . ($sFile == $aMenu['FILE'] ? ' class="actv"' : '') . '><a class="' . $aMenu['CLASS'] . '" href="' . $sUrl . '" title="' . $aMenu['TITLE'] . '">' . $aMenu['TITLE'] . '</a>';

			// Si contiene submenu
			if( !empty( $aMenu['MENU'] ) && $bSubmenu )
			{		
				$sHtml .= '<ul>';

				// Recorremos el submenu
				foreach( $aMenu['MENU'] as $aSubMenu )
				{
					// Comprobamos si la url empieza por http para no llamar a la funcion tep_href_link
					if( preg_match( '/^http/i', $aSubMenu['FILE'] ) )
						$sUrl = $aSubMenu['FILE'];
					else
						$sUrl = tep_href_link( $aSubMenu['FILE'] );
				
					$sHtml .= '<li' . ($sFile == $aSubMenu['FILE'] ? ' class="actv"' : '') . '><a class="' . $aSubMenu['CLASS'] . '" href="' . $sUrl . '" title="' . $aSubMenu['TITLE'] . '">' . $aSubMenu['TITLE'] . '</a></li>';
				}

				$sHtml .= '</ul>';
			}

			$sHtml .= '</li>';
		}

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}

	
	function _getMenuEstatico_mobile($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getMenuEstatico_custom' ) )
			return call_user_func_array( '_getMenuEstatico_custom', array( $aArgumentos ) );

		// Variables
		global $aMenuEstatico;
		$bSubmenu = (!isset( $aArgumentos['SHOW_SUBMENU'] ) ? true : false );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sHtml = '';
		$sFile = basename( $_SERVER['SCRIPT_NAME'] );
		$sUrl = null;

		// Recorremos el menu principal
		foreach( $aMenuEstatico as $aMenu )
		{		
			// Comprobamos si la url empieza por http para no llamar a la funcion tep_href_link
			if( preg_match( '/^http/i', $aMenu['FILE'] ) )
				$sUrl = $aMenu['FILE'];
			else
				$sUrl = tep_href_link( $aMenu['FILE'] );
		
			//$sHtml .= '<li' . ($sFile == $aMenu['FILE'] ? ' class="actv"' : '') . '><a class="' . $aMenu['CLASS'] . '" href="' . $sUrl . '" title="' . $aMenu['TITLE'] . '">' . $aMenu['TITLE'] . '</a>';
			
			
			$sHtml .= '<a class="' . $aMenu['CLASS'] . '" href="' . $sUrl . '" title="' . $aMenu['TITLE'] . '">' .'<li' . ($sFile == $aMenu['FILE'] ? ' class="actv"' : '') . '>'. $aMenu['TITLE'];

			// Si contiene submenu
			/*
			if( !empty( $aMenu['MENU'] ) && $bSubmenu )
			{		
				$sHtml .= '<ul>';

				// Recorremos el submenu
				foreach( $aMenu['MENU'] as $aSubMenu )
				{
					// Comprobamos si la url empieza por http para no llamar a la funcion tep_href_link
					if( preg_match( '/^http/i', $aSubMenu['FILE'] ) )
						$sUrl = $aSubMenu['FILE'];
					else
						$sUrl = tep_href_link( $aSubMenu['FILE'] );
				
					$sHtml .= '<li' . ($sFile == $aSubMenu['FILE'] ? ' class="actv"' : '') . '><a class="' . $aSubMenu['CLASS'] . '" href="' . $sUrl . '" title="' . $aSubMenu['TITLE'] . '">' . $aSubMenu['TITLE'] . '</a></li>';
				}

				$sHtml .= '</ul>';
			}
			
			*/

			//$sHtml .= '</li>';
			
			$sHtml .= '</li></a>';
		}

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}

	
	
	
	
	
	
	
	

	/**
	 * Nombre: _getSearchForm
	 * Informacion: Modulo para mostrar el formulario de busqueda comunmente usado en la cabecera
	 * Argumentos:
	 *	   - VALUE_SUBMIT: Valor del campo submit [Buscar|string]
	 *	   - SHOW: Mostrar o no el html resultante [false|boolean]
	**/
	function _getSearchForm($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getSearchForm_custom' ) )
			return call_user_func_array( '_getSearchForm_custom', array( $aArgumentos ) );

		// Variables
		$sHtml = '';
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sValueSubmit = (empty( $aArgumentos['VALUE_SUBMIT'] ) ? 'Buscar' : $aArgumentos['VALUE_SUBMIT'] );
		

        $sHtml .= tep_draw_form( 'search', tep_href_link( FILENAME_ADVANCED_SEARCH_RESULT, '', 'SSL', false ), 'get', 'id="form-srch" onsubmit="return  fnCheckAdvanceSearch(this);"' );
		$sHtml .= tep_draw_hidden_field( 'description', '1' );
		$sHtml .= tep_draw_hidden_field( 'auto', '1' );
		$sHtml .= tep_draw_input_field( 'buscar' );
		$sHtml .= tep_hide_session_id();
		$sHtml .= '<button type="submit">' . $sValueSubmit . '</button>';
        $sHtml .= '</form>';

		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}


	/**
	 * Nombre: _getBanderasIdioma
	 * Informacion: Modulo para mostrar las banderas de los idiomas disponibles
	 * Argumentos:
	 *	   - LISTA: Si deseamos o no que nos devuelva en forma de lista [true|boolean]
	 *	   - SHOW: Mostrar o no el html resultante [false|true]
	**/
	function _getBanderasIdioma($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getBanderasIdioma_custom' ) )
			return call_user_func_array( '_getBanderasIdioma_custom', array( $aArgumentos ) );

		// Variables
		global $lng, $PHP_SELF, $request_type;
		$bLista = (empty( $aArgumentos['LISTA'] ) ? true : false );
		$bShow = (empty( $aArgumentos['SHOW'] ) ? false : true );
		$sHtml = '';
		
		// Comprobamos que $lng sea un objeto ya definido si no lo creamos
		if( !isset( $lng ) || isset( $lng ) && ! is_object( $lng ) )
		{
			include( DIR_WS_CLASSES . 'language.php' );
			$lng = new language;
		}

		// Reiniciamos el array de lenguajes
		reset( $lng->catalog_languages );

		// Recorremos los lenguajes
		while( list($key, $value) = each( $lng->catalog_languages ) )
			$sHtml .= ($bLista ? '<li>' : '') . '<a id="' . getSlug( $value['name'] ) . ($value['directory'] == $language ? '-actv' : '') . '" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params( array('language', 'currency') ) . 'language=' . $key, $request_type) . '">' . $value['name'] . '</a>' . ($bLista ? '</li>' : '');
			
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
	function _getListaNoticia($aArgumentos = array())
	{
		global $languages_id;
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getListaNoticia_custom' ) )
			return call_user_func_array( '_getListaNoticia_custom', array( $aArgumentos ) );
	
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

		// Consultamos las noticias
		$aDatos = tep_db_query( 'select id, date_format(date,"%d/%m/%Y") as date, noticia, titulo
								 from noticias
								 order by date ' . $sOrder . ' limit ' . $nMax );

		// Si hemos obtenido noticias
		if( tep_db_num_rows( $aDatos ) > 0 )
		{
			$sHtml .= '<div id="box-ntcs">';
			$sHtml .= '<a id="ntcs-ttl" href="' . tep_href_link( 'noticias.php' ) . '" title="Ver todas las noticias"></a>';
			
			// Mostramos enlaces de ver mas noticias
			for( $nCont = 1; $nCont <= $nSizeMas; $nCont++ )
				$sHtml .= '<a id="box-ntcs-mas' . $nCont . '" href="' . tep_href_link( 'noticias.php' ) . '" title="Ver todas las noticias"></a>';
			
			$sHtml .= '<ul>';

			while( $aDato = tep_db_fetch_array( $aDatos ) )
			{
				$sHtml .= '<li>
					<div>' . ($bShowDate ? $aDato['date'] . ' - ' : '') . ($bShowTitle ? ($nSizeTitulo ? truncate( $aDato['titulo'], array( 'SIZE' => $nSizeTitulo ) ) : $aDato['titulo']) : '') . '</div>
					<span>' . ($nSizeTitulo ? truncate( $aDato['noticia'], array( 'SIZE' => $nSizeNoticia, 'CLEAR' => true ) ) : $aDato['noticia']) . '
					<a href="' . getSlug( truncate( $aDato['titulo'], array( 'SIZE' => 50 ) ) ) . '-n-' . $aDato['id'] . '.html" title="Leer noticia completa">'.ENTRY_READ_MORE.'</a></span> 
				</li>';
			}
			
			$sHtml .= '</ul><a id="box-ntcs-mas" href="noticias.php" title="Ver todas las noticias"></a> </div>';
		}
		
		// Retornamos o mostramos el html resultante
		if( !$bShow )
			return $sHtml;

		echo $sHtml;
	}


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
	function _getFiltro($aArgumentos = array())
	{
		// Si existe la funcion customizada la llamamos
		if( function_exists( '_getFiltro_custom' ) )
			return call_user_func_array( '_getFiltro_custom', array( $aArgumentos ) );
			
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
				$sHtml .= '<a class="'. (!empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'chng-vsta-hrzt' : 'chng-vsta-vrtl') . '" href="javascript:void(0);" id="chng-vsta">Cambiar vista</a>';

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
	
	

?>