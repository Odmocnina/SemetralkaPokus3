<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky na nahrani clanku
 */
class NahraniClankuController implements IController {

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
     * metoda zajistuji pridani noceho clanku a stazeni pdf souboru co byl na stranku nahran
     */
    public function pridejClanek() {
        global $login;
        if (isset($_POST["action"])) {
            if ($_POST["action"] == "odeslatClanek") {
                $hledanySoubor = "NahraneClanky/" . basename($_FILES["soubor"]["name"]);
                if (!file_exists($hledanySoubor)) {
                    var_dump("dsas");
                    move_uploaded_file($_FILES["soubor"]["tmp_name"], $hledanySoubor);
                }
                $this->db->pridatClanek($_POST, $_FILES["soubor"]["name"], $login->getID());
            }
        }
    }

    /**
     * Vrati obsah stranky na nahrani clanku.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {

        $this->pridejClanek();

        ob_start();
        require(DIRECTORY_VIEWS ."/NahraniClanku.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
