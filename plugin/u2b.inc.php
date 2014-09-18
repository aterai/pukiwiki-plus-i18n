<?php
/**
 * YouTube プラグイン
 *
 * @copyright   Copyright &copy; 2006,2009, Katsumi Saito <katsumi@jo1upk.ymt.prug.or.jp>
 * @version     $Id: u2b.inc.php,v 0.2 2009/11/14 00:21:00 upk Exp $
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 */

if (!defined('YOUTUBE_URL')) {
	define('YOUTUBE_URL','http://www.youtube.com/v/');
}

function plugin_u2b_convert()
{
	$argv = func_get_args();
	$argc = func_num_args();

	foreach($argv as $arg) {
		$val = explode('=', $arg);
		$val[1] = (empty($val[1])) ? htmlspecialchars($val[0]) : htmlspecialchars($val[1]);

		switch ($val[0]) {
		case 'clearl': return '<div style="clear:left;display:block;"></div>';
		case 'clearr': return '<div style="clear:right;display:block;"></div>';
		case 'clear' : return '<div style="clear:both;"></div>';
		case 'width':
		case 'w':
			$width = $val[1];
			break;
		case 'height':
		case 'h':
			$height = $val[1];
			break;
		case 'align':
		case 'right':
		case 'left':
		case 'center':
			$align = $val[1];
			break;
		case 'r':
			$align = 'right';
			break;
		case 'l':
			$align = 'left';
			break;
		case 'c':
			$align = 'center';
			break;
		case 'small':
		case 's':
			$width = 180;
			$height = 140;
			break;
		case 'large':
			$width = 425;
			$height = 350;
			break;
		case 'id':
		default:
			$id = $val[1];
			break;
		}
	}

	if (empty($id)) return '#u2b: ID parameter must be set.';
	if (empty($width) || empty($height)) {
		$width = 425;
		$height = 350;
	}
	if (empty($align)) $align = 'center';

	$youtube_url = YOUTUBE_URL.$id;

	switch($align) {
	case 'right':
	case 'left':
		$style = 'float:'.$align;
		break;
	case 'center':
	default:
		$style = 'text-align:'.$align;
	}

	return <<<EOD
<div class="u2b_img" style="$style">
  <object data="$youtube_url" type="application/x-shockwave-flash" width="$width" height="$height">
    <param name="src" value="$youtube_url" />
  </object>
</div>

EOD;
}

?>
