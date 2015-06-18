<?php
//-*- mode:java; Encoding:utf8n -*-
include_once(PLUGIN_DIR.'ref.inc.php');

function plugin_download_convert() {
    global $vars, $defaultpage;
    $args = func_get_args();
    $imgpath = '';

    $params = plugin_ref_body(func_get_args());
    if (isset($params['_error']) && $params['_error'] != '') {
        // Error
        $imgpath = '&amp;ref(): ' . $params['_error'] . ';';
    } else {
        $imgpath = $params['_body'];
    }

    $page = isset($vars['page']) ? $vars['page'] : '';
    if($page == '' || $page == $defaultpage) return '';

    $ads = '';
    if (! strstr($page, 'JLayeredPane1')) {
        $ads = <<<EOD
<aside class="col-md-6 col-md-offset-1 col-xs-12">
<!-- responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6939179021013694"
     data-ad-slot="1067574330"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</aside>
EOD;
    }

    $url = get_script_uri() . strtolower($page);
    $jar = $url . '/example.jar';
    $zip = $url . '/src.zip';
    $dir = str_replace('Swing/', '', $page);
    //$low = strtolower($dir);
    $git = "//github.com/aterai/java-swing-tips/tree/master/" . $dir;

    return <<<EOD
<div class="row">

<div class="col-md-5 col-xs-12">
<div itemscope="itemscope" itemtype="http://schema.org/Code">
<p><a href="$jar" class="btn btn-block btn-danger"  download="example.jar"><span class="glyphicon glyphicon-save icon-white"></span> Runnable JARファイル <small>example.jar</small></a></p>
<p><a href="$zip" class="btn btn-block btn-success" download="src.zip"><span class="glyphicon glyphicon-cloud-download icon-white"></span> ソースコード <small>src.zip</small></a></p>
<p><a href="$git" class="btn btn-block btn-info" itemprop="codeRepository"><span class="glyphicon glyphicon-import icon-white"></span> リポジトリ <small>repository</small></a></p>
</div>
<p>$imgpath</p>
</div>
$ads
</div>
EOD;
}
?>
