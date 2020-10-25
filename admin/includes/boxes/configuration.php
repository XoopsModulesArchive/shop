<?php

/*
  $Id: configuration.php,v 1.1 2006/03/27 09:05:36 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- configuration //-->
<tr>
    <td>
        <?php
        $heading = [];
        $contents = [];

        $heading[] = [
            'text' => BOX_HEADING_CONFIGURATION,
'link' => tep_href_link(FILENAME_CONFIGURATION, 'gID=1&selected_box=configuration'),
        ];

        if ('configuration' == $selected_box) {
            $cfg_groups = '';

            $configuration_groups_query = tep_db_query('select configuration_group_id as cgID, configuration_group_title as cgTitle from ' . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");

            while (false !== ($configuration_groups = tep_db_fetch_array($configuration_groups_query))) {
                $cfg_groups .= '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '" class="menuBoxContentLink">' . $configuration_groups['cgTitle'] . '</a><br>';
            }

            $contents[] = ['text' => $cfg_groups];
        }

        $box = new box();
        echo $box->menuBox($heading, $contents);
        ?>
    </td>
</tr>
<!-- configuration_eof //-->
