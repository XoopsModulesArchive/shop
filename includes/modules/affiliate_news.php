<?php
/*
  $Id: affiliate_news.php,v 2.00 2004/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2004 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- affiliate_news //-->
<?php

$affiliate_news_query = tep_db_query('SELECT * from ' . TABLE_AFFILIATE_NEWS . ' WHERE status = 1 ORDER BY date_added DESC LIMIT ' . MAX_DISPLAY_AFFILIATE_NEWS);

if (!tep_db_num_rows($affiliate_news_query)) { // there is no news
    echo '<!-- ' . TEXT_NO_AFFILIATE_NEWS . ' -->';
} else {
    $info_box_contents = [];
    $row               = 0;
    while (false !== ($affiliate_news = tep_db_fetch_array($affiliate_news_query))) {
        $info_box_contents[$row] = [
            'align'  => 'left',
            'params' => 'class="smallText" valign="top"',
            'text'   => '<b>' . $affiliate_news['headline'] . '</b> - <i>' . tep_date_long($affiliate_news['date_added']) . '</i><br>' . nl2br($affiliate_news['content']) . '<br>',
        ];
        $row++;
    }
    new contentBox($info_box_contents);
}
?>
<!-- affiliate_news_eof //-->
