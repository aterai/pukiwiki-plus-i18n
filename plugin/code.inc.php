<?php
/**
 * コードハイライト機能をPukiWikiに追加する
 * @author sky
 * Time-stamp: <05/07/30 20:00:55 sasaki>
 * 
 * GPL
 *
 * Ver. 0.5.0.1
 */

function plugin_code_convert() {
    global $vars, $defaultpage;
    $args = func_get_args();
    $pre = array_pop($args);
    $str = preg_replace('/&(#\d{2,4}|[a-zA-Z]+);/i', '&$1;', htmlspecialchars($pre));
    //$pre = sprintf('<pre class="prettyprint"><code class="notranslate language-java">%s</code></pre>', $str);

    $page = isset($vars['page']) ? $vars['page'] : '';

    //$lnk = array_shift($args);
    $arg_num = func_num_args();

    $buf = '';
    $flag = false;
    for ($i = 0; $i < $arg_num; $i++) { // 引数の数分ループ
        $tmp = $args[$i];
        if ($tmp == '') continue;
        if ($tmp == 'link' && $page != '' && $page != $defaultpage && strpos($page, 'Swing') >= 0) {
            $flag = true;
            continue;
        }
        if (strpos($tmp, "lang-") >= 0) {
            $buf .= $tmp . ' ';
        }
    }
    if ($buf != '') {
        $buf = ' ' + $buf.rtrim();
    }

    $pre = sprintf('<pre class="prettyprint' . $buf . '" itemscope="itemscope" itemtype="http://schema.org/Code"><code itemprop="sampleType" content="code snippet">%s</code></pre>', $str);
    //$svn = "http://java-swing-tips.googlecode.com/svn/trunk/" . str_replace('Swing/', '', $page) . "/src/java/example/MainPanel.java";
    $git = '//github.com/aterai/java-swing-tips/blob/master/' . str_replace('Swing/', '', $page) . '/src/java/example/MainPanel.java';
    //if ($flag && is_url($git)) {
    if ($flag) {
        return '<div style="position:relative">' . $pre . sprintf('<a href="%s" style="position:absolute;right:1.5EM;top:-1.5EM;z-index:50">view all</a></div>', $git);
    }else{
        return $pre;
    }
}
?>
