<?php
function plugin_adsensebar_convert() {
    return <<<EOD
<aside class="ad_bar">
<!-- ateraimemo.com, banner, 728x90, 08/03/04 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-6939179021013694"
     data-ad-slot="7354589301"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</aside>
EOD;
}
