<?php

/*
  $Id: update.php,v 1.1 2006/03/27 08:39:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if (!$_POST['DB_SERVER']) {
    ?>
    <html>
    <head>
        <title>osCommerce 2.2-CVS Orders Update Script</title>
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
        <b>osCommerce 2.2-CVS Orders Update Script</b>
    <p>
        This script updates inserts the order total information into the new
        orders_total table, which takes advantage of the new order_total modules.
    <p>
    <form name="database" action="update.php" method="post">
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
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top">orders_total Table:</td>
                <td><input type="text" name="OT_TABLE" value="orders_total"> <small>(eg, orders_total)</small><br><small>This table is dropped, created, then filled</small></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2"><input type="checkbox" name="DISPLAY_PRICES_WITH_TAX"> <b>Display Prices With Tax Included</b><br><small>Should the tax be added to the SubTotal? (the tax amount is still displayed)</small></td>
            </tr>
            <tr>
                <td colspan="2"><input type="checkbox" name="DISPLAY_MULTIPLE_TAXES"> <b>Display Multiple Tax Groups</b><br><small>If more than one tax rate is used, display the individual values, or as one global tax value?</small></td>
            </tr>
            <tr>
                <td colspan="2"><input type="checkbox" name="DISPLAY_SHIPPING"> <b>Display No/Free Shipping Charges</b><br><small>Display the shipping value if it equals $0.00?</small></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td>Sub-Total Text:</td>
                <td><input type="text" name="OT_SUBTOTAL" value="Sub-Total:"> <small>(eg, Sub-Total:)</small></td>
            </tr>
            <tr>
                <td>Tax Text:</td>
                <td><input type="text" name="OT_TAX" value="Tax:"> <small>(eg, Tax:)</small></td>
            </tr>
            <tr>
                <td>Multiple Tax Groups Text:</td>
                <td><input type="text" name="OT_TAX_MULTIPLE" value="Tax (%s):"> <small>(eg, Tax (16%):)</small></td>
            </tr>
            <tr>
                <td>Shipping Text:</td>
                <td><input type="text" name="OT_SHIPPING" value="Shipping:"> <small>(eg, Shipping:)</small></td>
            </tr>
            <tr>
                <td>Total Text:</td>
                <td><input type="text" name="OT_TOTAL" value="Total:"> <small>(eg, Total:)</small></td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
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

function tep_db_connect($link = 'db_link')
{
    global $_POST, $$link;

    $$link = mysql_connect($_POST['DB_SERVER'], $_POST['DB_SERVER_USERNAME'], $_POST['DB_SERVER_PASSWORD']);

    if ($$link) {
        mysqli_select_db($GLOBALS['xoopsDB']->conn, $_POST['DB_DATABASE']);
    }

    return $$link;
}

function tep_db_error($query, $errno, $error)
{
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_query($query, $link = 'db_link')
{
    global $$link;

    $result = $GLOBALS['xoopsDB']->queryF($query, $$link) or tep_db_error($query, $GLOBALS['xoopsDB']->errno(), $GLOBALS['xoopsDB']->error());

    return $result;
}

function tep_db_fetch_array($db_query)
{
    return $GLOBALS['xoopsDB']->fetchBoth($db_query, MYSQL_ASSOC);
}

function tep_db_num_rows($db_query)
{
    return $GLOBALS['xoopsDB']->getRowsNum($db_query);
}

function tep_currency_format($number, $calculate_currency_value = true, $currency_code = DEFAULT_CURRENCY, $value = '')
{
    $currency_query = tep_db_query("select symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from currencies where code = '" . $currency_code . "'");

    $currency = tep_db_fetch_array($currency_query);

    if (true === $calculate_currency_value) {
        if (3 == mb_strlen($currency_code)) {
            if ($value) {
                $rate = $value;
            } else {
                $rate = $currency['value'];
            }
        } else {
            $rate = 1;
        }

        $number2currency = $currency['symbol_left'] . number_format(($number * $rate), $currency['decimal_places'], $currency['decimal_point'], $currency['thousands_point']) . $currency['symbol_right'];
    } else {
        $number2currency = $currency['symbol_left'] . number_format($number, $currency['decimal_places'], $currency['decimal_point'], $currency['thousands_point']) . $currency['symbol_right'];
    }

    return $number2currency;
}

function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES)
{
    if (mb_strpos($value, '.')) {
        $loop = true;

        while ($loop) {
            if ('0' == mb_substr($value, -1)) {
                $value = mb_substr($value, 0, -1);
            } else {
                $loop = false;

                if ('.' == mb_substr($value, -1)) {
                    $value = mb_substr($value, 0, -1);
                }
            }
        }
    }

    if ($padding > 0) {
        if ($decimal_pos = mb_strpos($value, '.')) {
            $decimals = mb_strlen(mb_substr($value, ($decimal_pos + 1)));

            for ($i = $decimals; $i < $padding; $i++) {
                $value .= '0';
            }
        } else {
            $value .= '.';

            for ($i = 0; $i < $padding; $i++) {
                $value .= '0';
            }
        }
    }

    return $value;
}

tep_db_connect() || die('Unable to connect to database server!');

if (mb_strlen($_POST['OT_TABLE']) > 0) {
    tep_db_query('drop table if exists ' . $_POST['OT_TABLE']);

    tep_db_query(
        'create table '
        . $_POST['OT_TABLE']
        . ' ( orders_total_id int unsigned not null auto_increment, orders_id int not null, title varchar(255) not null, text varchar(255) not null, value decimal(8,2) not null, class varchar(32) not null, sort_order int not null, primary key (orders_total_id), key idx_orders_total_orders_id (orders_id))'
    );
}

$i = 0;
$orders_query = tep_db_query('SELECT orders_id, shipping_method, shipping_cost, currency, currency_value FROM orders');
while (false !== ($orders = tep_db_fetch_array($orders_query))) {
    $o = [];

    $total_cost = 0;

    $o['id'] = $orders['orders_id'];

    $o['shipping_method'] = $orders['shipping_method'];

    $o['shipping_cost'] = $orders['shipping_cost'];

    $o['currency'] = $orders['currency'];

    $o['currency_value'] = $orders['currency_value'];

    $o['tax'] = 0;

    $orders_products_query = tep_db_query("select final_price, products_tax, products_quantity from orders_products where orders_id = '" . $orders['orders_id'] . "'");

    while (false !== ($orders_products = tep_db_fetch_array($orders_products_query))) {
        $o['products'][$i]['final_price'] = $orders_products['final_price'];

        $o['products'][$i]['qty'] = $orders_products['products_quantity'];

        $o['products'][$i]['tax_groups'][(string)($orders_products['products_tax'])] += $orders_products['products_tax'] / 100 * ($orders_products['final_price'] * $orders_products['products_quantity']);

        $o['tax'] += $orders_products['products_tax'] / 100 * ($orders_products['final_price'] * $orders_products['products_quantity']);

        $total_cost += ($o['products'][$i]['final_price'] * $o['products'][$i]['qty']);
    }

    if ('on' == $_POST['DISPLAY_PRICES_WITH_TAX']) {
        $subtotal_text = tep_currency_format($total_cost + $o['tax'], true, $o['currency'], $o['currency_value']);

        $subtotal_value = $total_cost + $o['tax'];
    } else {
        $subtotal_text = tep_currency_format($total_cost, true, $o['currency'], $o['currency_value']);

        $subtotal_value = $total_cost;
    }

    tep_db_query('insert into ' . $_POST['OT_TABLE'] . " (orders_total_id, orders_id, title, text, value, class, sort_order) values ('', '" . $o['id'] . "', '" . $_POST['OT_SUBTOTAL'] . "', '" . $subtotal_text . "', '" . $subtotal_value . "', 'ot_subtotal', '1')");

    if ('on' == $_POST['DISPLAY_MULTIPLE_TAXES']) {
        @reset($o['products'][$i]['tax_groups']);

        while (@list($key, $value) = each($o['products'][$i]['tax_groups'])) {
            $tax_text = tep_currency_format($value, true, $o['currency'], $o['currency_value']);

            $tax_value = $value;

            tep_db_query(
                'insert into '
                . $_POST['OT_TABLE']
                . " (orders_total_id, orders_id, title, text, value, class, sort_order) values ('', '"
                . $o['id']
                . "', '"
                . sprintf($_POST['OT_TAX_MULTIPLE'], tep_display_tax_value($key) . '%')
                . "', '"
                . $tax_text
                . "', '"
                . $tax_value
                . "', 'ot_tax', '2')"
            );
        }
    } else {
        $tax_text = tep_currency_format($o['tax'], true, $o['currency'], $o['currency_value']);

        $tax_value = $o['tax'];

        tep_db_query('insert into ' . $_POST['OT_TABLE'] . " (orders_total_id, orders_id, title, text, value, class, sort_order) values ('', '" . $o['id'] . "', '" . $_POST['OT_TAX'] . "', '" . $tax_text . "', '" . $tax_value . "', 'ot_tax', '2')");
    }

    if (mb_strlen($o['shipping_method']) < 1) {
        $o['shipping_method'] = $_POST['OT_SHIPPING'];
    } else {
        $o['shipping_method'] .= ':';
    }

    if ('on' == $_POST['DISPLAY_SHIPPING']) {
        $shipping_text = tep_currency_format($o['shipping_cost'], true, $o['currency'], $o['currency_value']);

        $shipping_value = $o['shipping_cost'];

        tep_db_query('insert into ' . $_POST['OT_TABLE'] . " (orders_total_id, orders_id, title, text, value, class, sort_order) values ('', '" . $o['id'] . "', '" . $o['shipping_method'] . "', '" . $shipping_text . "', '" . $shipping_value . "', 'ot_shipping', '3')");
    } elseif ($o['shipping_cost'] > 0) {
        $shipping_text = tep_currency_format($o['shipping_cost'], true, $o['currency'], $o['currency_value']);

        $shipping_value = $o['shipping_cost'];

        tep_db_query('insert into ' . $_POST['OT_TABLE'] . " (orders_total_id, orders_id, title, text, value, class, sort_order) values ('', '" . $o['id'] . "', '" . $o['shipping_method'] . "', '" . $shipping_text . "', '" . $shipping_value . "', 'ot_shipping', '3')");
    }

    $total_text = tep_currency_format($total_cost + $o['tax'] + $o['shipping_cost'], true, $o['currency'], $o['currency_value']);

    $total_value = $total_cost + $o['tax'] + $o['shipping_cost'];

    tep_db_query('insert into ' . $_POST['OT_TABLE'] . " (orders_total_id, orders_id, title, text, value, class, sort_order) values ('', '" . $o['id'] . "', '" . $_POST['OT_TOTAL'] . "', '" . $total_text . "', '" . $total_value . "', 'ot_total', '4')");

    $i++;
}
?>
Done!
