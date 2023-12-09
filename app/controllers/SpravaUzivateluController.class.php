<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class SpravaUzivateluController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    public function zmenRoli() {
        global $login;
        if (isset($_POST["zmenitRoliNaPisar"])) {
            $this->db->zmeneniRole(1, $_POST["zmenitRoliNaPisar"]);
        }
        if (isset($_POST["zmenitRoliRec"])) {
            $this->db->zmeneniRole(2, $_POST["zmenitRoliRec"]);
        }
        if (isset($_POST["zmenitRoliAdmin"])) {
            $this->db->zmeneniRole(3, $_POST["zmenitRoliAdmin"]);
        }
    }

    public function zmenBanovani() {
        global $login;
        if (isset($_POST["zmenitZabanovaniZaban"])) {
            $this->db->zmenenitZabanovani(-1, $_POST["zmenitZabanovaniZaban"]);
        }
        if (isset($_POST["zmenitZabanovaniOdban"])) {
            $this->db->zmenenitZabanovani(0, $_POST["zmenitZabanovaniOdban"]);
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

        global $login;

        $this->zmenRoli();
        $this->zmenBanovani();

        $tplData = $this->db->getAllUzivatele();

        $tplDataUz[] = $this->db->getAllUzivatele();

        ob_start();
        require(DIRECTORY_VIEWS ."/SpravaUzivatelu.phtml");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
