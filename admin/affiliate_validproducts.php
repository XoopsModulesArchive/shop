<?php

/*
  $Id: affiliate_validproducts.php,v 2.00 2004/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

require DIR_WS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS;

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <base href="<?php echo(('SSL' == $request_type) ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <title><?php echo TITLE; ?></title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <head>
<body>
<table width="580" class="infoBoxContents">
    <tr>
        <td colspan="2" class="infoBoxHeading" align="center"><?php echo TEXT_VALID_PRODUCTS_LIST; ?></td>
    </tr>
    <?php
    echo '<tr><td><b>' . TEXT_VALID_PRODUCTS_ID . '</b></td><td><b>' . TEXT_VALID_PRODUCTS_NAME . '</b></td></tr><tr>';
    $result = $GLOBALS['xoopsDB']->queryF("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $languages_id . "' ORDER BY products_description.products_name");
    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
        do {
            echo "<td class='infoBoxContents'>&nbsp" . $row['products_id'] . "</td>\n";

            echo "<td class='infoBoxContents'>" . $row['products_name'] . "</td>\n";

            echo "</tr>\n";
        } while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result)));
    }
    echo "</table>\n";
    ?>
    <p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?>&nbsp;&nbsp;&nbsp;</p>
    <br>
</body>
</html>
<?php require __DIR__ . '/includes/application_bottom.php'; ?>
