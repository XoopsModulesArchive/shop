<?php
/*
  $Id: ipayment.php,v 1.1 2006/03/27 09:14:03 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('MODULE_PAYMENT_IPAYMENT_TEXT_TITLE', 'iPayment');
define('MODULE_PAYMENT_IPAYMENT_TEXT_DESCRIPTION', 'Tarjeta de Credito para Pruebas:<br><br>Numero: 4111111111111111<br>Caducidad: Cualquiera');
define('IPAYMENT_ERROR_HEADING', 'Ha ocurrido un error procesando su tarjeta de credito');
define('IPAYMENT_ERROR_MESSAGE', '¡Revise los datos de su tarjeta de credito!');
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_OWNER', 'Titular de la Tarjeta:');
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_NUMBER', 'Numero de la Tarjeta:');
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_EXPIRES', 'Fecha de Caducidad:');
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_CHECKNUMBER', 'Numero de Comprobacion:');
define('MODULE_PAYMENT_IPAYMENT_TEXT_CREDIT_CARD_CHECKNUMBER_LOCATION', '(lo puede encontrar en la parte de atras de la tarjeta de credito)');

define('MODULE_PAYMENT_IPAYMENT_TEXT_JS_CC_OWNER', '* El nombre del titular de la tarjeta de credito debe de tener al menos ' . CC_OWNER_MIN_LENGTH . ' caracteres.\n');
define('MODULE_PAYMENT_IPAYMENT_TEXT_JS_CC_NUMBER', '* El numero de la tarjeta de credito debe tener al menos ' . CC_NUMBER_MIN_LENGTH . ' caracteres.\n');
