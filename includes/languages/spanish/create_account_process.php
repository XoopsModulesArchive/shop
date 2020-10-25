<?php
/*
  $Id: create_account_process.php,v 1.1 2006/03/27 09:13:54 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Crear una Cuenta');
define('NAVBAR_TITLE_2', 'Proceso');
define('HEADING_TITLE', 'Informacion de Mi Cuenta');

define('EMAIL_SUBJECT', 'Bienvenido a ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Estimado ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_MS', 'Estimado ' . stripslashes($_POST['lastname']) . ',' . "\n\n");
define('EMAIL_GREET_NONE', 'Estimado ' . stripslashes($_POST['firstname']) . ',' . "\n\n");
define('EMAIL_WELCOME', 'le damos la bienvenida a <b>' . STORE_NAME . '</b>.' . "\n\n");
define(
    'EMAIL_TEXT',
    'Ahora puede disfrutar de los <b>servicios</b> que le ofrecemos. Algunos de estos servicios son:'
    . "\n\n"
    . '<li><b>Carrito Permanente</b> - Cualquier producto añadido a su carrito permanecera en el hasta que lo elimine, o hasta que realice la compra.'
    . "\n"
    . '<li><b>Libro de Direcciones</b> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumpleaños directamente a la persona que cumple años.'
    . "\n"
    . '<li><b>Historia de Pedidos</b> - Vea la relacion de compras que ha realizado con nosotros.'
    . "\n"
    . '<li><b>Comentarios</b> - Comparta su opinion sobre los productos con otros clientes.'
    . "\n\n"
);
define('EMAIL_CONTACT', 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Nota:</b> Esta direccion fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuniquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
