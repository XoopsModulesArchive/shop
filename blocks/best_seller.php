<?php

function b_shop_bestseller()
{
    global $categories_string, $id, $cPath, $min_display_bestseller, $languages_id;

    $block = [];

    $block['title'] = BOX_HEADING_BESTSELLERS;

    $block['datum'] = '2004-04-03';

    $block['content'] = '';

    $best_sellers_query = tep_db_query(
        'select p.products_id, pd.products_name, p.products_ordered from '
        . TABLE_PRODUCTS
        . ' p, '
        . TABLE_PRODUCTS_DESCRIPTION
        . " pd where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '"
        . $languages_id
        . "' order by p.products_ordered DESC, pd.products_name limit "
        . MAX_DISPLAY_BESTSELLERS
    );

    // }

    if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
        $rows = 0;

        while (false !== ($best_sellers = tep_db_fetch_array($best_sellers_query))) {
            $rows++;

            $block['content'] .= tep_row_number_format($rows) . '.&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . tep_get_product_path($best_sellers['products_id']) . '&products_id=' . $best_sellers['products_id'], 'NONSSL') . '">' . $best_sellers['products_name'] . '</a><br>';
        }
    } else {
        $block['content'] = 'No product';
    }

    return $block;
}
