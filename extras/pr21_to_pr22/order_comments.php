<?php

/*
  $Id: order_comments.php,v 1.1 2006/03/27 08:39:37 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

if (!$_POST['DB_SERVER']) {
    ?>
    <html>
    <head>
        <title>osCommerce Preview Release 2.2 Database Update Script</title>
        <style type=text/css><!--
            TD, P, BODY {
                font-family: Verdana, Arial, sans-serif;
                font-size: 14px;
                color: #000000;
            }

            /
            /
            --></style>
    </head>
    <body>
    <p>
        <b>osCommerce Preview Release 2.2 Database Update Script</b>
    <p>This script moves the order comments from the orders table to order_status_history table
    <form name="database" action="<?php echo basename($PHP_SELF); ?>" method="post">
        <table border="0" cellspacing="2" cellpadding="2">
            <tr>
                <td colspan="2"><b>Database Server Information</b></td>
            </tr>
            <tr>
                <td>Server:</td>
                <td><input type="text" name="DB_SERVER"> <small>(eg, 192.168.0.1)</small></td>
            </tr>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="DB_SERVER_USERNAME"> <small>(eg, root)</small></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="text" name="DB_SERVER_PASSWORD"> <small>(eg, bee)</small></td>
            </tr>
            <tr>
                <td>Database:</td>
                <td><input type="text" name="DB_DATABASE"> <small>(eg, catalog)</small></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="submit" value="Submit"></td>
            </tr>
        </table>
    </form>
    </body>
    </html>
    <?php
    exit;
}

function tep_db_connect()
{
    global $db_link, $_POST;

    $db_link = mysql_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD']);

    if ($db_link) {
        mysqli_select_db($GLOBALS['xoopsDB']->conn, $_POST['DB_DATABASE']);
    }

    return $db_link;
}

function tep_db_error($query, $errno, $error)
{
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_query($db_query)
{
    global $db_link;

    $result = $GLOBALS['xoopsDB']->queryF($db_query, $db_link) or tep_db_error($db_query, $GLOBALS['xoopsDB']->errno(), $GLOBALS['xoopsDB']->error());

    return $result;
}

function tep_db_fetch_array($db_query)
{
    $result = $GLOBALS['xoopsDB']->fetchBoth($db_query);

    return $result;
}

tep_db_connect() || die('Unable to connect to database server!');

tep_db_query('ALTER TABLE orders_status_history DROP old_value');
tep_db_query('ALTER TABLE orders_status_history ADD comments TEXT');
tep_db_query("ALTER TABLE orders_status_history CHANGE new_value orders_status_id INT(5) DEFAULT '0' NOT NULL");
$orders_query = tep_db_query("SELECT orders_id, date_purchased, comments FROM orders WHERE comments <> ''");
while (false !== ($order = tep_db_fetch_array($orders_query))) {
    tep_db_query("insert into orders_status_history (orders_id, orders_status_id, date_added, comments) values ('" . $order['orders_id'] . "', '1', '" . $order['date_purchased'] . "', '" . addslashes($order['comments']) . "')");
}
tep_db_query('ALTER TABLE orders DROP comments');

?>

Done!
