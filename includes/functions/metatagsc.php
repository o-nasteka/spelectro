<?php
// Metatags automaticas  
function get_category_name($category_id) {
    global $languages_id;

    $category_query = tep_db_query("select cd.categories_name, cd.categories_seo_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and c.categories_id = '" . (int)$category_id . "' and cd.language_id = '" . (int)$languages_id . "'");
    $category = tep_db_fetch_array($category_query);

   return $category['categories_name'];
}
function metatags() {
	global $cPath_array,$languages_id, $currencies;
	
	
	$salida=' ';
	if (is_array($cPath_array)) {
		$salida='<title>';
		$producto=false;
		if (isset($_GET['products_id'])) {
			$BuscaProducto = tep_db_query("select pd.language_id, p.products_id, pd.products_name, p.products_free_shipping, pd.products_description, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = '" . (int)$_GET['products_id'] . "'" . " and pd.language_id ='" .  (int)$languages_id . "'"); 
			$ResProducto = tep_db_fetch_array($BuscaProducto);
			echo '<link rel="image_src" href="'.tep_href_link('images/'.$ResProducto['products_image']).'" />' ."\n";
			$ProductoNombre = strip_tags($ResProducto['products_name']);
			define('TITLE_PRODUCT_INFO', $ProductoNombre);
			$ProductoModelo = strip_tags ($ResProducto['products_model']);
			$EnvioGratis = strip_tags($ResProducto['products_free_shipping']);
			$precio = strip_tags ($ResProducto['products_price'], "");
			$precio =$currencies->display_price($precio*1.18,'');

			if($EnvioGratis=='1'){
				$EnvioGratis=' - ¡Envío Gratis!';	
			}else{
				$EnvioGratis='';	
			}

			$BuscaFabricante = tep_db_query("select m.manufacturers_id, m.manufacturers_name from " . TABLE_MANUFACTURERS . " m left join " . TABLE_MANUFACTURERS_INFO . " mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "'), " . TABLE_PRODUCTS . " p  where p.products_id = '" . (int)$_GET['products_id'] . "' and p.manufacturers_id = m.manufacturers_id"); 
			$ResFabricante = tep_db_fetch_array($BuscaFabricante);
			$FabricanteNombre = strip_tags ($ResFabricante['manufacturers_name'], "");

			$salida.='Comprar ' .($ProductoNombre.' a ' .$precio.''.$EnvioGratis.' &gt; ');
			
			$keywords.='Comprar '.ucfirst(strtolower($ProductoNombre.', '));
			
			if( $ProductoModelo != '' )
				$keywords.=$ProductoModelo.', ';
			
			if( $FabricanteNombre != '' )			
				$keywords.=$FabricanteNombre.', ';

			$ProductoDescripcion = substr(strip_tags ($ResProducto['products_description']),0,250);
			
			$producto=true;
		}
		
		$cPath_array_reverse=array_reverse($cPath_array);
		$keywords.='comprar '.strtolower(get_category_name($cPath_array_reverse[0])).' de '.strtolower(get_category_name($cPath_array_reverse[1])).', ';
		foreach ($cPath_array_reverse as $value) {
			$salida.=ucfirst(mb_strtolower(get_category_name($value).' &gt; ', 'UTF-8'));
			$keywords.=mb_strtolower(get_category_name($value), 'UTF-8').', ';
			
		}
		$keywords= substr ($keywords, 0, -2);
		if ($producto==false) {
			$ProductoDescripcion='Comprar '.strtolower(get_category_name($cPath_array_reverse[0])).', comprar '.strtolower(get_category_name($cPath_array_reverse[0])).' de '.strtolower(get_category_name($cPath_array_reverse[1])). '('.strtolower(get_category_name($cPath_array_reverse[2]).')');
		}
		
		
		if (isset($_GET['manufacturers_id'])) {
			
			$query="select manufacturers_name from ".TABLE_MANUFACTURERS." WHERE manufacturers_id=".(int)$_GET['manufacturers_id'];
			$BuscaFabricante = tep_db_query($query); 
			echo $query;
			$ResFabricante = tep_db_fetch_array($BuscaFabricante);
			$FabricanteNombre = strip_tags ($ResFabricante['manufacturers_name']);

			$salida.='Productos de '.ucfirst(strtolower($FabricanteNombre.' &gt; '));
			$keywords.='Comprar, '.ucfirst(strtolower($FabricanteNombre.', ')).' marca';
			$ProductoDescripcion = 'Productos de la marca '.$FabricanteNombre.', comprar productos '.$ProductoNombre.' baratos';
		}
		
		
		$salida= substr ($salida, 0, -6);
		$salida.=' | '.TITLE.'</title>
		'."\n";
		
		$salida.='<meta name="keywords" content="'.$keywords.'" />
		'."\n";
		$salida.='<meta name="description" content="'.$ProductoDescripcion.'" />
		'."\n";
	}
	
		if (isset($_GET['manufacturers_id'])) {
			$salida='<title>';
			$query="select manufacturers_name from ".TABLE_MANUFACTURERS." WHERE manufacturers_id=".(int)$_GET['manufacturers_id'];
			$BuscaFabricante = tep_db_query($query); 
			$ResFabricante = tep_db_fetch_array($BuscaFabricante);
			$FabricanteNombre = strip_tags ($ResFabricante['manufacturers_name']);

			$salida.='Productos de '.ucfirst(strtolower($FabricanteNombre));
			$keywords.='Comprar, '.ucfirst(strtolower($FabricanteNombre.', ')).' marca';
			$ProductoDescripcion = 'Productos de la marca '.$FabricanteNombre.', comprar productos '.$FabricanteNombre.' baratos';
			$salida.=' | '.TITLE.'</title>' ."\n";
			$salida.='<meta name="keywords" content="'.$keywords.'" />
			' ."\n";
			$salida.='<meta name="description" content="'.$ProductoDescripcion.'" />
			'."\n";
		}

	if( isset( $_GET['info_id'] ) )
	{
		$aInforMetas = tep_db_query( 'SELECT information_description 
										  FROM information 
										  WHERE information_id = ' . $_GET['info_id'] ); 

		$aInforMeta = tep_db_fetch_array( $aInforMetas );
		$sDescriptionMeta = $aInforMeta['information_description'];
		$aRemplaceMeta = array( '-', '.', ',', '"', '\'', ' El ', ' La ', ' Los ', ' Las ', ' Un ', ' Una ', ' Unos ', ' Unas ', ' a ', ' ante ', ' bajo ', ' cabe ', ' con ', ' contra ', ' de ', ' desde ', ' durante ', ' en ', ' entre ',  ' excepto ', ' hacia ', ' hasta ', ' mediante ', ' para ', ' por ', ' segÃƒÂºn ', ' sin ', ' so ', ' sobre ', ' tras ', ' via ', ' que ', ' a ', ' e ', ' i ', ' o ', ' u ', 'esta' );
		$aReplaceMeta2 = array( ' ', ' ', ' ', '', '', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',  ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ' );
		$sInfoDescription = strip_tags( $aInforMeta['information_description'] );
		$sInfoDescription = str_ireplace( $aRemplaceMeta, $aReplaceMeta2, $sInfoDescription );
		$aInforMetas = explode( ' ', $sInfoDescription );
		$aCountMetas = array();
		$aCountMetas2 = array();
		$keywords = '';
		
		// Recorremos para obtener el numero que se repite una palabra
		foreach( $aInforMetas as $aInforMeta )
		{
			$aInfoMeta = strtolower( str_ireplace( $aRemplaceMeta, $aReplaceMeta2, $aInforMeta ) );

			if( empty( $aCountMetas[$aInforMeta] ) && $aInfoMeta != '' )
				$aCountMetas[$aInforMeta] = 1;
			else if( $aInfoMeta != '' )
				$aCountMetas[$aInforMeta] = $aCountMetas[$aInforMeta] + 1;
		}

		// Recorremos para crear un nuevo array con las key y las palabras ke se repiten
		foreach( $aCountMetas as $key => $value )
		{
			if( empty( $aCountMetas2[$value] ) )
				$aCountMetas2[$value] = $key;
			else
				$aCountMetas2[$value] = $aCountMetas2[$value] . ', ' . $key;
		}
		
		// Ordenamos el array
		ksort($aCountMetas2);
		
		// Si el array contiene muchas keys eliminamos las keys que se repiten solo 1 vez
		if( count($aCountMetas2) > 1 && ! empty( $aCountMetas2[1] ) )
			unset( $aCountMetas2[1] );
		
		foreach( $aCountMetas2 as $aInforMeta )
		{
			$keywords .= $aInforMeta . ', ';
		}
		
		$keywords = substr( $keywords, 0, -2 );
		
		$salida ='<title>'.TITLE.'</title>' ."\n";
		$salida .= '<meta name="description" content="' . trim( substr( preg_replace("/[\n|\r|\n\r]/", ' ', strip_tags( $sDescriptionMeta )), 0, 350) ) . '" />' ."\n";
		$salida .= '<meta name="keywords" content="' . $keywords . '" />' ."\n";
	}

	if ($salida!=' ') 
	{
		echo $salida;
	}
	else
	{
		echo '<title>'.TITLE.'</title>' ."\n";
		echo '<meta name="description" content="Repuestos para telefonia movil y tablet todas las marcas y los mejores precios " />' ."\n";
		echo '<meta name="keywords" content="pantalla, lcd, display, flex, tactil, digitalizador, circuito flex, flex de carga" />' ."\n";
	}
}
?>