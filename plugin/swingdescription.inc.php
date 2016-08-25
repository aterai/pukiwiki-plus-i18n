<?php
// $Id$
function plugin_swingdescription_convert() {
    global $vars;
    $flag = FALSE;

    $aryargs     = func_get_args();
    if (func_num_args() < 4) {
        return '';
    }

    $page        = htmlspecialchars(trim($aryargs[0]));
    $title       = htmlspecialchars(trim($aryargs[1]));
    $description = htmlspecialchars(trim($aryargs[2]));
    $paurl       = get_script_uri() . $page . '.html';
    $ssurl       = htmlspecialchars(trim($aryargs[3]));

    $pattern     = '/([\w\s\.\(\)]+)/i';
    $replacement = '<code>$1</code>';
    $description = preg_replace($pattern, $replacement, htmlspecialchars(trim($aryargs[2])));

    return <<<EOD
<div class="row">
  <div class="col-md-2 col-xs-12">
    <a href="$paurl" class="thumbnail" title="$page">
      <img src="$ssurl" itemprop="image" alt="thumbnail" />
    </a>
  </div>
  <div class="col-md-10 col-xs-12 caption">
    <h4 class="caption"><a href="$paurl" title="$page">$title</a></h4>
    <p>$description</p>
  </div>
</div>
EOD;

// <div class="row">
// <div class="col-md-2 col-xs-12">
// <a href="$paurl" class="thumbnail" title="$page"><img src="$ssurl" itemprop="image" alt="thumbnail" /></a>
// </div>
// <div class="col-md-10 col-xs-12">
// <div class="caption">
// <a href="$paurl" title="$page">$title</a>
// <hr />
// <p>$description</p>
// </div>
// </div>
// </div>
// EOD;
//     return <<<EOD
// <div class="col-md-6 col-xs-12">
// <div class="thumbnail">
// <a href="$paurl" title="$page"><img src="$ssurl" width="144" height="120" alt="thumbnail" /></a>
// <div class="caption">
// <h3><a href="$paurl" title="$page">$title</a></h3>
// <p>$description</p>
// </div>
// </div>
// </div>
// EOD;
}
?>
