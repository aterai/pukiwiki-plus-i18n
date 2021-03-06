<?php
//-*- mode:java; Encoding:utf8n -*-
function plugin_sitemaps_action() {
    global $vars, $non_list;
    //$nl_flag = TRUE;     //.*wiki.ini.php の$non_listで指定したpageを書き出す？(TRUE:有効)

    $sitemaps_max = 1000; //recent.datに合わせて適当に修正してください
    $recent = CACHE_DIR . PKWK_MAXSHOW_CACHE;
    if(!file_exists($recent)) die('recent.dat is not found');

    $num = func_num_args();
    $args = func_get_args();

    $category = isset($vars['category']) ? $vars['category'] : '';
    $reverse = isset($vars['reverse']) ? TRUE : FALSE;

    $self = get_script_uri();
    $date = $items = '';

    $array = array();

    $array[] = '.*ChangeLog.*';
    $array[] = '.*Questionnaire.*';
    $array[] = '.*Comments.*';
    $array[] = '.*RecentChanges.*';

    $array[] = '^ST.*';
    $array[] = '^JavaFX.*';
    $array[] = '^PukiWiki.*';
    $array[] = '^English.*';
    $array[] = '^Relaxer.*';
    $array[] = '^Bicycle.*';

    $array[] = '^aterai$';
    $array[] = '^terai$';
    $array[] = '^upk$';
    $array[] = '^Taka$';
    $array[] = '^xyzzy$';
    $array[] = '^Nitpick$';
    $array[] = '^Popular$';
    $array[] = '^_Bug$';

    // $array[] = '^RSS$';
    // $array[] = '^.+%2F_.+$';
    // $array[] = '^Earphones$';
    // $array[] = '^GaChk$';
    // $array[] = '^HighlightTextForeground$';

    $array[] = '^SwingTips$';

    $array[] = '^Swing%2FAnchorSelection$';
    $array[] = '^Swing%2FAutoAdjustRowHeight$';
    $array[] = '^Swing%2FCellAtPoint$';
    $array[] = '^Swing%2FDoubleBuffering$';
    $array[] = '^Swing%2FIncremental$';
    $array[] = '^Swing%2FJarFile$';
    $array[] = '^Swing%2FNonSelectableList$';
    $array[] = '^Swing%2FTabWithCloseButtoun$';
    $array[] = '^Swing%2F_Hacks$';
    $array[] = '^Swing%2F_ScreenShots$';
    $array[] = '^Swing%2FSwing%2F';

    $array[] = '^Subversion%2FSubclipse$';
    $array[] = '^Subversion%2FeSvn$';

    $array[] = '^Tips%2FAPIDocEnJa$';

    $non_list_pattern = '/' . $non_list . '|' . implode('|', $array) . '/S';

    foreach(array_splice(file($recent), 0, $sitemaps_max) as $line) {
        list($time, $page) = explode("\t", rtrim($line));
        $r_page = rawurlencode($page);
        if(preg_match($non_list_pattern, $r_page)) {
            continue;
        }
        if ($category != '' && preg_match("/".$category."/i", $r_page) == $reverse) {
            continue;
        }
        if ($category != '' && preg_match("/" . $category . "%2F_/i", $r_page) == 1) {
            continue;
        }

        //GMTで日時を出力する場合
        //$time = $time + date('Z'); //recent.datのtimeがdefine('UTIME', time()-LOCALZONE)で保存されているため
        //$date = gmdate('Y-m-d\TH:i:s', $time) . '+00:00';
        //それぞれのロケールで日時を出力する場合
        //$date = substr_replace(date('Y-m-d\TH:i:sO', $time),':',-2,0);
        //それぞれのロケールで日時を出力する場合(PHP 5.1.3以上)
        //$date = date('Y-m-d\TH:i:sO', $time);
        $date = date('c', $time);

        $loc = $self . $page . ".html";
        if(preg_match("/FrontPage/i", $r_page)) {
            $loc = $self;
        }
        $items .= <<<EOD
<url>
  <loc>$loc</loc>
  <lastmod>$date</lastmod>
</url>

EOD;
    }

    $items .= <<<EOD
<url>
  <loc>{$self}archive</loc>
</url>
<url>
  <loc>{$self}categories</loc>
</url>
<url>
  <loc>{$self}pages</loc>
</url>
<url>
  <loc>{$self}tags</loc>
</url>
EOD;

    header("Content-type:text/xml;charset=utf-8"); 
    //header('Content-type: application/xml');
    print '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    print <<<EOD
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
$items
</urlset>
EOD;
    exit;
}
?>
