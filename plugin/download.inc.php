<?php

//-*- mode:java; Encoding:utf8n -*-
include_once PLUGIN_DIR . 'ref.inc.php';

function plugin_download_convert(): string
{
    global $vars, $defaultpage;
    $args = func_get_args();
    $imgpath = '';

    $path = func_get_args();
    $image = htmlspecialchars(trim($path[0]));
    //$head_tags[] = '<meta property="og:image" content="' . $image . '" />';

    $page = $vars['page'] ?? '';
    if ($page == '' || $page == $defaultpage)
        return '';

    //     if (strstr($image, 'googleusercontent')) {
    //         $params = plugin_ref_body($args);
    //         if (isset($params['_error']) && $params['_error'] != '') {
    //             // Error
    //             $imgpath = '&amp;ref(): ' . $params['_error'] . ';';
    //         } else {
    //             $imgpath = $params['_body'];
    //         }
    //     } else {
    //         $imgpath = '<img src="' . $image . '" class="img-fluid" itemprop="image" alt="' . $page . '.png" title="' . $page . '.png" />';
    //     }

    $ads = '';
    if (!str_contains($page, 'JLayeredPane1')) {
        $ads = <<<EOD
        <aside class="col-md-6 col-md-offset-1 col-xs-12">
        <!-- ateraimemo.com, 336x280, 09/11/04 -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6939179021013694"
             data-ad-slot="9248548235"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
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
    $git = 'https://github.com/aterai/java-swing-tips/blob/main/examples/' . $dir;
    $imgpath =
        '<img src="' .
        $url .
        '/screenshot.png" class="img-fluid" itemprop="image" alt="screenshot" title="' .
        $page .
        '" />';

    return <<<EOD
    <div class="row">
      <div class="col-md-6 col-xs-12 h-100">
        <h3 id="screenshot" data-needslink="screenshot">Screenshot</h3>
        <figure>$imgpath</figure>
      </div>
      <div class="col-md-6 col-xs-12 h-100">
        <h3 id="download" data-needslink="download">Download</h3>
        <ul>
          <li><a href="$jar" download="example.jar">Runnable JAR <small>example.jar</small></a></li>
          <li><a href="$zip" download="src.zip">Source code <small>src.zip</small></a></li>
          <li><a href="$git">Repository <small>GitHub</small></a></li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h3 id="advertisement" data-needslink="advertisement">Advertisement</h3>
        $ads
      </div>
    </div>
    EOD;
}
