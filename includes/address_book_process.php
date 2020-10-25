<?php
/*
  $Id: address_book_process.php,v 1.1 2006/03/27 08:50:00 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

if (!tep_isset(\ < ? php
/*
  $Id: address_book_process.php,v 1.1 2006/03/27 08:50:00 mikhail Exp $

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

// needs to be included earlier to set the success message in the messageStack
require DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS;

if (isset($_GET['action']) && ($_GET['action'] == 'deleteconfirm') && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$customer_id . "'");

    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
}

// error checking when updating or adding an entry
$process = false;
if (isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update'))) {
    $process = true;
    $error   = false;

    if (ACCOUNT_GENDER == 'true') {
        $gender = tep_db_prepare_input($_POST['gender']);
    }
    if (ACCOUNT_COMPANY == 'true') {
        $company = tep_db_prepare_input($_POST['company']);
    }
    $firstname      = tep_db_prepare_input($_POST['firstname']);
    $lastname       = tep_db_prepare_input($_POST['lastname']);
    $street_address = tep_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') {
        $suburb = tep_db_prepare_input($_POST['suburb']);
    }
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city     = tep_db_prepare_input($_POST['city']);
    $country  = tep_db_prepare_input($_POST['country']);
    if (ACCOUNT_STATE == 'true') {
        if (isset($_POST['zone_id'])) {
            $zone_id = tep_db_prepare_input($_POST['zone_id']);
        } else {
            $zone_id = false;
        }
        $state = tep_db_prepare_input($_POST['state']);
    }

    if (ACCOUNT_GENDER == 'true') {
        if (($gender != 'm') && ($gender != 'f')) {
            $error = true;

            $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
        }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_CITY_ERROR);
    }

    if (!is_numeric($country)) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
        $zone_id               = 0;
        $check_query           = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
        $check                 = tep_db_fetch_array($check_query);
        $entry_state_has_zones = ($check['total'] > 0);
        if ($entry_state_has_zones === true) {
            $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
            if (tep_db_num_rows($zone_query) == 1) {
                $zone    = tep_db_fetch_array($zone_query);
                $zone_id = $zone['zone_id'];
            } else {
                $error = true;

                $messageStack->add('addressbook', ENTRY_STATE_ERROR_SELECT);
            }
        } else {
            if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
                $error = true;

                $messageStack->add('addressbook', ENTRY_STATE_ERROR);
            }
        }
    }

    if ($error === false) {
        $sql_data_array = [
            'entry_firstname'      => $firstname,
            'entry_lastname'       => $lastname,
            'entry_street_address' => $street_address,
            'entry_postcode'       => $postcode,
            'entry_city'           => $city,
            'entry_country_id'     => (int)$country,
        ];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['entry_gender'] = $gender;
        }
        if (ACCOUNT_COMPANY == 'true') {
            $sql_data_array['entry_company'] = $company;
        }
        if (ACCOUNT_SUBURB == 'true') {
            $sql_data_array['entry_suburb'] = $suburb;
        }
        if (ACCOUNT_STATE == 'true') {
            if ($zone_id > 0) {
                $sql_data_array['entry_zone_id'] = (int)$zone_id;
                $sql_data_array['entry_state']   = '';
            } else {
                $sql_data_array['entry_zone_id'] = '0';
                $sql_data_array['entry_state']   = $state;
            }
        }

        if ($_POST['action'] == 'update') {
            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_GET['edit'] . "' and customers_id ='" . (int)$customer_id . "'");

            // reregister session variables
            if ((isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($_GET['edit'] == $customer_default_address_id)) {
                $customer_first_name         = $firstname;
                $customer_country_id         = $country_id;
                $customer_zone_id            = (($zone_id > 0) ? (int)$zone_id : '0');
                $customer_default_address_id = (int)$_GET['edit'];

                $sql_data_array = [
                    'customers_firstname'          => $firstname,
                    'customers_lastname'           => $lastname,
                    'customers_default_address_id' => (int)$_GET['edit'],
                ];

                if (ACCOUNT_GENDER == 'true') {
                    $sql_data_array['customers_gender'] = $gender;
                }

                tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
            }
        } else {
            $sql_data_array['customers_id'] = (int)$customer_id;
            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

            $new_address_book_id = tep_db_insert_id();

            // reregister session variables
            if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                $customer_first_name = $firstname;
                $customer_country_id = $country_id;
                $customer_zone_id    = (($zone_id > 0) ? (int)$zone_id : '0');
                if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                    $customer_default_address_id = $new_address_book_id;
                }

                $sql_data_array = [
                    'customers_firstname' => $firstname,
                    'customers_lastname'  => $lastname,
                ];

                if (ACCOUNT_GENDER == 'true') {
                    $sql_data_array['customers_gender'] = $gender;
                }
                if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                    $sql_data_array['customers_default_address_id'] = $new_address_book_id;
                }

                tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
            }
        }

        $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
}

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $entry_query = tep_db_query(
        "select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from "
        . TABLE_ADDRESS_BOOK
        . " where customers_id = '"
        . (int)$customer_id
        . "' and address_book_id = '"
        . (int)$_GET['edit']
        . "'"
    );

    if (!tep_db_num_rows($entry_query)) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = tep_db_fetch_array($entry_query);
} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if ($_GET['delete'] == $customer_default_address_id) {
        $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$customer_id . "'");
        $check       = tep_db_fetch_array($check_query);

        if ($check['total'] < 1) {
            $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

            tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
        }
    }
} else {
    $entry = [];
}

if (!isset($_GET['delete']) && !isset($_GET['edit'])) {
    if (tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
        $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $_GET['edit'], 'SSL'));
} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'], 'SSL'));
} else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <?php
    if (!isset($_GET['delete'])) {
        require __DIR__ . '/includes/form_check.js.php';
    }
    ?>
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
        <td width="100%" valign="top"><?php if (!isset($_GET['delete'])) {
                echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'onSubmit="return check_form(addressbook);"');
            } ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="pageHeading"><?php if (isset($_GET['edit'])) {
                                        echo HEADING_TITLE_MODIFY_ENTRY;
                                    } elseif (isset($_GET['delete'])) {
                                        echo HEADING_TITLE_DELETE_ENTRY;
                                    } else {
                                        echo HEADING_TITLE_ADD_ENTRY;
                                    } ?></td>
                                <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', (isset($_GET['edit']) ? HEADING_TITLE_MODIFY_ENTRY : HEADING_TITLE_ADD_ENTRY), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <?php
                if ($messageStack->size('addressbook') > 0) {
                    ?>
                    <tr>
                        <td><?php echo $messageStack->output('addressbook'); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <?php
                }

                if (isset($_GET['delete'])) {
                    ?>
                    <tr>
                        <td class="main"><b><?php echo DELETE_ADDRESS_TITLE; ?></b></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                <tr class="infoBoxContents">
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                <td class="main" width="50%" valign="top"><?php echo DELETE_ADDRESS_DESCRIPTION; ?></td>
                                                <td align="right" width="50%" valign="top">
                                                    <table border="0" cellspacing="0" cellpadding="2">
                                                        <tr>
                                                            <td class="main" align="center" valign="top"><b><?php echo SELECTED_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                            <td class="main" valign="top"><?php echo tep_address_label($customer_id, $_GET['delete'], true, ' ', '<br>'); ?></td>
                                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                <tr class="infoBoxContents">
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&action=deleteconfirm', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></td>
                                                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <td><?php include DIR_WS_MODULES . 'address_book_details.php'; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <?php
                    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
                        ?>
                        <tr>
                            <td>
                                <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                    <tr class="infoBoxContents">
                                        <td>
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                    <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                                    <td align="right"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $_GET['edit']) . tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    } else {
                        if (count($navigation->snapshot) > 0) {
                            $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], [tep_session_name()]), $navigation->snapshot['mode']);
                        } else {
                            $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
                        }
                        ?>
                        <tr>
                            <td>
                                <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                    <tr class="infoBoxContents">
                                        <td>
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                    <td><?php echo '<a href="' . $back_link . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                                    <td align="right"><?php echo tep_draw_hidden_field('action', 'process') . tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <?php
                    }
                }
                ?>
            </table><?php if (!isset($_GET['delete'])) {
                echo '</form>';
            } ?></td>
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

// needs to be included earlier to set the success message in the messageStack
require DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK_PROCESS;

if (isset($_GET['action']) && ($_GET['action'] == 'deleteconfirm') && isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$customer_id . "'");

    $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_DELETED, 'success');

    tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
}

// error checking when updating or adding an entry
$process = false;
if (isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update'))) {
    $process = true;
    $error   = false;

    if (ACCOUNT_GENDER == 'true') {
        $gender = tep_db_prepare_input($_POST['gender']);
    }
    if (ACCOUNT_COMPANY == 'true') {
        $company = tep_db_prepare_input($_POST['company']);
    }
    $firstname      = tep_db_prepare_input($_POST['firstname']);
    $lastname       = tep_db_prepare_input($_POST['lastname']);
    $street_address = tep_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') {
        $suburb = tep_db_prepare_input($_POST['suburb']);
    }
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city     = tep_db_prepare_input($_POST['city']);
    $country  = tep_db_prepare_input($_POST['country']);
    if (ACCOUNT_STATE == 'true') {
        if (isset($_POST['zone_id'])) {
            $zone_id = tep_db_prepare_input($_POST['zone_id']);
        } else {
            $zone_id = false;
        }
        $state = tep_db_prepare_input($_POST['state']);
    }

    if (ACCOUNT_GENDER == 'true') {
        if (($gender != 'm') && ($gender != 'f')) {
            $error = true;

            $messageStack->add('addressbook', ENTRY_GENDER_ERROR);
        }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_CITY_ERROR);
    }

    if (!is_numeric($country)) {
        $error = true;

        $messageStack->add('addressbook', ENTRY_COUNTRY_ERROR);
    }

    if (ACCOUNT_STATE == 'true') {
        $zone_id               = 0;
        $check_query           = tep_db_query("select count(*) as total from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "'");
        $check                 = tep_db_fetch_array($check_query);
        $entry_state_has_zones = ($check['total'] > 0);
        if ($entry_state_has_zones === true) {
            $zone_query = tep_db_query("select distinct zone_id from " . TABLE_ZONES . " where zone_country_id = '" . (int)$country . "' and (zone_name like '" . tep_db_input($state) . "%' or zone_code like '%" . tep_db_input($state) . "%')");
            if (tep_db_num_rows($zone_query) == 1) {
                $zone    = tep_db_fetch_array($zone_query);
                $zone_id = $zone['zone_id'];
            } else {
                $error = true;

                $messageStack->add('addressbook', ENTRY_STATE_ERROR_SELECT);
            }
        } else {
            if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
                $error = true;

                $messageStack->add('addressbook', ENTRY_STATE_ERROR);
            }
        }
    }

    if ($error === false) {
        $sql_data_array = [
            'entry_firstname'      => $firstname,
            'entry_lastname'       => $lastname,
            'entry_street_address' => $street_address,
            'entry_postcode'       => $postcode,
            'entry_city'           => $city,
            'entry_country_id'     => (int)$country,
        ];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['entry_gender'] = $gender;
        }
        if (ACCOUNT_COMPANY == 'true') {
            $sql_data_array['entry_company'] = $company;
        }
        if (ACCOUNT_SUBURB == 'true') {
            $sql_data_array['entry_suburb'] = $suburb;
        }
        if (ACCOUNT_STATE == 'true') {
            if ($zone_id > 0) {
                $sql_data_array['entry_zone_id'] = (int)$zone_id;
                $sql_data_array['entry_state']   = '';
            } else {
                $sql_data_array['entry_zone_id'] = '0';
                $sql_data_array['entry_state']   = $state;
            }
        }

        if ($_POST['action'] == 'update') {
            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "address_book_id = '" . (int)$_GET['edit'] . "' and customers_id ='" . (int)$customer_id . "'");

            // reregister session variables
            if ((isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($_GET['edit'] == $customer_default_address_id)) {
                $customer_first_name         = $firstname;
                $customer_country_id         = $country_id;
                $customer_zone_id            = (($zone_id > 0) ? (int)$zone_id : '0');
                $customer_default_address_id = (int)$_GET['edit'];

                $sql_data_array = [
                    'customers_firstname'          => $firstname,
                    'customers_lastname'           => $lastname,
                    'customers_default_address_id' => (int)$_GET['edit'],
                ];

                if (ACCOUNT_GENDER == 'true') {
                    $sql_data_array['customers_gender'] = $gender;
                }

                tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
            }
        } else {
            $sql_data_array['customers_id'] = (int)$customer_id;
            tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

            $new_address_book_id = tep_db_insert_id();

            // reregister session variables
            if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                $customer_first_name = $firstname;
                $customer_country_id = $country_id;
                $customer_zone_id    = (($zone_id > 0) ? (int)$zone_id : '0');
                if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                    $customer_default_address_id = $new_address_book_id;
                }

                $sql_data_array = [
                    'customers_firstname' => $firstname,
                    'customers_lastname'  => $lastname,
                ];

                if (ACCOUNT_GENDER == 'true') {
                    $sql_data_array['customers_gender'] = $gender;
                }
                if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                    $sql_data_array['customers_default_address_id'] = $new_address_book_id;
                }

                tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");
            }
        }

        $messageStack->add_session('addressbook', SUCCESS_ADDRESS_BOOK_ENTRY_UPDATED, 'success');

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
}

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $entry_query = tep_db_query(
        "select entry_gender, entry_company, entry_firstname, entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_zone_id, entry_country_id from "
        . TABLE_ADDRESS_BOOK
        . " where customers_id = '"
        . (int)$customer_id
        . "' and address_book_id = '"
        . (int)$_GET['edit']
        . "'"
    );

    if (!tep_db_num_rows($entry_query)) {
        $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }

    $entry = tep_db_fetch_array($entry_query);
} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    if ($_GET['delete'] == $customer_default_address_id) {
        $messageStack->add_session('addressbook', WARNING_PRIMARY_ADDRESS_DELETION, 'warning');

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    } else {
        $check_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . (int)$_GET['delete'] . "' and customers_id = '" . (int)$customer_id . "'");
        $check       = tep_db_fetch_array($check_query);

        if ($check['total'] < 1) {
            $messageStack->add_session('addressbook', ERROR_NONEXISTING_ADDRESS_BOOK_ENTRY);

            tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
        }
    }
} else {
    $entry = [];
}

if (!isset($_GET['delete']) && !isset($_GET['edit'])) {
    if (tep_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
        $messageStack->add_session('addressbook', ERROR_ADDRESS_BOOK_FULL);

        tep_redirect(tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));
    }
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'));

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $breadcrumb->add(NAVBAR_TITLE_MODIFY_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $_GET['edit'], 'SSL'));
} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $breadcrumb->add(NAVBAR_TITLE_DELETE_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'], 'SSL'));
} else {
    $breadcrumb->add(NAVBAR_TITLE_ADD_ENTRY, tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'));
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <?php
    if (!isset($_GET['delete'])) {
        require __DIR__ . '/includes/form_check.js.php';
    }
    ?>
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
        <td width="100%" valign="top"><?php if (!isset($_GET['delete'])) {
                echo tep_draw_form('addressbook', tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'), 'post', 'onSubmit="return check_form(addressbook);"');
            } ?>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="pageHeading"><?php if (isset($_GET['edit'])) {
                                        echo HEADING_TITLE_MODIFY_ENTRY;
                                    } elseif (isset($_GET['delete'])) {
                                        echo HEADING_TITLE_DELETE_ENTRY;
                                    } else {
                                        echo HEADING_TITLE_ADD_ENTRY;
                                    } ?></td>
                                <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_address_book.gif', (isset($_GET['edit']) ? HEADING_TITLE_MODIFY_ENTRY : HEADING_TITLE_ADD_ENTRY), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <?php
                if ($messageStack->size('addressbook') > 0) {
                    ?>
                    <tr>
                        <td><?php echo $messageStack->output('addressbook'); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <?php
                }

                if (isset($_GET['delete'])) {
                    ?>
                    <tr>
                        <td class="main"><b><?php echo DELETE_ADDRESS_TITLE; ?></b></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                <tr class="infoBoxContents">
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                <td class="main" width="50%" valign="top"><?php echo DELETE_ADDRESS_DESCRIPTION; ?></td>
                                                <td align="right" width="50%" valign="top">
                                                    <table border="0" cellspacing="0" cellpadding="2">
                                                        <tr>
                                                            <td class="main" align="center" valign="top"><b><?php echo SELECTED_ADDRESS; ?></b><br><?php echo tep_image(DIR_WS_IMAGES . 'arrow_south_east.gif'); ?></td>
                                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                            <td class="main" valign="top"><?php echo tep_address_label($customer_id, $_GET['delete'], true, ' ', '<br>'); ?></td>
                                                            <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                <tr class="infoBoxContents">
                                    <td>
                                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                            <tr>
                                                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&action=deleteconfirm', 'SSL') . '">' . tep_image_button('button_delete.gif', IMAGE_BUTTON_DELETE) . '</a>'; ?></td>
                                                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?php
                } else {
                    ?>
                    <tr>
                        <td><?php include DIR_WS_MODULES . 'address_book_details.php'; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <?php
                    if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
                        ?>
                        <tr>
                            <td>
                                <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                    <tr class="infoBoxContents">
                                        <td>
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                    <td><?php echo '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                                    <td align="right"><?php echo tep_draw_hidden_field('action', 'update') . tep_draw_hidden_field('edit', $_GET['edit']) . tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?></td>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <?php
                    } else {
                        if (count($navigation->snapshot) > 0) {
                            $back_link = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], [tep_session_name()]), $navigation->snapshot['mode']);
                        } else {
                            $back_link = tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL');
                        }
                        ?>
                        <tr>
                            <td>
                                <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                                    <tr class="infoBoxContents">
                                        <td>
                                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                                <tr>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                    <td><?php echo '<a href="' . $back_link . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                                    <td align="right"><?php echo tep_draw_hidden_field('action', 'process') . tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                                                    <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <?php
                    }
                }
                ?>
            </table><?php if (!isset($_GET['delete'])) {
                echo '</form>';
            } ?></td>
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