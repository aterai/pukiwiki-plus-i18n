<?php
/////////////////////////////////////////////////
//-*- mode:java; Encoding:utf8n -*-
//
function plugin_search_form_convert() {
    global $vars;
//     $aryargs = func_get_args();
//     if (func_num_args() == 0 || func_num_args() > 2) return false;
//     $check = new searche_form_check_asin(htmlspecialchars($aryargs[0])); // for XSS
//     $align = 'right';
//     if (! $check->is_asin) return;
//<div id="search_form" class="bar">
// <br />
// <br />
// <form action="http://www.google.co.jp" id="cse-search-box">
//   <div>
//     <input type="hidden" name="cx" value="partner-pub-6939179021013694:5525296518" />
//     <input type="hidden" name="ie" value="UTF-8" />
//     <input type="text" name="q" size="24" />
//     <input type="submit" name="sa" value="Search" />
//   </div>
// </form>
// <script async defer type="text/javascript" src="http://www.google.co.jp/coop/cse/brand?form=cse-search-box&amp;lang="></script>
    $url = get_script_uri(); // . '?' .  rawurlencode($vars['page']); // . $name;
    return <<<EOD
<form action="$url?cmd=search" method="post" role="search" class="navbar-form navbar-right">
 <div class="form-group">
  <input type="hidden" name="encode_hint" value="ぷ" />
  <input type="text" name="word" value="" placeholder="サイト内検索" size="24" class="form-control search-query" />
  <button type="submit" class="btn btn-default">Search</button>
 </div>
</form>
EOD;
}
?>
