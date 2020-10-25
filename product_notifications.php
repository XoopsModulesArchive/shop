<?php
/*
  $Id: product_notifications.php,v 1.1 2006/03/27 08:42:21 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

if (!tep_isset(\<?php
/*
  $Id: product_notifications.php,v 1.1 2006/03/27 08:42:21 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

if (!tep_session_is_registered('customer_id')) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if (isset($_GET['action']) && ($_GET['action'] == 'update_notifications')) {
    $products = $_POST['products'];
    $remove   = '';
    for ($i = 0, $n = count($products); $i < $n; $i++) {
        $remove .= '\'' . $products[$i] . '\',';
    }
    $remove = substr($remove, 0, -1);

    if (tep_not_null($remove)) {
        tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $customer_id . "' and products_id in (" . $remove . ")");
    }

    tep_redirect(tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));
} elseif (isset($_GET['action']) && ($_GET['action'] == 'global_notify')) {
    if (isset($_POST['global']) && ($_POST['global'] == 'enable')) {
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '1' where customers_info_id = '" . $customer_id . "'");
    } else {
        $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "' and global_product_notifications = '1'");
        $check       = tep_db_fetch_array($check_query);
        if ($check['count'] > 0) {
            tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '0' where customers_info_id = '" . $customer_id . "'");
        }
    }

    tep_redirect(tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));
}

require DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_NOTIFICATIONS;

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));

$global_status_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "'");
$global_status       = tep_db_fetch_array($global_status_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require DIR_WS_INCLUDES . 'header.php'; ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
    <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
                <!-- left_navigation //-->
                <?php require DIR_WS_INCLUDES . 'column_left.php'; ?>
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                                <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCT_NOTIFICATIONS_INTRODUCTION; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <?php
                            if ($global_status['global_product_notifications'] == '1') {
                                ?>
                                <tr>
                                    <td class="main"><b><?php echo HEADING_GLOBAL_PRODUCT_NOTIFICATIONS; ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_ENABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DESCRIPTION_ENABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <?php echo tep_draw_form('global', tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=global_notify', 'SSL')); ?>
                                <tr>
                                    <td class="main"><?php echo tep_draw_checkbox_field('global', 'enable', true) . '&nbsp;' . TEXT_ENABLE_GLOBAL_NOTIFICATIONS; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                                </tr>
                                </form>
                                <?php
                            } else {
                                ?>
                                <tr>
                                    <td class="main"><b><?php echo HEADING_GLOBAL_PRODUCT_NOTIFICATIONS; ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DISABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DESCRIPTION_DISABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <?php echo tep_draw_form('global', tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=global_notify', 'SSL')); ?>
                                <tr>
                                    <td class="main"><?php echo tep_draw_checkbox_field('global', 'enable') . '&nbsp;' . TEXT_ENABLE_GLOBAL_NOTIFICATIONS; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                                </tr>
                                </form>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><b><?php echo HEADING_PRODUCT_NOTIFICATIONS; ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCT_NOTIFICATIONS_LIST; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <?php echo tep_draw_form('notifications', tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=update_notifications', 'SSL')); ?>
                                <?php
                                $products_query = tep_db_query(
                                    "select pd.products_id, pd.products_name from "
                                    . TABLE_PRODUCTS_DESCRIPTION
                                    . " pd, "
                                    . TABLE_PRODUCTS_NOTIFICATIONS
                                    . " pn where pn.customers_id = '"
                                    . $customer_id
                                    . "' and pn.products_id = pd.products_id and pd.language_id = '"
                                    . $languages_id
                                    . "' order by pd.products_name"
                                );
                                while (false !== ($products = tep_db_fetch_array($products_query))) {
                                    echo '          <tr>' . "\n" . '            <td class="main">' . tep_draw_checkbox_field('products[]', $products['products_id']) . '&nbsp;' . $products['products_name'] . '</td>' . "\n" . '          </tr>' . "\n";
                                }
                                ?>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo tep_image_submit('button_remove_notifications.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS); ?></td>
                                </tr>
                                </form>
                                <?php
                            }
                            ?>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <tr>
                                <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <!-- body_text_eof //-->
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
                <!-- right_navigation //-->
                <?php require DIR_WS_INCLUDES . 'column_right.php'; ?>
                <!-- right_navigation_eof //-->
            </table>
        </td>
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
SESSION['customer_id'])) {
    $navigation->set_snapshot();
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

if (isset($_GET['action']) && ($_GET['action'] == 'update_notifications')) {
    $products = $_POST['products'];
    $remove   = '';
    for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
        $remove .= '\'' . $products[$i] . '\',';
    }
    $remove = substr($remove, 0, -1);

    if (tep_not_null($remove)) {
        tep_db_query("delete from " . TABLE_PRODUCTS_NOTIFICATIONS . " where customers_id = '" . $customer_id . "' and products_id in (" . $remove . ")");
    }

    tep_redirect(tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));
} elseif (isset($_GET['action']) && ($_GET['action'] == 'global_notify')) {
    if (isset($_POST['global']) && ($_POST['global'] == 'enable')) {
        tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '1' where customers_info_id = '" . $customer_id . "'");
    } else {
        $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "' and global_product_notifications = '1'");
        $check       = tep_db_fetch_array($check_query);
        if ($check['count'] > 0) {
            tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set global_product_notifications = '0' where customers_info_id = '" . $customer_id . "'");
        }
    }

    tep_redirect(tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));
}

require DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_NOTIFICATIONS;

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, '', 'SSL'));

$global_status_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customer_id . "'");
$global_status       = tep_db_fetch_array($global_status_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require DIR_WS_INCLUDES . 'header.php'; ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
    <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
                <!-- left_navigation //-->
                <?php require DIR_WS_INCLUDES . 'column_left.php'; ?>
                <!-- left_navigation_eof //-->
            </table>
        </td>
        <!-- body_text //-->
        <td width="100%" valign="top">
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                                <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_account.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="main"><?php echo TEXT_PRODUCT_NOTIFICATIONS_INTRODUCTION; ?></td>
                            </tr>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <?php
                            if ($global_status['global_product_notifications'] == '1') {
                                ?>
                                <tr>
                                    <td class="main"><b><?php echo HEADING_GLOBAL_PRODUCT_NOTIFICATIONS; ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_ENABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DESCRIPTION_ENABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <?php echo tep_draw_form('global', tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=global_notify', 'SSL')); ?>
                                <tr>
                                    <td class="main"><?php echo tep_draw_checkbox_field('global', 'enable', true) . '&nbsp;' . TEXT_ENABLE_GLOBAL_NOTIFICATIONS; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                                </tr>
                                </form>
                                <?php
                            } else {
                                ?>
                                <tr>
                                    <td class="main"><b><?php echo HEADING_GLOBAL_PRODUCT_NOTIFICATIONS; ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DISABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_GLOBAL_PRODUCT_NOTIFICATIONS_DESCRIPTION_DISABLED; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <?php echo tep_draw_form('global', tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=global_notify', 'SSL')); ?>
                                <tr>
                                    <td class="main"><?php echo tep_draw_checkbox_field('global', 'enable') . '&nbsp;' . TEXT_ENABLE_GLOBAL_NOTIFICATIONS; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                                </tr>
                                </form>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '20'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><b><?php echo HEADING_PRODUCT_NOTIFICATIONS; ?></b></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo TEXT_PRODUCT_NOTIFICATIONS_LIST; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <?php echo tep_draw_form('notifications', tep_href_link(FILENAME_PRODUCT_NOTIFICATIONS, 'action=update_notifications', 'SSL')); ?>
                                <?php
                                $products_query = tep_db_query(
                                    "select pd.products_id, pd.products_name from "
                                    . TABLE_PRODUCTS_DESCRIPTION
                                    . " pd, "
                                    . TABLE_PRODUCTS_NOTIFICATIONS
                                    . " pn where pn.customers_id = '"
                                    . $customer_id
                                    . "' and pn.products_id = pd.products_id and pd.language_id = '"
                                    . $languages_id
                                    . "' order by pd.products_name"
                                );
                                while (false !== ($products = tep_db_fetch_array($products_query))) {
                                    echo '          <tr>' . "\n" . '            <td class="main">' . tep_draw_checkbox_field('products[]', $products['products_id']) . '&nbsp;' . $products['products_name'] . '</td>' . "\n" . '          </tr>' . "\n";
                                }
                                ?>
                                <tr>
                                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                                </tr>
                                <tr>
                                    <td class="main"><?php echo tep_image_submit('button_remove_notifications.gif', IMAGE_BUTTON_REMOVE_NOTIFICATIONS); ?></td>
                                </tr>
                                </form>
                                <?php
                            }
                            ?>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <tr>
                                <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
        <!-- body_text_eof //-->
        <td width="<?php echo BOX_WIDTH; ?>" valign="top">
            <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
                <!-- right_navigation //-->
                <?php require DIR_WS_INCLUDES . 'column_right.php'; ?>
                <!-- right_navigation_eof //-->
            </table>
        </td>
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
