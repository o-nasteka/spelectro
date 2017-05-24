 <?php if(ONLY_INDEX == 'null'){ ?>

<div class="row">
    <div class="col-md-12">

<?php
/*
  responsive-oscommerce.com
*/

  function tep_get_slides($random=false) {
    global $languages_id;
    
    $slides_query = tep_db_query("select po.slides_id, pod.slides_title, po.slides_url, pod.slides_html_text, po.slides_image FROM " . TABLE_SLIDES . " po, " . TABLE_SLIDES_DESCRIPTION . " pod where pod.slides_id = po.slides_id and pod.language_id = '" . (int)$languages_id . "' and status = '1' order by po.slides_id DESC"); 
  
	$slides_array = array();
    
      while ($slide_values = tep_db_fetch_array($slides_query)) {
        $slides_array[] = array('slide_name' => $slide_values['slides_title'],
                                   'slide_url' => $slide_values['slides_url'],
								   'slide_description' => $slide_values['slides_html_text'],
								   'slide_image' => $slide_values['slides_image']);
	  }
   
   if($random) shuffle( $slides_array );   

  	 $slides_ul = '<ul class="slides">';
  
   foreach ($slides_array as $k){
   
   	if($k['slide_url'])   
   		$slides_ul .=  '<li><a href="' . $k['slide_url']. '">' . tep_image(DIR_WS_IMAGES .'slideshow/' . $k['slide_image'], $k['slide_name']) . '</a></li>'."\n";
	 else $slides_ul .=  '<li>' . tep_image(DIR_WS_IMAGES .'slideshow/' . $k['slide_image'], $k['slide_name']) . '</li>'."\n";  
      }
     $slides_ul .= '</ul>';

   
    return $slides_ul;	
  }

   $slideshow = tep_get_slides(true);
  
?>

        <div id="slider">	
            <div id="slide-left" class="flexslider">
                <div class="flex-viewport">
                    <?php 
 					
					echo  $slideshow;
					
					?>
                </div>
            </div>
        </div>
    </div>
</div>

 	  
   <?php
      }
   ?>

   
   

<?php
	// Variables
	$aListas = array();

	// Obtenemos las categorias
	$aDatos = tep_db_query( 'select c.categories_id, cd.categories_name, c.parent_id, c.categories_image
							 from ' . TABLE_CATEGORIES . ' c
							 inner join ' . TABLE_CATEGORIES_DESCRIPTION . ' cd on (c.categories_id = cd.categories_id)
							 where c.categories_status != 0 and cd.language_id=' . (int)$languages_id . '
							 order by sort_order, cd.categories_name' );

	// Creamos el array principal
	while( $aDato = tep_db_fetch_array( $aDatos ) )
		$aListas[] = $aDato;


?>



<div class="row cat_header" id="cat_header" style="cursor:pointer;" onclick="showcatt();">
<div class="col-xs-1 col-md-1 col-lg-1">
<a>
+
</a>
</div>
  


<div class="col-xs-11 col-md-11 col-lg-11">



<?php echo BOX_HEADING_CATEGORIES; ?>



</div>



</div>



<div id="cat_opened">
<div id="cat_tree_mobile_container">




<?php
$sHref = '';

		foreach( $aListas as $aLista )
		{
			if( $aLista['parent_id'] == 0 )
			{
			 
	
?>


<div class="col-xs-4 col-md-4 col-lg-4 rounded_cat">

<?php

$img='<img class="cat_img" src="/images/categorias/'.$aLista['categories_image'].'" />';

?>


<?php

echo '<a href="' . tep_href_link( FILENAME_CATEGORIES, 'cPath=' . $aLista['categories_id'] ) . '" title="' . $aLista['categories_name'] . '">' . $img. $aLista['categories_name'] . '</a>';

?>


</div>
<?php



		}
		}
?>

</div>
</div>


   
   
<?php

	// Ofertas
	?>
	
	
	
		<div class="infoBoxHeading"><?php //echo sprintf(TABLE_HEADING_SPECIALS, strftime('%B')); ?></div>
	
	
	






<div id="tab-container" class="tab-container">
<ul class='etabs'>
    <li class='tab'><a href="#specials">
    	Specials en Mayo
    </a></li>
    <li class='tab'><a href="#destacados">Destacados</a></li>
  </ul>
 
  <div id="specials">
    <?php
    include( DIR_WS_COMPONENTS . FILENAME_SPECIALS );
	?>
    <!-- content -->
  </div> 


  <div id="destacados">
    <?php
    include( DIR_WS_COMPONENTS . FILENAME_FEATURED );
	
    ?>
    <!-- content -->
  </div>
  


</div>

ßß




	<?php
	
	//include( DIR_WS_COMPONENTS . FILENAME_SPECIALS );
	
	
	
	// Destacados
	//include( DIR_WS_COMPONENTS . FILENAME_FEATURED );
	
	// Novedades
	//include( DIR_WS_COMPONENTS . FILENAME_NEW_PRODUCTS );

	// Productos inicio
	//include( DIR_WS_COMPONENTS . FILENAME_PRODUCTS_HOME );
	
	// Proximamente
	//include( DIR_WS_COMPONENTS . FILENAME_UPCOMING_PRODUCTS );
	
	
	
	
	
	
?>

