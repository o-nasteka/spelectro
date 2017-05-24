<?php
    require( 'includes/application_top.php' );
    require( DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO );

    // BOF Separate Pricing per Customer
    if( isset( $_SESSION['sppc_customer_group_id'] ) && $_SESSION['sppc_customer_group_id'] != '0' )
        $customer_group_id = $_SESSION['sppc_customer_group_id'];
    else
        $customer_group_id = '0';
    // EOF Separate Pricing per Customer

    $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_check = tep_db_fetch_array($product_check_query);

    require(DIR_THEME. 'html/header.php');
    require(DIR_THEME. 'html/column_left.php');

    if( $product_check['total'] < 1 )
    {
        $hide_product = true; // needed for column_right
        echo '<h1 class="pageHeading" style="width:680px;"><span>' . TEXT_HEADER_ITEM_NOT_FOUND . '</span></h1>';
        echo '<div class="alert alert-info">' . TEXT_PRODUCT_NOT_FOUND . '</div>';
	
        echo '<div class="botonera"><a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a></div>';
    }
    else
    {
        // BOF: More Pics 6  Added: , p.products_subimage1, p.products_subimage2, p.products_subimage3, p.products_subimage4, p.products_subimage5, p.products_subimage6
        $product_info_query = tep_db_query("select " . SQL_SELECT . " m.manufacturers_image, p.products_id, pd.products_name, pd.products_description, p.product_ean, p.products_model, p.products_youtube, p.products_quantity, p.products_image, p.products_subimage1, p.products_subimage2, p.products_subimage3, p.products_subimage4, p.products_subimage5, p.products_subimage6, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id, p.products_free_shipping
											from " . TABLE_PRODUCTS . " p
											inner join " . TABLE_PRODUCTS_DESCRIPTION . " pd on (pd.products_id = p.products_id)
											left join manufacturers m on (m.manufacturers_id = p.manufacturers_id)
											" . SQL_FROM . "
											where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.language_id = '" . (int)$languages_id . "'");
        // EOF: More Pics 6
        $aProducto = tep_db_fetch_array($product_info_query);

		if( $customer_group_id != '0' )
        {
            $pg_query = tep_db_query("select pg.products_id, customers_group_price as price from " . TABLE_PRODUCTS_GROUPS . " pg where products_id in (" . (int)$_GET['products_id'] . ") and pg.customers_group_id = '" . $customer_group_id . "'");

			if( tep_db_num_rows( $pg_query ) > 0 )
			{
				$pg_array = tep_db_fetch_array( $pg_query );
				$aProducto['products_price'] = $pg_array['price'];
			}
        } // end if ($customer_group_id != '0')
		
        tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = '" . (int)$_GET['products_id'] . "' and language_id = '" . (int)$languages_id . "'");

        // BOF QPBPP for SPPC
        $pf->loadProduct((int)$_GET['products_id'], (int)$languages_id);
        $products_price_temp = $pf->getPriceString();
	
        //guardamos los precios
	$products_specials_price = false;
	if (strlen($products_price_temp['products_specials_price'])>1)
            $products_specials_price=$products_price_temp['products_specials_price'];

        $products_price=$products_price_temp['products_price'];
        // EOF QPBPP for SPPC

        $products_model = $aProducto['products_model'];
        $products_name = $aProducto['products_name'];
        $categoria=getCategoriaProducto($aProducto['products_id']);
        // BOF: More Pics 6

        $mopics_image_width = (MOPICS_RESTRICT_IMAGE_SIZE=='true'?SMALL_IMAGE_WIDTH:'');
        $mopics_image_height = (MOPICS_RESTRICT_IMAGE_SIZE=='true'?SMALL_IMAGE_HEIGHT:'');

        if( MOPICS_SHOW_ALL_ON_PRODUCT_INFO=='true' )
        {
            $mopics_output = '';
            $mo_row = 1;
            $mo_col = 1;

            $mopics_images = array();
            if( tep_not_null($aProducto['products_image']) && MOPICS_GROUP_WITH_PARENT == 'true')
                $mopics_images[] = $aProducto['products_image'];

            for ( $mo_item=1; $mo_item<7; $mo_item++ )
                if( tep_not_null($aProducto['products_subimage'.$mo_item]) )
                    $mopics_images[] = $aProducto['products_subimage'.$mo_item];
      
            $mopics_count = sizeof($mopics_images);
            if( $mopics_count > 0 )
            {
                for( $mo_item=0; $mo_item<$mopics_count; $mo_item++ )
                {
                    if( $mo_row<(MOPICS_NUMBER_OF_ROWS+1) )
                    {
                        //if ($mo_col==1) {$mopics_output.='<tr>'."\n";}
                        $partes = pathinfo($mopics_images[$mo_item]);
                        if( $partes['extension']!='' && is_file(DIR_WS_IMAGES . 'productos/' . $mopics_images[$mo_item]) )
                            $mopics_output .= '<a class="prdct-libo" href="' . tep_href_link(DIR_WS_IMAGES . 'productos/' . $mopics_images[$mo_item]) . '" rel="frky[g1]" title="'.$aProducto['products_name'].'" class="prdct-libo">' . tep_image(DIR_WS_IMAGES . 'productos/' . $mopics_images[$mo_item], $aProducto['products_name'], 72, 72) . '</a> ';
			//<a href="' . tep_href_link(DIR_WS_IMAGES . $mopics_images[$mo_item]) . '" rel="prettyPhoto[gallery2]" title="'.$product_info['products_name'].'" class="ampliar_imagen"><img src="theme/'.THEME.'/images/ampliar_imagen_peq.gif" border="0" alt="'.$product_info['products_name'].'"></a>'."\n";
                    //if ($mo_col==MOPICS_NUMBER_OF_COLS) { $mo_col=1; $mo_row++; $mopics_output.='</tr>'."\n"; } else { $mo_col++; }
                    }
                }
            //if ($mo_col!=1){ while (($mo_col++)<(MOPICS_NUMBER_OF_COLS+1)) { $mopics_output.='<td>&nbsp;</td>'; } $mopics_output.='</tr>'."\n"; }
            }
        }
        // EOF: More Pics 6
        // BOF QPBPP for SPPC
        $min_order_qty = $pf->getMinOrderQty();
        // EOF QPBPP for SPPC

		$sHtmlAtributos = '';
		// EOF - ATRIBUTOS DE PRODUCTOS - DENOX
		$products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' and find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0 ");
		$products_attributes = tep_db_fetch_array($products_attributes_query);
		if ($products_attributes['total'] > 0)
		{
			//$sHtmlAtributos .= TEXT_PRODUCT_OPTIONS;

			$products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' and find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0 order by popt.products_options_name");
			while ($products_options_name = tep_db_fetch_array($products_options_name_query)) 
			{
				$products_options_array = array();
				$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix, pa.products_attributes_id from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' and find_in_set('".$customer_group_id."', attributes_hide_from_groups) = 0");
				$list_of_prdcts_attributes_id = '';
				$products_options = array(); // makes sure this array is empty again

				while ($_products_options = tep_db_fetch_array($products_options_query))
				{
					$products_options[] = $_products_options;
					$list_of_prdcts_attributes_id .= $_products_options['products_attributes_id'].",";
				}

				if (tep_not_null($list_of_prdcts_attributes_id) && $customer_group_id != '0') 
				{ 
					$select_list_of_prdcts_attributes_ids = "(" . substr($list_of_prdcts_attributes_id, 0 , -1) . ")";
					$pag_query = tep_db_query("select products_attributes_id, options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES_GROUPS . " where products_attributes_id IN " . $select_list_of_prdcts_attributes_ids . " AND customers_group_id = '" . $customer_group_id . "'");
					
					while ($pag_array = tep_db_fetch_array($pag_query))
					{
						$cg_attr_prices[] = $pag_array;
					}

					// substitute options_values_price and prefix for those for the customer group (if available)
					if( $customer_group_id != '0' && tep_not_null($cg_attr_prices) )
					{
						for ($n = 0 ; $n < count($products_options); $n++)
						{
							for ($i = 0; $i < count($cg_attr_prices) ; $i++)
							{
								if( $cg_attr_prices[$i]['products_attributes_id'] == $products_options[$n]['products_attributes_id'] ) 
								{
									$products_options[$n]['price_prefix'] = $cg_attr_prices[$i]['price_prefix'];
									$products_options[$n]['options_values_price'] = $cg_attr_prices[$i]['options_values_price'];
								}
							} // end for ($i = 0; $i < count($cg_att_prices) ; $i++)
						}
					} // end if ($customer_group_id != '0' && (tep_not_null($cg_attr_prices))
				} // end if (tep_not_null($list_of_prdcts_attributes_id) && $customer_group_id != '0')

				for ($n = 0 ; $n < count($products_options); $n++)
				{
					$products_options_array[] = array('id' => $products_options[$n]['products_options_values_id'], 'text' => $products_options[$n]['products_options_values_name']);
					if($products_options[$n]['options_values_price'] != '0')
					{
						$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options[$n]['price_prefix'] . $currencies->display_price($products_options[$n]['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
					}
				}
				// EOF SPPC attributes mod

				if( isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) 
				{
					$selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
				}
				else
				{
					$selected_attribute = false;
				}
				
				$sHtmlAtributos .= $products_options_name['products_options_name'] . ': ';
				$sHtmlAtributos .= tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute);
			}
		}

		// BOF - ATRIBUTOS DE PRODUCTOS - DENOX		        

		// Theme
		include( DIR_THEME_ROOT . 'html/templates/' . basename(__FILE__) );

	/*if ( ($product_check['total'] > 0) && ( (PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
		include (DIR_WS_INCLUDES . 'products_next_previous.php');
	}*/
    }

    require( DIR_THEME. 'html/column_right.php' );
    require( DIR_THEME. 'html/footer.php' );
    require( DIR_WS_INCLUDES . 'application_bottom.php' );