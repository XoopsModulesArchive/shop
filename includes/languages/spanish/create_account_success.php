<?php
/*
  $Id: create_account_success.php,v 1.1 2006/03/27 09:13:54 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Crear una Cuenta');
define('NAVBAR_TITLE_2', 'Exito');
define('HEADING_TITLE', 'Su cuenta ha sido creada!');
define(
    'TEXT_ACCOUNT_CREATED',
    'Enhorabuena! Su cuenta ha sido creada con exito! Ahora puede disfrutar de las ventajas de disponer de una cuenta para mejorar su navegacion en nuestro catalogo. Si tiene <small><b>CUALQUIER</b></small> pregunta sobre el funcionamiento del catalogo, por favor comuniquela al <a href="'
    . tep_href_link(FILENAME_CONTACT_US)
    . '">encargado</a>.<br><br>Se ha enviado una confirmación a la direccion de correo que nos ha proporcionado. Si no lo ha recibido en 1 hora pongase en contacto con <a href="'
    . tep_href_link(FILENAME_CONTACT_US)
    . '">nosotros</a>.'
);
