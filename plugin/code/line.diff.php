<?php

/**
 * diff キーワード定義ファイル
 * 行指向モード用
 */

$switchHash['!'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // changed
$switchHash['|'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // changed
$switchHash['+'] = PLUGIN_CODE_IDENTIFIRE_WORD; // added
$switchHash['>'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // added
$switchHash[')'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // added
$switchHash['-'] = PLUGIN_CODE_IDENTIFIRE_WORD; // removed
$switchHash['<'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // removed
$switchHash['('] = PLUGIN_CODE_IDENTIFIRE_CHAR; // removed
$switchHash['*'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // control
$switchHash['\\'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // control
$switchHash['@'] = PLUGIN_CODE_IDENTIFIRE_CHAR; // control

$mkoutline = $option['outline'] = false; // アウトラインモード不可
$mkcomment = $option['comment'] = false; // コメント無し
$linemode = true; // 行内を解析しない

//
$code_identifire = array(
    '-' => array(
        '---',
    ),
    '+' => array(
        '+++',
    ),
);

// コメント定義
$switchHash['#'] = PLUGIN_CODE_COMMENT;

// コメントは # から改行まで

$code_css = array(
    'changed', //
    'added', //
    'removed', //
    'system', //
);

$code_keyword = array(
    '!' => 1,
    '|' => 1,
    '+' => 2,
    '>' => 2,
    ')' => 2,
    '/' => 2,
    '-' => 3,
    '<' => 3,
    '(' => 3,
    '\\' => 3,
    '*' => 4,
    '\\' => 4,
    '@' => 4,
    '---' => 4,
    '+++' => 4,
);
