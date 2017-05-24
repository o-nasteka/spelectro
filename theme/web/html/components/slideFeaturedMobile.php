<?php
$i=0;

?>
<ul class="bxslider">
<?php while( $aProducto = tep_db_fetch_array( $aDatos ) ): ?>


<?php
$i++;
?>


<li>

<div class="container_photo">
<a href="<?php echo tep_href_link( FILENAME_PRODUCT_INFO, 'products_id=' . $aProducto["products_id"] ); ?>">
<?php echo tep_image(DIR_WS_IMAGES . 'productos/' .$aProducto['products_image'], $aProductoInformacion['TITLE'], 250, 250, false); ?>
</a>
</div>

</li>













<?php
 endwhile; 
 ?>
 </ul>