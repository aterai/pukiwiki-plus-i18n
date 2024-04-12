<?php
// Prohibit direct access
if (! defined('DATA_DIR')) die('DATA_DIR is not set');
//$css_charset = 'UTF-8';
//$link  = & $_LINK;
//$image = & $_IMAGE['skin'];
//$rw    = ! PKWK_READONLY;
// Output HTTP headers
//$is_404page  = ! is_page($_page);
//$is_page  = is_page($_page) && ! arg_check('backup') && ! arg_check('edit');
//if($is_page && ! file_exists(get_filename($_page))) {
$is_page = ! arg_check('list') && ! arg_check('recent');
if($is_page && ! file_exists(get_filename($_page))) {
header("HTTP/1.0 404 Not Found");
}else{
pkwk_common_headers();
header('Cache-Control: private, max-age=0'); //: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);
header('ETag: ' . md5(MUTIME));
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="format-detection" content="telephone=no" />
<?php echo $head_tag ?>
<link rel="icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAFVBMVEX///+ZmZmCuGKZmZn///+q+Xzd+stVi+GoAAAAAnRSTlMAAHaTzTgAAABKSURBVHheZY5BCsBACAOzifr/JxcXi9WOpwxRhBaQDVJ49JgwTYpTS28jzcXc3XRQyII3t/hnmr45mIV1AbOxjZPLcJj6V2iUAA+UgAJpJuyTrAAAAABJRU5ErkJggg==" sizes="16x16" type="image/png" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/styles/github-dark-dimmed.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://unpkg.com/highlightjs-copy/dist/highlightjs-copy.min.css" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6939179021013694" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.9.0/build/highlight.min.js"></script>
<script src="https://unpkg.com/highlightjs-copy/dist/highlightjs-copy.min.js"></script>
<script>
hljs.addPlugin(new CopyButtonPlugin());
hljs.highlightAll();
</script>
<style>
html {
  max-width: 100ch;
  padding: 3em 1em;
  margin: auto;
  line-height: 1.75;
  font-size: 1.25em;
}

h1,h2,h3,h4,h5,h6 {
  margin: 1em 0 1em;
  color: #246230;
}

p,ul,ol {
  margin-bottom: 2em;
  color: #1d1d1d;
  font-family: sans-serif;
}

a:visited{
  color: #a63d21
}

pre {
  line-height: 1.5em
}

pre code.hljs {
  padding: .5em
}

.footer{background-color:#eee}
.commentform>input{margin:0 0 1em 0}
.summary{color:#666;background-color:inherit;border-left:solid 4px #999;padding-left:5px;clear:both;margin:45px 0 0}
.note{border-bottom:solid 1px #999}
.blog-sidebar{padding-left:2em}
.sidebar ul{padding:0 0 .5em .5em}
.sidebar li{list-style-type:none;overflow:auto;white-space:pre-wrap;word-wrap:break-word}
.popular_list{padding:0;margin:0;list-style-type:none}
.aa{font-size:12pt;line-height:17px;text-indent:0;letter-spacing:0;speak:none}
.download_box{margin:2.5em}
.ad_box,.ad_bar{margin:1.5em auto}
.ad_box{float:right}
.amazon{text-align:center;padding:.5em 1.5em}
.img_margin{margin-left:32px;margin-right:2.5em}
.page_action{margin:7px 0}
.note_super{color:#D33;background-color:inherit}
.note_super{vertical-align:30%}
.edit_form textarea{width:95%;min-width:95%;font-family:monospace}
.edit_form,.clear{clear:both}
.contents{border-top:solid 1px #999;border-left:solid 1px #999;border-right:0;border-bottom:0;margin:1em .5em}
.contents:before{content:"Contents";color:#fff;background-color:#999;font-weight:700;line-height:1.0;display:block;width:6em;text-align:center;padding:.2em}
.new1{color:red;background-color:inherit;font-size:x-small}
.new5{color:green;background-color:inherit;font-size:xx-small}
.size2,.comment_date{font-size:x-small}
.diff_added{color:blue;background-color:inherit}
.diff_removed{color:red;background-color:inherit}
.full_hr,.note_hr{border-color:#333}
.short_line{text-align:center;width:80%;margin:0 auto}
thead td.style_td,tfoot td.style_td{color:inherit;background-color:#d0e0d8}
thead th.style_th,tfoot th.style_th{color:inherit;background-color:#e0f0e8}
.style_table{text-align:left;color:inherit;background-color:inherit;border:1px solid;border-collapse:collapse;margin:auto;padding:0}
.style_th{text-align:center;border-collapse:collapse;color:inherit;background-color:inherit;border:1px solid;margin:1px;padding:5px}
.style_td{border:1px solid;color:inherit;background-color:inherit;border-collapse:collapse;margin:1px;padding:5px;vertical-align:top}
.tag_box{font-family:"Glyphicons Halflings","Helvetica Neue",Helvetica,Arial,sans-serif;list-style:none;margin:0;overflow:hidden}.tag_box li{line-height:2em}.tag_box li i{opacity:.9}.tag_box.inline li{float:left}.tag_box a{padding:.2em;margin:.2em;background:#eee;color:#555;border-radius:3px;text-decoration:none;border:1px dashed #cccccc}.tag_box a span{vertical-align:baseline;font-size:1em}.tag_box a:hover{background-color:#e5e5e5}.tag_box a.active{background:#57A957;border:1px solid #4c964d;color:#FFF}
.views-submit-button button {visibility: hidden}.views-exposed-widget:focus-within + .views-submit-button button{visibility: visible}
@media print{h1,h2,h3,h4,h5,h6{color:#000;background-color:#FFF}td,th,tr{border:1px solid;color:#000;background-color:#FFF;border-collapse:collapse}.content{width:100%}a:link,a:visited{text-decoration:underline}.sidebar,.navigator,.header,.footer,.menubar,.attach,.toolbar,.related,.logo,.ad_box,.jumpmenu,.paraedit,.anchor_super{display:none}}
</style>
</head>
<body itemscope="itemscope" itemtype="https://schema.org/WebPage">
  <header>
    <nav class="navbar navbar-expand-md navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="https://ateraimemo.com/"><?php echo $page_title ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item" itemprop="name"><a class="nav-link" itemprop="url" href="https://ateraimemo.com/Swing.html">Swing</a></li>
            <li class="nav-item" itemprop="name"><a class="nav-link" itemprop="url" href="https://ateraimemo.com/Tips.html">Java</a></li>
            <li class="nav-item" itemprop="name"><a class="nav-link" itemprop="url" href="https://ateraimemo.com/Javadoc.html">Javadoc</a></li>
            <li class="nav-item" itemprop="name"><a class="nav-link" itemprop="url" href="https://ateraimemo.com/Kotlin.html">Kotlin</a></li>
            <li class="nav-item" itemprop="name"><a class="nav-link" itemprop="url" href="https://ateraimemo.com/Ant.html">Ant</a></li>
            <li class="nav-item" itemprop="name"><a class="nav-link" itemprop="url" href="https://ateraimemo.com/Gradle.html">Gradle</a></li>
            <li class="nav-item dropdown" itemprop="name">
              <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Others</a>
              <ul class="dropdown-menu" aria-labelledby="dropdown01">
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/Jekyll.html">Jekyll</a>
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/Jenkins.html">Jenkins</a>
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/JavaScript.html">JavaScript</a>
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/Subversion.html">Subversion</a>
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/XSLT20.html">XSLT 2.0</a>
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/SurfacePro3.html">Surface Pro 3</a>
                <a class="dropdown-item" itemprop="url" href="https://ateraimemo.com/Solitaire.html">Solitaire</a>
              </ul>
            </li>
          </ul>
          <?php include_once(PLUGIN_DIR.'search_form.inc.php'); echo plugin_search_form_convert(); ?>
        </div>
      </div>
    </nav>
  </header>

  <main class="container" role="main">
    <div class="row">
      <div class="col-md-9">
        <article class="blog-post">
          <header>
            <?php include_once(PLUGIN_DIR.'header.inc.php'); echo plugin_header_convert(); ?>
          </header>

          <?php echo $body ?>

          <?php if ($notes != '') { ?>
            <aside class="summary">
              <div class="note"><?php echo $notes ?></div>
            </aside>
          <?php } ?>
        </article>
      </div>
      <div class="col-md-3 col-xs-12 blog-sidebar sidebar" itemscope="itemscope" itemtype="https://schema.org/WPSideBar"> 
        <nav>
          <?php if (exist_plugin_convert('menu')) { echo do_plugin_convert('menu'); } ?>
        </nav>
      </div>
    </div>
  </main>

  <footer class="footer text-center" itemscope="itemscope" itemtype="https://schema.org/WPFooter">
    <p>©2024 <span itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person"><a rel="author" itemprop="url" href="https://ateraimemo.com/:Users/aterai.html"><span itemprop="familyName">TERAI</span> <span itemprop="givenName">Atsuhiro</span></a></span> with help from <a href="https://github.com/miko2u/pukiwiki-plus-i18n" target="_blank" rel="noopener">PukiWiki Plus!</a> and <a href="https://github.com/plusjade/jekyll-bootstrap/" target="_blank" rel="noopener">Jekyll Bootstrap</a></p>
    <p>
      <a href="#">Back to top</a>
    </p>
  </footer>
  <script id="dsq-count-scr" src="https://javaswingtips.disqus.com/count.js" async></script>
</body>
</html>
