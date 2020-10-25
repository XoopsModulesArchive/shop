<?php

/*
  $Id: localization.php,v 1.1 2006/03/27 09:05:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- localization //-->
<tr>
    <td>
        <?php
        $heading = [];
        $contents = [];

        $heading[] = [
            'text' => BOX_HEADING_LOCALIZATION,
'link' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization'),
        ];

        if ('localization' == $selected_box) {
            $contents[] = [
                'text' => '<a href="'
                          . tep_href_link(FILENAME_CURRENCIES, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_LOCALIZATION_CURRENCIES
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_LANGUAGES, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_LOCALIZATION_LANGUAGES
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_ORDERS_STATUS, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_LOCALIZATION_ORDERS_STATUS
                          . '</a>',
            ];
        }

        $box = new box();
        echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- localization_eof //-->
