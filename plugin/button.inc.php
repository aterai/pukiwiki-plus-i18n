<?php

/////////////////////////////////////////////////
// PukiWiki - Yet another WikiWikiWeb clone.
//
// $Id: button.inc.php,v 1.0 2003/05/10 14:42:29 reimy Exp $
//

function plugin_button_inline()
{
    if (func_num_args() != 1) {
        return false;
    }

    list($body) = func_get_args();

    if ($body == '') {
        return false;
    }

    return "<button type=\"button\" style=\"text-indent:0px;line-height:1em;vertical-align:middle\"> $body </button>";
}
