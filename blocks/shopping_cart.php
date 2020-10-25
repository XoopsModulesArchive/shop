<?php

function b_shop_cart()
{
    global $cart, $xoopsConfig, $xoopsDB, $currencies, $languages_id, $language;

    $block = [
        'title' => 'Shopping Cart',
'datum' => '2004-09-03',
'content' => '',
    ];

    $cart_contents = $cart->contents;

    // print_r($cart_contents);

    $i = 0;

    foreach ($cart_contents as $key => $value) {
        // $block['content'].=$value['qty']."x ".$key."<br>";

        $i++;

        $prod_query = 'select products_name from ' . TABLE_PRODUCTS_DESCRIPTION . " where language_id='" . $languages_id . "' and products_id='" . $key . "'";

        $result = $xoopsDB->query($prod_query, 1, 0);

        $product_result = $xoopsDB->fetchArray($result);

        $block['content'] .= $value['qty'] . 'x <a href=' . XOOPS_URL . '/modules/shop/product_info.php?products_id=' . $key . '>' . $product_result['products_name'] . '</a><br>';
    }

    if ('0' == $i) {
        $block['content'] .= BOX_SHOPPING_CART_EMPTY;
    } else {
        $block['content'] .= '<HR>';

        $block['content'] .= $currencies->format($cart->show_total()) . '<br>';
    }

    return $block;
}
