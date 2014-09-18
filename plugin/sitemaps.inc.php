<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_sitemaps_action() {
    //global $whatsnew, $whatsdeleted, $interwiki, $menubar;
    global $non_list, $whatsnew;
    $nl_flag = TRUE;     //.*wiki.ini.php の$non_listで指定したpageを書き出す？(TRUE:有効)
    $sitemaps_max = 1000; //recent.datに合わせて適当に修正してください
    $recent = CACHE_DIR . PKWK_MAXSHOW_CACHE;
    if(!file_exists($recent)) die('recent.dat is not found');
    $self = get_script_uri();
    $date = $items = '';
    $non_list_pattern = '/' . $non_list . '|.*ChangeLog.*|.*SandBox$|^SwingTips$|^ST.*|^terai$|^xyzzy$|^RSS$|^Nitpick$|.*Questionnaire.*|^Comments.*|^JavaFX.*|^Popular$|^PukiWiki.*/S';

    foreach(array_splice(file($recent), 0, $sitemaps_max) as $line) {
        list($time, $page) = explode("\t", rtrim($line));
        $r_page = rawurlencode($page);
        if($r_page==$whatsnew || ($nl_flag && preg_match($non_list_pattern, $r_page)) ) continue;
        //GMTで日時を出力する場合
        //$time = $time + date('Z'); //recent.datのtimeがdefine('UTIME', time()-LOCALZONE)で保存されているため
        //$date = gmdate('Y-m-d\TH:i:s', $time) . '+00:00';
        //それぞれのロケールで日時を出力する場合
        $date = substr_replace(date('Y-m-d\TH:i:sO', $time),':',-2,0);
        //それぞれのロケールで日時を出力する場合(PHP 5.1.3以上)
        //$date = date('Y-m-d\TH:i:sO', $time);
        $items .= <<<EOD
<url>
  <loc>$self$page.html</loc>
  <lastmod>$date</lastmod>
</url>

EOD;
    }
    header("Content-type:text/xml;charset=utf-8"); 
    //header('Content-type: application/xml');
    print '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    //$r_whatsnew = rawurlencode($whatsnew);
    print <<<EOD
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
$items
</urlset>
EOD;
    exit;
}
?>
