<?php

// vynuceni chybovych vypisu na serveru students.kiv.zcu.cz
// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

define('FORCE_SSL_LOGIN', true);
define('FORCE_SSL_ADMIN', true);
define( 'CONCATENATE_SCRIPTS', false );
define( 'SCRIPT_DEBUG', true );

// nactu vlastni nastaveni webu
require_once("settings.inc.php");

// nactu tridu spoustejici aplikaci
require_once("app/ApplicationStart.class.php");

// spustim aplikaci
$app = new ApplicationStart();
$app->appStart();

?>
