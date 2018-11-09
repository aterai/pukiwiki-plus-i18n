<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_tags_convert() {
    global $page_tags, $head_tags;

    $num = func_num_args();
    if ($num == 0) { return 'Usage: #tags(tags)'; }
    $args = func_get_args();
    $contents = array_map("htmlspecialchars", $args);
    $head_tags[] = '<meta name="keywords" content="'.join(',', $contents).'" />';
    $page_tags = $contents;
    return '';
}

function plugin_tags_inline() {
    global $vars, $page_tags;
    $page = isset($vars['page']) ? $vars['page'] : '';
    if($page == '' || $page == $defaultpage) return '';

    $buf = '';
    //$args = func_get_args();
    $page_tags = & func_get_args();
    foreach ( $page_tags as $arg ) {
        $arg = trim($arg);
        $buf = $buf . '<li><a href="/tags.html#' . $arg . '-ref">' . $arg . '</a></li>';
    }
return <<<EOD
<ul class="tag_box inline">
<li><i class="glyphicon-tags" aria-hidden="true"></i></li>
$buf
</ul>
EOD;
}
?>
