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
    $lnk = array_shift($args);
    $str = preg_replace('/&(#\d{2,4}|[a-zA-Z]+);/i', '&$1;', htmlspecialchars($pre));
    //$pre = sprintf('<pre class="prettyprint"><code class="notranslate language-java">%s</code></pre>', $str);
    $pre = sprintf('<pre class="prettyprint" itemscope="itemscope" itemtype="http://schema.org/Code"><code itemprop="sampleType" content="code snippet">%s</code></pre>', $str);

    $page = isset($vars['page']) ? $vars['page'] : '';
    $flag = ($lnk != '' && $page != '' && $page != $defaultpage && strpos($page, 'Swing') >= 0);
    //$svn = "http://java-swing-tips.googlecode.com/svn/trunk/" . str_replace('Swing/', '', $page) . "/src/java/example/MainPanel.java";
    $git = 'https://github.com/aterai/java-swing-tips/blob/master/' . str_replace('Swing/', '', $page) . '/src/java/example/MainPanel.java';
    if ($flag && is_url($git)) {
        return '<div style="position:relative">' . $pre . sprintf('<a href="%s" style="position:absolute;right:20px;top:5px;z-index:50">view all</a></div>', $git);
    }else{
        return $pre;
    }
}
?>
