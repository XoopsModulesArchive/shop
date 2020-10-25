<?php

/*
  $Id: catalog.php,v 1.1 2006/03/27 09:05:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- catalog //-->
<tr>
    <td>
        <?php
        $heading = [];
        $contents = [];

        $heading[] = [
            'text' => BOX_HEADING_CATALOG,
'link' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'),
        ];

        if ('catalog' == $selected_box) {
            $contents[] = [
                'text' => '<a href="'
                          . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_CATALOG_CATEGORIES_PRODUCTS
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_CATALOG_MANUFACTURERS
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_CATALOG_REVIEWS
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_CATALOG_SPECIALS
                          . '</a><br>'
                          . '<a href="'
                          . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL')
                          . '" class="menuBoxContentLink">'
                          . BOX_CATALOG_PRODUCTS_EXPECTED
                          . '</a>',
            ];
        }

        $box = new box();
        echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- catalog_eof //-->
