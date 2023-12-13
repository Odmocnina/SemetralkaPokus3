<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s spravou clanku pro pisare
 */
class SpravaClankuPisarController implements IController {

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
     * metoda co jasistje vymazani clanku pokud uzivatel si preje clanek vymazat
     */
    public function vymazaniClanku() {
        if (isset($_POST["vymazaniClanku"])) {
            $this->db->odebratClanek($_POST["vymazaniClanku"]);
            header("Location:index.php?page=spravaclankupisar");
        }
    }

    /**
     * Vrati obsah stranky s spravou clanku pro pisare
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        global $tplData;
        $tplData = [];
        global $login;

        if ($login->isUserLogged()) {
            $tplData[] = $this->db->getClankyUz($login->getID());

            foreach ($tplData as $vnitrniPole) {
                foreach ($vnitrniPole as $clanek) {
                    foreach ($clanek as $prvek) {
                        $prvek = htmlspecialchars($prvek);
                    }
                }
            }
        }

        $this->vymazaniClanku();

        ob_start();
        require(DIRECTORY_VIEWS ."/SpravaClankuPisar.phtml");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
