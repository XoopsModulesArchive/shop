<?php

// $Id: categories.php,v 1.1 2006/03/27 08:38:55 mikhail Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <https://www.xoops.org>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
function show_shop_category($counter)
{
    global $block, $cPath, $languages_id, $xoopsDB;

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    $block = [];

    if ('' != $cPath) {
        $newPath = '0_' . $cPath;
    } else {
        $newPath = '0';
    }

    $id = preg_split('_', $newPath);

    $categories_query = tep_db_query(
        'select c.categories_id, cd.categories_name, c.parent_id from '
        . TABLE_CATEGORIES
        . ' c, '
        . TABLE_CATEGORIES_DESCRIPTION
        . " cd where c.parent_id = '"
        . $id[$counter]
        . "' and c.categories_id = cd.categories_id and cd.language_id='"
        . $languages_id
        . "' order by sort_order, cd.categories_name"
    );

    while (false !== ($categories = tep_db_fetch_array($categories_query))) {
        for ($i = 0; $i <= $counter; $i++) {
            $block['content'] .= '&nbsp;&nbsp;';
        }

        $block['content'] .= '<a href=' . XOOPS_URL . '/modules/shop/index.php?cPath=';

        for ($i = 1; $i <= $counter; $i++) {
            $block['content'] .= $id[$i] . '_';
        }

        $block['content'] .= $categories['categories_id'] . '>';

        $cat_num_query = tep_db_query('select count(*) from ' . TABLE_CATEGORIES . " where parent_id='" . $categories['categories_id'] . "'");

        $cat_num_result = tep_db_fetch_array($cat_num_query);

        $num_sub_cat = $cat_num_result['count(*)'];

        $has_sub_cat = ' -&gt; ';

        if ('0' == $num_sub_cat) {
            $has_sub_cat = '';

            $prod_num_query = tep_db_query('select count(*) from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $categories['categories_id'] . "'");

            $prod_num_result = tep_db_fetch_array($prod_num_query);

            $num_sub_cat = $prod_num_result['count(*)'];
        }

        if ($categories['categories_id'] == $id[$counter + 1]) {
            $block['content'] .= '<b>' . $categories['categories_name'] . "</b></a>$has_sub_cat(";

            $block['content'] .= show_shop_sub_cat($categories['categories_id']);

            $block['content'] .= ')<br>';

            show_shop_category($counter + 1);
        } else {
            $block['content'] .= $categories['categories_name'] . "</a>$has_sub_cat(";

            $block['content'] .= show_shop_sub_cat($categories['categories_id']);

            $block['content'] .= ')<br>';
        }
    }
}

function show_shop_sub_cat($id)
{
    $counter = '0';

    $cat_num_query = tep_db_query('select categories_id from ' . TABLE_CATEGORIES . " where parent_id='" . $id . "'");

    while (false !== ($category = tep_db_fetch_array($cat_num_query))) {
        $counter2 = show_shop_sub_cat($category['categories_id']);

        $counter += $counter2;
    }

    $prod_num_query = tep_db_query('select count(*) from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $id . "'");

    $prod_num_result = tep_db_fetch_array($prod_num_query);

    $counter += $prod_num_result['count(*)'];

    return $counter;
}

function b_shop_categories()
{
    global $block, $xoopsDB, $xoopsConfig, $cPath, $categorynav_string, $languages_id;

    $block = [];

    $categorynav_string = '';

    $counter = '0';

    $block['content'] = '';

    show_shop_category($counter);

    $block['datum'] = '2004-08-30';

    unset($foo);

    unset($categorynav_string);

    unset($id);

    return $block;
}
