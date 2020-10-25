<?php

/*
  $Id: categories.php,v 1.1 2006/03/27 09:05:33 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

require DIR_WS_CLASSES . 'currencies.php';
$currencies = new currencies();

$action = ($_GET['action'] ?? '');

if (tep_not_null($action)) {
    switch ($action) {
        case 'setflag':
            if (('0' == $_GET['flag']) || ('1' == $_GET['flag'])) {
                if (isset($_GET['pID'])) {
                    tep_set_product_status($_GET['pID'], $_GET['flag']);
                }

                if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');

                    tep_reset_cache_block('also_purchased');
                }
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
            break;
        case 'insert_category':
        case 'update_category':
            if (isset($_POST['categories_id'])) {
                $categories_id = tep_db_prepare_input($_POST['categories_id']);
            }
            $sort_order = tep_db_prepare_input($_POST['sort_order']);

            $sql_data_array = ['sort_order' => $sort_order];

            if ('insert_category' == $action) {
                $insert_sql_data = [
                    'parent_id' => $current_category_id,
'date_added' => 'now()',
                ];

                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

                $categories_id = tep_db_insert_id();
            } elseif ('update_category' == $action) {
                $update_sql_data = ['last_modified' => 'now()'];

                $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
            }

            $languages = tep_get_languages();
            for ($i = 0, $n = count($languages); $i < $n; $i++) {
                $categories_name_array = $_POST['categories_name'];

                $language_id = $languages[$i]['id'];

                $sql_data_array = ['categories_name' => tep_db_prepare_input($categories_name_array[$language_id])];

                if ('insert_category' == $action) {
                    $insert_sql_data = [
                        'categories_id' => $categories_id,
'language_id' => $languages[$i]['id'],
                    ];

                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                    tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
                } elseif ('update_category' == $action) {
                    tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
                }
            }

            if ($categories_image = new upload('categories_image', DIR_FS_CATALOG_IMAGES)) {
                tep_db_query('update ' . TABLE_CATEGORIES . " set categories_image = '" . tep_db_input($categories_image->filename) . "' where categories_id = '" . (int)$categories_id . "'");
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');

                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
            break;
        case 'delete_category_confirm':
            if (isset($_POST['categories_id'])) {
                $categories_id = tep_db_prepare_input($_POST['categories_id']);

                $categories = tep_get_category_tree($categories_id, '', '0', '', true);

                $products = [];

                $products_delete = [];

                for ($i = 0, $n = count($categories); $i < $n; $i++) {
                    $product_ids_query = tep_db_query('select products_id from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

                    while (false !== ($product_ids = tep_db_fetch_array($product_ids_query))) {
                        $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
                    }
                }

                reset($products);

                while (list($key, $value) = each($products)) {
                    $category_ids = '';

                    for ($i = 0, $n = count($value['categories']); $i < $n; $i++) {
                        $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
                    }

                    $category_ids = mb_substr($category_ids, 0, -2);

                    $check_query = tep_db_query('select count(*) as total from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ')');

                    $check = tep_db_fetch_array($check_query);

                    if ($check['total'] < '1') {
                        $products_delete[$key] = $key;
                    }
                }

                // removing categories can be a lengthy process

                tep_set_time_limit(0);

                for ($i = 0, $n = count($categories); $i < $n; $i++) {
                    tep_remove_category($categories[$i]['id']);
                }

                reset($products_delete);

                while (list($key) = each($products_delete)) {
                    tep_remove_product($key);
                }
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');

                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
            break;
        case 'delete_product_confirm':
            if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
                $product_id = tep_db_prepare_input($_POST['products_id']);

                $product_categories = $_POST['product_categories'];

                for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
                    tep_db_query('delete from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
                }

                $product_categories_query = tep_db_query('select count(*) as total from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");

                $product_categories = tep_db_fetch_array($product_categories_query);

                if ('0' == $product_categories['total']) {
                    tep_remove_product($product_id);
                }
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');

                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
            break;
        case 'move_category_confirm':
            if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
                $categories_id = tep_db_prepare_input($_POST['categories_id']);

                $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

                $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

                if (in_array($categories_id, $path, true)) {
                    $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
                } else {
                    tep_db_query('update ' . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

                    if (USE_CACHE == 'true') {
                        tep_reset_cache_block('categories');

                        tep_reset_cache_block('also_purchased');
                    }

                    tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
                }
            }

            break;
        case 'move_product_confirm':
            $products_id = tep_db_prepare_input($_POST['products_id']);
            $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

            $duplicate_check_query = tep_db_query('select count(*) as total from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
            $duplicate_check = tep_db_fetch_array($duplicate_check_query);
            if ($duplicate_check['total'] < 1) {
                tep_db_query('update ' . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");
            }

            if (USE_CACHE == 'true') {
                tep_reset_cache_block('categories');

                tep_reset_cache_block('also_purchased');
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
            break;
        case 'insert_product':
        case 'update_product':
            if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
                $action = 'new_product';
            } else {
                if (isset($_GET['pID'])) {
                    $products_id = tep_db_prepare_input($_GET['pID']);
                }

                $products_date_available = tep_db_prepare_input($_POST['products_date_available']);

                $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

                $sql_data_array = [
                    'products_quantity' => tep_db_prepare_input($_POST['products_quantity']),
'products_model' => tep_db_prepare_input($_POST['products_model']),
'products_price' => tep_db_prepare_input($_POST['products_price']),
'products_date_available' => $products_date_available,
'products_weight' => tep_db_prepare_input($_POST['products_weight']),
'products_status' => tep_db_prepare_input($_POST['products_status']),
'products_tax_class_id' => tep_db_prepare_input($_POST['products_tax_class_id']),
'manufacturers_id' => tep_db_prepare_input($_POST['manufacturers_id']),
                ];

                if (isset($_POST['products_image']) && tep_not_null($_POST['products_image']) && ('none' != $_POST['products_image'])) {
                    $sql_data_array['products_image'] = tep_db_prepare_input($_POST['products_image']);
                }

                if ('insert_product' == $action) {
                    $insert_sql_data = ['products_date_added' => 'now()'];

                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array);

                    $products_id = tep_db_insert_id();

                    tep_db_query('insert into ' . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");
                } elseif ('update_product' == $action) {
                    $update_sql_data = ['products_last_modified' => 'now()'];

                    $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
                }

                $languages = tep_get_languages();

                for ($i = 0, $n = count($languages); $i < $n; $i++) {
                    $language_id = $languages[$i]['id'];

                    $sql_data_array = [
                        'products_name' => tep_db_prepare_input($_POST['products_name'][$language_id]),
'products_description' => tep_db_prepare_input($_POST['products_description'][$language_id]),
'products_url' => tep_db_prepare_input($_POST['products_url'][$language_id]),
                    ];

                    if ('insert_product' == $action) {
                        $insert_sql_data = [
                            'products_id' => $products_id,
'language_id' => $language_id,
                        ];

                        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                    } elseif ('update_product' == $action) {
                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
                    }
                }

                if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');

                    tep_reset_cache_block('also_purchased');
                }

                tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products_id));
            }
            break;
        case 'copy_to_confirm':
            if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
                $products_id = tep_db_prepare_input($_POST['products_id']);

                $categories_id = tep_db_prepare_input($_POST['categories_id']);

                if ('link' == $_POST['copy_as']) {
                    if ($categories_id != $current_category_id) {
                        $check_query = tep_db_query('select count(*) as total from ' . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");

                        $check = tep_db_fetch_array($check_query);

                        if ($check['total'] < '1') {
                            tep_db_query('insert into ' . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
                        }
                    } else {
                        $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
                    }
                } elseif ('duplicate' == $_POST['copy_as']) {
                    $product_query = tep_db_query('select products_quantity, products_model, products_image, products_price, products_date_available, products_weight, products_tax_class_id, manufacturers_id from ' . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");

                    $product = tep_db_fetch_array($product_query);

                    tep_db_query(
                        'insert into ' . TABLE_PRODUCTS . " (products_quantity, products_model,products_image, products_price, products_date_added, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id) values ('" . tep_db_input(
                            $product['products_quantity']
                        ) . "', '" . tep_db_input($product['products_model']) . "', '" . tep_db_input($product['products_image']) . "', '" . tep_db_input($product['products_price']) . "',  now(), '" . tep_db_input($product['products_date_available']) . "', '" . tep_db_input(
                            $product['products_weight']
                        ) . "', '0', '" . (int)$product['products_tax_class_id'] . "', '" . (int)$product['manufacturers_id'] . "')"
                    );

                    $dup_products_id = tep_db_insert_id();

                    $description_query = tep_db_query('select language_id, products_name, products_description, products_url from ' . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");

                    while (false !== ($description = tep_db_fetch_array($description_query))) {
                        tep_db_query(
                            'insert into ' . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_description, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input(
                                $description['products_name']
                            ) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_url']) . "', '0')"
                        );
                    }

                    tep_db_query('insert into ' . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");

                    $products_id = $dup_products_id;
                }

                if (USE_CACHE == 'true') {
                    tep_reset_cache_block('categories');

                    tep_reset_cache_block('also_purchased');
                }
            }

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
            break;
        case 'new_product_preview':
            // copy image only if modified
            $products_image = new upload('products_image');
            $products_image->set_destination(DIR_FS_CATALOG_IMAGES);
            if ($products_image->parse() && $products_image->save()) {
                $products_image_name = $products_image->filename;
            } else {
                $products_image_name = ($_POST['products_previous_image'] ?? '');
            }
            break;
    }
}

// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writable(DIR_FS_CATALOG_IMAGES)) {
        $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
    }
} else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require DIR_WS_INCLUDES . 'header.php'; ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
    <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
                <!-- left_navigation //-->
                <?php require DIR_WS_INCLUDES . 'column_left.php'; ?>
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
            <?php
            if ('new_product' == $action) {
                $parameters = [
                    'products_name' => '',
'products_description' => '',
'products_url' => '',
'products_id' => '',
'products_quantity' => '',
'products_model' => '',
'products_image' => '',
'products_price' => '',
'products_weight' => '',
'products_date_added' => '',
'products_last_modified' => '',
'products_date_available' => '',
'products_status' => '',
'products_tax_class_id' => '',
'manufacturers_id' => '',
                ];

                $pInfo = new objectInfo($parameters);

                if (isset($_GET['pID']) && empty($_POST)) {
                    $product_query = tep_db_query(
                        "select pd.products_name, pd.products_description, pd.products_url, p.products_id, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.manufacturers_id from "
                        . TABLE_PRODUCTS
                        . ' p, '
                        . TABLE_PRODUCTS_DESCRIPTION
                        . " pd where p.products_id = '"
                        . (int)$_GET['pID']
                        . "' and p.products_id = pd.products_id and pd.language_id = '"
                        . (int)$languages_id
                        . "'"
                    );

                    $product = tep_db_fetch_array($product_query);

                    $pInfo->__construct($product);
                } elseif (tep_not_null($_POST)) {
                    $pInfo->__construct($_POST);

                    $products_name = $_POST['products_name'];

                    $products_description = $_POST['products_description'];

                    $products_url = $_POST['products_url'];
                }

                $manufacturers_array = [['id' => '', 'text' => TEXT_NONE]];

                $manufacturers_query = tep_db_query('select manufacturers_id, manufacturers_name from ' . TABLE_MANUFACTURERS . ' order by manufacturers_name');

                while (false !== ($manufacturers = tep_db_fetch_array($manufacturers_query))) {
                    $manufacturers_array[] = [
                        'id' => $manufacturers['manufacturers_id'],
'text' => $manufacturers['manufacturers_name'],
                    ];
                }

                $tax_class_array = [['id' => '0', 'text' => TEXT_NONE]];

                $tax_class_query = tep_db_query('select tax_class_id, tax_class_title from ' . TABLE_TAX_CLASS . ' order by tax_class_title');

                while (false !== ($tax_class = tep_db_fetch_array($tax_class_query))) {
                    $tax_class_array[] = [
                        'id' => $tax_class['tax_class_id'],
'text' => $tax_class['tax_class_title'],
                    ];
                }

                $languages = tep_get_languages();

                if (!isset($pInfo->products_status)) {
                    $pInfo->products_status = '1';
                }

                switch ($pInfo->products_status) {
                    case '0':
                        $in_status = false;
                        $out_status = true;
                        break;
                    case '1':
                    default:
                        $in_status = true;
                        $out_status = false;
                } ?>
            <link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
                <script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
                <script language="javascript"><!--
                    var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available", "btnDate1", "<?php echo $pInfo->products_date_available; ?>", scBTNMODE_CUSTOMBLUE);
                    //--></script>
                <script language="javascript"><!--
                    var tax_rates = new Array();
                    <?php
                    for ($i = 0, $n = count($tax_class_array); $i < $n; $i++) {
                        if ($tax_class_array[$i]['id'] > 0) {
                            echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . tep_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
                        }
                    } ?>

                    function doRound(x, places) {
                        return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
                    }

                    function getTaxRate() {
                        var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
                        var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

                        if ((parameterVal > 0) && (tax_rates[parameterVal] > 0)) {
                            return tax_rates[parameterVal];
                        } else {
                            return 0;
                        }
                    }

                    function updateGross() {
                        var taxRate = getTaxRate();
                        var grossValue = document.forms["new_product"].products_price.value;

                        if (taxRate > 0) {
                            grossValue = grossValue * ((taxRate / 100) + 1);
                        }

                        document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
                    }

                    function updateNet() {
                        var taxRate = getTaxRate();
                        var netValue = document.forms["new_product"].products_price_gross.value;

                        if (taxRate > 0) {
                            netValue = netValue / ((taxRate / 100) + 1);
                        }

                        document.forms["new_product"].products_price.value = doRound(netValue, 4);
                    }

                    //--></script>
            <?php echo tep_draw_form('new_product', FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_product_preview', 'post', 'enctype="multipart/form-data"'); ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?></td>
                                    <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15')
                                                                . '&nbsp;'
                                                                . tep_draw_radio_field('products_status', '1', $in_status)
                                                                . '&nbsp;'
                                                                . TEXT_PRODUCT_AVAILABLE
                                                                . '&nbsp;'
                                                                . tep_draw_radio_field('products_status', '0', $out_status)
                                                                . '&nbsp;'
                                                                . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small>(YYYY-MM-DD)</small></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
                                        <script language="javascript">dateAvailable.writeControl();
                                            dateAvailable.dateFormat = "yyyy-MM-dd";</script>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <?php
                                for ($i = 0, $n = count($languages); $i < $n; $i++) {
                                    ?>
                                    <tr>
                                        <td class="main"><?php if (0 == $i) {
                                        echo TEXT_PRODUCTS_NAME;
                                    } ?></td>
                                        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field(
                                        'products_name[' . $languages[$i]['id'] . ']',
                                        ($products_name[$languages[$i]['id']] ?? tep_get_products_name($pInfo->products_id, $languages[$i]['id']))
                                    ); ?></td>
                                    </tr>
                                    <?php
                                } ?>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr bgcolor="#ebebff">
                                    <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
                                </tr>
                                <tr bgcolor="#ebebff">
                                    <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
                                </tr>
                                <tr bgcolor="#ebebff">
                                    <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <script language="javascript"><!--
                                    updateGross();
                                    //--></script>
                                <?php
                                for ($i = 0, $n = count($languages); $i < $n; $i++) {
                                    ?>
                                    <tr>
                                        <td class="main" valign="top"><?php if (0 == $i) {
                                        echo TEXT_PRODUCTS_DESCRIPTION;
                                    } ?></td>
                                        <td>
                                            <table border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td class="main" valign="top"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                                                    <td class="main"><?php echo tep_draw_textarea_field(
                                        'products_description[' . $languages[$i]['id'] . ']',
                                        'soft',
                                        '70',
                                        '15',
                                        ($products_description[$languages[$i]['id']] ?? tep_get_products_description($pInfo->products_id, $languages[$i]['id']))
                                    ); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                } ?>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_model', $pInfo->products_model); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('products_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $pInfo->products_image . tep_draw_hidden_field(
                                                            'products_previous_image',
                                                            $pInfo->products_image
                                                        ); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <?php
                                for ($i = 0, $n = count($languages); $i < $n; $i++) {
                                    ?>
                                    <tr>
                                        <td class="main"><?php if (0 == $i) {
                                        echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>';
                                    } ?></td>
                                        <td class="main"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field(
                                        'products_url[' . $languages[$i]['id'] . ']',
                                        ($products_url[$languages[$i]['id']] ?? tep_get_products_url($pInfo->products_id, $languages[$i]['id']))
                                    ); ?></td>
                                    </tr>
                                    <?php
                                } ?>
                                <tr>
                                    <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
                                    <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                        <td class="main" align="right"><?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(
                                                    FILENAME_CATEGORIES,
                                                    'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')
                                                ) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
                    </tr>
                </table></form>
            <?php
            } elseif ('new_product_preview' == $action) {
                if (tep_not_null($_POST)) {
                    $pInfo = new objectInfo($_POST);

                    $products_name = $_POST['products_name'];

                    $products_description = $_POST['products_description'];

                    $products_url = $_POST['products_url'];
                } else {
                    $product_query = tep_db_query(
                        'select p.products_id, pd.language_id, pd.products_name, pd.products_description, pd.products_url, p.products_quantity, p.products_model, p.products_image, p.products_price, p.products_weight, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.manufacturers_id  from '
                    . TABLE_PRODUCTS
                    . ' p, '
                    . TABLE_PRODUCTS_DESCRIPTION
                    . " pd where p.products_id = pd.products_id and p.products_id = '"
                    . (int)$_GET['pID']
                    . "'"
                    );

                    $product = tep_db_fetch_array($product_query);

                    $pInfo = new objectInfo($product);

                    $products_image_name = $pInfo->products_image;
                }

                $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';

                echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

                $languages = tep_get_languages();

                for ($i = 0, $n = count($languages);
            $i < $n;
            $i++) {
                    if (isset($_GET['read']) && ('only' == $_GET['read'])) {
                        $pInfo->products_name = tep_get_products_name($pInfo->products_id, $languages[$i]['id']);

                        $pInfo->products_description = tep_get_products_description($pInfo->products_id, $languages[$i]['id']);

                        $pInfo->products_url = tep_get_products_url($pInfo->products_id, $languages[$i]['id']);
                    } else {
                        $pInfo->products_name = tep_db_prepare_input($products_name[$languages[$i]['id']]);

                        $pInfo->products_description = tep_db_prepare_input($products_description[$languages[$i]['id']]);

                        $pInfo->products_url = tep_db_prepare_input($products_url[$languages[$i]['id']]);
                    } ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="pageHeading"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
                                <td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                    <td class="main"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . $products_image_name, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $pInfo->products_description; ?></td>
                </tr>
                <?php
                if ($pInfo->products_url) {
                    ?>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                    </tr>
                    <tr>
                        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
                    </tr>
                    <?php
                } ?>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <?php
                if ($pInfo->products_date_available > date('Y-m-d')) {
                    ?>
                    <tr>
                        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, tep_date_long($pInfo->products_date_available)); ?></td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, tep_date_long($pInfo->products_date_added)); ?></td>
                    </tr>
                    <?php
                } ?>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <?php
                }

                if (isset($_GET['read']) && ('only' == $_GET['read'])) {
                    if (isset($_GET['origin'])) {
                        $pos_params = mb_strpos($_GET['origin'], '?', 0);

                        if (false !== $pos_params) {
                            $back_url = mb_substr($_GET['origin'], 0, $pos_params);

                            $back_url_params = mb_substr($_GET['origin'], $pos_params + 1);
                        } else {
                            $back_url = $_GET['origin'];

                            $back_url_params = '';
                        }
                    } else {
                        $back_url = FILENAME_CATEGORIES;

                        $back_url_params = 'cPath=' . $cPath . '&pID=' . $pInfo->products_id;
                    } ?>
                <tr>
                    <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
                </tr>
                <?php
                } else {
                    ?>
                <tr>
                    <td align="right" class="smallText">
                        <?php
                        /* Re-Post all POST'ed variables */
                        reset($_POST);

                    while (list($key, $value) = each($_POST)) {
                        if (!is_array($_POST[$key])) {
                            echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value), ENT_QUOTES | ENT_HTML5));
                        }
                    }

                    $languages = tep_get_languages();

                    for ($i = 0, $n = count($languages); $i < $n; $i++) {
                        echo tep_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']]), ENT_QUOTES | ENT_HTML5));

                        echo tep_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']]), ENT_QUOTES | ENT_HTML5));

                        echo tep_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']]), ENT_QUOTES | ENT_HTML5));
                    }

                    echo tep_draw_hidden_field('products_image', stripslashes($products_image_name));

                    echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

                    if (isset($_GET['pID'])) {
                        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
                    } else {
                        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
                    }

                    echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
                </tr>
                </table></form>
            <?php
                }
            } else {
                ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                                    <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
                                    <td align="right">
                                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="smallText" align="right">
                                                    <?php
                                                    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');

                echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');

                echo '</form>'; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="smallText" align="right">
                                                    <?php
                                                    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');

                echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');

                echo '</form>'; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td valign="top">
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr class="dataTableHeadingRow">
                                                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                                                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                                                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                                            </tr>
                                            <?php
                                            $categories_count = 0;

                $rows = 0;

                if (isset($_GET['search'])) {
                    $search = tep_db_prepare_input($_GET['search']);

                    $categories_query = tep_db_query(
                        'select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from '
                                                    . TABLE_CATEGORIES
                                                    . ' c, '
                                                    . TABLE_CATEGORIES_DESCRIPTION
                                                    . " cd where c.categories_id = cd.categories_id and cd.language_id = '"
                                                    . (int)$languages_id
                                                    . "' and cd.categories_name like '%"
                                                    . tep_db_input($search)
                                                    . "%' order by c.sort_order, cd.categories_name"
                    );
                } else {
                    $categories_query = tep_db_query(
                        'select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from '
                                                    . TABLE_CATEGORIES
                                                    . ' c, '
                                                    . TABLE_CATEGORIES_DESCRIPTION
                                                    . " cd where c.parent_id = '"
                                                    . (int)$current_category_id
                                                    . "' and c.categories_id = cd.categories_id and cd.language_id = '"
                                                    . (int)$languages_id
                                                    . "' order by c.sort_order, cd.categories_name"
                    );
                }

                while (false !== ($categories = tep_db_fetch_array($categories_query))) {
                    $categories_count++;

                    $rows++;

                    // Get parent_id for subcategories if search

                    if (isset($_GET['search'])) {
                        $cPath = $categories['parent_id'];
                    }

                    if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && ('new' != mb_substr($action, 0, 3))) {
                        $category_childs = ['childs_count' => tep_childs_in_category_count($categories['categories_id'])];

                        $category_products = ['products_count' => tep_products_in_category_count($categories['categories_id'])];

                        $cInfo_array = array_merge($categories, $category_childs, $category_products);

                        $cInfo = new objectInfo($cInfo_array);
                    }

                    if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
                        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(
                            FILENAME_CATEGORIES,
                            tep_get_path($categories['categories_id'])
                        ) . '\'">' . "\n";
                    } else {
                        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\''
                                                         . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'])
                                                         . '\'">'
                                                         . "\n";
                    } ?>
                                                <td class="dataTableContent"><?php echo '<a href="'
                                                                                        . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id']))
                                                                                        . '">'
                                                                                        . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER)
                                                                                        . '</a>&nbsp;<b>'
                                                                                        . $categories['categories_name']
                                                                                        . '</b>'; ?></td>
                                                <td class="dataTableContent" align="center">&nbsp;</td>
                                                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
                                                                                            echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                                                                                        } else {
                                                                                            echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                                                                                        } ?>&nbsp;
                                                </td>
                                                </tr>
                                                <?php
                }

                $products_count = 0;

                if (isset($_GET['search'])) {
                    $products_query = tep_db_query(
                        'select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from '
                                                    . TABLE_PRODUCTS
                                                    . ' p, '
                                                    . TABLE_PRODUCTS_DESCRIPTION
                                                    . ' pd, '
                                                    . TABLE_PRODUCTS_TO_CATEGORIES
                                                    . " p2c where p.products_id = pd.products_id and pd.language_id = '"
                                                    . (int)$languages_id
                                                    . "' and p.products_id = p2c.products_id and pd.products_name like '%"
                                                    . tep_db_input($search)
                                                    . "%' order by pd.products_name"
                    );
                } else {
                    $products_query = tep_db_query(
                        'select p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from '
                                                    . TABLE_PRODUCTS
                                                    . ' p, '
                                                    . TABLE_PRODUCTS_DESCRIPTION
                                                    . ' pd, '
                                                    . TABLE_PRODUCTS_TO_CATEGORIES
                                                    . " p2c where p.products_id = pd.products_id and pd.language_id = '"
                                                    . (int)$languages_id
                                                    . "' and p.products_id = p2c.products_id and p2c.categories_id = '"
                                                    . (int)$current_category_id
                                                    . "' order by pd.products_name"
                    );
                }

                while (false !== ($products = tep_db_fetch_array($products_query))) {
                    $products_count++;

                    $rows++;

                    // Get categories_id for product if search

                    if (isset($_GET['search'])) {
                        $cPath = $products['categories_id'];
                    }

                    if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && ('new' != mb_substr($action, 0, 3))) {
                        // find out the rating average from customer reviews

                        $reviews_query = tep_db_query('select (avg(reviews_rating) / 5 * 100) as average_rating from ' . TABLE_REVIEWS . " where products_id = '" . (int)$products['products_id'] . "'");

                        $reviews = tep_db_fetch_array($reviews_query);

                        $pInfo_array = array_merge($products, $reviews);

                        $pInfo = new objectInfo($pInfo_array);
                    }

                    if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
                        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(
                            FILENAME_CATEGORIES,
                            'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only'
                        ) . '\'">' . "\n";
                    } else {
                        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\''
                                                         . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'])
                                                         . '\'">'
                                                         . "\n";
                    } ?>
                                                <td class="dataTableContent"><?php echo '<a href="'
                                                                                        . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only')
                                                                                        . '">'
                                                                                        . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW)
                                                                                        . '</a>&nbsp;'
                                                                                        . $products['products_name']; ?></td>
                                                <td class="dataTableContent" align="center">
                                                    <?php
                                                    if ('1' == $products['products_status']) {
                                                        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10)
                                                             . '&nbsp;&nbsp;<a href="'
                                                             . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath)
                                                             . '">'
                                                             . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10)
                                                             . '</a>';
                                                    } else {
                                                        echo '<a href="'
                                                             . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath)
                                                             . '">'
                                                             . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10)
                                                             . '</a>&nbsp;&nbsp;'
                                                             . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                                                    } ?></td>
                                                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
                                                        echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', '');
                                                    } else {
                                                        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                                                    } ?>&nbsp;
                                                </td>
                                                </tr>
                                                <?php
                }

                $cPath_back = '';

                if (count($cPath_array) > 0) {
                    for ($i = 0, $n = count($cPath_array) - 1; $i < $n; $i++) {
                        if (empty($cPath_back)) {
                            $cPath_back .= $cPath_array[$i];
                        } else {
                            $cPath_back .= '_' . $cPath_array[$i];
                        }
                    }
                }

                $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : ''; ?>
                                            <tr>
                                                <td colspan="3">
                                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                        <tr>
                                                            <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                                                            <td align="right" class="smallText"><?php if (count($cPath_array) > 0) {
                    echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;';
                }

                if (!isset($_GET['search'])) {
                    echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(
                        FILENAME_CATEGORIES,
                        'cPath=' . $cPath . '&action=new_product'
                    ) . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>';
                } ?>&nbsp;
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <?php
                                    $heading = [];

                $contents = [];

                switch ($action) {
                                        case 'new_category':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>'];

                                            $contents = ['form' => tep_draw_form('newcategory', FILENAME_CATEGORIES, 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"')];
                                            $contents[] = ['text' => TEXT_NEW_CATEGORY_INTRO];

                                            $category_inputs_string = '';
                                            $languages = tep_get_languages();
                                            for ($i = 0, $n = count($languages); $i < $n; $i++) {
                                                $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
                                            }

                                            $contents[] = ['text' => '<br>' . TEXT_CATEGORIES_NAME . $category_inputs_string];
                                            $contents[] = ['text' => '<br>' . TEXT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image')];
                                            $contents[] = ['text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"')];
                                            $contents[] = ['align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'];
                                            break;
                                        case 'edit_category':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>'];

                                            $contents = ['form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('categories_id', $cInfo->categories_id)];
                                            $contents[] = ['text' => TEXT_EDIT_INTRO];

                                            $category_inputs_string = '';
                                            $languages = tep_get_languages();
                                            for ($i = 0, $n = count($languages); $i < $n; $i++) {
                                                $category_inputs_string .= '<br>' . tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field(
                                                    'categories_name[' . $languages[$i]['id'] . ']',
                                                    tep_get_category_name(
                                                            $cInfo->categories_id,
                                                            $languages[$i]['id']
                                                        )
                                                );
                                            }

                                            $contents[] = ['text' => '<br>' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string];
                                            $contents[] = ['text' => '<br>' . tep_image(DIR_WS_CATALOG_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . DIR_WS_CATALOG_IMAGES . '<br><b>' . $cInfo->categories_image . '</b>'];
                                            $contents[] = ['text' => '<br>' . TEXT_EDIT_CATEGORIES_IMAGE . '<br>' . tep_draw_file_field('categories_image')];
                                            $contents[] = ['text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"')];
                                            $contents[] = [
                                                'align' => 'center',
'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>',
                                            ];
                                            break;
                                        case 'delete_category':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>'];

                                            $contents = ['form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id)];
                                            $contents[] = ['text' => TEXT_DELETE_CATEGORY_INTRO];
                                            $contents[] = ['text' => '<br><b>' . $cInfo->categories_name . '</b>'];
                                            if ($cInfo->childs_count > 0) {
                                                $contents[] = ['text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count)];
                                            }
                                            if ($cInfo->products_count > 0) {
                                                $contents[] = ['text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count)];
                                            }
                                            $contents[] = [
                                                'align' => 'center',
'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>',
                                            ];
                                            break;
                                        case 'move_category':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>'];

                                            $contents = ['form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id)];
                                            $contents[] = ['text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name)];
                                            $contents[] = ['text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id)];
                                            $contents[] = [
                                                'align' => 'center',
'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>',
                                            ];
                                            break;
                                        case 'delete_product':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>'];

                                            $contents = ['form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id)];
                                            $contents[] = ['text' => TEXT_DELETE_PRODUCT_INTRO];
                                            $contents[] = ['text' => '<br><b>' . $pInfo->products_name . '</b>'];

                                            $product_categories_string = '';
                                            $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
                                            for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
                                                $category_path = '';

                                                for ($j = 0, $k = count($product_categories[$i]); $j < $k; $j++) {
                                                    $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
                                                }

                                                $category_path = mb_substr($category_path, 0, -16);

                                                $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][count($product_categories[$i]) - 1]['id'], true) . '&nbsp;' . $category_path . '<br>';
                                            }
                                            $product_categories_string = mb_substr($product_categories_string, 0, -4);

                                            $contents[] = ['text' => '<br>' . $product_categories_string];
                                            $contents[] = [
                                                'align' => 'center',
'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>',
                                            ];
                                            break;
                                        case 'move_product':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>'];

                                            $contents = ['form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id)];
                                            $contents[] = ['text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name)];
                                            $contents[] = ['text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>'];
                                            $contents[] = ['text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id)];
                                            $contents[] = [
                                                'align' => 'center',
'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>',
                                            ];
                                            break;
                                        case 'copy_to':
                                            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>'];

                                            $contents = ['form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id)];
                                            $contents[] = ['text' => TEXT_INFO_COPY_TO_INTRO];
                                            $contents[] = ['text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>'];
                                            $contents[] = ['text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id)];
                                            $contents[] = ['text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE];
                                            $contents[] = [
                                                'align' => 'center',
'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>',
                                            ];
                                            break;
                                        default:
                                            if ($rows > 0) {
                                                if (isset($cInfo) && is_object($cInfo)) { // category info box contents
                                                    $heading[] = ['text' => '<b>' . $cInfo->categories_name . '</b>'];

                                                    $contents[] = [
                                                        'align' => 'center',
'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(
    FILENAME_CATEGORIES,
    'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category'
) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . tep_image_button(
                                                                'button_move.gif',
                                                                IMAGE_MOVE
                                                            ) . '</a>',
                                                    ];

                                                    $contents[] = ['text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added)];

                                                    if (tep_not_null($cInfo->last_modified)) {
                                                        $contents[] = ['text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified)];
                                                    }

                                                    $contents[] = ['text' => '<br>' . tep_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $cInfo->categories_image];

                                                    $contents[] = ['text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count];
                                                } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
                                                    $heading[] = ['text' => '<b>' . tep_get_products_name($pInfo->products_id, $languages_id) . '</b>'];

                                                    $contents[] = [
                                                        'align' => 'center',
'text' => '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(
    FILENAME_CATEGORIES,
    'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product'
) . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . tep_image_button(
                                                                'button_move.gif',
                                                                IMAGE_MOVE
                                                            ) . '</a> <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>',
                                                    ];

                                                    $contents[] = ['text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($pInfo->products_date_added)];

                                                    if (tep_not_null($pInfo->products_last_modified)) {
                                                        $contents[] = ['text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($pInfo->products_last_modified)];
                                                    }

                                                    if (date('Y-m-d') < $pInfo->products_date_available) {
                                                        $contents[] = ['text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($pInfo->products_date_available)];
                                                    }

                                                    $contents[] = ['text' => '<br>' . tep_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br>' . $pInfo->products_image];

                                                    $contents[] = ['text' => '<br>' . TEXT_PRODUCTS_PRICE_INFO . ' ' . $currencies->format($pInfo->products_price) . '<br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity];

                                                    $contents[] = ['text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%'];
                                                }
                                            } else { // create category/product info
                                                $heading[] = ['text' => '<b>' . EMPTY_CATEGORY . '</b>'];

                                                $contents[] = ['text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS];
                                            }
                                            break;
                                    }

                if ((tep_not_null($heading)) && (tep_not_null($contents))) {
                    echo '            <td width="25%" valign="top">' . "\n";

                    $box = new box();

                    echo $box->infoBox($heading, $contents);

                    echo '            </td>' . "\n";
                } ?>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <?php
            }
            ?>
        </td>
        <!-- body_text_eof //-->
    </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require DIR_WS_INCLUDES . 'footer.php'; ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require DIR_WS_INCLUDES . 'application_bottom.php'; ?>
