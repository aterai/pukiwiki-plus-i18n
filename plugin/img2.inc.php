<?php
//-*- mode:java; Encoding:utf8n -*-
include_once(PLUGIN_DIR.'ref.inc.php');

function plugin_img2_convert() {
    global $vars, $defaultpage;
    $args = func_get_args();
    $imgpath = '';

    $path = func_get_args();
    $image = htmlspecialchars(trim($path[0]));

    $imgpath = '<img src="' . $image . '" class="img-responsive" itemprop="image" />';

    return <<<EOD
<p>$imgpath</p>
EOD;
}
?>
