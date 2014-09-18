<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_time_convert() {
    global $page_pubdate;
    if(func_num_args() == 0) {
        return '';
    }
    $aryargs = func_get_args();
    $time = strtotime($aryargs[0]);
    $isotime = date('c', $time);
    $pubdate = date('Y-m-d', $time);
    $page_pubdate = $time;
    return '';
}
function plugin_time_inline() {
    global $page_pubdate;
    if(func_num_args() == 0) {
        return '';
    }
    $aryargs = func_get_args();
    $time = strtotime($aryargs[0]);
    $isotime = date('c', $time);
    $pubdate = date('Y-m-d', $time);
    $page_pubdate = $time;
    return <<<EOD
<time pubdate="$isotime">$pubdate</time>
EOD;
}
?>
