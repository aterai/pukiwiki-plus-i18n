<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_flair_inline() {
return <<<EOD
<span itemprop="author" itemscope="itemscope" itemtype="http://schema.org/Person">
<a rel="me" itemprop="url" href="http://stackoverflow.com/users/177145/aterai">
<img src="http://stackoverflow.com/users/flair/177145.png" width="208" height="58" alt="profile for aterai at Stack Overflow, Q&amp;A for professional and enthusiast programmers" title="profile for aterai at Stack Overflow, Q&amp;A for professional and enthusiast programmers" />
</a>
</span>
EOD;
}
?>
