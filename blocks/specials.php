<?php

function b_shop_specials()
{
    global $languages_id, $currencies;

    $block = [];

    $block['title'] = 'Bestseller';

    $block['datum'] = '2004-04-03';

    $block['content'] = '';

    if ($random_product = tep_random_select(
        'select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from '
        . TABLE_PRODUCTS
        . ' p, '
        . TABLE_PRODUCTS_DESCRIPTION
        . ' pd, '
        . TABLE_SPECIALS
        . " s where p.products_status = '1' and p.products_id= s.products_id and pd.products_id = s.products_id and pd.language_id = '"
        . $languages_id
        . "' and s.status = '1' order by s.specials_date_added desc limit "
        . MAX_RANDOM_SELECT_SPECIALS
    )) {
        $block['content'] = '<center><a href=../../modules/shop/product_info.php?products_id=' . $random_product['products_id'] . '>';

        $block['content'] .= '<img src="' . DIR_WS_IMAGES . $random_product['products_image'] . '"><br>';

        $block['content'] .= $random_product['products_name'] . '<br>';

        $block['content'] .= '<s>' . $currencies->display_price($random_product['products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</s><br>';

        $block['content'] .= '<span class="productSpecialPrice">' . $currencies->display_price($random_product['specials_new_products_price'], tep_get_tax_rate($random_product['products_tax_class_id'])) . '</span>';

        $block['content'] .= '</a></center>';
    }

    return $block;
}
