<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class SpravaClankuRecenzentController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    public function odeslaniRecenze() {
        global $login;
        if (isset($_POST["hodnoceni"])) {
            $prumer = $_POST["obsah"] + $_POST["formalita"] +
                $_POST["originalita"] + $_POST["jazyk"] + $_POST["uprava"];
            $this->db->pridejRecenzi($login->getID(), $prumer, $_POST["Clanek"]);
        }
    }

    public function getRecenzeClanku($clanek) {
        return $this->db->getVsechnyRecenzeClanku($clanek);
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        //// vsechna data sablony budou globalni
        global $tplData;
        global $login;
        $tplData = [];

        $this->odeslaniRecenze();


        $tplData[] = $this->db->getClankyRecenzentaNezrecenzovane($login->getID());

        $tplDataZrecenzovano[] = $this->db->getClankyRecenzentaZrecenzovane($login->getID());


        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/SpravaClankuRecenzent.phtml");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>
