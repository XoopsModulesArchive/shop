<?php
/*
  $Id: cc.php,v 1.1 2006/03/27 09:14:03 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_PAYMENT_CC_TEXT_TITLE', 'Tarjeta de Credito');
define('MODULE_PAYMENT_CC_TEXT_DESCRIPTION', 'Tarjeta de Credito para Pruebas:<br><br>Numero: 4111111111111111<br>Caducidad: Cualquiera');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_TYPE', 'Tipo de Tarjeta:');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_OWNER', 'Titular de la Tarjeta:');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
define('MODULE_PAYMENT_CC_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
define('MODULE_PAYMENT_CC_TEXT_JS_CC_OWNER', '* El titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' letras.\n');
define('MODULE_PAYMENT_CC_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n');
define('MODULE_PAYMENT_CC_TEXT_ERROR', 'Error en Tarjeta de Credito!');
