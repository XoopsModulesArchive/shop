<?php

function b_shop_displaycategories()
{
    global $currentlang, $categories_string, $id, $cPath, $min_display_bestseller, $max_display_bestseller, $languages_id;

    $block = [];

    $block['title'] = BOX_HEADING_DISPLAY;

    $block['datum'] = '2004-04-03';

    $block['content'] = '';

    $category_depth = 'nested';

    $block['content'] = '';

    $block['content'] .= '<table border="0" width="100%" cellspacing="5" cellpadding="5">
                <tr>';

    if (($current_category_id) && (preg_match('_', $current_category_id))) {
        $category_links = tep_array_reverse($current_category_id_array);

        for ($i = 0, $iMax = count($category_links); $i < $iMax; $i++) {
            // $categories = $GLOBALS['xoopsDB']->queryF("select categories_id, categories_name, parent_id from categories where parent_id = '" . $category_links[$i] . "' order by sort_order, categories_name");

            $categories = tep_db_query(
                'select c.categories_id, cd.categories_name, c.parent_id from '
                . TABLE_CATEGORIES
                . ' c, '
                . TABLE_CATEGORIES_DESCRIPTION
                . "  cd where c.status = '1' and c.parent_id = '"
                . $category_links[$i]
                . "' and cd.language_id='"
                . $languages_id
                . "' order by c.sort_order, cd.categories_name"
            );

            if (tep_db_num_rows($categories) < 1) {
            } else {
                break;
            }
        }
    } else {
        $categories = tep_db_query(
            'select c.categories_id, c.categories_image, cd.categories_name, cd.language_id, c.parent_id from '
            . TABLE_CATEGORIES
            . ' c, '
            . TABLE_CATEGORIES_DESCRIPTION
            . " cd where c.parent_id = '"
            . $current_category_id
            . "' and c.categories_id = cd.categories_id and cd.language_id='"
            . $languages_id
            . "' order by c.sort_order, cd.categories_name"
        );

        //  $categories = $GLOBALS['xoopsDB']->queryF("select categories_id, categories_name, categories_image, parent_id from categories where parent_id = '" . $current_category_id . "' order by sort_order, categories_name");
    }

    $rows = 0;

    while (false !== ($categories_values = tep_db_fetch_array($categories))) {
        $rows++;

        $current_category_id_new = tep_get_path($categories_values['categories_id']);

        $block['content'] .= '  <td class="infoBoxContents" align="center">' . '<a href="' . tep_href_link(FILENAME_DEFAULT, $current_category_id_new, 'NONSSL') . '">' . tep_image(
            DIR_WS_IMAGES . $categories_values['categories_image'],
            $categories_values['categories_name'],
            SUBCATEGORY_IMAGE_WIDTH,
            SUBCATEGORY_IMAGE_HEIGHT
        ) . '<br>' . $categories_values['categories_name'] . '</a>
                   </td><br>';

        if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != tep_db_num_rows($categories))) {
            $block['content'] .= '</tr><tr>';
        }
    }

    $block['content'] .= '</tr></table>';

    return $block;
}
