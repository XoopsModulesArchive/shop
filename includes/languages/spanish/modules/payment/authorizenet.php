<?php
/*
  $Id: authorizenet.php,v 1.1 2006/03/27 09:14:03 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TITLE', 'Authorize.net');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DESCRIPTION', 'Tarjeta de Credito para Pruebas:<br><br>Numero: 4111111111111111<br>Caducidad: Cualquiera');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_TYPE', 'Tipo de Tarjeta:');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_OWNER', 'Titular de la Tarjeta:');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_OWNER', '* El nombre del titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' caracteres.\n');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe de tener al menos ' . CC_NUMBER_MIN_LENGTH . ' numeros.\n');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR_MESSAGE', 'Ha ocurrido un error procesando su tarjeta de credito. Por gentileza, intentelo de nuevo.');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_DECLINED_MESSAGE', 'Su tarjeta ha sido denegada. Pruebe con otra tarjeta o consulte con su banco.');
define('MODULE_PAYMENT_AUTHORIZENET_TEXT_ERROR', 'Error en Tarjeta de Credito!');
