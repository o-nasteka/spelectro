<?php 
$subtotal=0;
/*
<a href="shopping_cart.php" id="cbcr-crrt-<?php echo $languages_id; ?>">
	<span class="ShoppingCartTitle">Mi compra</span>
	<span class="num-cntnt">
		<?php
			$aTotal = str_split( $cart->count_contents() );

			foreach( $aTotal as $sNumero )
				echo $sNumero;
		?>
	</span>
	<span id="cbcr-crrt-ttal">
        TOTAL: <strong><?php echo $currencies->format($cart->show_total()) ?></strong>
    </span>
</a>
*/ ?>

 <?php
                        if ($cart->count_contents() > 0) {
                    ?>
                    <li class="dropdown sm_popup_cart">
                        <a class="dropdown-toggle popup_cart"

						id="cart_button_top" 
						href="/shopping_cart.php">
						Mi Compra <i class="icon-cart"></i> <?php echo $cart->count_contents(); ?></a>
                        <ul class="dropdown-menu" id="cart_top_ul" >
						
						<div class="titl">Mi cesta:</div>
						
						<div class="list">
						
                             <?php      
                                $products = $cart->get_products();
                                
                                foreach ($products as $k => $v) {
                                   
								  // echo '<li><span class="small">' . $v['quantity'] .' X '. $v['name'] . '</span></li> <li role="separator" class="divider"></li>';
                                ?>
								<li class="item_top_cart">
								
								
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 fotocart">  
								
								<img src="<?php echo DIR_WS_IMAGES ."productos/". $v['image']; ?>" />
								
								</div>
								
								
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 desccart">  
								
								<span>
								<?php
								echo $v['name'];
								
								?>
								</span>
								
								</div>
								
								
								
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 cantcart">  
								
								
								<span class="cant_top_cart">
								<?php
								//echo $cart->count_contents();
								
								echo $v['quantity'];
								?>
								</span>
								
								
								<span class="precio_top_cart">
								<?php
								
								echo $v['final_price']."&euro;";
								$subtotal+=$v['final_price'];
								?>
								</span>
								
								
								</div>
								
								
								
								</li>								
								<?php
								}        
                             ?>
                            </div>
                             <li class="subt">
							 <?php //echo '<a href="' . tep_href_link('shopping_cart.php') . '">' . 'Ver Cesta de la Compra' . '</a>'; ?>
							 
							 <span>Subtotal:</span><i>
							 <?php

								echo $subtotal."&euro;";
								
								?>
							 </i>
							 
							 </li>
                             
							 <li>
							 
							 
							 </li>
							 
							 <li><a class="pedido" href="<?php echo tep_href_link('checkout_shipping.php', '', 'SSL') . '">Realizar Pedido' . '</a>'; ?></li>
                        </ul>
                    </li>
                    <?php
                        
                    }
                    else {
                        echo '<li class="sm_popup_cart"><p class="navbar-text">' . 'La Cesta esta vacía' . '</p></li>';
                    }
                    ?>  

