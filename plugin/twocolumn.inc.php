<?php
function plugin_twocolumn_convert() {
    static $times = 0;

    $times++;
    if ($times == 1) {
        return '<div class="row"><div class="col-md-6 col-xs-12">';
    } else if ( $times == 2) {
        return '</div><div class="col-md-6 col-xs-12">';
    }
    $times = 0;
    return '</div></div>';
}
?>