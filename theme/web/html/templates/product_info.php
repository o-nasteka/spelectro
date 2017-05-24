<?php 
	$aProductoInformacion = getInformacionProducto( $aProducto, array( 'MAS_INFO' => true ) );

    echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action')) . 'action=add_product'));
    		
	//Clase para producto agotado
	if($aProducto['products_quantity']<='0'){
		$agotado='prdct-agtd';
	}else{
		$agotado='';		
	}
 ?>

<div class="row">
	<div class="<?php echo $aProductoInformacion['CLASS_ENVIO'] . ' ' . $agotado . ' ' . $aProductoInformacion['CLASS_OFERTA']; ?>" itemscope itemtype="http://data-vocabulary.org/Product">
		
		<div class="col-sm-6 col-xs-12">
		    <?php echo ($aProductoInformacion['CLASS_OFERTA'] != '' ? '<div class="icon-ofrt-'.$languages_id.'"></div>' : ''); ?>
   			<?php echo ($aProductoInformacion['CLASS_ENVIO'] != '' ? '<div class="icon-envo"></div>' : ''); ?>

            <?php if( tep_not_null( $aProducto['products_image'] ) ): ?>
                <a class="prdct-img" 

				data-lity 
				data-lity-desc="<?php echo $aProducto['products_name']; ?>"
				href="<?php echo tep_href_link(DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'] ); ?>" title="<?php echo $aProducto['products_name']; ?>">
                    <?php echo tep_image(DIR_WS_IMAGES . 'productos/' . $aProducto['products_image'], $aProducto['products_name'], 265, 265, 'itemprop="image"') ?>
                </a>
            <?php endif; ?>
            <?php echo $mopics_output ?>
		</div>
		
        <div class="col-sm-6 col-xs-12">
            <h1><?php echo $aProducto['products_name']; ?></h1>
            
			<?php if(tep_not_null($aProducto['product_ean']) ): ?>
				<p class="">Referencia: [<?php echo $aProducto['product_ean']; ?>]</p>
			<?php endif; ?>
            <a href="#" title="Oferta <?php echo $aProducto['products_name']; ?>" class="prdct-icon-ofrt"></a>
            
            <div class="prdct-star-cnmt">
            	<img src="<?php echo DIR_THEME . 'images/custom/s' . $aProductoInformacion['RATING'] ?>.jpg" alt="Puntuacion" />
            	<span><?php echo $aProductoInformacion['PUNTUACION'] . '/5'; ?></span><br />
            	<?php echo $aProductoInformacion['NUMERO_COMENTARIO'] . TEXT_COMMENTS .' /'; ?>
            	<?php echo '<a id="link-reviews" href="' . tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ) . '#revv" title="' . $aProductoInformacion['TITLE'] . '"><b>'. TEXT_YOUR_COMMENT .'</b></a>'; ?>	
            </div>
        
			<?php
			/*
            <div class="prdct-info-scal">
	        	<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style ">
					<span class='st_facebook'></span>
					<span class='st_twitter'></span>
					<span class='st_googleplus'></span>
					<span class='st_email'></span>
					<span class='st_sharethis'></span>
				</div>
				<script type="text/javascript">var switchTo5x=false;</script>
				<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
				<!-- AddThis Button END -->

				<a class="fckb-icon" href="http://www.facebook.com/sharer.php?u=<?php echo getCurrentUrl(); ?>" title="Compártelo en facebook" target="_blank">Facebook</a>
				<a class="twtr-icon" href="http://www.twitter.com/share?url=<?php echo getCurrentUrl(); ?>" target="_blank" title="Compártelo en Twitter">Twitter</a>
				<a class="ggle-icon" href="https://plus.google.com/share?url=<?php echo getCurrentUrl(); ?>" target="_blank" title="Compártelo en Google Plus">Google Plus</a>
            </div>
			*/
			?>
            <?php
            /* Rapels de descuento por cantidades - EOF */
            	$rapels=getTableRappels( $aProducto['products_id'] );
            	if(!empty($rapels)){
            ?>
	            <table id="fich-tbl">
	            	<tr>
	            		<td><?php echo TEXT_DISCOUNT_QTTY; ?></td>
	            <?php
	            	foreach($rapels as $i => $rapel){
		            	if($rapels[$i+1]['CANTIDAD']!=''){
		            		echo '<td>'.$rapels[$i]['CANTIDAD'].' - '.$rapels[$i+1]['CANTIDAD'].'</td>';
		            	}else{
		            		echo '<td>+'.$rapels[$i]['CANTIDAD'].'</td>';	            	
		            	}
	            	}
	            ?>
	            	</tr>
	            	<tr class="tr-claro">
	            		<td><?php echo TEXT_PRICE_UNITY; ?></td>
	           <?php
	            	foreach($rapels as $rapel){
	            		echo '<td>'.$rapel['PRECIO'].'</td>';
	            	}
	            ?> 
	            	</tr>
	            </table>
          	<?php
          		}
            /* Rapels de descuento por cantidades - BOF */
          	?>
                <br />
                <div class="row">
                	<input type="hidden" value="<?php echo $aProducto['products_id']; ?>" name="products_id">
                    
                    <div class="col-sm-3">
                        <label style="float: left;line-height: 35px;padding-right: 10px;text-transform: capitalize;"><?php echo TEXT_UNITS; ?></label>
                    </div>
                    <div class="col-sm-9">
                       <input type="text" maxlength="2" value="1" id="cart_quantity" name="cart_quantity" class="form-control" style="width:60px;">
                    </div>
                </div>
                
                <div class="prdct-prco">
                    <div class="price" style="font-size:20px;">
                    	<s><?php //echo $aProductoInformacion['PRECIO_ANTERIOR']; ?></s>
            			<?php echo getPrecioImagen( $aProductoInformacion ); ?>
                    </div>
                </div>
              
                <div id="icon-stock" title="Quedan <?php echo $aProducto['products_quantity'];?> productos" class="prdct-icon-stock <?php echo $aProductoInformacion['CLASS_STOCK']; ?>"></div>
                 <?php  if($aProducto['products_quantity']<='0'){ ?>
                <a   class="btn btn-danger disabled" title="Comprar <?php echo $aProducto['products_name']; ?>"><?php echo AGOTADO; ?></a>
                <?php } else {?>
                <button value="" type="submit" class="btn btn-info" title="Comprar <?php echo $aProducto['products_name']; ?>"><?php echo IMAGE_BUTTON_IN_CART; ?></button>
                <?php } ?><br />
                <?php echo ENTRY_TAX_INCL; ?>
                <?php //echo $sHtmlAtributos;  ?>
            
    	</div>
	</div>
</div>
	</form>

<div class="row">
    <div class="col-md-12">
        <div id="tt1">
            <ul class="nav nav-tabs">   
                <?php if (tep_not_null($aProducto['products_description'])) { ?>
                <li class="product-description-tab"><a data-toggle="tab-pane2" href="#product-description-tab-content"><?php echo TEXT_TAB_CARACT; ?></a></li>
                <?php  } ?>  
                <li class="product-reviews-tab"><a href="#product-reviews-tab-content" data-toggle="tab-pane2"><?php echo TEXT_TAB_COMMENT; ?> (<?php echo $aProductoInformacion['NUMERO_COMENTARIO']; ?>)</a></li>
            </ul>
    
            <div id="content" class="tab-content">
                <?php if (tep_not_null($aProducto['products_description'])) { ?>
                <div itemprop="description" id="product-description-tab-content" class="product-description-tab-content tab-pane2">
                    <p><?php echo stripslashes($aProducto['products_description']); ?></p>
                </div>
                <?php  } ?> 
     
                <div id="product-reviews-tab-content" class="product-reviews-tab-content tab-pane2">
                    <p><?php include(DIR_WS_COMPONENTS . 'comentarios.php');?></p>
                 </div>
            </div>    
        </div> 
    </div>
</div>


<script type="text/javascript">
    jQuery(document).ready(function ($){
 	  $(".tab-pane2").hide();
	  $(".tab-pane2:first").show();
	  $("#tt1 > ul > li > a:first").addClass("active");
	  
	  $("#tt1 > ul > li > a").click(function(){
	  var activeTab = $(this).attr("href");
	  $("#tt1 > ul > .product-reviews-tab").removeClass("active");
	  $("#tt1 ul li a").removeClass("active");
	  $(this).addClass("active");
	  $(".tab-pane2").hide();
	  $(activeTab).fadeIn();
	  return false;
	  });  

	  $('#link-reviews').click(function(){
		  $(".tab-pane2").hide();
		  $("#tt1 ul li a").removeClass("active");
		  $(".product-reviews-tab-content").show();
		  $("#tt1 > ul > .product-reviews-tab").addClass("active");
	});  
    });
	
</script> 
