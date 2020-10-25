<?php

function b_shop_manufacturers()
{
    global $manufacturers_id, $manufacturers, $xoopsConfig, $_GET, $PHP_SELF, $languages_id;

    $block = [];

    $block['title'] = BOX_HEADING_MANUFACTURERS;

    $block['datum'] = '2004-04-03';

    $block['content'] = '';

    $manufacturers_query = tep_db_query('select manufacturers_id,manufacturers_name from ' . TABLE_MANUFACTURERS . ' order by manufacturers_name');

    if (tep_db_num_rows($manufacturers_query) <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
        // Display a list

        $manufacturers_list = '';

        while (false !== ($manufacturers_values = tep_db_fetch_array($manufacturers_query))) {
            $manufacturers_list .= '<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_values['manufacturers_id'], 'NONSSL') . '">' . mb_substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</a><br>';
        }

        $block['content'] .= $manufacturers_list;
    } else {
        // Display a drop-down

        $manufacturers_select = '';

        $select_box = '<center><form name="manufacturers" method="get" action="index.php">';

        $select_box .= '<select name="manufacturers_id" onChange="this.form.submit();">';

        while (false !== ($manufacturers_values = tep_db_fetch_array($manufacturers_query))) {
            $select_box .= '<option value="' . $manufacturers_values['manufacturers_id'] . '"';

            if ($manufacturers_id == $manufacturers_values['manufacturers_id']) {
                $select_box .= ' SELECTED';
            }

            $select_box .= '>' . mb_substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
        }

        $select_box .= '</select></center>';

        if (SID) {
            $select_box .= tep_draw_hidden_field([tep_session_name()]);
        }

        $block['content'] .= $select_box;
    }

    return $block;
}
