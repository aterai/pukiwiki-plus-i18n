<?php
//-*- mode:java; Encoding:utf8n -*-
//function plugin_matched_inline() {
function plugin_matched_convert() {
    global $vars;
    $_page = isset($vars['page']) ? $vars['page'] : '';
    if(! file_exists(get_filename($_page))) {
        return '';
    }
return <<<EOD
<aside class="ad_bar">
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6939179021013694"
     data-ad-slot="7781965536"
     data-ad-format="autorelaxed"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</aside>
EOD;
}
