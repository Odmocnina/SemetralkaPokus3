<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky se spravou clanku pro admina
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

    /**
     * metoda zajistuji prirazovani recenzenta k clanku
     */
    public function prirazeniRec() {
        if (isset($_POST["zvolenyRecenzent"])) {
            $this->db->priradRecenzenta($_POST["zvolenyClanek"], $_POST["zvolenyRecenzent"]);
        }
    }

    /**
     * metoda zajistuji odebrani recenzenta od clanku
     */
    public function odebraniRecenzenta() {
        if (isset($_POST["OdebraniRecenzenta"])) {
            $this->db->odebratRecenzenta($_POST["OdebraniRecenzenta"], $_POST["Clanek"]);
        }
    }

    /**
     * metoda vracejici vraceni recenzentu daneho clanku
     * @parm $clanek id clanku
     * @return recenze daneho clanku
     */
    public function getRecenzentiClanku($clanek) {
        return $this->db->getRecenzentyClankuJmena($clanek);
    }

    /**
     * metoda vracejici recenzenty co jeste nerecenzuji dany clanek
     * @parm $clanek id clanku
     * @return recenzenti co nerecenzuji clanek
     */
    public function getRecenzentyCoJesteNerecenzuji($clanek) {
        return $this->db->getRecenztyNerecenzujiciClanek($clanek);
    }

    /**
     * metoda zajistuji schvaleni/zamitnuti clanku
     */
    public function schvaleniCiZamitnutiClanku() {
        if (isset($_POST["Schvaleni"])) {
            $this->db->zmenStavClanku(3, $_POST["Schvaleni"]);
        }
        if (isset($_POST["Zamitnuti"])) {
            $this->db->zmenStavClanku(2, $_POST["Zamitnuti"]);
        }
    }

    /**
     * metoda vracejici recenzi od daneho recenzenta k danemu clanku
     * @parm $clanek id clanku
     * @parm $uzivatel uzivatel od ktereho zjistujem recenzi
     * @return recenze clanku od daneho uzivatele
     */
    public function getRecenzeClanku($clanek, $uzivatel) {
        return $this->db->getRecenzeClanku($clanek, $uzivatel);
    }

    /**
     * Vrati obsah stranky se spravou clanku pro admina
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

        foreach ($tplData as $vnitrniPole) {
            foreach ($vnitrniPole as $clanek) {
                foreach ($clanek as $prvek) {
                    $prvek = htmlspecialchars($prvek);
                }
            }
        }

        $tplData2[] = $this->db->getUzRole($roleRec);

        foreach ($tplData2 as $vnitrniPole) {
            foreach ($vnitrniPole as $recenzent) {
                foreach ($recenzent as $prvek) {
                    $prvek = htmlspecialchars($prvek);
                }
            }
        }


        ob_start();
        require(DIRECTORY_VIEWS ."/SpravaClankuAdmin.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
