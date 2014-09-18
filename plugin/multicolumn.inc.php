<?php
function plugin_multicolumn_convert() {
    static $times = 0;
    static $divide = 0;
    static $width = 0;

    if (func_num_args() > 1) return FALSE;
    if ($times == 0) {
        list($divide) = func_get_args();
        if (is_numeric($divide)) {
            if ($divide > 7) return FALSE;
            $width = 90 / $divide;
        } else {
            $width = 90 / 2;
            $divide = 2;
        }
    }
    $times++;

    if ($times == 1)
      return "<div style=\"padding:0 1%;position:relative;float:left;width:".sprintf("%d",$width)."%;\">";
    if ($times <= $divide)
      return "</div>\n<div style=\"padding:0 1%;position:relative;float:left;width:".sprintf("%d",$width)."%;\">";

    $times = 0;
    return "</div><div style=\"clear:both;\"><br style=\"display:none;\" /></div>";
}
?>
