<?php
//-*- mode:java; Encoding:utf8n -*-
/**
 * RecentChanges の RSS を本文に diff を入れて生成する
 *
 * CACHE_DIR/recent.dat がないと何も出力しない。
 * 出力する件数は pukiwiki.ini.php の $rss_max で設定する。
 *
 * @since   2005-01-01
 * @since   2005-02-17 RSS 2.0 対応
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @version $Revision: 1.7 $
 */

/** DESCRIPTION に出力する最大バイト数 */
define( 'PLUGIN_RSSDIFF_MAX_DESCRIPTION_SIZE', 512 );
/** RSS の出力文字コード */
define( 'PLUGIN_RSSDIFF_OUTPUT_ENCODING', 'UTF-8' );
/** time zone 文字列（設定不要） */
define( 'PLUGIN_RSSDIFF_TZ', preg_replace( '/([0-9]{2})([0-9]{2})/', '\1:\2', date( "O" ) ) );
/** 出力する RSS のバージョン 1 or 2 */
define( 'RSS_VER', 2 );

//DESCRIPTION追加
define( 'PLUGIN_RSSDIFF_DESCRIPTION', 'ソフトウェア開発関連(Java,Swing,Ant,Subversionなど)のメモ' );

/**
 * 呼び出されるプラグイン本体
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @version $Revision: 1.7 $
 */
function plugin_rssdiff_action() {
  global $rss_max;
  global $item;

  $item = array();  // 各エントリの情報が入る配列

  // caache から最近のエントリを $max_rss の数だけチェック
  $recent = file( CACHE_DIR.'recent.dat' );
  $recent = array_slice( $recent, 0, $rss_max );
  // 個々のエントリでインスタンスを生成して配列に
  foreach ( $recent as $line ) {
    list( $unixtime, $pagename ) = explode( "\t", chop( $line ) );
    array_push( $item, new plugin_rssdiff_item( $unixtime, $pagename ) );
  }

  // 出力
  plugin_rssdiff_output_rss();
  exit;
} // End of plugin_rssdiff_action()

/**
 * スクリプト自身への uri を取得する
 *
 * https は port 443 に決め打ちされてます
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @param   str $script ユーザー設定のスクリプト名
 * @return  str 自身への uri
 * @version 1.1
 */
function plugin_rssdiff_getself( $script ) {
  if ( preg_match( '#^https?://#', $script ) ) {
    $self = $script;
  } else {
    $host   = getenv( 'HTTP_HOST' );
    $port   = getenv( 'SERVER_PORT' );
    $scheme = 'http'.(($port == 443) ? 's' : '').'://';
    if ( preg_match( '#^/#', $script ) ) {
      $self = $scheme.$host.(($port != 80 && $port != 443) ? $port : '').$script;
    } else {
      $self = $scheme.$host.(($port != 80 && $port != 443) ? $port : '').getenv( 'SCRIPT_URL' );
    }
  }

  return $self;
} // End of plugin_rssdiff_getself()

/**
 * RSS のヘッダを出力する
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @since   2005-02-17 RSS 2 対応
 * @version 2.0
 */
function plugin_rssdiff_put_header() {
  $lang = LANG;

  print '<?xml version="1.0" encoding="'.PLUGIN_RSSDIFF_OUTPUT_ENCODING.'"?>'."\n";

  switch ( RSS_VER ) {
  case 1:
    print <<<EOD
<rdf:RDF xmlns="http://purl.org/rss/1.0/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xml:lang="$lang">
EOD;
    break;
  case 2:
  default:
    print <<<EOD
<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xml:lang="$lang">
<channel>
EOD;
  }

}

/**
 * RSS のフッタを出力する
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @since   2005-02-17 RSS 2 対応
 * @version 2.0
 */
function plugin_rssdiff_put_footer() {
  switch ( RSS_VER ) {
  case 1:
    print <<<EOD
</rdf:RDF>
EOD;
    break;
  case 2:
  default:
    print <<<EOD
</channel>
</rss>
EOD;
  }
}


/**
 * RSS の出力を分岐する
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @since   2005-02-17 wrapper 化
 * @version 2.0
 */
function plugin_rssdiff_output_rss() {
  switch( RSS_VER ) {
  case 1:
    plugin_rssdiff_output_rss1();
    break;
  case 2:
  default:
    plugin_rssdiff_output_rss2();
  }
}

/**
 * RSS 1.0 を実際に出力する
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @since   2005-02-17 名前変えただけ
 * @version 1.0
 */
function plugin_rssdiff_output_rss1() {
  global $script, $item, $page_title;

  // $script を scheme からセット
  $self = plugin_rssdiff_getself( $script );
  // サイトタイトルの文字コード変換
  $title = mb_convert_encoding( $page_title, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING );

  header( 'Content-Type: application/xml' );
  // ヘッダ
  plugin_rssdiff_put_header();
  // チャンネル
  print "\n".'<channel rdf:about="'.$self.'">'."\n";
  print '<title>'.$title.'</title>'."\n";
  print '<link>'.$self.'</link>'."\n";
  print '<description>'.$title.' Recent Diffs</description>'."\n";
  // リスト
  print "<items><rdf:Seq>\n";
  foreach ( $item as $obj ) {
    print '<rdf:li rdf:resource="'.$obj->get_uri( $self ).'" />'."\n";
  }
  print "</rdf:Seq></items>\n";
  print "</channel>\n";

  // 個々のアイテムの詳細
  foreach ( $item as $obj ) {
    print "\n".'<item rdf:about="'.$obj->get_uri( $self ).'">'."\n";
    print "<link>".$obj->get_uri( $self )."</link>\n";
    print "<dc:date>".$obj->get_date()."</dc:date>\n";
    print "<title>".$obj->get_title()."</title>\n";
    print "<description>".$obj->get_description()."</description>\n";
    print "<content:encoded><![CDATA[".$obj->get_content()."]]></content:encoded>\n";
    print "</item>\n";
  }
  plugin_rssdiff_put_footer();
} // End of plugin_rssdiff_output_rss()

/**
 * RSS 2.0 を実際に出力する
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-02-17
 * @version 1.0
 */
function plugin_rssdiff_output_rss2() {
  global $script, $item, $page_title;
  //  global $modifier;

  // $script を scheme からセット
  $self = plugin_rssdiff_getself( $script );
  // 文字コード変換
  $title = mb_convert_encoding( $page_title, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING );
  //  $modifier = mb_convert_encoding( $modifier, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING );

  $description = htmlspecialchars( mb_convert_encoding( PLUGIN_RSSDIFF_DESCRIPTION, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING ));

  header( 'Content-Type: application/xml' );
  plugin_rssdiff_put_header();
  // チャンネル
  print '<title>'.$title.'</title>'."\n";
  print '<link>'.$self.'</link>'."\n";
//  print '<description>'.$title.' Recent Diffs</description>'."\n";
  print '<description>'.$description.'</description>'."\n";
  print '<language>'.LANG.'</language>'."\n";
  print "<generator>rssdiff plugin for PukiWiki</generator>\n";
  //  print "<webMaster>$modifier</webMaster>\n";
  // リスト
  foreach ( $item as $obj ) {
    print "<item>\n";
    print "<link>".$obj->get_uri( $self )."</link>\n";
    print "<title>".$obj->get_title()."</title>\n";
    print "<pubDate>".$obj->get_date()."</pubDate>\n";
    print "<description>".$obj->get_description()."</description>\n";
    print "<content:encoded><![CDATA[".$obj->get_content()."]]></content:encoded>\n";
    print "</item>\n";
  }
  plugin_rssdiff_put_footer();
}

/**
 * 一つ一つのアイテムを保持するクラス
 *
 * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
 * @since   2005-01-01
 * @version $Revision: 1.7 $
 */
class plugin_rssdiff_item {
  /**
   * タイトル
   *
   * @var string
   */
  var $title;
  /**
   * url encode したページ名
   *
   * @var string
   */
  var $pagename;
  /**
   * ファイル名
   *
   * @var string
   */
  var $filename;
  /**
   * 修正時刻
   *
   * @var string
   */
  var $date;
  /**
   * 内容
   *
   * @var string
   */
  var $content;
  /**
   * 容量を節約した内容
   *
   * @var string
   */
  var $description;
  /**
   * 削除された行数
   *
   * @var int
   */
  var $deleted = 0;

  /**
   * コンストラクタ
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @param   int $unixtime ページの更新時刻の unixtime
   * @param   str $pagename ページの名前
   * @version 1.0
   */
  function plugin_rssdiff_item( $unixtime, $pagename ) {
    $this->set_date( $unixtime );
    $this->set_title( $pagename );
    $this->set_pagename( $pagename );
    $this->set_filename( $pagename );
    $this->make_content();
    $this->make_description();
  }

  /**
   * 与えられた unixtime の数値を RDF 用の書式に変換して納める
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @since   2005-01-02 get_date() を使うように
   * @since   2005-02-17 書式を get_date() に追い出してシンプルに
   * @param   int $unixtime
   * @version 2
   */
  function set_date( $unixtime ) {
    if ( checkdate( date("n", $unixtime), date("j", $unixtime), date( "Y", $unixtime) ) ) {
      $this->date = $unixtime;
      return true;
    } else {
      return false;
    }
  }

  /**
   * タイトルをセットする
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @param   str $pagename ページ名
   * @version 1.0
   */
  function set_title( $pagename ) {
    $this->title = mb_convert_encoding( $pagename, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING );
  }

  /**
   * 与えられたページ名を uri に変換して納める
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @param   str $pagename ページ名
   * @version 1.0
   */
  function set_pagename( $pagename ) {
    $this->pagename = rawurlencode( $pagename );
  }

  /**
   * 与えられたページ名を PukiWiki 内部で使うファイル名に変換して納める
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @param   str $pagename ページ名
   * @version 1.0
   */
  function set_filename( $pagename ) {
    $this->filename = DIFF_DIR.encode( $pagename ).'.txt';
  }

  /**
   * diff の出力を生成する（この段階で htmlspecialchars 済み）
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @since   2005-01-24 pre を div に変更
   * @version 1.1
   */
  function make_content() {
    $all = file( $this->filename );
    $diff = preg_grep( '/^(\+|\-)/', array_map( 'chop', $all ) );
    foreach ( $diff as $num => $line ) {
      // diff の行番号が飛ぶまでをひとかたまりとする
      if ( $last > 0 && $num > $last + 1 ) {
        $content .= "</div>\n<div class=\"block\">\n".($num - $this->deleted + 1)."\n";
      }
      if ( !$content ) {
        $content = "<div class=\"block\">\n".($num + 1)."\n";
      }
      
      $content .= "<div class=\"line\">".$this->make_line( $line )."</div>\n";
      $last = $num;
    }
    if ( $content ) {
      $content .= "</div>\n";
    }
    $this->content = $content;
  } // End of make_content()

  /**
   * content を設定した容量でカットする（マークアップはしない）
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @version 1.1
   */
  function make_description() {
    if ( !$this->content ) {
      return false;
    }
    $all = file( $this->filename );
    $diff = preg_grep( '/^(\+|\-)/', array_map( 'chop', $all ) );
    foreach ( $diff as $num => $line ) {
      // diff の行番号が飛ぶまでをひとかたまりとする
      if ( $last > 0 && $num > $last + 1 ) {
        $description .= "\n";
      }
      $description .= mb_convert_encoding( $line, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING )."\n";
      $last = $num;
    }
    if ( strlen( $description ) > PLUGIN_RSSDIFF_MAX_DESCRIPTION_SIZE ) {
      $description =  mb_strimwidth( $description, 0, PLUGIN_RSSDIFF_MAX_DESCRIPTION_SIZE, '...', PLUGIN_RSSDIFF_OUTPUT_ENCODING );
    }
    $this->description = htmlspecialchars( $description );
  } // End of make_description()

  /**
   * 文字コード変換やタグ付けを施した diff の行を生成
   *
   * 削除された行のカウントアップも行う
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-24
   * @version 1.0
   */
  function make_line( $line ) {
    $tag  = array( '+' => 'add', '-' => 'del' );

    $diff = substr( $line, 0, 1 );
    if ( $diff == '-' ) {
      $this->deleted++;
    }
    $str  = substr( $line, 1 );

    return "<$tag[$diff]><code>".htmlspecialchars( mb_convert_encoding( $str, PLUGIN_RSSDIFF_OUTPUT_ENCODING, SOURCE_ENCODING ) )."</code></$tag[$diff]>";
  } // End of make_line()

  /**
   * リソースの uri を取得
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @param   str $self 絶対URI
   * @version 1.0
   */
  function get_uri( $self ) {
    if ( $this->pagename ) {
      //return $self.'?'.$this->pagename;
      return $self . $this->title . '.html';
    } else {
      return false;
    }
  }

  /**
   * タイトルを取得
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @version 1.0
   */
  function get_title() {
    if ( $this->title ) {
      return $this->title;
    } else {
      return false;
    }
  }

  /**
   * 変更時刻を取得
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @since   2005-02-17 RSS のバージョンによる書式の違いに対応
   * @version 2.0
   */
  function get_date() {
    if ( $this->date ) {
      $date = '';
      switch ( RSS_VER ) {
      case 1:
        $date = gmdate( "Y-m-d\TH:i:s", $this->date ) . PLUGIN_RSSDIFF_TZ; 
        break;
      case 2:
      default:
        //$date = date( "r", $this->date + ZONETIME );
        $date = date( "r", $this->date );
      }
      return $date;
    } else {
      return false;
    }
  }

  /**
   * content を取得
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @version 1.0
   */
  function get_content() {
    if ( $this->content ) {
      return $this->content;
    } else {
      return false;
    }
  }

  /**
   * description を取得
   *
   * @author  T.Watanabe <pctraining_at_s21_dot_xrea_dot_com>
   * @since   2005-01-01
   * @version 1.0
   */
  function get_description() {
    if ( $this->description ) {
      return $this->description;
    } else {
      return false;
    }
  }
} // End of class plugin_rssdiff_item
?>
