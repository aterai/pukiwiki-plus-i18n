<?php
//-*- mode:java; Encoding:utf8n -*-
include_once(PLUGIN_DIR.'ref.inc.php');

function plugin_download_convert() {
    global $vars, $defaultpage;
    $args = func_get_args();
    $imgpath = '';

    $path = func_get_args();
    $image = htmlspecialchars(trim($path[0]));
    //$head_tags[] = '<meta property="og:image" content="' . $image . '" />';

    $page = isset($vars['page']) ? $vars['page'] : '';
    if($page == '' || $page == $defaultpage) return '';

    if (strstr($image, 'googleusercontent')) {
        $params = plugin_ref_body($args);
        if (isset($params['_error']) && $params['_error'] != '') {
            // Error
            $imgpath = '&amp;ref(): ' . $params['_error'] . ';';
        } else {
            $imgpath = $params['_body'];
        }
    } else {
        $imgpath = '<img src="' . $image . '" class="img-fluid" itemprop="image" alt="' . $page . '.png" title="' . $page . '.png" />';
    }

    $ads = '';
    if (! strstr($page, 'JLayeredPane1')) {
        $ads = <<<EOD
<aside class="col-md-6 col-md-offset-1 col-xs-12">
<!-- ateraimemo.com, 336x280, 09/11/04 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-6939179021013694"
     data-ad-slot="9248548235"></ins>
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
    $git = "https://github.com/aterai/java-swing-tips/tree/master/" . $dir;

    return <<<EOD
<div class="row">
  <div class="col-md-6 col-xs-12">
    <ul>
      <li><a href="$jar" download="example.jar">Runnable JARファイル <small>example.jar</small></a></li>
      <li><a href="$zip" download="src.zip">ソースコード <small>src.zip</small></a></li>
      <li><a href="$git">リポジトリ <small>repository</small></a></li>
    </ul>
    <figure>$imgpath</figure>
  </div>
  <div class="col-md-6 col-xs-12">
    $ads
  </div>
</div>
EOD;
}
?>
