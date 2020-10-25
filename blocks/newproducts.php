<?php

function b_shop_newproducts()
{
    global $categories_string, $id, $cPath, $min_display_bestseller, $max_display_bestseller, $languages_id, $xoopsDB;

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    $block['title'] = BOX_HEADING_NEWPRODUCTS;

    $block['datum'] = '2004-04-03';

    $block['content'] = '';

    global $currentlang, $max_display_new_products, $cPath, $small_image_width, $color_special_price, $languages_id;

    $currencies = new currencies();

    $block['content'] = '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr>';

    if ((!isset($new_products_category_id)) || ('0' == $new_products_category_id)) {
        $new_products_query = tep_db_query(
            'select p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from '
            . TABLE_PRODUCTS
            . ' p left join '
            . TABLE_SPECIALS
            . " s on p.products_id = s.products_id where products_status = '1' order by p.products_date_added desc limit "
            . MAX_DISPLAY_NEW_PRODUCTS
        );
    } else {
        $new_products_query = tep_db_query(
            'select distinct p.products_id, p.products_image, p.products_tax_class_id, if(s.status, s.specials_new_products_price, p.products_price) as products_price from '
            . TABLE_PRODUCTS
            . ' p left join '
            . TABLE_SPECIALS
            . ' s on p.products_id = s.products_id, '
            . TABLE_PRODUCTS_TO_CATEGORIES
            . ' p2c, '
            . TABLE_CATEGORIES
            . " c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '"
            . $new_products_category_id
            . "' and p.products_status = '1' order by p.products_date_added desc limit "
            . MAX_DISPLAY_NEW_PRODUCTS
        );
    }

    $row = 0;

    $col = 0;

    while (false !== ($new_products = tep_db_fetch_array($new_products_query))) {
        $new_products['products_name'] = tep_get_products_name($new_products['products_id']);

        $row++;

        $block['content'] .= '<td class="infoBoxContents" align="center" valign="top">';

        //if ($special==1) {

        // $block['content'] .= '<s>' .$currencies->format($new_products['products_price']) . '</s><br><font color='.$color_special_price.'>'. $currencies->format($new_products['specials_new_products_price']).'</font>';

        //} else {

        //$block['content'] .=  $currencies->format($new_products['products_price']) ;//tep_currency_format($new_products['products_price']);

        //}

        $block['content'] .= '<br>'
                             . '<a href="'
                             . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id'])
                             . '">'
                             . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT)
                             . '<br>'
                             . $new_products['products_name']
                             . '</a><br>'
                             . $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id']))
                             . '</td>';

        if ((($row / 3) == floor($row / 3)) && (MAX_DISPLAY_PRODUCTS_NEW != $row)) {
            $block['content'] .= '  </tr>
                   <tr>
                     <td>
                       &nbsp;
                     </td>
                   </tr>
                   <tr>';
        }
    }

    $block['content'] .= '</tr></table>';

    $col++;

    if ($col > 2) {
        $col = 0;

        $row++;
    }

    return $block;
}
