<?php

/*
  $Id: affiliate.php,v 2.00 2004/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2004 osCommerce

  Released under the GNU General Public License
*/
function b_shop_affiliate()
{
    global $id, $languages_id;

    $block = [];

    $block['title'] = BOX_HEADING_AFFILIATE;

    $block['datum'] = '2004-10-11';

    $block['content'] = '';

    $block['content'] .= "<li><A HREF='" . XOOPS_URL . '/modules/shop/' . FILENAME_AFFILIATE_INFO . "'>" . BOX_AFFILIATE_INFO . '</a><br>';

    $block['content'] .= "<li><A HREF='" . XOOPS_URL . '/modules/shop/' . FILENAME_AFFILIATE_FAQ . "'>" . BOX_AFFILIATE_FAQ . '</a><br>';

    $block['content'] .= "<li><A HREF='" . XOOPS_URL . '/modules/shop/' . FILENAME_AFFILIATE . "'>" . BOX_AFFILIATE_LOGIN . '</a>';

    return $block;
}
