<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class SpravaClankuAdminController implements IController {

    /** @var DatabaseModel $db  Sprava databaze. */
    private $db;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
        $this->db = new DatabaseModel();
    }

    public function prirazeniRec() {
        global $login;
        if (isset($_POST["odeslaniRec"])) {
            $this->db->priradRecenzenta($_POST["value"], $_POST["valueC"]);
        }
    }

    /**
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {

        global $tplData;
        $tplData = [];
        $tplData2 = [];
        $stavCekajici = 1;
        $stavNaSchvaleni = 2;
        $roleRec = 2;

        $tplData[] = $this->db->getClankyStav($stavCekajici);

        $tplData2[] = $this->db->getClankyStav($stavNaSchvaleni);

        $tplDataRec[] = $this->db->getUzRole($roleRec);


        //// vypsani prislusne sablony
        // zapnu output buffer pro odchyceni vypisu sablony
        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/SpravaClankuAdmin.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>
