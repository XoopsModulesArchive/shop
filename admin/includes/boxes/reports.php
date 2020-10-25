<?php

/*
  $Id: reports.php,v 1.1 2006/03/27 09:05:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- reports //-->
<tr>
    <td>
        <?php
        $heading = [];
        $contents = [];

        $heading[] = [
            'text' => BOX_HEADING_REPORTS,
'link' => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'),
        ];

        if ('reports' == $selected_box) {
            $contents[] = [
                'text' => '<a href="'
                          . tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_REPORTS_PRODUCTS_VIEWED
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_REPORTS_PRODUCTS_PURCHASED
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_STATS_CUSTOMERS, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_REPORTS_ORDERS_TOTAL
                          . '</a>',
            ];
        }

        $box = new box();
        echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- reports_eof //-->
