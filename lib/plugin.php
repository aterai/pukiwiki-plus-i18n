<?php

// PukiWiki - Yet another WikiWikiWeb clone.
// $Id: plugin.php,v 1.15.17 2008/01/13 23:29:00 upk Exp $
// Copyright (C)
//   2005-2006,2008 PukiWiki Plus! Team
//   2002-2005 PukiWiki Developers Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// Plugin related functions

const PKWK_PLUGIN_CALL_TIME_LIMIT = 768;

// Set global variables for plugins
function set_plugin_messages($messages)
{
    foreach ($messages as $name => $val)
        if (!isset($GLOBALS[$name]))
            $GLOBALS[$name] = $val;
}

// Same as getopt for plugins
function get_plugin_option($args, &$params, $tolower = true, $separator = ':')
{
    if (empty($args)) {
        $params['_done'] = true;
        return true;
    }
    $keys = array_keys($params);

    foreach ($args as $val) {
        list($_key, $_val) = array_pad(explode($separator, $val, 2), 2, true);
        if ($tolower === true)
            $_key = strtolower($_key);
        $_key = trim($_key);
        if (is_string($_val))
            $_val = trim($_val);
        if (in_array($_key, $keys) && $params['_done'] !== true) {
            $params[$_key] = $_val; // Exist keys
        } elseif ($val != '') {
            $params['_args'][] = $val; // Not exist keys, in '_args'
            $params['_done'] = true;
        }
    }
    $params['_done'] = true;
    return true;
}

// Check arguments for plugins
function check_plugin_option($val, &$params, $tolower = true)
{
    if ($val != '') {
        if ($tolower === true)
            $_val = strtolower($val);
        foreach (array_keys($params) as $key) {
            if (strpos($key, $_val) === 0) {
                $params[$key] = true;
                return;
            }
        }
    }
    $params['_args'][] = $val;
}

// Check plugin limit
function limit_plugin($name)
{
    global $vars;
    static $count = array();

    $name = strtolower($name);
    if (!isset($count[$name]) || $name === 'epoch') {
        $count[$name] = 1;
    }
    if (++$count[$name] > PKWK_PLUGIN_CALL_TIME_LIMIT) {
        die(
            'Alert: plugin "' .
            htmlspecialchars($name, ENT_QUOTES, 'UTF-8') .
                '" was called over ' .
                PKWK_PLUGIN_CALL_TIME_LIMIT .
                ' times. SPAM or someting?<br />' .
                "\n" .
                '<a href="' .
                get_cmd_uri('edit', $vars['page']) .
                '">Try to edit this page</a><br />' .
                "\n" .
                '<a href="' .
                get_cmd_uri() .
                '">Return to frontpage</a>'
        );
    }
    return true;
}

// Check plugin '$name' is here
function exist_plugin($name)
{
    global $exclude_plugin, $plugin_lang_path;
    static $exist = array();

    $name = strtolower($name);

    // (plus!)added exclude plugin spec.
    if (in_array($name, $exclude_plugin)) {
        $exist[$name] = false;
        return false;
    }

    if (preg_match('/^\w{1,64}$/', $name)) {
        foreach (array(EXT_PLUGIN_DIR, PLUGIN_DIR) as $p_dir) {
            if (file_exists($p_dir . $name . '.inc.php')) {
                $plugin_lang_path[$name] = PLUGIN_DIR == $p_dir ? LANG_DIR : EXT_LANG_DIR;
                $exist[$name] = true;
                load_init_value($name);
                require_once $p_dir . $name . '.inc.php';
                return true;
            }
        }
    }
    $exist[$name] = false;
    return false;
}

// Check if plguin API exists
function exist_plugin_function($name, $func)
{
    if (function_exists($func)) {
        return limit_plugin($name);
    } elseif (exist_plugin($name) && function_exists($func)) {
        return limit_plugin($name);
    }
    return false;
}

// Check if plugin API 'action' exists
function exist_plugin_action($name)
{
    return exist_plugin_function($name, 'plugin_' . $name . '_action');
}

// Check if plugin API 'convert' exists
function exist_plugin_convert($name)
{
    return exist_plugin_function($name, 'plugin_' . $name . '_convert');
}

// Check if plugin API 'inline' exists
function exist_plugin_inline($name)
{
    return exist_plugin_function($name, 'plugin_' . $name . '_inline');
}

// Do init the plugin
function do_plugin_init($name)
{
    global $plugin_lang_path;
    static $checked = array();

    if (isset($checked[$name]))
        return $checked[$name];

    if (empty($plugin_lang_path[$name])) {
        bindtextdomain($name, LANG_DIR);
    } else {
        bindtextdomain($name, $plugin_lang_path[$name]);
    }
    bind_textdomain_codeset($name, SOURCE_ENCODING);
    $func = 'plugin_' . $name . '_init';
    if (function_exists($func)) {
        // TRUE or FALSE or NULL (return nothing)
        textdomain($name);
        $checked[$name] = call_user_func($func);
        textdomain(DOMAIN);
        if (!isset($checked[$name])) {
            $checked[$name] = true; // checked.
        }
    } else {
        $checked[$name] = true; // checked.
    }

    return $checked[$name];
}

// Call API 'action' of the plugin
function do_plugin_action($name)
{
    if (!exist_plugin_action($name))
        return array();

    if (do_plugin_init($name) === false)
        die_message('Plugin init failed: ' . $name);

    textdomain($name);
    $retvar = call_user_func('plugin_' . $name . '_action');
    textdomain(DOMAIN);

    // Insert a hidden field, supports idenrtifying text enconding
    if (PKWK_ENCODING_HINT != '')
        $retvar = preg_replace(
            '/(<form[^>]*>)(?!\n<div><input type="hidden" name="encode_hint")/',
            '$1' . "\n" . '<div><input type="hidden" name="encode_hint" value="' . PKWK_ENCODING_HINT . '" /></div>',
            $retvar,
        );

    return $retvar;
}

// Call API 'convert' of the plugin
function do_plugin_convert($name, $args = '')
{
    global $digest;

    if (do_plugin_init($name) === false)
        return '[Plugin init failed: ' . $name . ']';

    if (!PKWKEXP_DISABLE_MULTILINE_PLUGIN_HACK) {
        // Multiline plugin?
        $pos = strpos($args, "\r"); // "\r" is just a delimiter
        if ($pos !== false) {
            $body = substr($args, $pos + 1);
            $args = substr($args, 0, $pos);
        }
    }

    if ($args === '') {
        $aryargs = array(); // #plugin()
    } else {
        $aryargs = csv_explode(',', $args); // #plugin(A,B,C,D)
    }
    if (!PKWKEXP_DISABLE_MULTILINE_PLUGIN_HACK) {
        if (isset($body))
            $aryargs[] = &$body; // #plugin(){{body}}
    }

    $_digest = $digest;
    textdomain($name);
    $retvar = call_user_func_array('plugin_' . $name . '_convert', $aryargs);
    textdomain(DOMAIN);
    $digest = $_digest; // Revert

    if ($retvar === false) {
        return htmlspecialchars('#' . $name . ($args != '' ? ('(' . $args . ')') : ''), ENT_QUOTES, 'UTF-8');
    } else if (PKWK_ENCODING_HINT != '') {
        // Insert a hidden field, supports idenrtifying text enconding
        return preg_replace(
            '/(<form[^>]*>)(?!\n<div><input type="hidden" name="encode_hint")/',
            '$1' . "\n" . '<div><input type="hidden" name="encode_hint" value="' . PKWK_ENCODING_HINT . '" /></div>',
            $retvar,
        );
    } else {
        return $retvar;
    }
}

// Call API 'inline' of the plugin
function do_plugin_inline($name, $args, &$body)
{
    global $digest;

    if (do_plugin_init($name) === false)
        return '[Plugin init failed: ' . $name . ']';

    if ($args !== '') {
        $aryargs = csv_explode(',', $args);
    } else {
        $aryargs = array();
    }

    // NOTE: A reference of $body is always the last argument
    $aryargs[] = &$body; // func_num_args() != 0

    $_digest = $digest;
    textdomain($name);
    $retvar = call_user_func_array('plugin_' . $name . '_inline', $aryargs);
    textdomain(DOMAIN);
    $digest = $_digest; // Revert

    if ($retvar === false) {
        // Do nothing
        return htmlspecialchars('&' . $name . ($args ? ('(' . $args . ')') : '') . ';', ENT_QUOTES, 'UTF-8');
    } else {
        return $retvar;
    }
}

// Used Plugin?
function use_plugin($plugin, $lines): bool|string
{
    if (!is_array($lines)) {
        $delim = array("\r\n", "\r");
        $lines = str_replace($delim, "\n", $lines);
        $lines = explode("\n", $lines);
    }

    foreach ($lines as $line) {
        if (str_starts_with($line, '//'))
            continue;
        // Diff data
        if (str_starts_with($line, '+') || str_starts_with($line, '-')) {
            $line = substr($line, 1);
        }
        if (preg_match('/^[#|&]' . $plugin . '[^a-zA-Z]*$/', $line, $matches)) {
            return $matches[0];
        }
    }
    return false;
}
