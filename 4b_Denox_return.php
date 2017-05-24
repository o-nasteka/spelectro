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
require(DIR_WS_MODULES . 'payment/Qb_Denox.php');
$Qb=new Qb_Denox();
$Qb->save_result();
?>