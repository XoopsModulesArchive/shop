<?php
/*
  $Id: sessions.php,v 1.1 2006/03/27 09:08:28 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
        $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name)
    {
        return true;
    }

    function _sess_close()
    {
        return true;
    }

    function _sess_read($key)
    {
        $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
        $value       = tep_db_fetch_array($value_query);

        return $value['value'] ?? false;
    }

    function _sess_write($key, $val)
    {
        global $SESS_LIFE;

        $expiry = time() + $SESS_LIFE;
        $value  = $val;

        $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
        $check       = tep_db_fetch_array($check_query);

        if ($check['total'] > 0) {
            return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
        } else {
            return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
        }
    }

    function _sess_destroy($key)
    {
        return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime)
    {
        tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

        return true;
    }

    session_set_saveHandler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
}

function tep_session_start()
{
    return session_start();
}

function tep_session_register($variable)
{
    global $session_started;

    if ($session_started === true) {
        return session_register($variable);
    } else {
        return false;
    }
}

function tep_isset(\<?php
/*
  $Id: sessions.php,v 1.1 2006/03/27 09:08:28 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
        $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name)
    {
        return true;
    }

    function _sess_close()
    {
        return true;
    }

    function _sess_read($key)
    {
        $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
        $value       = tep_db_fetch_array($value_query);

        return $value['value'] ?? false;
    }

    function _sess_write($key, $val)
    {
        global $SESS_LIFE;

        $expiry = time() + $SESS_LIFE;
        $value  = $val;

        $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
        $check       = tep_db_fetch_array($check_query);

        if ($check['total'] > 0) {
            return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
        } else {
            return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
        }
    }

    function _sess_destroy($key)
    {
        return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime)
    {
        tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

        return true;
    }

    session_set_saveHandler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
}

function tep_session_start()
{
    return session_start();
}

function tep_session_register($variable)
{
    global $session_started;

    if ($session_started === true) {
        return session_register($variable);
    } else {
        return false;
    }
}

function tep_session_is_registered($variable)
{
    return session_is_registered($variable);
}

function tep_session_unregister($variable)
{
    return session_unregister($variable);
}

function tep_session_id($sessid = '')
{
    if (!empty($sessid)) {
        return session_id($sessid);
    } else {
        return session_id();
    }
}

function tep_session_name($name = '')
{
    return 'xoops_session';
}

function tep_session_close()
{
    if (function_exists('session_close')) {
        return session_close();
    }
}

function tep_session_destroy()
{
    return session_destroy();
}

function tep_session_save_path($path = '')
{
    if (!empty($path)) {
        return session_save_path($path);
    } else {
        return session_save_path();
    }
}

function tep_session_recreate()
{
    if (PHP_VERSION >= 4.1) {
        $session_backup = $_SESSION;

        unset($_COOKIE[tep_session_name()]);

        tep_session_destroy();

        if (STORE_SESSIONS == 'mysql') {
            session_set_saveHandler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
        }

        tep_session_start();

        $_SESSION = $session_backup;
        unset($session_backup);
    }
}

?>
SESSION[$variable])
{
    return isset(\<?php
/*
  $Id: sessions.php,v 1.1 2006/03/27 09:08:28 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

if (STORE_SESSIONS == 'mysql') {
    if (!$SESS_LIFE = get_cfg_var('session.gc_maxlifetime')) {
        $SESS_LIFE = 1440;
    }

    function _sess_open($save_path, $session_name)
    {
        return true;
    }

    function _sess_close()
    {
        return true;
    }

    function _sess_read($key)
    {
        $value_query = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "' and expiry > '" . time() . "'");
        $value       = tep_db_fetch_array($value_query);

        return $value['value'] ?? false;
    }

    function _sess_write($key, $val)
    {
        global $SESS_LIFE;

        $expiry = time() + $SESS_LIFE;
        $value  = $val;

        $check_query = tep_db_query("select count(*) as total from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
        $check       = tep_db_fetch_array($check_query);

        if ($check['total'] > 0) {
            return tep_db_query("update " . TABLE_SESSIONS . " set expiry = '" . tep_db_input($expiry) . "', value = '" . tep_db_input($value) . "' where sesskey = '" . tep_db_input($key) . "'");
        } else {
            return tep_db_query("insert into " . TABLE_SESSIONS . " values ('" . tep_db_input($key) . "', '" . tep_db_input($expiry) . "', '" . tep_db_input($value) . "')");
        }
    }

    function _sess_destroy($key)
    {
        return tep_db_query("delete from " . TABLE_SESSIONS . " where sesskey = '" . tep_db_input($key) . "'");
    }

    function _sess_gc($maxlifetime)
    {
        tep_db_query("delete from " . TABLE_SESSIONS . " where expiry < '" . time() . "'");

        return true;
    }

    session_set_saveHandler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
}

function tep_session_start()
{
    return session_start();
}

function tep_session_register($variable)
{
    global $session_started;

    if ($session_started === true) {
        return session_register($variable);
    } else {
        return false;
    }
}

function tep_session_is_registered($variable)
{
    return session_is_registered($variable);
}

function tep_session_unregister($variable)
{
    return session_unregister($variable);
}

function tep_session_id($sessid = '')
{
    if (!empty($sessid)) {
        return session_id($sessid);
    } else {
        return session_id();
    }
}

function tep_session_name($name = '')
{
    return 'xoops_session';
}

function tep_session_close()
{
    if (function_exists('session_close')) {
        return session_close();
    }
}

function tep_session_destroy()
{
    return session_destroy();
}

function tep_session_save_path($path = '')
{
    if (!empty($path)) {
        return session_save_path($path);
    } else {
        return session_save_path();
    }
}

function tep_session_recreate()
{
    if (PHP_VERSION >= 4.1) {
        $session_backup = $_SESSION;

        unset($_COOKIE[tep_session_name()]);

        tep_session_destroy();

        if (STORE_SESSIONS == 'mysql') {
            session_set_saveHandler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
        }

        tep_session_start();

        $_SESSION = $session_backup;
        unset($session_backup);
    }
}

?>
SESSION[$variable]);
}

function tep_session_unregister($variable)
{
    return session_unregister($variable);
}

function tep_session_id($sessid = '')
{
    if (!empty($sessid)) {
        return session_id($sessid);
    } else {
        return session_id();
    }
}

function tep_session_name($name = '')
{
    return 'xoops_session';
}

function tep_session_close()
{
    if (function_exists('session_close')) {
        return session_close();
    }
}

function tep_session_destroy()
{
    return session_destroy();
}

function tep_session_save_path($path = '')
{
    if (!empty($path)) {
        return session_save_path($path);
    } else {
        return session_save_path();
    }
}

function tep_session_recreate()
{
    if (PHP_VERSION >= 4.1) {
        $session_backup = $_SESSION;

        unset($_COOKIE[tep_session_name()]);

        tep_session_destroy();

        if (STORE_SESSIONS == 'mysql') {
            session_set_saveHandler('_sess_open', '_sess_close', '_sess_read', '_sess_write', '_sess_destroy', '_sess_gc');
        }

        tep_session_start();

        $_SESSION = $session_backup;
        unset($session_backup);
    }
}

?>
