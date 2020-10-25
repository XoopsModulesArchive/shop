<?php

/*
  $Id: backup.php,v 1.1 2006/03/27 08:51:50 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Copia de Seguridad de la Base de Datos');

define('TABLE_HEADING_TITLE', 'Título');
define('TABLE_HEADING_FILE_DATE', 'Fecha');
define('TABLE_HEADING_FILE_SIZE', 'Tamaño');
define('TABLE_HEADING_ACTION', 'Acción');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Nueva Copia De Seguridad');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Restaurar Localmente');
define('TEXT_INFO_NEW_BACKUP', 'No interrumpa el proceso de copia, que puede durar unos minutos.');
define('TEXT_INFO_UNPACK', '<br><br>(despues de descomprimir el archivo)');
define(
    'TEXT_INFO_RESTORE',
    'No interrumpa el proceso de restauración.<br><br>Cuanto mas grande sea la copia de seguridad, mas tardará este proceso!<br><br>Si es posible, use el cliente de mysql.<br><br>Por ejemplo:<br><br><b>mysql -h'
    . DB_SERVER
    . ' -u'
    . DB_SERVER_USERNAME
    . ' -p '
    . DB_DATABASE
    . ' < %s </b> %s'
);
define('TEXT_INFO_RESTORE_LOCAL', 'No interrumpa el proceso de restauracion.<br><br>Cuanto mas grande sea la copia de seguridad, mas tardará este proceso!');
define('TEXT_INFO_RESTORE_LOCAL_RAW_FILE', 'El fichero subido debe ser de texto.');
define('TEXT_INFO_DATE', 'Fecha:');
define('TEXT_INFO_SIZE', 'Tamaño:');
define('TEXT_INFO_COMPRESSION', 'Compresión:');
define('TEXT_INFO_USE_GZIP', 'Usar GZIP');
define('TEXT_INFO_USE_ZIP', 'Usar ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Sin Compresión (directamente SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Bajar solo (no guardar en el servidor)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Preferiblemente con una conexion segura');
define('TEXT_NO_EXTENSION', 'Ninguna');
define('TEXT_BACKUP_DIRECTORY', 'Directorio para Copias de Seguridad:');
define('TEXT_LAST_RESTORATION', 'Ultima Restauración:');
define('TEXT_FORGET', '(<u>olvidar</u>)');
define('TEXT_DELETE_INTRO', 'Seguro que quiere eliminar esta copia?');

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', 'Error: No existe el directorio de copias de seguridad.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', 'Error: No hay permiso de escritura en el directorio de copias de seguridad.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Error: Download link not acceptable.');

define('SUCCESS_LAST_RESTORE_CLEARED', 'Success: The last restoration date has been cleared.');
define('SUCCESS_DATABASE_SAVED', 'Success: The database has been saved.');
define('SUCCESS_DATABASE_RESTORED', 'Success: The database has been restored.');
define('SUCCESS_BACKUP_DELETED', 'Success: The backup has been removed.');
