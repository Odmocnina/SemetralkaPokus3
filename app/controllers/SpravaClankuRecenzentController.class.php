<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s spravou clanku pro recenzenta
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

    /**
     * metoda co zajistuje poslni recenze a volanim metod database novou recenzi vytvori
     */
    public function odeslaniRecenze() {
        global $login;
        if (isset($_POST["hodnoceni"])) {
            $prumer = $_POST["obsah"] + $_POST["formalita"] +
                $_POST["originalita"] + $_POST["jazyk"] + $_POST["uprava"];
            $this->db->pridejRecenzi($login->getID(), $prumer, $_POST["Clanek"]);
        }
    }

    /**
     * metoda co zajistuje vraceni recenze k clanku k zadanemu clanku
     * @parm id clanku ke kteremu zjistujeme recenze
     */
    public function getRecenzeClanku($clanek) {
        return $this->db->getVsechnyRecenzeClanku($clanek);
    }

    /**
     * Vrati obsah stranky s spravou clanku pro recenzenta
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        //// vsechna data sablony budou globalni
        global $tplData;
        global $login;
        $tplData = [];

        $this->odeslaniRecenze();

        if ($login->isUserLogged()) {
            $tplData[] = $this->db->getClankyRecenzentaNezrecenzovane($login->getID());

            foreach ($tplData as $vnitrniPole) {
                foreach ($vnitrniPole as $clanek) {
                    foreach ($clanek as $prvek) {
                        $prvek = htmlspecialchars($prvek);
                    }
                }
            }

            $tplDataZrecenzovano[] = $this->db->getClankyRecenzentaZrecenzovane($login->getID());

            foreach ($tplDataZrecenzovano as $vnitrniPole) {
                foreach ($vnitrniPole as $clanek) {
                    foreach ($clanek as $prvek) {
                        $prvek = htmlspecialchars($prvek);
                    }
                }
            }
        }

        ob_start();
        require(DIRECTORY_VIEWS ."/SpravaClankuRecenzent.phtml");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
