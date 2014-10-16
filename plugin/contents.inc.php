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
    if($type == 'amazon' ) {
        $ad = <<<EOD
<script type="text/javascript"><!--
amazon_ad_tag = "teraixreajp-22";  amazon_ad_width = "180";  amazon_ad_height = "150";  amazon_ad_logo = "hide";  amazon_ad_link_target = "new";  amazon_ad_price = "retail";//--></script>
<script type="text/javascript" src="http://www.assoc-amazon.jp/s/ads.js"></script>
EOD;
    }else if($type == 'none' ) {
        $ad = '';
    }else{
        $ad = <<<EOD
<!-- xrea, big, 336x280, 09/11/04 -->
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
  <div class="ad_box" itemscope="itemscope" itemtype="http://schema.org/WPAdBlock">$ad</div>
</div>
<div class="clearfix"></div>
</div>
EOD;
}
?>
