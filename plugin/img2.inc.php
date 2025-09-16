<?php
//-*- mode:java; Encoding:utf8n -*-
include_once(PLUGIN_DIR . 'ref.inc.php');

function plugin_img2_convert(): string
{
    global $vars, $defaultpage;
    $imgpath = '';

    $path = func_get_args();
    $ssurl = preg_replace('/https:\/\/drive\.google\.com\/uc/', 'https://drive.google.com/thumbnail', htmlspecialchars(trim($path[0])));
    if (str_contains($ssurl, 'thumbnail')) {
        $image = $ssurl . '&sz=w1000';
    } else {
        $image = $ssurl;
    }
    // $image = htmlspecialchars(trim($path[0]));
    if (func_num_args() == 2) {
        $imgpath = '<img src="' . $image . '" class="img-fluid" itemprop="image" />';
        $link = htmlspecialchars(trim($path[1]));
        $url = get_script_uri() . $link . '.html';
        return <<<EOD
<a href="$url">$imgpath</a>
EOD;
    } else {
        $imgpath = '<img src="' . $image . '" class="img-fluid" itemprop="image" />';
        return <<<EOD
<p>$imgpath</p>
EOD;
    }
}


