<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_user_inline() {
    $num = func_num_args();
    if ($num == 0) {
        return 'Usage: #user(user)';
    }
    $args = func_get_args();
    $contents = array_map("htmlspecialchars", $args);
    $buf = join(' ', $contents);
return <<<EOD
<strong itemprop="creator" itemscope itemtype="http://schema.org/Person"><span itemprop="name">$buf</span></strong>
EOD;
}
?>
