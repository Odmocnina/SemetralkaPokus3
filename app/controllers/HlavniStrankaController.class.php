<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
 */
class HlavniStrankaController implements IController {

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
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        //// vsechna data sablony budou globalni
        global $tplData;
        $tplData = [];
        $role = 3;
        $tplData[] = $this->db->getClankyStav($role);

        foreach ($tplData as $vnitrniPole) {
            foreach ($vnitrniPole as $clanek) {
                foreach ($clanek as $prvek) {
                    $prvek = htmlspecialchars($prvek);
                }
            }
        }

        ob_start();
        require(DIRECTORY_VIEWS ."/HlavniStranka.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
