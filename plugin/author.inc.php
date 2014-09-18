<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_author_convert() {
    global $page_author;
    $name = '';
    if(func_num_args() > 0) {
        $aryargs = func_get_args();
        $name = htmlspecialchars(trim($aryargs[0]));
    }
    $page_author = $name;
    return '';
}

function plugin_author_inline() {
    global $vars, $page_author;
    $name = '';
    if(func_num_args() > 0) {
        $aryargs = func_get_args();
        $name = htmlspecialchars(trim($aryargs[0]));
    }
    $page_author = $name;
    $url = get_script_uri() . $page_author . '.html';
    if (! is_url($url)) return '';
    return <<<EOD
<a rel="author" href="$url"><span itemprop="nickname">$page_author</span></a>
EOD;
}
?>
