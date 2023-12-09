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
        if (isset($_POST["zvolenyRecenzent"])) {
            $this->db->priradRecenzenta($_POST["zvolenyClanek"], $_POST["zvolenyRecenzent"]);
        }
    }

    public function odebraniRecenzenta() {
        if (isset($_POST["OdebraniRecenzenta"])) {
            $this->db->odebratRecenzenta($_POST["OdebraniRecenzenta"], $_POST["Clanek"]);
        }
    }

    public function getRecenzentiClanku($clanek) {
        return $this->db->getRecenzentyClankuJmena($clanek);
    }

    public function getRecenzentyCoJesteNerecenzuji($clanek) {
        return $this->db->getRecenztyNerecenzujiciClanek($clanek);
    }

    public function schvaleniCiZamitnutiClanku() {
        if (isset($_POST["Schvaleni"])) {
            $this->db->zmenStavClanku(3, $_POST["Schvaleni"]);
        }
        if (isset($_POST["Zamitnuti"])) {
            $this->db->zmenStavClanku(2, $_POST["Zamitnuti"]);
        }
    }

    public function getRecenzeClanku($clanek, $uzivatel) {
        return $this->db->getRecenzeClanku($clanek, $uzivatel);
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
        $roleRec = 2;

        $this->prirazeniRec();
        $this->odebraniRecenzenta();
        $this->schvaleniCiZamitnutiClanku();

        $tplData[] = $this->db->getClankyStav($stavCekajici);

        $tplData2[] = $this->db->getUzRole($roleRec);


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
