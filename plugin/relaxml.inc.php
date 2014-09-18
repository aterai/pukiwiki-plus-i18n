<?php
// $Id$
function plugin_relaxml_inline() {
    $number = -1;
    $title  = '';
    if(func_num_args() == 3) {
        $aryargs = func_get_args();
        $title  = htmlspecialchars(trim($aryargs[0]));
        $number = htmlspecialchars(trim($aryargs[1]));
    }else{
        return '';
        //         $aryargs = func_get_args();
        //         $page = htmlspecialchars(trim($aryargs[0]));
        //         $name = '/' . htmlspecialchars(trim($aryargs[1]));
        //         if($page == '')  $page = isset($vars['page']) ? $vars['page'] : '';
        //         if($name == '/') $name = '/screenshot.png';
    }
    $rujurl = 'http://www2.xml.gr.jp/log.html?MLID=relax-users-j&amp;N=' . $number;
    return <<<EOD
<a class="ext" href="$rujurl">[relax-users-j: $number] $title</a>
EOD;
}
?>
