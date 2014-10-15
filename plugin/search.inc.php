<?php
//-*- mode:java; Encoding:utf8n -*-
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: search.inc.php,v 1.8 2005/04/02 06:33:39 henoheno Exp $
//
// Search plugin

// Allow search via GET method 'index.php?plugin=search&word=keyword'
// NOTE: Also allows DoS to your site more easily by SPAMbot or worm or ...
define('PLUGIN_SEARCH_DISABLE_GET_ACCESS', 1); // 1, 0

define('PLUGIN_SEARCH_MAX_LENGTH', 80);

define('PLUGIN_SEARCH_DETAIL',     1);
define('PLUGIN_SEARCH_RESULT_LEN', 10);

global $_msg_foundnavigator;
global $_msg_result_title;
// -----------
$_msg_foundnavigator = '<span class="small">(<strong>$1</strong> - <strong>$2</strong> 件目)</span>';
$_msg_result_title   = '検索結果ページ: ';
// -----------

function plugin_search_init()
{
	global $session;

	// for SESSION Variables
	if (ini_get('session.auto_start') != 1) {
		session_name('pukiwiki');
		session_start();
	}
	$_SESSION = input_filter($_SESSION);
}

// Show a search box on a page
function plugin_search_convert()
{
	static $done;

	if (func_get_args()) {
		return '#search(): No argument<br />' . "\n";
	} else if (isset($done)) {
		return '#search(): You already view a search box<br />' . "\n";
	} else {
		$done = TRUE;
		return plugin_search_search_form();
	}
}

function plugin_search_action()
{
	global $post, $vars, $_title_result, $_title_search, $_msg_searching;

	$id = isset($vars['id']) ? htmlspecialchars($vars['id']) : '';
	$start = isset($vars['start']) ? htmlspecialchars($vars['start']) : 0;
	$len = isset($vars['len']) ? htmlspecialchars($vars['len']) : PLUGIN_SEARCH_RESULT_LEN;

	if( $id!=='') {
		// display search result
		$highlight = isset($vars['word']) ? $vars['word'] : '';
		return plugin_search_display_format($highlight, $id, $start, $len);
	}

	if (PLUGIN_SEARCH_DISABLE_GET_ACCESS) {
		$s_word = isset($post['word']) ? htmlspecialchars($post['word']) : '';
	} else {
		$s_word = isset($vars['word']) ? htmlspecialchars($vars['word']) : '';
	}
	if (strlen($s_word) > PLUGIN_SEARCH_MAX_LENGTH) {
		unset($vars['word']); // Stop using $_msg_word at lib/html.php
		die_message('Search words too long');
	}

	$type = isset($vars['type']) ? $vars['type'] : '';

	if ($s_word != '') {
		// Search
		$id = plugin_search_do_search($vars['word'], $type);
		$r_id = rawurlencode($id);
		$r_word = rawurlencode($vars['word']);
		header('Location: ' . get_script_uri() . '?cmd=search&id=' . $r_id .
			'&word=' . $r_word);
		exit;
	} else {
		// Init
		unset($vars['word']); // Stop using $_msg_word at lib/html.php
		$msg  = $_title_search;
		$body = '<br />' . "\n" . $_msg_searching . "\n";
	}

	// Show search form
	$body .= plugin_search_search_form($s_word, $type);

	return array('msg'=>$msg, 'body'=>$body);
}

function plugin_search_search_form($s_word = '', $type = '')
{
	global $script;
	$_btn_search    = _('Search');
	$_btn_and       = _('AND');
	$_btn_or        = _('OR');

	$and_check = $or_check = '';
	if ($type == 'OR') {
		$or_check  = ' checked="checked"';
	} else {
		$and_check = ' checked="checked"';
	}

	if (! PLUGIN_SEARCH_DISABLE_GET_ACCESS) {
	return <<<EOD
<form action="$script" method="get">
 <div>
  <input type="hidden" name="cmd" value="search" />
  <input type="text"  name="word" value="$s_word" size="20" />
  <input type="radio" name="type" id="_p_search_AND" value="AND" $and_check />
  <label for="_p_search_AND">$_btn_and</label>
  <input type="radio" name="type" id="_p_search_OR" value="OR"  $or_check />
  <label for="_p_search_OR">$_btn_or</label>
  &nbsp;<input type="submit" value="$_btn_search" />
 </div>
</form>
EOD;
	}
	return <<<EOD
<form action="$script?cmd=search" method="post">
 <div>
  <input type="text"  name="word" value="$s_word" size="20" />
  <input type="radio" name="type" id="_p_search_AND" value="AND" $and_check />
  <label for="_p_search_AND">$_btn_and</label>
  <input type="radio" name="type" id="_p_search_OR" value="OR"  $or_check />
  <label for="_p_search_OR">$_btn_or</label>
  &nbsp;<input type="submit" value="$_btn_search" />
 </div>
</form>
EOD;
}

// display search result
function plugin_search_display_format($highlight, $id = '', $start = 0, $len = 10)
{
	global $_title_search;
	global $_msg_andresult, $_msg_orresult, $_msg_notfoundresult;
	global $_title_result, $_msg_foundnavigator;
	global $_msg_result_title;
	global $_navi_prev, $_navi_next;

	$session = & $_SESSION;
	$session_value = & $session['search'];

	if ($id === '' || !isset($session_value['time']) ||
		$session_value['time'] != $id || $session_value['word'] != $highlight) {
		// display form when the value of ticktime or search words is different
		header('Location: ' . get_script_uri() . '?cmd=search');
		exit;
	}
	$word  = & $session_value['word'];
	$pages = & $session_value['pages'];
	$total_pages = & $session_value['total_pages'];
	$found_pages = & $session_value['found_pages'];
	$b_type      = & $session_value['type'];

	$r_word = rawurlencode($word);
	$s_word = htmlspecialchars($word);

	$found_pages = count($pages);
	if ($found_pages == 0)
	{
		$msg  = str_replace('$1', $s_word, $_title_result);
		$body = str_replace('$1', $s_word, $_msg_notfoundresult);
		// Show search form
		$body .= plugin_search_search_form($s_word, $b_type);
		return array('msg'=>$msg, 'body'=>$body);
	}

	
	// detail result enable
	$search_detail = PLUGIN_SEARCH_DETAIL;

	// displays only the number of $len from $start.
	$disp_pages = array_slice($pages, $start, $len);

	$retval = '<ul>' . "\n";
	foreach ($disp_pages as $arr) {
		list($page, $time, $lines) = $arr;
		$r_page  = rawurlencode($page);
		$s_page  = htmlspecialchars($page);
		$passage = get_passage($time);
		$retval .= ' <li><a href="' . get_script_uri() . '?cmd=read&amp;page=' .
			$r_page . '&amp;word=' . $r_word . '">' . $s_page .
			'</a>' . $passage . '</li>' . "\n";
		if ($search_detail == 1) {
			$source = '';
			foreach ($lines as $line) {
				$source .= ' '.trim($line)." \n";
			}
			$retval .= convert_html($source);
		}
	}
	$retval .= '</ul>' . "\n";

	$retval .= '<p>' . "\n";
	$retval .= str_replace('$1', $s_word, str_replace('$2', $found_pages,
		str_replace('$3', $total_pages, $b_type ? $_msg_andresult : $_msg_orresult)));

	// navigator links
	if ($found_pages > $len) {
		$pos_start = $start + 1;
		$pos_end   = $start + $len;
		if ($pos_end>$found_pages) {
			$pos_end = $found_pages;
		}
		$retval .= str_replace('$1', $pos_start, str_replace('$2', $pos_end,
			$_msg_foundnavigator));

		if ($found_pages>$len) {
			$_total = ceil($found_pages / $len);
			$retval .= '<p>' . $_msg_result_title;
			
			if($start>0) {
				// Add Prev link
				$pos_start = ($start>$len)? $start - $len : 0;
				$retval .= plugin_search_makelink($id, $word, $pos_start, $len, $_navi_prev, $_navi_prev);
			}
			
			for ($i=0; $i<$_total; $i++) {
				$pos_start = $i * $len;
				$pos_end   = $pos_start + $len;
				if ($pos_end>$found_pages) {
					$pos_end = $found_pages;
				}
				$title = $pos_start + 1 . '-' . $pos_end;
				$retval .= plugin_search_makelink($id, $word, $pos_start, $len, $i + 1, $title, $start);
			}

			if($start + $len < $found_pages) {
				// Add Next link
				$pos_start = $start + $len;
				$retval .= plugin_search_makelink($id, $word, $pos_start, $len, $_navi_next, $_navi_next);
			}

			$retval .= '</p>';
		}
	}
	$retval .= '</p>' . "\n";

	$msg  = str_replace('$1', $s_word, $_title_result);

	// Show search form
	$retval .= plugin_search_search_form($s_word, $b_type);

	return array('msg'=>$msg, 'body'=>$retval);
}

// make result pages link
function plugin_search_makelink($id, $word, $start, $len, $label, $title, $now = '')
{
	$r_id    = rawurlencode($id);
	$r_word  = rawurlencode($word);
	$r_start = rawurlencode($start);
	$r_len   = rawurlencode($len);
	$s_label = htmlspecialchars($label);

	if( $now!=='' && $start == $now ) {
		$body = '<span " title="' . $title . '"><strong>' . $s_label . '</strong></span>';
	} else {
		$body = '<a href="' . get_script_uri() . '?cmd=search&id=' . $r_id .
				'&word=' . $r_word . '&start=' . $r_start . '&len=' . $r_len .
				'" title="' . $title . '">' . $s_label . '</a>';
	}
	$body .= "\n";

	return $body;
}

// 'Search' main function (defined for keeping compatible)
function plugin_search_do_search($word, $type = 'AND', $non_format = FALSE)
{
	global $whatsnew, $non_list, $search_non_list;
	global $_msg_andresult, $_msg_orresult, $_msg_notfoundresult;
	global $search_auth;

	$session = & $_SESSION;
	$retval = array();

	$session_value = & $session['search'];
	$pages         = & $session_value['pages'];
	$total_pages   = & $session_value['total_pages'];
	$found_pages   = & $session_value['found_pages'];
	$b_type        = & $session_value['type'];
	$ticktime      = & $session_value['time'];

	$b_type = ($type == 'AND'); // AND:TRUE OR:FALSE

	if (!isset($session_value['word']) || $session_value['word'] != $word) {
		$session_value['word'] = $word;
		$ticktime = time();

		$keys = get_search_words(preg_split('/\s+/', $word, -1, PREG_SPLIT_NO_EMPTY));

		$_pages = get_existpages();
		$total_pages = count($_pages);
		$pages = array();

		$non_list_pattern = '/' . $non_list . '/';
		foreach ($_pages as $page) {
			if ($page == $whatsnew || (! $search_non_list && preg_match($non_list_pattern, $page)))
				continue;

			// 検索対象ページの制限をかけるかどうか (ページ名は制限外)
			if ($search_auth && ! check_readable($page, false, false)) {
				$source = get_source(); // 検索対象ページ内容を空に。
			} else {
				$source = get_source($page);
			}
			if (! $non_format)
				array_unshift($source, $page); // ページ名も検索対象に

			$b_match = FALSE;
			$lines = array();
			foreach ($keys as $key) {
				$tmp     = preg_grep('/' . $key . '/', $source);
				$b_match = ! empty($tmp);
				$lines += $tmp;
				if ($b_match xor $b_type) break;
			}
			if ($b_match) {
				if ($non_format) {
					$pages[$page] = 0;		// only page name
				} else {
					// array_sliceを使うとkeyがなくなるので、いれておく
					$pages[$page] = array($page, get_filetime($page), $lines);
				}
			}
		}
		ksort($pages);
	}
	if ($non_format) return array_keys($pages);

	return $ticktime;
}
?>
