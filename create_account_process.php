<?php

/*
  $Id: create_account_process.php,v 1.1 2006/03/27 08:42:21 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

require DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_PROCESS;

if (!isset($_POST['action'])) {
    tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT));
}

$gender = tep_db_prepare_input($_POST['gender']);
$firstname = tep_db_prepare_input($_POST['firstname']);
$lastname = tep_db_prepare_input($_POST['lastname']);
$dob = tep_db_prepare_input($_POST['dob']);
$email_address = tep_db_prepare_input($_POST['email_address']);
$telephone = tep_db_prepare_input($_POST['telephone']);
$fax = tep_db_prepare_input($_POST['fax']);
$newsletter = tep_db_prepare_input($_POST['newsletter']);
$password = tep_db_prepare_input($_POST['password']);
$confirmation = tep_db_prepare_input($_POST['confirmation']);
$street_address = tep_db_prepare_input($_POST['street_address']);
$company = tep_db_prepare_input($_POST['company']);
$suburb = tep_db_prepare_input($_POST['suburb']);
$postcode = tep_db_prepare_input($_POST['postcode']);
$city = tep_db_prepare_input($_POST['city']);
$zone_id = tep_db_prepare_input($_POST['zone_id']);
$state = tep_db_prepare_input($_POST['state']);
$country = tep_db_prepare_input($_POST['country']);

$error = false; // reset error flag

if (ACCOUNT_GENDER == 'true') {
    if (('m' == $gender) || ('f' == $gender)) {
        $entry_gender_error = false;
    } else {
        $error = true;

        $entry_gender_error = true;
    }
}

if (mb_strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;

    $entry_firstname_error = true;
} else {
    $entry_firstname_error = false;
}

if (mb_strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;

    $entry_lastname_error = true;
} else {
    $entry_lastname_error = false;
}

if (ACCOUNT_DOB == 'true') {
    if (checkdate(mb_substr(tep_date_raw($dob), 4, 2), mb_substr(tep_date_raw($dob), 6, 2), mb_substr(tep_date_raw($dob), 0, 4))) {
        $entry_date_of_birth_error = false;
    } else {
        $error = true;

        $entry_date_of_birth_error = true;
    }
}

if (mb_strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;

    $entry_email_address_error = true;
} else {
    $entry_email_address_error = false;
}

if (!tep_validate_email($email_address)) {
    $error = true;

    $entry_email_address_check_error = true;
} else {
    $entry_email_address_check_error = false;
}

if (mb_strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;

    $entry_street_address_error = true;
} else {
    $entry_street_address_error = false;
}

if (mb_strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;

    $entry_post_code_error = true;
} else {
    $entry_post_code_error = false;
}

if (mb_strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;

    $entry_city_error = true;
} else {
    $entry_city_error = false;
}

if (!$country) {
    $error = true;

    $entry_country_error = true;
} else {
    $entry_country_error = false;
}

if (ACCOUNT_STATE == 'true') {
    if (true === $entry_country_error) {
        $entry_state_error = true;
    } else {
        $zone_id = 0;

        $entry_state_error = false;

        $check_query = tep_db_query('select count(*) as total from ' . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "'");

        $check_value = tep_db_fetch_array($check_query);

        $entry_state_has_zones = ($check_value['total'] > 0);

        if (true === $entry_state_has_zones) {
            $zone_query = tep_db_query('select zone_id from ' . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_name = '" . tep_db_input($state) . "'");

            if (1 == tep_db_num_rows($zone_query)) {
                $zone_values = tep_db_fetch_array($zone_query);

                $zone_id = $zone_values['zone_id'];
            } else {
                $zone_query = tep_db_query('select zone_id from ' . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($country) . "' and zone_code = '" . tep_db_input($state) . "'");

                if (1 == tep_db_num_rows($zone_query)) {
                    $zone_values = tep_db_fetch_array($zone_query);

                    $zone_id = $zone_values['zone_id'];
                } else {
                    $error = true;

                    $entry_state_error = true;
                }
            }
        } else {
            if (false === $state) {
                $error = true;

                $entry_state_error = true;
            }
        }
    }
}

if (mb_strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;

    $entry_telephone_error = true;
} else {
    $entry_telephone_error = false;
}

$passlen = mb_strlen($password);
if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;

    $entry_password_error = true;
} else {
    $entry_password_error = false;
}

if ($password != $confirmation) {
    $error = true;

    $entry_password_error = true;
}

$check_email = tep_db_query('select customers_email_address from ' . TABLE_CUSTOMERS . " where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id <> '" . tep_db_input($customer_id) . "'");
if (tep_db_num_rows($check_email)) {
    $error = true;

    $entry_email_address_exists = true;
} else {
    $entry_email_address_exists = false;
}

if (true === $error) {
    $processed = true;

    $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CREATE_ACCOUNT));

    $breadcrumb->add(NAVBAR_TITLE_2); ?>
    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <base href="<?php echo(('SSL' == $request_type) ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
        <link rel="stylesheet" type="text/css" href="stylesheet.css">
        <?php require __DIR__ . '/includes/form_check.js.php'; ?>
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
            <td width="100%" valign="top"><?php echo tep_draw_form('account_edit', tep_href_link(FILENAME_CREATE_ACCOUNT_PROCESS, '', 'SSL'), 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('action', 'process'); ?>
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
                        <td><?php include DIR_WS_MODULES . 'account_details.php'; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <tr>
                        <td>
                            <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                <tr>
                                    <td class="main" align="right"><?php echo tep_image_submit('button_continue.gif', IMAGE_BUTTON_CONTINUE); ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </form></td>
            <!-- body_text_eof //-->
            <td width="<?php echo BOX_WIDTH; ?>" valign="top">
                <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
                    <!-- right_navigation //-->
                    <?php include DIR_WS_INCLUDES . 'column_right.php'; ?>
                    <!-- right_navigation_eof //-->
                </table>
            </td>
        </tr>
    </table>
    <!-- body_eof //-->

    <!-- footer //-->
    <?php include DIR_WS_INCLUDES . 'footer.php'; ?>
    <!-- footer_eof //-->
    <br>
    </body>
    </html>
    <?php
} else {
        $sql_data_array = [
        'customers_firstname' => $firstname,
'customers_lastname' => $lastname,
'customers_email_address' => $email_address,
'customers_telephone' => $telephone,
'customers_fax' => $fax,
'customers_newsletter' => $newsletter,
'customers_password' => tep_encrypt_password($password),
'customers_default_address_id' => 1,
    ];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['customers_gender'] = $gender;
        }

        if (ACCOUNT_DOB == 'true') {
            $sql_data_array['customers_dob'] = tep_date_raw($dob);
        }

        tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

        $customer_id = tep_db_insert_id();

        $sql_data_array = [
        'customers_id' => $customer_id,
'address_book_id' => 1,
'entry_firstname' => $firstname,
'entry_lastname' => $lastname,
'entry_street_address' => $street_address,
'entry_postcode' => $postcode,
'entry_city' => $city,
'entry_country_id' => $country,
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
                $sql_data_array['entry_zone_id'] = $zone_id;

                $sql_data_array['entry_state'] = '';
            } else {
                $sql_data_array['entry_zone_id'] = '0';

                $sql_data_array['entry_state'] = $state;
            }
        }

        tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

        tep_db_query('insert into ' . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . tep_db_input($customer_id) . "', '0', now())");

        $customer_first_name = $firstname;

        $customer_default_address_id = 1;

        $customer_country_id = $country;

        $customer_zone_id = $zone_id;

        tep_session_register('customer_id');

        tep_session_register('customer_first_name');

        tep_session_register('customer_default_address_id');

        tep_session_register('customer_country_id');

        tep_session_register('customer_zone_id');

        // restore cart contents

        $cart->restore_contents();

        // build the message content

        $name = $firstname . ' ' . $lastname;

        if (ACCOUNT_GENDER == 'true') {
            if ('m' == $_POST['gender']) {
                $email_text = EMAIL_GREET_MR;
            } else {
                $email_text = EMAIL_GREET_MS;
            }
        } else {
            $email_text = EMAIL_GREET_NONE;
        }

        $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;

        tep_mail($name, $email_address, EMAIL_SUBJECT, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        tep_redirect(tep_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));
    }

require DIR_WS_INCLUDES . 'application_bottom.php';
?>
