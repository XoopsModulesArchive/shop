<?php
/*
  $Id: shopping_cart.php,v 1.1 2006/03/27 09:13:54 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Contenido de la Cesta');
define('HEADING_TITLE', 'Que hay en mi Cesta?');
define('TABLE_HEADING_REMOVE', 'Quitar');
define('TABLE_HEADING_QUANTITY', 'Cantidad');
define('TABLE_HEADING_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Producto(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TEXT_CART_EMPTY', 'Tu Cesta de la Compra esta vacia!');
define('SUB_TITLE_SUB_TOTAL', 'Subtotal:');
define('SUB_TITLE_TOTAL', 'Total:');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Los productos marcados con ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' no estan disponibles en la cantidad que requiere.<br>Modifique la cantidd de productos marcados con ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ', Gracias');
define(
    'OUT_OF_STOCK_CAN_CHECKOUT',
    'Los productos marcados con ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' no estan disponibles en cantidad que requiere.<br>De todas formas, puede comprar los que hay disponibles y el resto se lo enviamos mas tarde o esperar a que la cantidad requerida este disponible.'
);
