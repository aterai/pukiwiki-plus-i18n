<?php
//-*- mode:java; Encoding:utf8n -*-
// $Id: title.inc.php $
// $newtitle: Extend TITLE: if (preg_match('/^(TITLE):(.*)$/',$line,$matches))
function plugin_title_inline() {
    global $title, $page_title, $newtitle;

    if (func_num_args() > 0) {
        $aryargs = func_get_args();
        $h1 = $aryargs[0];
        if ($h1 === 'h1') {
            if ($newtitle != '') {
                return $newtitle . ' <small>' . str_replace('/', ' / ', $title) . '</small>';
            } else {
                return $title;
            }
        }
    }

    if ($newtitle != '') {
        if (strpos($title, 'Swing/') === false) {
            return $newtitle." - ".$page_title;
        } else {
            return $newtitle." - Java Swing Tips";
        }
    } else {
        return $title." - ".$page_title;
    }
}
?>
