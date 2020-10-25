<?php
/*
  $Id: login.php,v 1.1 2006/03/27 09:13:54 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

if ($_GET['origin'] == FILENAME_CHECKOUT_PAYMENT) {
    define('NAVBAR_TITLE', 'Realizar Pedido');
    define('HEADING_TITLE', 'Comprar aqui es facil.');
    define('TEXT_STEP_BY_STEP', 'Le guiaremos por el proceso paso a paso.');
} else {
    define('NAVBAR_TITLE', 'Entrar');
    define('HEADING_TITLE', 'Dejame Entrar!');
    define('TEXT_STEP_BY_STEP', ''); // should be empty
}

define('HEADING_NEW_CUSTOMER', 'Nuevo Cliente');
define('TEXT_NEW_CUSTOMER', 'Soy un nuevo cliente.');
define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Al crear una cuenta en ' . STORE_NAME . ' podrá realizar sus compras rapidamente, revisar el estado de sus pedidos y consultar sus operaciones anteriores.');

define('HEADING_RETURNING_CUSTOMER', 'Ya Soy Cliente');
define('TEXT_RETURNING_CUSTOMER', 'He comprado otras veces.');
define('ENTRY_EMAIL_ADDRESS', 'Direccion E-Mail:');
define('ENTRY_PASSWORD', 'Contraseña:');

define('TEXT_PASSWORD_FORGOTTEN', '¿Ha olvidado su contraseña? Siga este enlace y se la enviamos.');

define('TEXT_LOGIN_ERROR', '<font color="#ff0000"><b>ERROR:</b></font> El \'E-Mail\' y/o \'Contraseña\' no figuran en nuestros datos.');
define('TEXT_VISITORS_CART', '<font color="#ff0000"><b>NOTA:</b></font> El contenido de su &quot;Cesta de Visitante&quot; será añadido a su &quot;Cesta de Asociado&quot; una vez que haya entrado. <a href="javascript:session_win();">[Mas Informacion]</a>');
