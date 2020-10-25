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

$xoopsOption = ['nocommon' => '1'];

require dirname(__DIR__, 3) . '/mainfile.php';

define('HTTP_SERVER', XOOPS_URL); // eg, http://localhost or - https://localhost should not be empty for productive servers
define('HTTP_CATALOG_SERVER', XOOPS_URL);
define('HTTPS_CATALOG_SERVER', XOOPS_URL);
define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
define('DIR_FS_DOCUMENT_ROOT', XOOPS_ROOT_PATH); // where the pages are located on the server
define('DIR_WS_ADMIN', '/modules/shop/admin/'); // absolute path required
define('DIR_FS_ADMIN', XOOPS_ROOT_PATH . '/modules/shop/admin/'); // absolute pate required
define('DIR_WS_CATALOG', '/modules/shop/'); // absolute path required
define('DIR_FS_CATALOG', XOOPS_ROOT_PATH . '/modules/shop/'); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
define('DB_SERVER', XOOPS_DB_HOST); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', XOOPS_DB_USER);
define('DB_SERVER_PASSWORD', XOOPS_DB_PASS);
define('XOOPS_SHOPDB_PREFIX', XOOPS_DB_PREFIX . '_shop');
define('DB_DATABASE', XOOPS_DB_NAME);
define('USE_PCONNECT', 'false'); // use persisstent connections?
define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
define('STORE_DB_TRANSACTIONS', false);

/*
  define('HTTP_SERVER', 'http://l0017021.vz.ba.de'); // eg, http://localhost or - https://localhost should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'http://l0017021.vz.ba.de');
  define('HTTPS_CATALOG_SERVER', 'https://l0017021.vz.ba.de');
  define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', '/usr/local/httpd/htdocs/xoops2'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/xoops2/modules/shop/admin/'); // absolute path required
  define('DIR_FS_ADMIN', '/usr/local/httpd/htdocs/xoops2/modules/shop/admin/'); // absolute pate required
  define('DIR_WS_CATALOG', '/xoops2/modules/shop/'); // absolute path required
  define('DIR_FS_CATALOG', '/usr/local/httpd/htdocs/xoops2/modules/shop/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
  define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'xoops');
  define('DB_SERVER_PASSWORD', 'xoops');
  define('DB_DATABASE', 'xoops');
  define('USE_PCONNECT', 'false'); // use persisstent connections?
  define('STORE_SESSIONS', ''); // leave empty '' for default handler or set to 'mysql'
*/
