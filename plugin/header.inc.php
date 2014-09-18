<?php
//-*- mode:java; Encoding:utf8n -*-
// $Id: header.inc.php $

include_once(PLUGIN_DIR.'counter.inc.php');

function plugin_header_convert() {
    global $title, $newtitle, $whatsnew;
    global $vars;
    global $page_title;
    global $page_tags;
    global $page_author;
    global $page_pubdate;

    $_page   = isset($vars['page']) ? $vars['page'] : '';
    $is_page = (is_pagename($_page) && ! arg_check('backup') && $_page != $whatsnew);
    $is_read = (arg_check('read') && is_page($_page));

    $path = explode('/', $_page);
    $navi = '';
    if ($is_read && count($path) > 1) {
        include_once(PLUGIN_DIR.'navi.inc.php');
        $navi = plugin_navi_convert($path[0]);
    }

    if ($newtitle != '') {
        $h1 = $newtitle; // . ' <small>' . str_replace('/', ' / ', $title) . '</small>';
    } else {
        $h1 = $title;
    }

    $tags_buf = '';
    foreach ( $page_tags as $tag ) {
        $tag = trim($tag);
        $tags_buf = $tags_buf . '<li><a href="/tags.html#' . $tag . '-ref"><span itemprop="keywords">' . $tag . '</span></a></li>';
    }
    if ($tags_buf != '') {
        $tags_buf = '<ul class="tag_box inline"><li><i class="glyphicon-tags"></i></li>' . $tags_buf . '</ul>';
    }

    $time  = $is_read ? get_filetime($_page) : 0;
    $total = plugin_counter_inline();
    $today = plugin_counter_inline('today');
    $yesterday = plugin_counter_inline('yesterday');

    $counter = '';
    if ($is_read) {
        $counter = 'Total: <code>' . $total . '</code>, Today: <code>' . $today . '</code>, Yesterday: <code>' . $yesterday . '</code>';
    }

    $last_modified_str = '';
    if ( $time ) {
        $isotime = date('c', $time);
        $lastmod = date('Y-m-d H:i', $time);
        //$last_modified_str = '<br />Last-modified: <a href="' . get_script_uri() . '?cmd=diff&page=' . $_page . '"><time datetime="' . $isotime . '">' . $lastmod . '</time></a>';
        $last_modified_str = '<br />Last-modified: <time itemprop="dateModified" datetime="' . $isotime . '">' . $lastmod . '</time>';
    }

    $posted_by_str = '';
    if ($page_pubdate) {
        $iso_pubdate_str = date('c', $page_pubdate);
        $pubdate_str = date('Y-m-d', $page_pubdate);
        $url = get_script_uri() . $page_author . '.html';
        //$posted_by_str = '<br />Posted by <a rel="author" href="' . $url . '"><span itemprop="nickname">' . $page_author . '</span></a> at <a href="' . get_script_uri() . '?cmd=backup&page=' . $_page . '"><time pubdate="' . $iso_pubdate_str . '">' . $pubdate_str . '</time></a>';
        $posted_by_str = '<br />Posted by <span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person"><a rel="author" itemprop="url" href="' . $url . '"><span itemprop="name">' . $page_author . '</span></a></span> at <time itemprop="datePublished" datetime="' . $iso_pubdate_str . '">' . $pubdate_str . '</time>';
    }

    return <<<EOD
<div class="page-header">
$navi
<h1 itemprop="headline">$h1</h1>
<div class="row">
<div class="col-md-8 col-xs-12">
$tags_buf
</div><!-- col-md-8 -->
<div class="col-md-4 col-xs-12">
<p class="text-right">
$counter
$posted_by_str
$last_modified_str
</p>
</div><!-- col-md-4 -->
</div><!-- /row -->
</div><!-- /page-header -->
EOD;
}
?>
