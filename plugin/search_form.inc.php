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
<form class="form-inline my-2 my-lg-0" action="$url?cmd=search" method="post" role="search">
  <input type="hidden" name="encode_hint" value="ぷ" />
  <input class="form-control mr-sm-2" type="search" name="word" value="" placeholder="サイト内検索" aria-label="Search">
  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
</form>
EOD;
}
?>
