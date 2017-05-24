<div id="dstc">
	<div class="box-slde">
		<a id="dstc-drch" class="box-slde-drch" href="javascript:void(0);"></a>
		<a id="dstc-izqd" class="box-slde-izqd" href="javascript:void(0);"></a>
		<div class="box-slde-cntd">
			<div id="dstc-sldr" class="box-slde-slde">
			    <?php while( $aBanner = mysql_fetch_array( $aBanners ) ): ?>
					<div class="prdct-slde"><a title="<?php echo $aBanner['titulo']; ?>" href="<?php echo $aBanner['enlace']; ?>"><img alt="<?php echo $aBanner['titulo']; ?>" src="images/<?php echo $aBanner['imagen_grande']; ?>" width="702" height="365" /></a></div>
					<?php $nCont++; ?>
        		<?php endwhile;?>
		 	</div>
		 	<div class="box-slde-pgnd" id="dstc-pgnd"></div>
		</div>
	</div>
</div>