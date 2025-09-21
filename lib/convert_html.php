<?php

// PukiWiki Plus! - Yet another WikiWikiWeb clone
// $Id: convert_html.php,v 1.19.22 2008/05/04 19:53:00 upk Exp $
// Copyright (C)
//   2005-2008 PukiWiki Plus! Team
//   2002-2007 PukiWiki Developers Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// function 'convert_html()', wiki text parser
// and related classes-and-functions

function convert_html($lines): string
{
    global $vars, $digest;
    static $contents_id = 0;

    // Set digest
    $digest = md5(join('', get_source($vars['page'])));

    if (!is_array($lines))
        $lines = explode("\n", $lines);

    $body = new Body(++$contents_id);
    $body->parse($lines);

    return $body->toString();
}

// Block elements
class Element
{
    var $parent;
    var array $elements; // References of childs
    var $last; // Insert new one at the back of the $last

    function __construct()
    {
        $this->elements = array();
        $this->last = &$this;
    }

    function setParent(&$parent): void
    {
        $this->parent = &$parent;
    }

    function &add(&$obj)
    {
        if ($this->canContain($obj)) {
            return $this->insert($obj);
        } else {
            return $this->parent->add($obj);
        }
    }

    function &insert(&$obj)
    {
        $obj->setParent($this);
        $this->elements[] = &$obj;

        return $this->last = &$obj->last;
    }

    function canContain($obj): bool
    {
        return true;
    }

    function wrap($string, $tag, $param = '', $canomit = true): string
    {
        return $canomit && $string == '' ? '' : ('<' . $tag . $param . '>' . $string . '</' . $tag . '>');
    }

    function toString(): string
    {
        $ret = array();
        foreach (array_keys($this->elements) as $key)
            $ret[] = $this->elements[$key]->toString();
        return join("\n", $ret);
    }

    function dump($indent = 0): string
    {
        $ret = str_repeat(' ', $indent) . get_class($this) . "\n";
        $indent += 2;
        foreach (array_keys($this->elements) as $key) {
            $ret .= is_object($this->elements[$key]) ? $this->elements[$key]->dump($indent) : '';

            //str_repeat(' ', $indent) . $this->elements[$key];
        }
        return $ret;
    }
}

// Returns inline-related object
function &Factory_Inline($text): Paragraph|Inline
{
    // Check the first letter of the line
    if (str_starts_with($text, '~')) {
        return new Paragraph(' ' . substr($text, 1));
    } else {
        return new Inline($text);
    }
}

function &Factory_DList(&$root, $text): Paragraph|DList|Inline
{
    $out = explode('|', ltrim($text), 2);
    if (count($out) < 2) {
        return Factory_Inline($text);
    } else {
        return new DList($out);
    }
}

// '|'-separated table
function &Factory_Table(&$root, $text)
{
    if (!preg_match('/^\|(.+)\|([hHfFcC]?)$/', $text, $out)) {
        return Factory_Inline($text);
    } else {
        return new Table($out);
    }
}

// Comma-separated table
function &Factory_YTable(&$root, $text)
{
    if ($text == ',') {
        return Factory_Inline($text);
    } else {
        return new YTable(csv_explode(',', substr($text, 1)));
    }
}

function &Factory_Div(&$root, $text)
{
    $matches = array();

    // Seems block plugin?
    if (PKWKEXP_DISABLE_MULTILINE_PLUGIN_HACK) {
        // Usual code
        if (preg_match('/^#([^(]+)(?:\((.*)\))?/', $text, $matches) && exist_plugin_convert($matches[1])) {
            return new Div($matches);
        }
    } else {
        // Hack code
        if (preg_match('/^#([^({]+)(?:\(([^\r]*)\))?(\{*)/', $text, $matches) && exist_plugin_convert($matches[1])) {
            $len = strlen($matches[3]);
            $body = array();
            if ($len == 0) {
                return new Div($matches); // Seems legacy block plugin
            } else if (preg_match('/\{{' . $len . '}\s*\r(.*)\r\}{' . $len . '}/', $text, $body)) {
                $matches[2] .= "\r" . $body[1] . "\r";
                return new Div($matches); // Seems multiline-enabled block plugin
            }
        }
    }

    return new Paragraph($text);
}

// Inline elements
class Inline extends Element
{
    function Inline($text)
    {
        $this->__construct($text);
    }

    function __construct($text)
    {
        parent::__construct();
        $this->elements[] = trim(str_starts_with($text, "\n") ? $text : make_link($text));
    }

    function &insert(&$obj)
    {
        $this->elements[] = $obj->elements[0];
        return $this;
    }

    function canContain($obj): bool
    {
        return is_a($obj, 'Inline');
    }

    function toString(): string
    {
        global $line_break;
        return join($line_break ? ('<br />' . "\n") : "\n", $this->elements);
    }

    function &toPara($class = ''): Paragraph
    {
        $obj = new Paragraph('', $class);
        $obj->insert($this);
        return $obj;
    }
}

// Paragraph: blank-line-separated sentences
class Paragraph extends Element
{
    var $param;

    function Paragraph($text, $param = ''): void
    {
        $this->__construct($text, $param);
    }

    function __construct($text, $param = '')
    {
        parent::__construct();
        $this->param = $param;
        if ($text == '')
            return;

        if (str_starts_with($text, '~'))
            $text = ' ' . substr($text, 1);

        $this->insert(Factory_Inline($text));
    }

    function canContain($obj): bool
    {
        return is_a($obj, 'Inline');
    }

    function toString(): string
    {
        return $this->wrap(parent::toString(), 'p', $this->param);
    }
}

// * Heading1
// ** Heading2
// *** Heading3
class Heading extends Element
{
    var $level;
    var $id;
    var $msg_top;
    var $text;

    function Heading(&$root, $text): void
    {
        $this->__construct($root, $text);
    }

    function __construct(&$root, $text)
    {
        parent::__construct();

        $this->text = $text;
        $this->level = min(3, strspn($text, '*'));
        list($text, $this->msg_top, $this->id) = $root->getAnchor($text, $this->level);
        $this->insert(Factory_Inline($text));
        $this->level++; // h2,h3,h4
    }

    function &insert(&$obj)
    {
        parent::insert($obj);
        return $this->last = &$this;
    }

    function canContain($obj): bool
    {
        return false;
    }

    function toString(): string
    {
        return $this->msg_top . $this->wrap(parent::toString(), 'h' . $this->level, ' id="' . $this->id . '"');
    }
}

// ----
// Horizontal Rule
class HRule extends Element
{
    function HRule(&$root, $text): void
    {
        $this->__construct($root, $text);
    }

    function __construct(&$root, $text)
    {
        parent::__construct();
    }

    function canContain($obj): bool
    {
        return false;
    }

    function toString(): string
    {
        global $hr;
        return $hr;
    }
}

// Lists (UL, OL, DL)
class ListContainer extends Element
{
    var $tag;
    var $tag2;
    var $level;
    var $style;

    function ListContainer($tag, $tag2, $head, $text): void
    {
        $this->__construct($tag, $tag2, $head, $text);
    }

    function __construct($tag, $tag2, $head, $text)
    {
        parent::__construct();

        $this->tag = $tag;
        $this->tag2 = $tag2;
        $this->level = min(3, strspn($text, $head));
        $text = ltrim(substr($text, $this->level));

        parent::insert(new ListElement($this->level, $tag2));
        if ($text != '')
            $this->last = &$this->last->insert(Factory_Inline($text));
    }

    function canContain($obj): bool
    {
        return !is_a($obj, 'ListContainer') || $this->tag == $obj->tag && $this->level == $obj->level;
    }

    function setParent(&$parent): void
    {
        parent::setParent($parent);

        $step = $this->level;
        if (isset($parent->parent) && is_a($parent->parent, 'ListContainer'))
            $step -= $parent->parent->level;

        $this->style = sprintf(pkwk_list_attrs_template(), $this->level, $step);
    }

    function &insert(&$obj)
    {
        if (!is_a($obj, get_class($this)))
            return $this->last = &$this->last->insert($obj);

        // Break if no elements found (BugTrack/524)
        if (count($obj->elements) == 1 && empty($obj->elements[0]->elements))
            return $this->last->parent; // up to ListElement

        // Move elements
        foreach (array_keys($obj->elements) as $key)
            parent::insert($obj->elements[$key]);

        return $this->last;
    }

    function toString(): string
    {
        return $this->wrap(parent::toString(), $this->tag, $this->style);
    }
}

#[AllowDynamicProperties]
class ListElement extends Element
{
    function ListElement($level, $head): void
    {
        $this->__construct($level, $head);
    }

    function __construct($level, $head)
    {
        parent::__construct();
        $this->level = $level;
        $this->head = $head;
    }

    function canContain($obj): bool
    {
        return !is_a($obj, 'ListContainer') || $obj->level > $this->level;
    }

    function toString(): string
    {
        return $this->wrap(parent::toString(), $this->head);
    }
}

// - One
// - Two
// - Three
class UList extends ListContainer
{
    function UList(&$root, $text): void
    {
        $this->__construct($root, $text);
    }

    function __construct(&$root, $text)
    {
        parent::__construct('ul', 'li', '-', $text);
    }
}

// + One
// + Two
// + Three
class OList extends ListContainer
{
    function OList(&$root, $text): void
    {
        $this->__construct($root, $text);
    }

    function __construct(&$root, $text)
    {
        parent::__construct('ol', 'li', '+', $text);
    }
}

// : definition1 | description1
// : definition2 | description2
// : definition3 | description3
class DList extends ListContainer
{
    function DList($out): void
    {
        $this->__construct($out);
    }

    function __construct($out)
    {
        parent::__construct('dl', 'dt', ':', $out[0]);
        $this->last = &Element::insert(new ListElement($this->level, 'dd'));
        if ($out[1] != '')
            $this->last = &$this->last->insert(Factory_Inline($out[1]));
    }
}

// > Someting cited
// > like E-mail text
class BQuote extends Element
{
    var $level;

    function BQuote(&$root, $text): void
    {
        $this->__construct($root, $text);
    }

    function __construct(&$root, $text)
    {
        parent::__construct();

        $head = substr($text, 0, 1);
        $this->level = min(3, strspn($text, $head));
        $text = ltrim(substr($text, $this->level));

        if ($head == '<') { // Blockquote close
            $level = $this->level;
            $this->level = 0;
            $this->last = &$this->end($root, $level);
            if ($text != '')
                $this->last = &$this->last->insert(Factory_Inline($text));
        } else {
            $this->insert(Factory_Inline($text));
        }
    }

    function canContain($obj): bool
    {
        return !is_a($obj, get_class($this)) || $obj->level >= $this->level;
    }

    function &insert(&$obj)
    {
        // BugTrack/521, BugTrack/545
        if (is_a($obj, 'inline'))
            return parent::insert($obj->toPara(' class="quotation"'));

        if (is_a($obj, 'BQuote') && $obj->level == $this->level && count($obj->elements)) {
            $obj = &$obj->elements[0];
            if (is_a($this->last, 'Paragraph') && count($obj->elements))
                $obj = &$obj->elements[0];
        }
        return parent::insert($obj);
    }

    function toString(): string
    {
        return $this->wrap(parent::toString(), 'blockquote');
    }

    function &end(&$root, $level)
    {
        $parent = &$root->last;

        while (is_object($parent)) {
            if (is_a($parent, 'BQuote') && $parent->level == $level)
                return $parent->parent;
            $parent = &$parent->parent;
        }
        return $this;
    }
}

class TableCell extends Element
{
    var $tag = 'td'; // {td|th}
    var $colspan = 1;
    var $rowspan = 1;
    var $style; // is array('width'=>, 'align'=>...);

    function TableCell($text, $is_template = false): void
    {
        $this->__construct($text, $is_template);
    }

    function __construct($text, $is_template = false)
    {
        parent::__construct();
        $this->style = $matches = array();

        while (
            preg_match(
                '/^(?:(LEFT|CENTER|RIGHT)|(BG)?COLOR\((#?\w{1,20})\)|SIZE\((\d{1,2})\)|(BOLD)):(.*)$/',
                $text,
                $matches,
            )
        ) {
            if ($matches[1]) {
                $this->style['align'] = 'text-align:' . strtolower($matches[1]) . ';';
                $text = $matches[6];
            } else if ($matches[3]) {
                $name = $matches[2] ? 'background-color' : 'color';
                $this->style[$name] = $name . ':' . htmlsc($matches[3]) . ';';
                $text = $matches[6];
            } else if (is_numeric($matches[4])) {
                $this->style['size'] = 'font-size:' . htmlsc($matches[4]) . 'px;';
                $text = $matches[6];
            } else if ($matches[5]) {
                $this->style['bold'] = 'font-weight:bold;';
                $text = $matches[6];
            }
        }
        if ($is_template && is_numeric($text))
            $this->style['width'] = 'width:' . $text . 'px;';

        if ($text == '>') {
            $this->colspan = 0;
        } else if ($text == '~') {
            $this->rowspan = 0;
        } else if (str_starts_with($text, '~')) {
            $this->tag = 'th';
            $text = substr($text, 1);
        }

        if ($text != '' && $text[0] == '#') {
            // Try using Div class for this $text
            $obj = &Factory_Div($this, $text);
            if (is_a($obj, 'Paragraph'))
                $obj = &$obj->elements[0];
        } else {
            $obj = &Factory_Inline($text);
        }

        $this->insert($obj);
    }

    function setStyle(&$style): void
    {
        foreach ($style as $key => $value)
            if (!isset($this->style[$key]))
                $this->style[$key] = $value;
    }

    function toString(): string
    {
        if ($this->rowspan == 0 || $this->colspan == 0)
            return '';

        $param = ' class="style_' . $this->tag . '"';
        if ($this->rowspan > 1)
            $param .= ' rowspan="' . $this->rowspan . '"';
        if ($this->colspan > 1) {
            $param .= ' colspan="' . $this->colspan . '"';
            unset($this->style['width']);
        }
        if (!empty($this->style))
            $param .= ' style="' . join(' ', $this->style) . '"';

        return $this->wrap(parent::toString(), $this->tag, $param, false);
    }
}

// | title1 | title2 | title3 |
// | cell1  | cell2  | cell3  |
// | cell4  | cell5  | cell6  |
class Table extends Element
{
    var $type;
    var $types;
    var $col; // number of column

    function Table($out): void
    {
        $this->__construct($out);
    }

    function __construct($out)
    {
        parent::__construct();

        $cells = explode('|', $out[1]);
        $this->col = count($cells);
        $this->type = strtolower($out[2]);
        $this->types = array($this->type);
        $is_template = $this->type == 'c';
        $row = array();
        foreach ($cells as $cell)
            $row[] = new TableCell($cell, $is_template);
        $this->elements[] = $row;
    }

    function canContain($obj): bool
    {
        return is_a($obj, 'Table') && $obj->col == $this->col;
    }

    function &insert(&$obj)
    {
        $this->elements[] = $obj->elements[0];
        $this->types[] = $obj->type;
        return $this;
    }

    function toString(): string
    {
        static $parts = array('h' => 'thead', 'f' => 'tfoot', '' => 'tbody');

        // Set rowspan (from bottom, to top)
        for ($ncol = 0; $ncol < $this->col; $ncol++) {
            $rowspan = 1;
            foreach (array_reverse(array_keys($this->elements)) as $nrow) {
                $row = &$this->elements[$nrow];
                if ($row[$ncol]->rowspan == 0) {
                    ++$rowspan;
                    continue;
                }
                $row[$ncol]->rowspan = $rowspan;
                // Inherits row type
                while (--$rowspan)
                    $this->types[$nrow + $rowspan] = $this->types[$nrow];
                $rowspan = 1;
            }
        }

        // Set colspan and style
        $stylerow = null;
        foreach (array_keys($this->elements) as $nrow) {
            $row = &$this->elements[$nrow];
            if ($this->types[$nrow] == 'c')
                $stylerow = &$row;
            $colspan = 1;
            foreach (array_keys($row) as $ncol) {
                if ($row[$ncol]->colspan == 0) {
                    ++$colspan;
                    continue;
                }
                $row[$ncol]->colspan = $colspan;
                if ($stylerow !== null) {
                    $row[$ncol]->setStyle($stylerow[$ncol]->style);
                    // Inherits column style
                    while (--$colspan)
                        $row[$ncol - $colspan]->setStyle($stylerow[$ncol]->style);
                }
                $colspan = 1;
            }
        }

        // toString
        $string = '';
        foreach ($parts as $type => $part) {
            $part_string = '';
            foreach (array_keys($this->elements) as $nrow) {
                if ($this->types[$nrow] != $type)
                    continue;
                $row = &$this->elements[$nrow];
                $row_string = '';
                foreach (array_keys($row) as $ncol)
                    $row_string .= $row[$ncol]->toString();
                $part_string .= $this->wrap($row_string, 'tr') . "\n";
            }
            $string .= $this->wrap($part_string, $part);
        }
        $string = $this->wrap($string, 'table', ' class="style_table" cellspacing="1" border="0"');

        return $this->wrap($string, 'div', ' class="ie5"');
    }
}

// , cell1  , cell2  ,  cell3
// , cell4  , cell5  ,  cell6
// , cell7  ,        right,==
// ,left          ,==,  cell8
class YTable extends Element
{
    var $col; // Number of columns

    function YTable($row = array('cell1 ', ' cell2 ', ' cell3')): void
    {
        $this->__construct($row);
    }

    // TODO: Seems unable to show literal '==' without tricks.
    //       But it will be imcompatible.
    // TODO: Why toString() or toXHTML() here
    function __construct($row = array('cell1 ', ' cell2 ', ' cell3'))
    {
        parent::__construct();

        $str = array();
        $col = count($row);

        $matches = $_value = $_align = array();
        foreach ($row as $cell) {
            if (preg_match('/^(\s+)?(.+?)(\s+)?$/', $cell, $matches)) {
                if ($matches[2] == '==') {
                    // Colspan
                    $_value[] = false;
                    $_align[] = false;
                } else {
                    $_value[] = $matches[2];
                    if ($matches[1] == '') {
                        $_align[] = ''; // left
                    } else if (isset($matches[3])) {
                        $_align[] = 'center';
                    } else {
                        $_align[] = 'right';
                    }
                }
            } else {
                $_value[] = $cell;
                $_align[] = '';
            }
        }

        for ($i = 0; $i < $col; $i++) {
            if ($_value[$i] === false)
                continue;
            $colspan = 1;
            while (isset($_value[$i + $colspan]) && $_value[$i + $colspan] === false)
                ++$colspan;
            $colspan = $colspan > 1 ? (' colspan="' . $colspan . '"') : '';
            $align = $_align[$i] ? (' style="text-align:' . $_align[$i] . '"') : '';
            $str[] = '<td class="style_td"' . $align . $colspan . '>';
            $str[] = make_link($_value[$i]);
            $str[] = '</td>';
            unset($_value[$i], $_align[$i]);
        }

        $this->col = $col;
        $this->elements[] = implode('', $str);
    }

    function canContain($obj): bool
    {
        return is_a($obj, 'YTable') && $obj->col == $this->col;
    }

    function &insert(&$obj)
    {
        $this->elements[] = $obj->elements[0];
        return $this;
    }

    function toString(): string
    {
        $rows = '';
        foreach ($this->elements as $str) {
            $rows .= "\n" . '<tr class="style_tr">' . $str . '</tr>' . "\n";
        }
        $rows = $this->wrap($rows, 'table', ' class="style_table" cellspacing="1" border="0"');
        return $this->wrap($rows, 'div', ' class="ie5"');
    }
}

// ' 'Space-beginning sentence
// ' 'Space-beginning sentence
// ' 'Space-beginning sentence
class Pre extends Element
{
    function Pre(&$root, $text): void
    {
        $this->__construct($root, $text);
    }

    function __construct(&$root, $text)
    {
        global $preformat_ltrim;
        parent::__construct();
        $this->elements[] = htmlsc(!$preformat_ltrim || $text == '' || $text[0] != ' ' ? $text : substr($text, 1));
    }

    function canContain($obj): bool
    {
        return is_a($obj, 'Pre');
    }

    function &insert(&$obj)
    {
        $this->elements[] = $obj->elements[0];
        return $this;
    }

    function toString(): string
    {
        return $this->wrap(join("\n", $this->elements), 'pre');
    }
}

// // ' 'Space-beginning sentence with color(started with '# ')
// // ' 'Space-beginning sentence with color
// // ' 'Space-beginning sentence with color
// class CPre extends Element
// {
// 	function CPre(&$root,$text)
// 	{
// 		global $preformat_ltrim;
//
// 		parent::Element();
// 		if (substr($text, 0, 2) === '# ') $text=substr($text,1);
// 		$this->elements[] = (!$preformat_ltrim or $text == '' or substr($text, 0, 1) !== ' ') ? $text : substr($text,1);
// 	}
// 	function canContain(& $obj)
// 	{
// 		return is_a($obj, 'CPre');
// 	}
// 	function &insert(&$obj)
// 	{
// 		$this->elements[] = $obj->elements[0];
// 		return $this;
// 	}
// 	function toString()
// 	{
// 		static $saved_glossary, $saved_autolink, $make_link;
// 		global $glossary, $autolink;
// 		$saved_glossary=$glossary;
// 		$saved_autolink=$autolink;
// 		$glossary=FALSE;
// 		$autolink=FALSE;
// 		$made_link=make_link(join("\n",$this->elements));
// 		$autolink=$saved_autolink;
// 		$glossary=$saved_glossary;
// 		return $this->wrap($made_link,'pre');
// 	}
// }

// #something (started with '#')
class Div extends Element
{
    var $name;
    var $param;

    function Div($out): void
    {
        $this->__construct($out);
    }

    function __construct($out)
    {
        parent::__construct();
        list(, $this->name, $this->param) = array_pad($out, 3, '');
    }

    function canContain($obj): bool
    {
        return false;
    }

    function toString(): string
    {
        // Call #plugin
        return do_plugin_convert($this->name, $this->param);
    }
}

// LEFT:/CENTER:/RIGHT:
class Align extends Element
{
    var $align;

    function Align($align): void
    {
        $this->__construct($align);
    }

    function __construct($align)
    {
        parent::__construct();
        $this->align = $align;
    }

    function canContain($obj): bool
    {
        return is_a($obj, 'Inline');
    }

    function toString(): string
    {
        return $this->wrap(parent::toString(), 'div', ' style="text-align:' . $this->align . '"');
    }
}

// Body
class Body extends Element
{
    var $id;
    var $count = 0;
    var $contents;
    var $contents_last;
    var $classes = array(
        '-' => 'UList',
        '+' => 'OList',
        '>' => 'BQuote',
        '<' => 'BQuote',
    );
    var array $factories = array(
        ':' => 'DList',
        '|' => 'Table',
        ',' => 'YTable',
        '#' => 'Div',
    );

    function Body($id): void
    {
        $this->__construct($id);
    }

    function __construct($id)
    {
        $this->id = $id;
        $this->contents = new Element();
        $this->contents_last = &$this->contents;
        parent::__construct();
    }

    function parse(&$lines): void
    {
        $this->last = &$this;
        $matches = array();

        while (!empty($lines)) {
            $line = array_shift($lines);

            // Escape comments
            if (str_starts_with($line, '//'))
                continue;

            // 			// Extend TITLE by miko
            // 			if (preg_match('/^(TITLE):(.*)$/',$line,$matches))
            // 			{
            // 				global $newtitle, $newbase;
            // 				if ($newbase == '') {
            // 					// $newbase = trim($matches[2]);
            // 					$newbase = convert_html($matches[2]);
            // 					$newbase = strip_htmltag($newbase);
            // 					//$newbase = trim($newbase);
            // 					$newtitle = trim($newbase);
            // 					// For BugTrack/132.
            // 					// $newtitle = htmlspecialchars($newbase);
            // 					//$newtitle = str_replace('&amp;','&',htmlspecialchars($newbase));
            // 				}
            // 				continue;
            // 			}

            if (preg_match('/^(LEFT|CENTER|RIGHT):(.*)$/', $line, $matches)) {
                // <div style="text-align:...">
                $this->last = &$this->last->add(new Align(strtolower($matches[1])));
                if ($matches[2] == '')
                    continue;
                $line = $matches[2];
            }

            $line = rtrim($line, "\r\n");

            // Empty
            if ($line == '') {
                $this->last = &$this;
                continue;
            }

            // Horizontal Rule
            if (str_starts_with($line, '----')) {
                $this->insert(new HRule($this, $line));
                continue;
            }

            // Multiline-enabled block plugin
            if (!PKWKEXP_DISABLE_MULTILINE_PLUGIN_HACK && preg_match('/^#[^{]+(\{\{+)\s*$/', $line, $matches)) {
                $len = strlen($matches[1]);
                $line .= "\r"; // Delimiter
                while (!empty($lines)) {
                    $next_line = rtrim(array_shift($lines), "\n\r");
                    if (preg_match('/}{' . $len . '}/', $next_line)) {
                        $line .= $next_line;
                        break;
                    } else {
                        $line .= $next_line . "\r"; // Delimiter
                    }
                }
            }

            // The first character
            $head = $line[0];

            // Heading
            if ($head == '*') {
                $this->insert(new Heading($this, $line));
                continue;
            }

            // Pre
            if ($head == ' ' || $head == "\t") {
                $this->last = &$this->last->add(new Pre($this, $line));
                continue;
            }

            // 			// CPre
            // 			if (substr($line,0,2) == '# ' or substr($line,0,2) == "#\t") {
            // 				$this->last = &$this->last->add(new CPre($this,$line));
            // 				continue;
            // 			}

            // Line Break
            if (str_ends_with($line, '~'))
                $line = substr($line, 0, -1) . "\r";

            // Other Character
            if (isset($this->classes[$head])) {
                $classname = $this->classes[$head];
                $this->last = &$this->last->add(new $classname($this, $line));
                continue;
            }

            // Other Character
            if (isset($this->factories[$head])) {
                $factoryname = 'Factory_' . $this->factories[$head];
                $this->last = &$this->last->add($factoryname($this, $line));
                continue;
            }

            // Default
            $this->last = &$this->last->add(Factory_Inline($line));
        }
    }

    function getAnchor($text, $level): array
    {
        global $top, $_symbol_anchor;

        // Heading id (auto-generated)
        $autoid = 'content_' . $this->id . '_' . $this->count;
        $this->count++;

        // Heading id (specified by users)
        $id = make_heading($text, false); // Cut fixed-anchor from $text
        if ($id == '') {
            // Not specified
            $id = &$autoid;
            $anchor = '';
        } else {
            $anchor = '&aname(' . $id . ',super,full,nouserselect){' . $_symbol_anchor . '};';
        }
        $text = trim($text);

        // Add 'page contents' link to its heading
        $this->contents_last = &$this->contents_last->add(new Contents_UList($text, $level, $id));
        // Add heading
        return array($text . $anchor, $this->count > 1 ? ("\n" . $top) : '', $autoid);
    }

    function &insert(&$obj)
    {
        if (is_a($obj, 'Inline'))
            $obj = &$obj->toPara();
        return parent::insert($obj);
    }

    function toString(): string
    {
        global $vars;

        $text = parent::toString();

        // #contents
        $text = preg_replace_callback('/<#_contents_>/', array(&$this, 'replace_contents'), $text);

        return $text . "\n";
    }

    function replace_contents($arr): string
    {
        return (
            '<div class="contents">' .
            "\n" .
            '<a id="contents_' .
            $this->id .
            '"></a>' .
            "\n" .
            $this->contents->toString() .
            "\n" .
            '</div>' .
            "\n"
        );
    }
}

class Contents_UList extends ListContainer
{
    function Contents_UList($text, $level, $id): void
    {
        $this->__construct($text, $level, $id);
    }

    function __construct($text, $level, $id)
    {
        // Reformatting $text
        // A line started with "\n" means "preformatted" ... X(
        make_heading($text);
        $text = "\n" . '<a href="#' . $id . '">' . $text . '</a>' . "\n";
        parent::__construct('ul', 'li', '-', str_repeat('-', $level));
        $this->insert(Factory_Inline($text));
    }

    function setParent(&$parent): void
    {
        parent::setParent($parent);
        $step = $this->level;
        if (isset($parent->parent) && is_a($parent->parent, 'ListContainer')) {
            $step -= $parent->parent->level;
        }
        $indent_level = $step == $this->level ? 1 : $step;
        $this->style = sprintf(pkwk_list_attrs_template(), $this->level, $indent_level);
    }
}
