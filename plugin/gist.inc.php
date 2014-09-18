<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_gist_convert() {
    $args = func_get_args();
    $id   = array_shift($args);
    return <<<EOD
<div><script src="https://gist.github.com/aterai/$id.js"></script></div>
EOD;
}
?>
