<?php
/*
  $Id: order_total.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class order_total
{
    public $modules;

    // class constructor

    public function __construct()
    {
        global $language;

        if (defined('MODULE_ORDER_TOTAL_INSTALLED') && tep_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {
            $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);

            reset($this->modules);

            while (list(, $value) = each($this->modules)) {
                include DIR_WS_LANGUAGES . $language . '/modules/order_total/' . $value;

                include DIR_WS_MODULES . 'order_total/' . $value;

                $class = mb_substr($value, 0, mb_strrpos($value, '.'));

                $GLOBALS[$class] = new $class();
            }
        }
    }

    public function process()
    {
        $order_total_array = [];

        if (is_array($this->modules)) {
            reset($this->modules);

            while (list(, $value) = each($this->modules)) {
                $class = mb_substr($value, 0, mb_strrpos($value, '.'));

                if ($GLOBALS[$class]->enabled) {
                    $GLOBALS[$class]->process();

                    for ($i = 0, $n = count($GLOBALS[$class]->output); $i < $n; $i++) {
                        if (tep_not_null($GLOBALS[$class]->output[$i]['title']) && tep_not_null($GLOBALS[$class]->output[$i]['text'])) {
                            $order_total_array[] = [
                                'code' => $GLOBALS[$class]->code,
                                'title' => $GLOBALS[$class]->output[$i]['title'],
                                'text' => $GLOBALS[$class]->output[$i]['text'],
                                'value' => $GLOBALS[$class]->output[$i]['value'],
                                'sort_order' => $GLOBALS[$class]->sort_order,
                            ];
                        }
                    }
                }
            }
        }

        return $order_total_array;
    }

    public function output()
    {
        $output_string = '';

        if (is_array($this->modules)) {
            reset($this->modules);

            while (list(, $value) = each($this->modules)) {
                $class = mb_substr($value, 0, mb_strrpos($value, '.'));

                if ($GLOBALS[$class]->enabled) {
                    $size = count($GLOBALS[$class]->output);

                    for ($i = 0; $i < $size; $i++) {
                        $output_string .= '              <tr>'
                                          . "\n"
                                          . '                <td align="right" class="main">'
                                          . $GLOBALS[$class]->output[$i]['title']
                                          . '</td>'
                                          . "\n"
                                          . '                <td align="right" class="main">'
                                          . $GLOBALS[$class]->output[$i]['text']
                                          . '</td>'
                                          . "\n"
                                          . '              </tr>';
                    }
                }
            }
        }

        return $output_string;
    }
}
