<?php
//-*- mode:java; Encoding:utf8n -*-
// $Id: title.inc.php $
// $newtitle: Extend TITLE: if (preg_match('/^(TITLE):(.*)$/',$line,$matches))
function plugin_title_inline() {
    global $title, $page_title, $newtitle;

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
