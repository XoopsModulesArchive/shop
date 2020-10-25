<?php
/*
  $Id: default.php,v 1.1 2006/03/27 09:13:54 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

define(
    'TEXT_MAIN',
    'Esta es la configuracion por defecto de osCommerce, los productos mostrados aqui son unicamente para demonstracion, <b>cualquier compra realizada no será entregada al cliente, ni se le cobrara</b>. Cualquier informacion que vea sobre estos productos debe ser tratada como ficticia.<br><br>Si desea descargar la solucion que hace posible esta tienda, o si quiere contribuir al proyecto de osCommerce, por favor visite <a href="http://oscommerce.com"><u>la web de soporte de osCommerce</u></a>. Esta tienda corre bajo la version <font color="#f0000"><b>'
    . PROJECT_VERSION
    . '</b></font>.<br><br>Este texto se puede cambiar editando el siguiente fichero, uno por cada idioma: [camino&nbsp;al&nbsp;catalogo]/includes/languages/[language]/default.php.<br><br>Puede editarlo manualmente, o a traves de la Herramienta de Administracion con la opcion Idiomas->[idioma]->Definir, o utilizando el Herramientas->Administrador de Ficheros.'
);
define('TABLE_HEADING_NEW_PRODUCTS', 'Nuevos Productos En %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Proximamente');
define('TABLE_HEADING_DATE_EXPECTED', 'Lanzamiento');

if (($category_depth == 'products') || ($_GET['manufacturers_id'])) {
    define('HEADING_TITLE', 'A ver que tenemos aqui');
    define('TABLE_HEADING_IMAGE', '');
    define('TABLE_HEADING_MODEL', 'Modelo');
    define('TABLE_HEADING_PRODUCTS', 'Productos');
    define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
    define('TABLE_HEADING_QUANTITY', 'Cantidad');
    define('TABLE_HEADING_PRICE', 'Precio');
    define('TABLE_HEADING_WEIGHT', 'Peso');
    define('TABLE_HEADING_BUY_NOW', 'Compre Ahora');
    define('TEXT_NO_PRODUCTS', 'No hay productos en esta categoria.');
    define('TEXT_NO_PRODUCTS2', 'No hay productos de este fabricante.');
    define('TEXT_NUMBER_OF_PRODUCTS', 'Numero de Productos: ');
    define('TEXT_SHOW', '<b>Mostrar:</b>');
    define('TEXT_BUY', 'Compre 1 \'');
    define('TEXT_NOW', '\' ahora');
    define('TEXT_ALL', 'All');
} elseif ($category_depth == 'top') {
    define('HEADING_TITLE', '¿Que hay de nuevo por aqui?');
} elseif ($category_depth == 'nested') {
    define('HEADING_TITLE', 'Categorias');
}
