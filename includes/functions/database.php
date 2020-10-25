<?php
/*
  $Id: database.php,v 1.1 2006/03/27 09:08:28 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link')
{
    global $$link;

    if (USE_PCONNECT == 'true') {
        $$link = mysql_pconnect($server, $username, $password);
    } else {
        $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) {
        mysqli_select_db($GLOBALS['xoopsDB']->conn, $database);
    }

    return $$link;
}

function tep_db_close($link = 'db_link')
{
    global $$link;

    return $GLOBALS['xoopsDB']->close($$link);
}

function tep_db_error($query, $errno, $error)
{
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_query($query, $link = 'db_link')
{
    global $$link;

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = $GLOBALS['xoopsDB']->queryF($query, $$link) or tep_db_error($query, $GLOBALS['xoopsDB']->errno(), $GLOBALS['xoopsDB']->error());

    if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
        $result_error = $GLOBALS['xoopsDB']->error();
        error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
}

function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link')
{
    reset($data);
    if ($action == 'insert') {
        $query = 'insert into ' . $table . ' (';
        while (list($columns, ) = each($data)) {
            $query .= $columns . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        while (list(, $value) = each($data)) {
            switch ((string)$value) {
                case 'now()':
                    $query .= 'now(), ';
                    break;
                case 'null':
                    $query .= 'null, ';
                    break;
                default:
                    $query .= '\'' . tep_db_input($value) . '\', ';
                    break;
            }
        }
        $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
        $query = 'update ' . $table . ' set ';
        while (list($columns, $value) = each($data)) {
            switch ((string)$value) {
                case 'now()':
                    $query .= $columns . ' = now(), ';
                    break;
                case 'null':
                    $query .= $columns .= ' = null, ';
                    break;
                default:
                    $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
                    break;
            }
        }
        $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
}

function tep_db_fetch_array($db_query)
{
    return $GLOBALS['xoopsDB']->fetchBoth($db_query, MYSQL_ASSOC);
}

function tep_db_num_rows($db_query)
{
    return $GLOBALS['xoopsDB']->getRowsNum($db_query);
}

function tep_db_data_seek($db_query, $row_number)
{
    return mysql_data_seek($db_query, $row_number);
}

function tep_db_insert_id()
{
    return $GLOBALS['xoopsDB']->getInsertId();
}

function tep_db_free_result($db_query)
{
    return $GLOBALS['xoopsDB']->freeRecordSet($db_query);
}

function tep_db_fetch_fields($db_query)
{
    return mysql_fetch_field($db_query);
}

function tep_db_output($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
}

function tep_db_input($string)
{
    return addslashes($string);
}

function tep_db_prepare_input($string)
{
    if (is_string($string)) {
        return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
        reset($string);
        while (list($key, $value) = each($string)) {
            $string[$key] = tep_db_prepare_input($value);
        }
        return $string;
    } else {
        return $string;
    }
}
