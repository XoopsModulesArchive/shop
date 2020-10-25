<?php

/*
  $Id: modules.php,v 1.1 2006/03/27 09:05:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- modules //-->
<tr>
    <td>
        <?php
        $heading = [];
        $contents = [];

        $heading[] = [
            'text' => BOX_HEADING_MODULES,
'link' => tep_href_link(FILENAME_MODULES, 'set=payment&selected_box=modules'),
        ];

        if ('modules' == $selected_box) {
            $contents[] = [
                'text' => '<a href="'
                          . tep_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_MODULES_PAYMENT
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_MODULES_SHIPPING
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_MODULES, 'set=ordertotal', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_MODULES_ORDER_TOTAL
                          . '</a>',
            ];
        }

        $box = new box();
        echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- modules_eof //-->
