<?php
/////////////////////////////////////////////////
// $Id: canonical.inc.php,v 0.3 2009/02/16 aterai $
//
function plugin_canonical_convert() {
    global $script, $vars, $title, $defaultpage, $interwiki, $whatsnew, $whatsdeleted, $menubar, $nofollow;

    $args = func_get_args();
    $is_read = array_shift($args);
    if ($nofollow || ! $is_read || $title==$whatsnew || $title==$whatsdeleted || $title==$interwiki || $title==$menubar) {
        return '<meta name="robots" content="NOINDEX,FOLLOW" />';
    } else {
        if ($title == $defaultpage) {
            return '<link rel="canonical" href="'.$script.' " />';
        } else {
            return '<link rel="canonical" href="'.$script.$title.'.html" />';
        }
    }
}
?>
