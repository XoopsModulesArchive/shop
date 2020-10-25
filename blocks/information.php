<?php

function b_shop_information()
{
    global $languages_id;

    $block = [];

    $block['title'] = 'Information';

    $block['datum'] = '2004-04-03';

    $block['content'] = '<a href="' . tep_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a><br>';

    $block['content'] .= '<a href="' . tep_href_link(FILENAME_PRIVACY) . '">' . BOX_INFORMATION_PRIVACY . '</a><br>';

    $block['content'] .= '<a href="' . tep_href_link(FILENAME_CONDITIONS) . '">' . BOX_INFORMATION_CONDITIONS . '</a><br>';

    $block['content'] .= '<a href="' . tep_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a>';

    return $block;
}
