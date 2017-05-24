<?php
/*

Graphical Boxes v1.00

     Functions
*/

  function mws_header ($msg='') {
    $output = '
    <td valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/upper_left.gif','') . '</td>
          <td class="mws_boxTop" align="center" valign="middle" width="100%">' . $msg . '</td>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/upper_right.gif','') . '</td>
        </tr>
        <tr>
          <td class="mws_boxLeft"></td>
          <td class="mws_boxCenter">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>';
    return $output;
  }

  function mws_footer ($msg='') {
    $output = '
              </tr>
            </table>
          </td>
          <td class="mws_boxRight"></td>
        </tr>
        <tr>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/lower_left.gif','') . '</td>
          <td class="mws_boxBottom">' . $msg . '</td>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/lower_right.gif','') . '</td>
        </tr>
      </table>
    </td>';
    return $output;
  }

  function mws_boxHeader ($msg='') {
    $output = '
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/upper_left.gif','') . '</td>
          <td class="mws_boxTop" align="center" valign="middle" width="100%">' . $msg . '</td>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/upper_right.gif','') . '</td>
        </tr>
        <tr>
          <td class="mws_boxLeft"></td>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="mws_boxCenter">';
    return $output;
  }

  function mws_boxFooter ($msg='') {
    $output = '
                </td>
              </tr>
            </table>
          </td>
          <td class="mws_boxRight"></td>
        </tr>
        <tr>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/lower_left.gif','') . '</td>
          <td class="mws_boxBottom">' . $msg . '</td>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox/lower_right.gif','') . '</td>
        </tr>
      </table>';
    return $output;
  }

  function mws_header_main ($msg='') {
    $output = '
    <td valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox_main/upper_left.gif','') . '</td>
          <td class="mws_boxTop_main" align="center" valign="middle" width="100%">' . $msg . '</td>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox_main/upper_right.gif','') . '</td>
        </tr>
        <tr>
          <td class="mws_boxLeft_main"></td>
          <td class="mws_boxCenter_main">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>';
    return $output;
  }

  function mws_footer_main ($msg='') {
    $output = '
              </tr>
            </table>
          </td>
          <td class="mws_boxRight_main"></td>
        </tr>
        <tr>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox_main/lower_left.gif','') . '</td>
          <td class="mws_boxBottom_main">' . $msg . '</td>
          <td>' . tep_image(DIR_WS_IMAGES . 'infobox_main/lower_right.gif','') . '</td>
        </tr>
      </table>
    </td>';
    return $output;
  }
?>