<?php
/*
  Módulo de págo de la pasarela 4B 
  Author:
  Denox
  http://www.Denox.es
  
  Copyright (c) 2008 Denox
  Released under the GNU General Public License  
*/

require('includes/application_top.php');
tep_redirect(tep_href_link(FILENAME_CHECKOUT_PROCESS, 'pszPurchorderNum='.$_REQUEST['pszPurchorderNum'], 'SSL', true, false)); 
?>