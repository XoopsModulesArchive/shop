<?php

function b_shop_reviews()
{
    global $currentlang, $cPath, $languages_id, $xoopsDB, $_GET;

    $block = [];

    $block['title'] = BOX_HEADING_REVIEWS;

    $block['datum'] = '2004-08-25';

    $block['content'] = '';

    $random_select = 'select r.reviews_id, r.reviews_rating, substring(rd.reviews_text, 1, 60) as reviews_text, p.products_id, p.products_image from '
                     . TABLE_REVIEWS
                     . ' r left join '
                     . TABLE_PRODUCTS
                     . ' p on r.products_id = p.products_id, '
                     . TABLE_REVIEWS_DESCRIPTION
                     . " rd where p.products_status = '1' and rd.reviews_id = r.reviews_id and languages_id = '"
                     . $languages_id
                     . "'";

    if ($_GET['products_id']) {
        $random_select .= " and p.products_id = '" . $_GET['products_id'] . "'";
    }

    $random_select .= ' order by r.reviews_id DESC limit ' . MAX_RANDOM_SELECT_REVIEWS;

    $random_product = tep_random_select($random_select);

    if ($random_product) {
        // display random review box

        $random_product['products_name'] = tep_get_products_name($random_product['products_id']);

        $review = htmlspecialchars($random_product['reviews_text'], ENT_QUOTES | ENT_HTML5);

        $review = tep_break_string($review, 15, '-<br>');

        $block['content'] = '<div align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . tep_image(
            DIR_WS_IMAGES . $random_product['products_image'],
            $random_product['products_name'],
            SMALL_IMAGE_WIDTH,
            SMALL_IMAGE_HEIGHT
        ) . '</a></div><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_INFO, 'products_id=' . $random_product['products_id'] . '&reviews_id=' . $random_product['reviews_id']) . '">' . $review . ' </a><br><div align="center">' . tep_image(
                DIR_WS_IMAGES . 'stars_' . $random_product['reviews_rating'] . '.gif',
                sprintf(BOX_REVIEWS_TEXT_OF_5_STARS, $random_product['reviews_rating'])
            ) . '</div>';
    } elseif ($_GET['products_id']) {
        // display 'write a review' box

        $block['content'] = '<center><a href="' . tep_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id=' . $_GET['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'box_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a></td><td><a href="' . tep_href_link(
            FILENAME_PRODUCT_REVIEWS_WRITE,
            'products_id=' . $_GET['products_id'],
            'NONSSL'
        ) . '">' . BOX_REVIEWS_WRITE_REVIEW . '</a></center>';
    } else {
        // display 'no reviews' box

        $block['content'] = BOX_REVIEWS_NO_REVIEWS;
    }

    return $block;
}
