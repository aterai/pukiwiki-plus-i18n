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

function plugin_code_convert(): string
{
    global $vars, $defaultpage;
    $args = func_get_args();
    $pre = array_pop($args);
    $str = preg_replace('/&(#\d{2,4}|[a-zA-Z]+);/i', '&$1;', htmlspecialchars($pre));
    //$pre = sprintf('<pre class="prettyprint"><code class="notranslate language-java">%s</code></pre>', $str);
    $page = $vars['page'] ?? '';
    //$lnk = array_shift($args);
    $arg_num = count($args); // func_num_args();
    $buf = '';
    $flag = false;
    for ($i = 0; $i < $arg_num; $i++) { // 引数の数分ループ
        $tmp = $args[$i];
        if ($tmp == '')
            continue;
        if ($tmp == 'link' && $page != '' && $page != $defaultpage && strpos($page, 'Swing') >= 0) {
            $flag = true;
            $buf .= ' class="language-java"';
            continue;
        }
        if (strpos($tmp, 'lang-') >= 0) {
            $buf .= ' ' . $tmp;
        }
    }
    //     if ($buf != '') {
    //         $buf = ' ' + $buf.rtrim();
    //     }

    //$pre = sprintf('<pre class="prettyprint' . $buf . '" itemscope="itemscope" itemtype="https://schema.org/Code"><code itemprop="sampleType" content="code snippet">%s</code></pre>', $str);
    //$svn = "http://java-swing-tips.googlecode.com/svn/trunk/" . str_replace('Swing/', '', $page) . "/src/java/example/MainPanel.java";
    //if ($flag && is_url($git)) {

    $pre = sprintf('<pre><code ' . $buf . ' translate="no">%s</code></pre>', $str);
    if ($flag) {
        $git =
            'https://github.com/aterai/java-swing-tips/blob/main/examples/' .
            str_replace('Swing/', '', $page) .
            '/src/java/example/MainPanel.java';
        $kotlin =
            'https://github.com/aterai/kotlin-swing-tips/blob/main/examples/' .
            str_replace('Swing/', '', $page) .
            '/src/main/kotlin/example/App.kt';
        // return '<div style="position:relative">' . $pre . sprintf('<span style="position:absolute;right:1.5EM;top:-1.5EM;z-index:50">View in GitHub: <a href="%s">Java</a>, <a href="%s">Kotlin</a></span></div>', $git, $kotlin);
        return (
            '<div style="position:relative">' .
            $pre .
            '<span style="position:absolute;right:1.5EM;top:-1.5EM;z-index:50">View in GitHub: <a href="' .
            $git .
            '">Java</a>, <a href="' .
            $kotlin .
            '">Kotlin</a></span></div>'
        );
    } else {
        return $pre;
    }
}
