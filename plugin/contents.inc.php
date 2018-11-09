<?php
// PukiWiki - Yet another WikiWikiWeb clone
// $Id: contents.inc.php,v 1.1 2005/04/10 18:41:02 teanan Exp $
//

function plugin_contents_convert() {
    $aryargs = func_get_args();
    if (func_num_args() == 0 || func_num_args() > 2) {
        $type = 'adsense';
    }else{
        $type = htmlspecialchars(trim($aryargs[0]));
    }
    if($type == 'none' ) {
        $ad = '';
    }else{
        $ad = <<<EOD
<!-- ateraimemo.com, 336x280, 09/11/04 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-6939179021013694"
     data-ad-slot="9248548235"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
EOD;
    }
    // This character string is substituted later.
    return <<<EOD
<div class="row">
<div class="col-md-7 col-xs-12">
  <#_contents_>
</div>
<div class="col-md-5 col-xs-12">
  <aside class="ad_box">$ad</aside>
</div>
<div class="clearfix"></div>
</div>
EOD;
}
?>
