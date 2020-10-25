<?php
/*
  $Id: default.php,v 1.1 2006/03/27 09:13:10 mikhail Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

define(
    'TEXT_MAIN',
    'Dies ist die Standardinstallation des osCommerce Projektes. Alle dargestellten Produkte dienen zur Demonstration der Funktionsweise. <b>Wenn Sie Produkte bestellen, so werden diese weder ausgeliefert, noch in Rechnung gestellt.</b> Alle Informationen zu den verschiedenen Produkten sind erfunden und daher kann kein Anspruch daraus abgeleitet werden.<br><br>Sollten Sie daran interessiert sein das Programm, welches die Grundlage f&uuml;r diesen Shop bildet, einzusetzen, so besuchen Sie bitte die <a href="http://www.oscommerce.de"><u>Supportseite von osCommerce</u></a>. Dieser Shop basiert auf der osCommerce Version <font color="#f0000"><b>'
    . PROJECT_VERSION
    . '</b></font>.<br><br>Der hier dargestellte Text kann in der folgenden Datei einer jeden Sprache ge&auml;ndert werden: [Pfad&nbsp;zu&nbsp;catalog]/includes/languages/[language]/default.php.<br><br>Das kann manuell geschehen, oder &uuml;ber das Administration Tool mit Sprache->[language]->Sprache definieren, oder durch Verwendung des Hilfsprogrammes->Datei Manager.'
);
define('TABLE_HEADING_NEW_PRODUCTS', 'Neue Produkte im %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Wann ist was verf&uuml;gbar');
define('TABLE_HEADING_DATE_EXPECTED', 'Datum');

if (($category_depth == 'products') || ($_GET['manufacturers_id'])) {
    define('HEADING_TITLE', 'Unser Angebot');
    define('TABLE_HEADING_IMAGE', '');
    define('TABLE_HEADING_MODEL', 'Artikel-Nr.');
    define('TABLE_HEADING_PRODUCTS', 'Produkte');
    define('TABLE_HEADING_MANUFACTURER', 'Hersteller');
    define('TABLE_HEADING_QUANTITY', 'Anzahl');
    define('TABLE_HEADING_PRICE', 'Preis');
    define('TABLE_HEADING_WEIGHT', 'Gewicht');
    define('TABLE_HEADING_BUY_NOW', 'Bestellen');
    define('TEXT_NO_PRODUCTS', 'Es gibt keine Produkte in dieser Kategorie.');
    define('TEXT_NO_PRODUCTS2', 'Es gibt kein Produkt, das von diesem Hersteller stammt.');
    define('TEXT_NUMBER_OF_PRODUCTS', 'Artikel: ');
    define('TEXT_SHOW', '<b>Darstellen:</b>');
    define('TEXT_BUY', '1 x \'');
    define('TEXT_NOW', '\' bestellen!');
    define('TEXT_ALL', 'Alle');
} elseif ($category_depth == 'top') {
    define('HEADING_TITLE', 'Unser Angebot');
} elseif ($category_depth == 'nested') {
    define('HEADING_TITLE', 'Kategorien');
}
