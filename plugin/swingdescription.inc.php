<?php
// $Id$
function plugin_swingdescription_convert() {
    global $vars;
    $flag = FALSE;

    $aryargs = func_get_args();
    if (func_num_args() < 4) {
        return '';
    }

    $page        = htmlspecialchars(trim($aryargs[0]));
    $title       = htmlspecialchars(trim($aryargs[1]));
    $description = htmlspecialchars(trim($aryargs[2]));
    $paurl       = get_script_uri() . $page . '.html';
    $ssurl       = preg_replace('/https:\/\/drive\.google\.com\/uc/', 'https://drive.google.com/thumbnail', htmlspecialchars(trim($aryargs[3])));

    $pattern     = '/([\w\s\.\(\)]+)/i';
    $replacement = '<code>$1</code>';
    $description = preg_replace($pattern, $replacement, htmlspecialchars(trim($aryargs[2])));

    return <<<EOD
<div class="card mb-3" style="max-width:728px;">
  <div class="row no-gutters">
    <div class="col-md-4">
      <a class="card-img" href="$paurl" title="$page"><img src="$ssurl" itemprop="image" alt="thumbnail" /></a>
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title"><a href="$paurl" title="$page">$title</a></h5>
        <p class="card-text">$description</p>
      </div>
    </div>
  </div>
</div>
EOD;
}
?>
