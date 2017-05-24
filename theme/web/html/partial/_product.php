<?php
  /*  function _product($aArgumentos = array())
    {
        // Variables
		global $aProducto, $nCont, $nProductosTotal, $languages_id;
		$sClass = (empty( $aArgumentos['CLASS'] ) ? '' : $aArgumentos['CLASS'] );
		$sClassPrecio = (empty( $aArgumentos['CLASS_PRECIO'] ) ? '' : $aArgumentos['CLASS_PRECIO'] );
		$nSizeImagen = (empty( $aArgumentos['SIZE_IMAGEN'] ) ? '185' : $aArgumentos['SIZE_IMAGEN'] );
		$bDescription = (!isset( $aArgumentos['DESCRIPCION'] ) ? true : $aArgumentos['DESCRIPCION'] );
		$bOferta = (!isset( $aArgumentos['OFERTA'] ) ? true : $aArgumentos['OFERTA'] );
		$bEnvio = (!isset( $aArgumentos['ENVIO'] ) ? true : $aArgumentos['ENVIO'] );
		$bStock = (empty( $aArgumentos['STOCK'] ) ? false : $aArgumentos['STOCK'] );
		$bVista = (!isset( $aArgumentos['VISTA'] ) ? true : $aArgumentos['VISTA'] );
		$nSizeDescription = (empty( $aArgumentos['SIZE_DESCRIPTION'] ) ? 10 : $aArgumentos['SIZE_DESCRIPTION'] );
        $aProductoInformacion = getInformacionProducto( $aProducto );
		$sHtml = '';
		
		//Clase para producto agotado
		if($aProducto['products_quantity']<='0'){
			$agotado='prdct-agtd';//out of stock
		}else{
			$agotado='';		
		}

		$sClass = ( $bVista && !empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'prdct-hrzt' : ' prdct-vrtl');
		
		$nColum = ceil($nProductosTotal / 3);
		$nResto = 3 - (($nColum * 3) - $nProductosTotal);

		$sClass .= (($nCont + 1) % 3 == 0 ? ' prdct-vrtl-drch' : '') . ($nCont >= ($nProductosTotal - $nResto) ? ' prdct-vrtl-last' : '');
	
		$sHtml = '<div class="grid-item-list product-item '.$agotado.' ' . $aProductoInformacion['CLASS_ENVIO'] . ' ' . $aProductoInformacion['CLASS_OFERTA'] . '">';
			// Comprobamos icono de oferta
			if( $bOferta )
				$sHtml .= ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : '');

			// Comprobamos icono de envio
			if( $bEnvio )
				$sHtml .= ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : '');
			// Nombre
			$sHtml .= '<h5 itemprop="name" class="product-name"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '"><b>' . $aProducto['products_name'] . '</b></a></h5>';
				
			// Imagen
			$sHtml .= '<div class="product-thumb"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '">';
			$sHtml .=     tep_image( DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'], $aProductoInformacion['TITLE'], $nSizeImagen, $nSizeImagen, 'class="img-responsive"', false );
			$sHtml .= '</a></div>';
			
			$sHtml .='<div class="product-details">';	
			
			// Comprobamos si contiene información
			if( $bDescription )
				$sHtml .= '<div class="prdct-dscp">' . truncate( getDescriptionProductById( $aProducto['products_id'] ), array( 'SIZE' => $nSizeDescription ) ) . '</div>';

			// Precio
			$sHtml .= '<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" class="price">
				
					' . getPrecioImagen( $aProductoInformacion );

					// Comprobamos que el producto este en onferta
					if( $aProductoInformacion['CLASS_OFERTA'] != '' )
					$sHtml .= '<s>' . $aProductoInformacion['PRECIO_ANTERIOR'] . '</s>';

			$sHtml .= '
			</div>';

			// Boton comprar
			$sHtml .= '<a class="btn btn-sm btn-info" title="Comprar ' . $aProductoInformacion['TITLE'] . '" href="' . tep_href_link( FILENAME_DEFAULT, tep_get_all_get_params( array('action') ) . 'action=buy_now&products_id=' . $aProducto["products_id"] ) . '"><i class="icon-cart"></i></a>';
			$sHtml .= '<div class="iva">'.ENTRY_TAX_INCL.'</div>';
			
			// Comprobamos icono de stock
			if( $bStock )
				$sHtml .= '<div id="icon-stock" title="Quedan ' . $aProducto['products_quantity'] . ' productos" class="prdct-icon-stock ' . $aProductoInformacion['CLASS_STOCK'] . '">Disponible</div>';
			
			$sHtml .='</div>';
        	$sHtml .= '</div>';

		return $sHtml;
    }
		
		
	*/	
		
		
	    function _product($aArgumentos = array())
    {
        // Variables
		global $aProducto, $nCont, $nProductosTotal, $languages_id;
		$sClass = (empty( $aArgumentos['CLASS'] ) ? '' : $aArgumentos['CLASS'] );
		$sClassPrecio = (empty( $aArgumentos['CLASS_PRECIO'] ) ? '' : $aArgumentos['CLASS_PRECIO'] );
		$nSizeImagen = (empty( $aArgumentos['SIZE_IMAGEN'] ) ? '185' : $aArgumentos['SIZE_IMAGEN'] );
		$bDescription = (!isset( $aArgumentos['DESCRIPCION'] ) ? true : $aArgumentos['DESCRIPCION'] );
		$bOferta = (!isset( $aArgumentos['OFERTA'] ) ? true : $aArgumentos['OFERTA'] );
		$bEnvio = (!isset( $aArgumentos['ENVIO'] ) ? true : $aArgumentos['ENVIO'] );
		$bStock = (empty( $aArgumentos['STOCK'] ) ? false : $aArgumentos['STOCK'] );
		$bVista = (!isset( $aArgumentos['VISTA'] ) ? true : $aArgumentos['VISTA'] );
		$nSizeDescription = (empty( $aArgumentos['SIZE_DESCRIPTION'] ) ? 10 : $aArgumentos['SIZE_DESCRIPTION'] );
        $aProductoInformacion = getInformacionProducto( $aProducto );
		$sHtml = '';
		
		/*Clase para producto agotado
		if($aProducto['products_quantity']<='0'){
			$agotado='prdct-agtd';//out of stock
		}else{
			$agotado='';		
		}

		*/
		
		$sClass = ( $bVista && !empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'prdct-hrzt' : ' prdct-vrtl');
		
		$nColum = ceil($nProductosTotal / 3);
		$nResto = 3 - (($nColum * 3) - $nProductosTotal); 

		$sClass .= (($nCont + 1) % 3 == 0 ? ' prdct-vrtl-drch' : '') . ($nCont >= ($nProductosTotal - $nResto) ? ' prdct-vrtl-last' : '');
	
		$sHtml = '<div class="grid-item-list product-item'.$agotado.' ' . $aProductoInformacion['CLASS_ENVIO'] . ' ' . $aProductoInformacion['CLASS_OFERTA'] . '">';
			// Comprobamos icono de oferta
			if( $bOferta )
				$sHtml .= ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : '');

			// Comprobamos icono de envio
			if( $bEnvio )
				$sHtml .= ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : '');
			// Nombre
			$sHtml .= '<h5 itemprop="name" class="product-name"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '"><b>' . $aProducto['products_name'] . '</b></a></h5>';
				
			// Imagen
			$sHtml .= '<div class="product-thumb"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '">';
			$sHtml .=     tep_image( DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'], $aProductoInformacion['TITLE'], $nSizeImagen, $nSizeImagen, 'class="img-responsive"', false );
			$sHtml .= '</a></div>';
			
			$sHtml .='<div class="product-details">';	
			
			// Comprobamos si contiene información
			if( $bDescription )
				$sHtml .= '<div class="prdct-dscp">' . truncate( getDescriptionProductById( $aProducto['products_id'] ), array( 'SIZE' => $nSizeDescription ) ) . '</div>';

			// Precio
			$sHtml .= '<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" class="price prco-3">
				
					' . getPrecioImagen( $aProductoInformacion );

					// Comprobamos que el producto este en onferta
					if( $aProductoInformacion['CLASS_OFERTA'] != '' )
					$sHtml .= '<s>' . $aProductoInformacion[''] . '</s>';
 
			$sHtml .= '
			</div>';

			// Boton comprar
			
			if($aProducto['products_quantity']<='0'){
			    $sHtml .= '<a class="btn btn-sm btn-danger  btn-block disabled" title="Comprar ' . $aProductoInformacion['TITLE'] . '" href="' . tep_href_link( FILENAME_DEFAULT, tep_get_all_get_params( array('action') ) . 'action=buy_now&products_id=' . $aProducto["products_id"] ) . '"><i class="icon-cross"></i></a>';
			
			}else{
			    
			$sHtml .= '<a class="btn btn-sm btn-success btn-block" title="Comprar ' . $aProductoInformacion['TITLE'] . '" href="' . tep_href_link( FILENAME_DEFAULT, tep_get_all_get_params( array('action') ) . 'action=buy_now&products_id=' . $aProducto["products_id"] ) . '"><i class="icon-cart"></i></a>';
			$sHtml .= '<div class="iva">'.ENTRY_TAX_INCL.'</div>';
			
	
			
			
			    
			}
			
			// Comprobamos icono de stock
		 
				$sHtml .= '<div id="icon-stock" title="Quedan ' . $aProducto['products_quantity'] . ' productos" class="prdct-icon-stock ' . $aProductoInformacion['CLASS_STOCK'] . '">IVA No Incl.</div>';
			
			$sHtml .='</div>';
        	$sHtml .= '</div>';

		return $sHtml;
    }
		
		
		
		
		
		
		
			
		
	    function _product2($aArgumentos = array())
    {
        // Variables
		global $aProducto, $nCont, $nProductosTotal, $languages_id;
		$sClass = (empty( $aArgumentos['CLASS'] ) ? '' : $aArgumentos['CLASS'] );
		$sClassPrecio = (empty( $aArgumentos['CLASS_PRECIO'] ) ? '' : $aArgumentos['CLASS_PRECIO'] );
		$nSizeImagen = (empty( $aArgumentos['SIZE_IMAGEN'] ) ? '185' : $aArgumentos['SIZE_IMAGEN'] );
		$bDescription = (!isset( $aArgumentos['DESCRIPCION'] ) ? true : $aArgumentos['DESCRIPCION'] );
		$bOferta = (!isset( $aArgumentos['OFERTA'] ) ? true : $aArgumentos['OFERTA'] );
		$bEnvio = (!isset( $aArgumentos['ENVIO'] ) ? true : $aArgumentos['ENVIO'] );
		$bStock = (empty( $aArgumentos['STOCK'] ) ? false : $aArgumentos['STOCK'] );
		$bVista = (!isset( $aArgumentos['VISTA'] ) ? true : $aArgumentos['VISTA'] );
		$nSizeDescription = (empty( $aArgumentos['SIZE_DESCRIPTION'] ) ? 10 : $aArgumentos['SIZE_DESCRIPTION'] );
        $aProductoInformacion = getInformacionProducto( $aProducto );
		$sHtml = '';
		
		/*Clase para producto agotado
		if($aProducto['products_quantity']<='0'){
			$agotado='prdct-agtd';//out of stock
		}else{
			$agotado='';		
		}

		*/
		
		$sClass = ( $bVista && !empty($_SESSION['vista']) && $_SESSION['vista'] == 'chng-vsta-hrzt' ? 'prdct-hrzt' : ' prdct-vrtl');
		
		$nColum = ceil($nProductosTotal / 3);
		$nResto = 3 - (($nColum * 3) - $nProductosTotal); 

		$sClass .= (($nCont + 1) % 3 == 0 ? ' prdct-vrtl-drch' : '') . ($nCont >= ($nProductosTotal - $nResto) ? ' prdct-vrtl-last' : '');
	
		$sHtml = '<div class="col-xs-6 col-md-4 col-sm-4 col-lg-3 row-grid" style="padding:0 5px;"> <div class="grid-item-listt product-item'.$agotado.' ' . $aProductoInformacion['CLASS_ENVIO'] . ' ' . $aProductoInformacion['CLASS_OFERTA'] . '">';
			// Comprobamos icono de oferta
			if( $bOferta )
				$sHtml .= ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : '');

			// Comprobamos icono de envio
			if( $bEnvio )
				$sHtml .= ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : '');
			// Nombre
			$sHtml .= '<h5 itemprop="name" class="product-name"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '"><b>' . $aProducto['products_name'] . '</b></a></h5>';
				
			// Imagen
			$sHtml .= '<div class="product-thumb"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '">';
			$sHtml .=     tep_image( DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'], $aProductoInformacion['TITLE'], $nSizeImagen, $nSizeImagen, 'class="img-responsive"', false );
			$sHtml .= '</a></div>';
			
			$sHtml .='<div class="product-details">';	
			
			// Comprobamos si contiene información
			if( $bDescription )
				$sHtml .= '<div class="prdct-dscp">' . truncate( getDescriptionProductById( $aProducto['products_id'] ), array( 'SIZE' => $nSizeDescription ) ) . '</div>';

			// Precio
			$sHtml .= '<div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" class="price prco-3">
				
					' . getPrecioImagen( $aProductoInformacion );

					// Comprobamos que el producto este en onferta
					if( $aProductoInformacion['CLASS_OFERTA'] != '' )
					$sHtml .= '<s>' . $aProductoInformacion[''] . '</s>';
 
			$sHtml .= '
			</div>';

			// Boton comprar
			
			if($aProducto['products_quantity']<='0'){
			    $sHtml .= '<a class="btn btn-sm btn-danger  btn-block disabled" title="Comprar ' . $aProductoInformacion['TITLE'] . '" href="' . tep_href_link( FILENAME_DEFAULT, tep_get_all_get_params( array('action') ) . 'action=buy_now&products_id=' . $aProducto["products_id"] ) . '"><i class="icon-cross"></i></a>';
			
			}else{
			    
			$sHtml .= '<a class="btn btn-sm btn-success btn-block" title="Comprar ' . $aProductoInformacion['TITLE'] . '" href="' . tep_href_link( FILENAME_DEFAULT, tep_get_all_get_params( array('action') ) . 'action=buy_now&products_id=' . $aProducto["products_id"] ) . '"><i class="icon-cart"></i></a>';
			$sHtml .= '<div class="iva">'.ENTRY_TAX_INCL.'</div>';
			
	
			
			
			    
			}
			
			// Comprobamos icono de stock
		 
				$sHtml .= '<div id="icon-stock" title="Quedan ' . $aProducto['products_quantity'] . ' productos" class="prdct-icon-stock ' . $aProductoInformacion['CLASS_STOCK'] . '">IVA No Incl.</div>';
			
			$sHtml .='</div>';
        	$sHtml .= '</div></div>';

		return $sHtml;
    }
		
		
		
		
		
		
		
		
	function _product_slide_box($aArgumentos = array())
	{
        // Variables
        global $aProducto, $languages_id;
        $sHtml = '';
        $aProductoInformacion = getInformacionProducto( $aProducto );

		$sHtml = '<div class="prdct-slde">';

			$sHtml .= ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : '');
			$sHtml .= ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : '');

			$sHtml .= '<h3 class="prdct-title"><a href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '" title="' . $aProductoInformacion['TITLE'] . '"><b>' . $aProducto['products_name'] . '</b></a></h3>';

			$sHtml .= '<a class="prdct-img" href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '" title="' . $aProductoInformacion['TITLE'] . '">' . tep_image(DIR_WS_IMAGES . 'productos/' .$aProducto['products_image'], $aProductoInformacion['TITLE'], 160, 160, false ) . '</a>';
				
			$sHtml .= '<div class="prdct-prco-'.$languages_id.'">
				<div class="prco-'.$languages_id.'">
					' . getPrecioImagen( $aProductoInformacion ) . ' <s style="">' . $aProductoInformacion['PRECIO_ANTERIOR'] . '</s>' . '
					<div class="prdct-iva">'.ENTRY_TAX_INCL.'</div>
				</div>
			</div>';
				
		$sHtml .= '</div>';

        return $sHtml;	
	}
?>