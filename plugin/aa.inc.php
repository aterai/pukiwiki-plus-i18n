<?php
/*
アスキーアート出力 Ver1.01 by sue445
参考URL: http://pukiwiki.sourceforge.jp/?%E8%B3%AA%E5%95%8F%E7%AE%B13%2F162
*/
// 設定
// アスキーアート出力書式
//   %s の部分にアスキーアートが入ります
//define('PLUGIN_AA_FORMAT', '<pre class="aa">%s</pre>');
// 1.4.6ではcssのfont-familyが反映されないのでこっちを推奨
define('PLUGIN_AA_FORMAT', '<pre class="aa"><font size=3 face="ＭＳ Ｐゴシック">%s</font></pre>');
function plugin_aa_convert() {
    $arg = array_pop(func_get_args());
    // 文字実体参照が効かなかったので修正(thx tokageさん)
    return sprintf(PLUGIN_AA_FORMAT, preg_replace('/&(#\d{2,4}|[a-zA-Z]+);/i', '&$1;', htmlspecialchars($arg)));
}
?>
