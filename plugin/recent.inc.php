<?php
// $Id: recent.inc.php,v 1.25.3 2008/07/08 00:25:00 upk Exp $
// Copyright (C)
//   2005-2008 PukiWiki Plus! Team
//   2002-2007 PukiWiki Developers Team
//   2002      Y.MASUI http://masui.net/pukiwiki/ masui@masui.net
// License: GPL version 2
//
// Recent plugin -- Show RecentChanges list
//   * Usually used at 'MenuBar' page
//   * Also used at special-page, without no #recnet at 'MenuBar'

// Default number of 'Show latest N changes'
define('PLUGIN_RECENT_DEFAULT_LINES', 10);

// Limit number of executions
define('PLUGIN_RECENT_EXEC_LIMIT', 3); // N times per one output

// ----

define('PLUGIN_RECENT_USAGE', '#recent(number-to-show)');

// Place of the cache of 'RecentChanges'
define('PLUGIN_RECENT_CACHE', CACHE_DIR . PKWK_MAXSHOW_CACHE);

// RecentChangesViewerのタイトル
define('PLUGIN_RECENT_TITLE', 'RecentChanges');

function plugin_recent_convert()
{
	global $vars, $date_format, $show_passage; // , $_recent_plugin_frame;
	static $exec_count = 1;

	$_recent_plugin_frame_s = _('recent(%d)');
	$_recent_plugin_frame   = sprintf('<h3>%s</h3><div>%%s</div>', $_recent_plugin_frame_s);

	$recent_lines = PLUGIN_RECENT_DEFAULT_LINES;
	if (func_num_args()) {
		$args = func_get_args();
		if (! is_numeric($args[0]) || isset($args[1])) {
			return PLUGIN_RECENT_USAGE . '<br />';
		} else {
			$recent_lines = $args[0];
		}
	}

	// Show only N times
	if ($exec_count > PLUGIN_RECENT_EXEC_LIMIT) {
		return '#recent(): You called me too much' . '<br />' . "\n";
	} else {
		++$exec_count;
	}

	if (! file_exists(PLUGIN_RECENT_CACHE))
		return '#recent(): Cache file of RecentChanges not found' . '<br />';

	// Get latest N changes
	$lines = file_head(PLUGIN_RECENT_CACHE, $recent_lines);
	if ($lines == FALSE) return '#recent(): File can not open' . '<br />' . "\n";

	$auth_key = auth::get_user_info();
	$date = $items = '';
	foreach ($lines as $line) {
		list($time, $page) = explode("\t", rtrim($line));
		if (! auth::is_page_readable($page,$auth_key['key'],$auth_key['group'])) continue;

		$_date = date($date_format, $time);
		if ($date != $_date) {
			// End of the day
			if ($date != '') $items .= '</ul>' . "\n";

			// New day
			$date = $_date;
			$items .= '<strong>' . $date . '</strong>' . "\n" .
				'<ul class="recent_list">' . "\n";
		}

		$s_page = htmlspecialchars($page);

		if($page === $vars['page']) {
			// No need to link to the page you just read, or notify where you just read
			$items .= ' <li>' . $s_page . '</li>' . "\n";
		} else {
			$passage = $show_passage ? ' ' . get_passage($time) : '';
			$items .= ' <li><a href="' . get_page_uri($page) . '"' . 
				' title="' . $s_page . $passage . '">' . $s_page . '</a></li>' . "\n";
		}
	}
	// End of the day
	if ($date != '') $items .= '</ul>' . "\n";

	return sprintf($_recent_plugin_frame, count($lines), $items);
}

function plugin_recent_action()
{
	global $vars;
//	global $_recent_plugin_header;

	$offset = isset($vars['offset']) ? $vars['offset'] : 0;
	$lines  = isset($vars['lines'] ) ? $vars['lines']  : PLUGIN_RECENT_DEFAULT_LINES;

	$retval = plugin_recent_getlist($offset,$lines,'');
	$header = PLUGIN_RECENT_TITLE;
	$body   = "<div>".$retval['items']."</div>";

	$prev_offset = 0;
	$prev_lines  = 0;
	if($offset > 0)
	{
		if($offset > $lines)
		{
			$prev_offset = $offset - $lines;
			$prev_lines  = $lines;
		}
		else
		{
			$prev_offset = 0;
			$prev_lines  = $offset;
		}
	}

	$next_offset = 0;
	$next_lines  = 0;
	if($retval['count'] == $lines)
	{
		$next_offset = $offset + $lines;
		$next_lines = $lines;
	}
	if($retval['total'] > $lines)
	{
		$total_page = ceil($retval['total'] / $lines) + 1;
		$now_page = ceil($offset / $lines);
	}

	$prev_link = '';
	$next_link = '';
	$page_link = '';
	$script = get_script_uri();
	if($prev_lines > 0)
	{
		$prev_link = "<a href=\"$script?plugin=recent" . "&amp;offset=$prev_offset&amp;lines=$prev_lines\" >«</a>";
	}
	if($next_lines > 0)
	{
		$next_link = "<a href=\"$script?plugin=recent" . "&amp;offset=$next_offset&amp;lines=$next_lines\" >»</a>";
	}
	if($total_page > 1)
	{
		for( $i=0; $i<$total_page; )
		{
			$page_offset = $i * $lines;
			$page_num = $i + 1;
			if($i!=$now_page)
			{
				$page_link .= "<li><a href=\"$script?plugin=recent" . "&amp;offset=$page_offset&amp;lines=$lines\">$page_num</a></li>";
			}
			else
			{
				$page_link .= "<li class=\"active\"><a href=\"#\">$page_num</a></li>";
			}
			$i++;
// 			if($i<$total_page)
// 			{
// 				$page_link .= " / ";
// 			}
		}
	}
	$body .= <<<EOD
<hr class="full_hr" />
<nav role="navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
<ul class="pagination pagination-sm">
<li class="previous">$prev_link</li>
$page_link
<li class="next">$next_link</li>
</ul>
</nav>
EOD;

	return array('msg' => $header, 'body' => $body);
}

function plugin_recent_getlist($offset, $recent_lines, $varpage)
{
	global $date_format;

	// N件(行)を取り出す
	$recent_pages = file(PLUGIN_RECENT_CACHE);
	$lines = array_splice($recent_pages, $offset, $recent_lines);

	$date = $items = '';
	foreach ($lines as $line) {
		list($time, $page) = explode("\t", rtrim($line));
		$_date = date($date_format, $time);
		if ($date != $_date) {
			if ($date != '') $items .= '</ul>' . "\n";

			$date = $_date;
			$items .= '<strong>' . $date . '</strong>' . "\n" .
				'<ul class="recent_list">' . "\n";
		}
		$s_page = htmlspecialchars($page);
		$r_page = rawurlencode($page);
		$pg_passage = get_pg_passage($page, FALSE);
		if($page == $varpage) {
			// No need to link itself, notifies where you just read
			$items .= ' <li>' . $s_page . '</li>' . "\n";
		} else {
			$items .= ' <li>' . make_pagelink($page, '', '', '', FALSE) . "</li>\n";
		}
	}
	if (! empty($lines)) $items .= '</ul>' . "\n";

	$retval = array();
	$retval['items'] = $items;
	$retval['count'] = count($lines);
	$retval['total'] = count($recent_pages);

	return $retval;
}
?>
