<?php
//-*- mode:java; Encoding:utf8n -*-
include_once(PLUGIN_DIR.'ref.inc.php');

function plugin_img2_convert() {
    global $vars, $defaultpage;
    $imgpath = '';

    $path = func_get_args();
    if (func_num_args() == 2) {
        $image = htmlspecialchars(trim($path[0]));
        $imgpath = '<img src="' . $image . '" class="img-responsive" itemprop="image" />';
        $link = htmlspecialchars(trim($path[1]));
        $url = get_script_uri() . $link . '.html';
        return <<<EOD
<a href="$url">$imgpath</a>
EOD;
    } else {
        $image = htmlspecialchars(trim($path[0]));
        $imgpath = '<img src="' . $image . '" class="img-responsive" itemprop="image" />';
        return <<<EOD
<p>$imgpath</p>
EOD;
    }
}
?>
