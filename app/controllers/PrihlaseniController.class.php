<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");
require_once(DIRECTORY_MODELS."/MujLogin.class.php");

/**
 * Ovladac zajistujici vypsani stranky s prihlasovacim formularem
 */
class PrihlaseniController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    /**
     * metoda zajistuji prihlaseni do webu, skontroluje se jestli existuje ucet v databazi a jestli ano tak vyvori a
     * zinicializuje instani tridy login
     */
    public function prihlaseniSpachaniBezParm() {
        global $login;
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "login") {
                if ($_POST["jmeno"] && $_POST["jmeno"] != "") {
                    $poleUzivatele = $this->db->getLogin($_POST["jmeno"]);
                    if ($poleUzivatele != false) {
                        if (password_verify($_POST["heslo"], $poleUzivatele["heslo_uzivatele"])) {
                            if ($poleUzivatele["stav_zabanovani"] != "-1") {
                                $login = new MujLogin();
                                $login->login($poleUzivatele["login_uzivatele"], $poleUzivatele["id"], $poleUzivatele["role_uzivatele_id"]);
                                header("Location:index.php?page=hlavniStranka");
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Vrati obsah stranky s prihlasovacim formularem
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        $this->prihlaseniSpachaniBezParm();

        ob_start();

        require(DIRECTORY_VIEWS ."/Prihlaseni.php");

        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
