<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)

// MB Xoops Modification

global $xoopsOption;

$xoopsOption = ["nocommon" => "1"];

require dirname(__DIR__, 2) . '/mainfile.php';

define('HTTP_SERVER', XOOPS_URL); // eg, http://localhost - should not be empty for productive servers
define('HTTPS_SERVER', XOOPS_URL); // eg, https://localhost - should not be empty for productive servers
define('ENABLE_SSL', false); // secure webserver for checkout procedure?
define('HTTP_COOKIE_DOMAIN', 'workshop.myxoopsshop.org');
define('HTTPS_COOKIE_DOMAIN', '');
define('HTTP_COOKIE_PATH', '/');
define('HTTPS_COOKIE_PATH', '/');
define('DIR_WS_HTTP_CATALOG', '/modules/shop/');
define('DIR_WS_HTTPS_CATALOG', '');

define('DIR_WS_CATALOG', '/modules/shop/'); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

define('DIR_WS_DOWNLOAD_PUBLIC', DIR_WS_CATALOG . 'pub/');
define('DIR_FS_DOCUMENT_ROOT', XOOPS_ROOT_PATH);
define('DIR_FS_CATALOG', XOOPS_ROOT_PATH . '/modules/shop/');
define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
define('DB_SERVER', XOOPS_DB_HOST); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', XOOPS_DB_USER);
define('DB_SERVER_PASSWORD', XOOPS_DB_PASS);
define('DB_DATABASE', XOOPS_DB_NAME);
define('XOOPS_SHOPDB_PREFIX', XOOPS_DB_PREFIX . '_shop');
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
define('STORE_DB_TRANSACTIONS', false);
unset($xoopsOption);
/*
  define('HTTP_SERVER', 'http://l0017021.vz.ba.de'); // eg, http://localhost - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://l0017021.vz.ba.de'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', false); // secure webserver for checkout procedure?
  define('DIR_WS_CATALOG', '/xoops2/modules/shop/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

  define('DIR_WS_DOWNLOAD_PUBLIC', DIR_WS_CATALOG . 'pub/');
  define('DIR_FS_DOCUMENT_ROOT', '/usr/local/httpd/htdocs/xoops2');
  define('DIR_FS_CATALOG', '/usr/local/httpd/htdocs/xoops2/modules/shop/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'xoops');
  define('DB_SERVER_PASSWORD', 'xoops');
  define('DB_DATABASE', 'xoops');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
*/
