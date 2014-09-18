<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: navi.inc.php,v 1.22.5 2008/01/05 18:36:00 upk Exp $
//
// Navi plugin: Show DocBook-like navigation bar and contents

/*
 * Usage:
 *   #navi(contents-page-name)   <for ALL child pages>
 *   #navi([contents-page-name][,reverse]) <for contents page>
 *
 * Parameter:
 *   contents-page-name - Page name of home of the navigation (default:itself)
 *   reverse            - Show contents revese
 *
 * Behaviour at contents page:
 *   Always show child-page list like 'ls' plugin
 *
 * Behaviour at child pages:
 *
 *   The first plugin call - Show a navigation bar like a DocBook header
 *
 *     Prev  <contents-page-name>  Next
 *     --------------------------------
 *
 *   The second call - Show a navigation bar like a DocBook footer
 *
 *     --------------------------------
 *     Prev          Home          Next
 *     <pagename>     Up     <pagename>
 *
 * Page-construction example:
 *   foobar    - Contents page, includes '#navi' or '#navi(foobar)'
 *   foobar/1  - One of child pages, includes one or two '#navi(foobar)'
 *   foobar/2  - One of child pages, includes one or two '#navi(foobar)'
 */
include_once(PLUGIN_DIR.'topicpath.inc.php');

// Exclusive regex pattern of child pages
define('PLUGIN_NAVI_EXCLUSIVE_REGEX', '');
//define('PLUGIN_NAVI_EXCLUSIVE_REGEX', '#/_#'); // Ignore 'foobar/_memo' etc.

// Insert <link rel=... /> tags into XHTML <head></head>
define('PLUGIN_NAVI_LINK_TAGS', FALSE);	// FALSE, TRUE

// ----
function plugin_navi_convert()
{
	global $vars, $head_tags, $whatsnew;
//	global $_navi_prev, $_navi_next, $_navi_up, $_navi_home;
	static $navi = array();
	$_navi_prev = _('Prev');
	$_navi_next = _('Next');
	$_navi_up   = _('Up');
	$_navi_home = _('Home');

	$current = $vars['page'];

    $_page  = isset($vars['page']) ? $vars['page'] : '';
    $is_page = (is_pagename($_page) && ! arg_check('backup') && $_page != $whatsnew);
    $is_read = (arg_check('read') && is_page($_page));
	$fmt = $is_read ? get_filetime($_page) : 0;

	$reverse = FALSE;
	if (func_num_args()) {
		list($home, $reverse) = array_pad(func_get_args(), 2, '');
		// strip_bracket() is not necessary but compatible
		$home    = get_fullname(strip_bracket($home), $current);
		$is_home = ($home == $current);
		if (! is_page($home)) {
			return '#navi(contents-page-name): No such page: ' .
				htmlspecialchars($home) . '<br />';
		} else if (! $is_home &&
		    ! preg_match('/^' . preg_quote($home, '/') . '/', $current)) {
			return '#navi(' . htmlspecialchars($home) .
				'): Not a child page like: ' .
				htmlspecialchars($home . '/' . basepagename($current)) .
				'<br />';
		}
		$reverse = (strtolower($reverse) == 'reverse');
	} else {
		$home    = $vars['page'];
		$is_home = TRUE; // $home == $current
	}

	$pages  = array();
	$footer = isset($navi[$home]); // The first time: FALSE, the second: TRUE
	if (! $footer) {
		$navi[$home] = array(
			'up'   =>'',
			'prev' =>'',
			'prev1'=>'',
			'next' =>'',
			'next1'=>'',
			'home' =>'',
			'home1'=>'',
		);

		$pages = preg_grep('/^' . preg_quote($home, '/') .
			'($|\/)/', auth::get_existpages());
		if (PLUGIN_NAVI_EXCLUSIVE_REGEX != '') {
			// If old PHP could use preg_grep(,,PREG_GREP_INVERT)...
			$pages = array_diff($pages,
				preg_grep(PLUGIN_NAVI_EXCLUSIVE_REGEX, $pages));
		}
		$pages[] = $current; // Sentinel :)
		$pages   = array_unique($pages);
		natcasesort($pages);
		if ($reverse) $pages = array_reverse($pages);

		$prev = $home;
		foreach ($pages as $page) {
			if ($page == $current) break;
			$prev = $page;
		}
		$next = current($pages);

		$pos = strrpos($current, '/');
		$up = '';
		if ($pos > 0) {
			$up = substr($current, 0, $pos);
			$navi[$home]['up']    = make_pagelink($up, $_navi_up);
		}
		if (! $is_home) {
			$navi[$home]['prev']  = make_pagelink($prev);
			$navi[$home]['prev1'] = make_pagelink($prev, $_navi_prev);
		}
		if ($next != '') {
			$navi[$home]['next']  = make_pagelink($next);
			$navi[$home]['next1'] = make_pagelink($next, $_navi_next);
		}
		$navi[$home]['home']  = plugin_topicpath_inline(); //make_pagelink($home);
		$navi[$home]['home1'] = make_pagelink($home, $_navi_home);

		// Generate <link> tag: start next prev(previous) parent(up)
		// Not implemented: contents(toc) search first(begin) last(end)
		if (PLUGIN_NAVI_LINK_TAGS) {
			foreach (array('start'=>$home, 'next'=>$next,
			    'prev'=>$prev, 'up'=>$up) as $rel=>$_page) {
				if ($_page != '') {
					$s_page = htmlspecialchars($_page);
					$head_tags[] = ' <link rel="' .
						$rel . '" href="' .
						get_page_uri($_page) . '" title="' .
						$s_page . '" />';
				}
			}
		}
	}

	$ret = '';

	if ($is_home) {
		// Show contents
		$count = count($pages);
		if ($count == 0) {
			return '#navi(contents-page-name): You already view the result<br />';
		} else if ($count == 1) {
			// Sentinel only: Show usage and warning
			$home = htmlspecialchars($home);
			$ret .= '#navi(' . $home . '): No child page like: ' .
				$home . '/Foo';
		} else {
			$ret .= '<ul>';
			foreach ($pages as $page)
				if ($page != $home)
					$ret .= ' <li>' . make_pagelink($page) . '</li>';
			$ret .= '</ul>';
		}

	} else if (! $footer) {
		// Header
		$ret = <<<EOD
<nav>
<ul class="navi">
 <li class="navi_left">{$navi[$home]['prev1']}</li>
 <li class="navi_right">{$navi[$home]['next1']}</li>
 <li class="navi_none">{$navi[$home]['home']}</li>
</ul>
</nav>
<hr />
EOD;
	} else {
		// Footer
		$ret = <<<EOD
<hr class="full_hr" />
<nav>
<ul class="navi">
 <li class="navi_left">{$navi[$home]['prev1']}<br />{$navi[$home]['prev']}</li>
 <li class="navi_right">{$navi[$home]['next1']}<br />{$navi[$home]['next']}</li>
 <li class="navi_none">{$navi[$home]['home1']}<br />{$navi[$home]['up']}</li>
</ul>
</nav>
EOD;
	}
	return $ret;
}
?>
