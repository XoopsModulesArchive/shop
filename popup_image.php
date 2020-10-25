<?php
/*
  $Id: popup_image.php,v 1.1 2006/03/27 08:42:21 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

require __DIR__ . '/includes/application_top.php';

$navigation->remove_current_page();

$products_query = tep_db_query(
    "select pd.products_name, p.products_image from " . TABLE_PRODUCTS . " p left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id where p.products_status = '1' and p.products_id = '" . (int)$_GET['pID'] . "' and pd.language_id = '" . (int)$languages_id . "'"
);
$products       = tep_db_fetch_array($products_query);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo $products['products_name']; ?></title>
    <base href="<?php echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <script language="javascript"><!--
        var i = 0;

        function resize() {
            if (navigator.appName == 'Netscape') i = 40;
            if (document.images[0]) window.resizeTo(document.images[0].width + 30, document.images[0].height + 60 - i);
            self.focus();
        }

        //--></script>
</head>
<body onload="resize();">
<?php echo tep_image(DIR_WS_IMAGES . $products['products_image'], $products['products_name']); ?>
</body>
</html>

