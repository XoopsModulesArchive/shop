<?php

/*
  $Id: localization.php,v 1.1 2006/03/27 09:06:04 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

function quote_oanda_currency($code, $base = DEFAULT_CURRENCY)
{
    $page = file('http://www.oanda.com/convert/fxdaily?value=1&redirected=1&exch=' . $code . '&format=CSV&dest=Get+Table&sel_list=' . $base);

    $match = [];

    preg_match('/(.+),(\w{3}),([0-9.]+),([0-9.]+)/i', implode('', $page), $match);

    if (count($match) > 0) {
        return $match[3];
    }

    return false;
}

function quote_xe_currency($to, $from = DEFAULT_CURRENCY)
{
    $page = file('http://www.xe.net/ucc/convert.cgi?Amount=1&From=' . $from . '&To=' . $to);

    $match = [];

    preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', implode('', $page), $match);

    if (count($match) > 0) {
        return $match[1];
    }

    return false;
}
