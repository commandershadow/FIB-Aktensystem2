<?php
session_start();
  // alle Fehler anzeigen
  //error_reporting(E_ALL);
  // Fehler in der Webseite anzeigen (nicht in Produktion verwenden)
  //ini_set('display_errors', 'On');
/* DATABASE CONFIGURATION */
define('DB_SERVER', 'localhost:3306');
define('DB_USERNAME', 'commandershadow');
define('DB_PASSWORD', 'Snow23061994');
define('DB_DATABASE', 'fib_db');
define("BASE_URL", ""); // Eg. http://yourwebsite.com

//Rangezugriff VARIABLE | RANG
//Bilderupload bei neuem Bürger
$BU_RECHT = 1;
// Bürger Bilder Upload
$BUE_BU = 3;
// Bürger bearbeiten
$BUE_Bearb = 3;
//Bürger löschen
$BUE_Loesch = 7;
//Gruppierung Fraktion Und Unternehmen löschen
$grunfr_loesch = 7;
//Grunfr_bearb
$grunfr_bearb = 6;
//Buerger details
$BUE_Details = 3;
?>