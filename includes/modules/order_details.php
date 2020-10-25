<?php
/*
  $Id: order_details.php,v 1.1 2006/03/27 09:13:58 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- order_details -->
<?php
echo '  <tr>' . "\n";

$colspan = 3;

if (false !== strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
    $colspan++;
    echo '    <td align="center" class="smallText"><b>' . TABLE_HEADING_REMOVE . '</b></td>' . "\n";
}

echo '    <td align="center" class="tableHeading">' . TABLE_HEADING_QUANTITY . '</td>' . "\n";

if ((PRODUCT_LIST_MODEL > 0) && false !== strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
    $colspan++;
    echo '    <td class="tableHeading">' . TABLE_HEADING_MODEL . '</td>' . "\n";
}

echo '    <td class="tableHeading">' . TABLE_HEADING_PRODUCTS . '</td>' . "\n";

if (false === strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
    $colspan++;
    echo '    <td align="center" class="tableHeading">' . TABLE_HEADING_TAX . '</td>' . "\n";
}

echo '    <td align="right" class="tableHeading">' . TABLE_HEADING_TOTAL . '</td>' . "\n" . '  </tr>' . "\n" . '  <tr>' . "\n" . '    <td colspan="' . $colspan . '">' . tep_draw_separator() . '</td>' . "\n" . '  </tr>' . "\n";

for ($i = 0, $n = count($products); $i < $n; $i++) {
    echo '  <tr>' . "\n";

    // Delete box only for shopping cart
    if (false !== strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
        echo '    <td align="center" valign="top">' . tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']) . '</td>' . "\n";
    }

    // Quantity box or information as an input box or text
    if (false !== strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
        echo '    <td align="center" valign="top">' . tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . '</td>' . "\n";
    } else {
        echo '    <td align="center" valign="top" class ="main">' . $products[$i]['quantity'] . '</td>' . "\n";
    }

    // Model
    if ((PRODUCT_LIST_MODEL > 0) && false !== strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
        echo '    <td valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . $products[$i]['model'] . '</a></td>' . "\n";
    }

    // Product name, with or without link
    if (false !== strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
        echo '    <td valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '"><b>' . $products[$i]['name'] . '</b></a>';
    } else {
        echo '    <td valign="top" class="main"><b>' . $products[$i]['name'] . '</b>';
    }

    // Display marker if stock quantity insufficient
    if (false === strpos($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
        if (STOCK_CHECK == 'true') {
            echo $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
            if ($stock_check) {
                $any_out_of_stock = 1;
            }
        }
    }

    // Product options names
    $attributes_exist = ((isset($products[$i]['attributes'])) ? 1 : 0);

    if ($attributes_exist == 1) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
            echo '<br><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
    }

    echo '</td>' . "\n";

    // Tax (not in shopping cart, tax rate may be unknown)
    if (false === strpos($PHP_SELF, FILENAME_SHOPPING_CART)) {
        echo '    <td align="center" valign="top" class="main">' . number_format($products[$i]['tax'], TAX_DECIMAL_PLACES) . '%</td>' . "\n";
    }

    // Product price
    if (false === strpos($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
        echo '    <td align="right" valign="top" class="main"><b>' . $currencies->display_price($products[$i]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</b>';
    } else {
        echo '    <td align="right" valign="top" class="main"><b>' . $currencies->display_price($products[$i]['price'], $products[$i]['tax'], $products[$i]['quantity']) . '</b>';
    }

    // Product options prices
    if ($attributes_exist == 1) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
            if ($products[$i][$option]['options_values_price'] != 0) {
                if (false === strpos($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
                    echo '<br><small><i>' . $products[$i][$option]['price_prefix'] . $currencies->display_price($products[$i][$option]['options_values_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</i></small>';
                } else {
                    echo '<br><small><i>' . $products[$i][$option]['price_prefix'] . $currencies->display_price($products[$i][$option]['options_values_price'], $products[$i]['tax'], $products[$i]['quantity']) . '</i></small>';
                }
            } else {
                // Keep price aligned with corresponding option
                echo '<br><small><i>&nbsp;</i></small>';
            }
        }
    }

    echo '</td>' . "\n" . '  </tr>' . "\n";
}
?>
<!-- order_details_eof -->
