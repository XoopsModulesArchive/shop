<?php

function b_shop_currencies()
{
    global $currency, $currencies, $xoopsConfig, $_GET, $PHP_SELF, $languages_id;

    $block = [];

    $block['title'] = BOX_HEADING_CURRENCIES;

    $block['datum'] = '2004-04-03';

    $block['content'] = "<form name=\"currencies\" action=\"$PHP_SELF\" method=\"get\">";

    $block['content'] .= '<select name="currency" onChange="this.form.submit();">';

    reset($currencies->currencies);

    $currencies_array = [];

    while (list($key, $value) = each($currencies->currencies)) {
        $block['content'] .= "<option value=\"$key\"";

        if ($key == $currency) {
            $block['content'] .= ' selected';
        }

        $block['content'] .= '>' . $value['title'] . '</option>';
    }

    reset($_GET);

    while (list($key, $value) = each($_GET)) {
        if (('currency' != $key) && ($key != tep_session_name()) && ('x' != $key) && ('y' != $key)) {
            $block['content'] .= "<input type=\"hidden\" name=\"$key\" value=\"$value\">";
        }
    }

    $block['content'] .= '</select></form>';

    return $block;
}
