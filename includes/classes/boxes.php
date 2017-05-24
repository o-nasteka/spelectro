<?php
/*
  $Id: boxes.php 1739 2007-12-20 00:52:16Z hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '2';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
   function __construct(){

   }

    function tableBox($contents, $direct_output = false) {
      /*$tableBox_string = '<div';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";*/

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
            }
          }
        } else {
          $tableBox_string .=  $contents[$i]['text']. "\n";
        }

        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      /*$tableBox_string .= '</div>' . "\n";*/

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
    function tableBoxCart($contents, $direct_output = false) {
      $tableBox_string = '<table';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }
  class infoBox extends tableBox {
    // Graphical Borders
    function __construct($contents) {
    parent::__construct();
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->mws_infoBoxContents($contents));
      $this->tableBox($info_box_contents, true);
    }

    function mws_infoboxcontents($contents) {
      global $mws_headerText, $mws_headerLink, $mws_TxtLink;
      if ($mws_TxtLink) $mws_headerText = '<a href="' . $mws_TxtLink . '" class="mws_boxTop">' . $mws_headerText . '</a>'; 
      $this->table_cellpadding = '0';
      $this->align = 'center';
      $info_box_contents = array();
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                            'form' => (isset($contents[$i]['form'])  ? $contents[$i]['form'] : ''),
                                          'params' => 'class="boxText"',
                                            'text' => (isset($contents[$i]['text'])  ? $contents[$i]['text'] : '')));
      }
      $output = '
      <div class="infobox">
	  		<div class="bottom">
				<div class="top"><h3>';
				if ($mws_headerLink != false) {
				  $output .= '<a href="' . $mws_headerLink . '" class="flechita">';
				}
				$output.=$mws_headerText;
				if ($mws_headerLink != false) {
					  $output .= '</a>';
				}
				$output.='</h3>';
				$output .= $this->tableBox($info_box_contents) . '
			  </div>
		  </div>
	</div>';
			$mws_headerText = ''; $mws_headerLink = ''; $mws_TxtLink = '';
      return $output;
    }
// Graphical Borders - end modification

    function infoBoxContents($contents) {
      $this->table_cellpadding = '3';
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')));
      }
      return $this->tableBox($info_box_contents);
    }
  }

  // Graphical Borders
  class infoBoxHeading extends tableBox {
    function __construct($contents, $left_corner = true, $right_corner = true, $right_arrow = false, $title_link = false) {
      global $mws_headerText, $mws_headerLink, $mws_TxtLink;
      
      parent::__construct();
      $mws_headerText = $contents[0]['text'];
      $mws_headerLink = $right_arrow;
      $mws_TxtLink = $title_link;
    }
  }
// Graphical Borders - end modification

// Graphical Borders
  class contentBox extends tableBox {
    function __contsruct($contents) {
    parent::__construct();
     $this->table_cellpadding = '0';
     $this->table_cellspacing = '3';
     $this->table_data_parameters = 'class="noborderBox"';
     $this->tableBox($contents, true);
     echo mws_boxFooter ();
    }

   function contentBoxContents($contents) {
      $this->table_cellpadding = '4';
      $this->table_parameters = 'class="infoBoxContents"';
      return $this->tableBox($contents);
    }
  }
// Graphical Borders - end modification

// Graphical Borders
 class contentBoxHeading extends tableBox {
    function __construct($contents, $head = true) {
    parent::__construct();
		echo mws_boxHeader ($contents[0]['text']);
     }
  }
// Graphical Borders - end modification

  class errorBox extends tableBox {
    function __construct($contents) {
    parent::__construct();
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

 // Graphical Borders
  class productListingBox extends tableBox {
    function __construct($contents) {
    parent::__construct();
      global $mws_headerText;
      echo mws_boxHeader ($mws_headerText);
      $this->table_parameters = 'class="productListing"';
      $this->tableBox($contents, true);
			$mws_headerText = '';
      echo mws_boxFooter ();
    }
  }
  class noborderBox extends tableBox {
  function __construct($contents, $cart=false) {
  parent::__construct();
    $this->table_cellpadding = '0';
    $this->table_cellspacing = '3';
    $this->table_data_parameters = 'class="noborderBox"';
	if ($cart===true)
    $this->tableBoxCart($contents, true);
	else
	$this->tableBox($contents, true);
   }
 }
 
// Graphical Borders - end modification
?>
