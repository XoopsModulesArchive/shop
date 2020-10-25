<?php

/*
  $Id: customers.php,v 1.1 2006/03/27 09:05:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- customers //-->
<tr>
    <td>
        <?php
        $heading = [];
        $contents = [];

        $heading[] = [
            'text' => BOX_HEADING_CUSTOMERS,
'link' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'),
        ];

        if ('customers' == $selected_box) {
            $contents[] = [
                'text' => '<a href="' . tep_href_link(FILENAME_CUSTOMERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CUSTOMERS_CUSTOMERS . '</a><br>' . '<a href="' . tep_href_link(FILENAME_ORDERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CUSTOMERS_ORDERS . '</a>',
            ];
        }

        $box = new box();
        echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- customers_eof //-->
