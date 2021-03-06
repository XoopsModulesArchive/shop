<?php

/*
  $Id: currencies.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

////
// Class to handle currencies
// TABLES: currencies
class currencies
{
    public $currencies;

    // class constructor

    public function __construct()
    {
        $this->currencies = [];

        $currencies_query = tep_db_query('select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from ' . TABLE_CURRENCIES);

        while (false !== ($currencies = tep_db_fetch_array($currencies_query))) {
            $this->currencies[$currencies['code']] = [
                'title' => $currencies['title'],
'symbol_left' => $currencies['symbol_left'],
'symbol_right' => $currencies['symbol_right'],
'decimal_point' => $currencies['decimal_point'],
'thousands_point' => $currencies['thousands_point'],
'decimal_places' => $currencies['decimal_places'],
'value' => $currencies['value'],
            ];
        }
    }

    // class methods

    public function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '')
    {
        global $currency;

        if (empty($currency_type)) {
            $currency_type = $currency;
        }

        if (true === $calculate_currency_value) {
            $rate = (tep_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];

            $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(
                tep_round($number * $rate, $this->currencies[$currency_type]['decimal_places']),
                $this->currencies[$currency_type]['decimal_places'],
                $this->currencies[$currency_type]['decimal_point'],
                $this->currencies[$currency_type]['thousands_point']
            ) . $this->currencies[$currency_type]['symbol_right'];

            // if the selected currency is in the european euro-conversion and the default currency is euro,

            // the currency will displayed in the national currency and euro currency

            if ((DEFAULT_CURRENCY == 'EUR')
                && ('DEM' == $currency_type
                    || 'BEF' == $currency_type || 'LUF' == $currency_type || 'ESP' == $currency_type || 'FRF' == $currency_type || 'IEP' == $currency_type || 'ITL' == $currency_type || 'NLG' == $currency_type || 'ATS' == $currency_type || 'PTE' == $currency_type || 'FIM' == $currency_type
                    || 'GRD' == $currency_type)) {
                $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
            }
        } else {
            $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(
                tep_round($number, $this->currencies[$currency_type]['decimal_places']),
                $this->currencies[$currency_type]['decimal_places'],
                $this->currencies[$currency_type]['decimal_point'],
                $this->currencies[$currency_type]['thousands_point']
            ) . $this->currencies[$currency_type]['symbol_right'];
        }

        return $format_string;
    }

    public function is_set($code)
    {
        if (isset($this->currencies[$code]) && tep_not_null($this->currencies[$code])) {
            return true;
        }

        return false;
    }

    public function get_value($code)
    {
        return $this->currencies[$code]['value'];
    }

    public function get_decimal_places($code)
    {
        return $this->currencies[$code]['decimal_places'];
    }

    public function display_price($products_price, $products_tax, $quantity = 1)
    {
        return $this->format(tep_add_tax($products_price, $products_tax) * $quantity);
    }
}
