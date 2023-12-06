<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");
require_once(DIRECTORY_MODELS."/MujLogin.class.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
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

    public function prihlaseniSpachaniBezParm() {
        global $login;
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "login") {
                if ($_POST["jmeno"] && $_POST["jmeno"] != "") {
                    $poleUzivatele = $this->db->getLogin($_POST["jmeno"]);
                    if ($poleUzivatele != false) {
                        //if (password_verify($_POST["heslo"], $poleUzivatele["heslo_uzivatele"])) {
                        if ($_POST["heslo"] == $poleUzivatele["heslo_uzivatele"]) {
                            if ($poleUzivatele["stav_zabanovani"] != "-1") {
                                $login = new MujLogin();
                                $login->login($poleUzivatele["login_uzivatele"], $poleUzivatele["id"], $poleUzivatele["role_uzivatele_id"]);
                                //$_SESSION["prihlasenyUzivatel"] = $poleUzivatele["login_uzivatele"];
                                //$_SESSION["role_uzivatele"] = $poleUzivatele["role_uzivatele_id"];
                                //$_SESSION["id_prihlasenyUzivatel"] = $poleUzivatele["id"];
                                header("Location:index.php?page=hlavniStranka");
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];

        $this->prihlaseniSpachaniBezParm();

        $tplData = $this->db->getAllUzivatele();

        ob_start();

        require(DIRECTORY_VIEWS ."/Prihlaseni.php");

        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
