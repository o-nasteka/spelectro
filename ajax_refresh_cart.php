<?php 
  require('includes/application_top.php');
  
  if ($cart->count_contents() > 0) echo '<a class="dropdown-toggle popup_cart" id="cart_button_top" href="/shopping_cart.php">Mi Compra <i class="icon-cart"></i> '.$cart->count_contents().'</a>';
  else echo '<p class="navbar-text">La Cesta esta vacía</p>';  
?>  