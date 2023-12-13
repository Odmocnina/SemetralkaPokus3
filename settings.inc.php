<?php
//////////////////////////////////////////////////////////////////
/////////////////  Globalni nastaveni aplikace ///////////////////
//////////////////////////////////////////////////////////////////

//// Pripojeni k databazi ////

/** Adresa serveru. */
define("DB_SERVER","localhost");
/** Nazev databaze. */
define("DB_NAME","databasesemestralka");
/** Uzivatel databaze. */
define("DB_USER","root");
/** Heslo uzivatele databaze */
define("DB_PASS","");


//// Nazvy tabulek v DB ////

/** Tabulka s pohadkami. */
define("TABLE_UZIVATEL", "UZIVATEL");

define("TABLE_CLANEK", "clanek");


//// Dostupne stranky webu ////

/** Adresar kontroleru. */
const DIRECTORY_CONTROLLERS = "app/Controllers";
/** Adresar modelu. */
const DIRECTORY_MODELS = "app/Models";
/** Adresar sablon */
const DIRECTORY_VIEWS = "app/Views";

/** Klic defaultni webove stranky. */
const DEFAULT_WEB_PAGE_KEY = "hlavnistranka";

const DEFAULT_WEB_PAGE_KEY_HLAVA = "hlavicka";

/** Dostupne webove stranky. */
const WEB_PAGES = array(

    "hlavicka" => array(
        "file_name" => "HlavickaController.class.php",
        "class_name" => "HlavickaController",
    ),

    //// Uvodni stranka ////
    "hlavnistranka" => array(
        "file_name" => "HlavniStrankaController.class.php",
        "class_name" => "HlavniStrankaController",
    ),
    //// KONEC: Uvodni stranka ////

    "prihlaseni" => array(
        "file_name" => "PrihlaseniController.class.php",
        "class_name" => "PrihlaseniController",
    ),

    "spravauzivatelu" => array(
        "file_name" => "SpravaUzivateluController.class.php",
        "class_name" => "SpravaUzivateluController",
    ),

    "nahraniclanku" => array(
        "file_name" => "NahraniClankuController.class.php",
        "class_name" => "NahraniClankuController",
    ),

    "registrace" => array(
        "file_name" => "RegistraceController.class.php",
        "class_name" => "RegistraceController",
    ),

    "spravaclankuadmin" => array(
        "file_name" => "SpravaClankuAdminController.class.php",
        "class_name" => "SpravaClankuAdminController",
    ),

    "spravaclankupisar" => array(
        "file_name" => "SpravaClankuPisarController.class.php",
        "class_name" => "SpravaClankuPisarController",
    ),

    "spravaclankurecenzent" => array(
        "file_name" => "SpravaClankuRecenzentController.class.php",
        "class_name" => "SpravaClankuRecenzentController",
    ),

    "odhlaseni" => array(
        "file_name" => "OdhlaseniController.class.php",
        "class_name" => "OdhlaseniController",
    ),

    "uspesnaregistrace" => array(
        "file_name" => "UspesnaRegistraceController.class.php",
        "class_name" => "UspesnaRegistraceController",
    ),

    "spravavlastnichudaju" => array(
        "file_name" => "SpravaOsobnichUdajuController.class.php",
        "class_name" => "SpravaOsobnichUdajuController",
    ),

);

?>
