<?php
/*
  $Id: shipping.php,v 1.1 2006/03/27 09:08:12 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

class shipping
{
    public $modules;

    // class constructor

    public function __construct($module = '')
    {
        global $language, $PHP_SELF;

        if (defined('MODULE_SHIPPING_INSTALLED') && tep_not_null(MODULE_SHIPPING_INSTALLED)) {
            $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);

            $include_modules = [];

            if ((tep_not_null($module)) && (in_array(mb_substr($module['id'], 0, mb_strpos($module['id'], '_')) . '.' . mb_substr($PHP_SELF, (mb_strrpos($PHP_SELF, '.') + 1)), $this->modules, true))) {
                $include_modules[] = ['class' => mb_substr($module['id'], 0, mb_strpos($module['id'], '_')), 'file' => mb_substr($module['id'], 0, mb_strpos($module['id'], '_')) . '.' . mb_substr($PHP_SELF, (mb_strrpos($PHP_SELF, '.') + 1))];
            } else {
                reset($this->modules);

                while (list(, $value) = each($this->modules)) {
                    $class = mb_substr($value, 0, mb_strrpos($value, '.'));

                    $include_modules[] = ['class' => $class, 'file' => $value];
                }
            }

            for ($i = 0, $n = count($include_modules); $i < $n; $i++) {
                include DIR_WS_LANGUAGES . $language . '/modules/shipping/' . $include_modules[$i]['file'];

                include DIR_WS_MODULES . 'shipping/' . $include_modules[$i]['file'];

                $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class']();
            }
        }
    }

    public function quote($method = '', $module = '')
    {
        global $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes;

        $quotes_array = [];

        if (is_array($this->modules)) {
            $shipping_quoted = '';

            $shipping_num_boxes = 1;

            $shipping_weight = $total_weight;

            if ($shipping_weight * SHIPPING_BOX_PADDING / 100 <= SHIPPING_BOX_WEIGHT) {
                $shipping_weight = $shipping_weight + SHIPPING_BOX_WEIGHT;
            } else {
                $shipping_weight = $shipping_weight + ($shipping_weight * SHIPPING_BOX_PADDING / 100);
            }

            if ($shipping_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
                $shipping_num_boxes = ceil($shipping_weight / SHIPPING_MAX_WEIGHT);

                $shipping_weight = $shipping_weight / $shipping_num_boxes;
            }

            $include_quotes = [];

            reset($this->modules);

            while (list(, $value) = each($this->modules)) {
                $class = mb_substr($value, 0, mb_strrpos($value, '.'));

                if (tep_not_null($module)) {
                    if (($module == $class) && ($GLOBALS[$class]->enabled)) {
                        $include_quotes[] = $class;
                    }
                } elseif ($GLOBALS[$class]->enabled) {
                    $include_quotes[] = $class;
                }
            }

            $size = count($include_quotes);

            for ($i = 0; $i < $size; $i++) {
                $quotes = $GLOBALS[$include_quotes[$i]]->quote($method);

                if (is_array($quotes)) {
                    $quotes_array[] = $quotes;
                }
            }
        }

        return $quotes_array;
    }

    public function cheapest()
    {
        if (is_array($this->modules)) {
            $rates = [];

            reset($this->modules);

            while (list(, $value) = each($this->modules)) {
                $class = mb_substr($value, 0, mb_strrpos($value, '.'));

                if ($GLOBALS[$class]->enabled) {
                    $quotes = $GLOBALS[$class]->quotes;

                    for ($i = 0, $n = count($quotes['methods']); $i < $n; $i++) {
                        if (isset($quotes['methods'][$i]['cost']) && tep_not_null($quotes['methods'][$i]['cost'])) {
                            $rates[] = [
                                'id' => $quotes['id'] . '_' . $quotes['methods'][$i]['id'],
                                'title' => $quotes['module'] . ' (' . $quotes['methods'][$i]['title'] . ')',
                                'cost' => $quotes['methods'][$i]['cost'],
                            ];
                        }
                    }
                }
            }

            $cheapest = false;

            for ($i = 0, $n = count($rates); $i < $n; $i++) {
                if (is_array($cheapest)) {
                    if ($rates[$i]['cost'] < $cheapest['cost']) {
                        $cheapest = $rates[$i];
                    }
                } else {
                    $cheapest = $rates[$i];
                }
            }

            return $cheapest;
        }
    }
}
