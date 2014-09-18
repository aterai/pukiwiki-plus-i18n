<?php
/////////////////////////////////////////////////
// PukiWiki Plus! - Yet another WikiWikiWeb clone.
//
// $Id: description.inc.php,v 0.2 2004/09/30 09:41:57 miko Exp $
//

function plugin_description_convert()
{
	global $head_tags;

	$num = func_num_args();
	if ($num == 0) { return 'Usage: #description(description)'; }
	$args = func_get_args();
    //$content = htmlspecialchars(implode(',', $args));
	$contents = array_map("htmlspecialchars", $args);
	array_push($head_tags, '<meta name="description" content="'.join(',', $contents).'" />');
	//array_push($head_tags, '<meta name="description" content="'.$content.'" />');
	//$head_tags[] = '<meta http-equiv="Description" content="'.$content.'" />';
	return '';
}
?>
