<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_user_inline() {
    $num = func_num_args();
    if ($num == 0) {
        return 'Usage: #user(user)';
    }
    $args = func_get_args();
    $contents = array_map("htmlspecialchars", $args);
    $name = join('', $contents);
    //<a itemprop="creator" itemscope="itemscope" itemtype="http://schema.org/Person"><span itemprop="name">$buf</span></a>
    //<span itemprop="name" itemscope="itemscope" itemtype="http://schema.org/Person"><a rel="author" itemprop="url" href="http://ateraimemo.com/:Users/$name.html"><span itemprop="name">$name</span></a></span>
return <<<EOD
<span itemprop="creator" itemscope="itemscope" itemtype="http://schema.org/Person"><span itemprop="name">$name</span></span>
EOD;
}
