<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky s registracnim formularem
 */
class RegistraceController implements IController {

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
     * metoda zajistuji pridani noveho uzivatele
     */
    public function pridejUzivatele() {
        $povedlaSeRegistrace = false;
        global $login;

        if (isset($_POST["action"])) {
            if ($_POST["action"] == "registrace") {
                $povedlaSeRegistrace = $this->db->pridatUzivatele($_POST);
                if ($povedlaSeRegistrace) {
                    header("Location:index.php?page=uspesnaregistrace");
                }
            }
        }
    }

    /**
     * Vrati obsah stranky s registracnim formularem
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {

        $this->pridejUzivatele();


        ob_start();
        require(DIRECTORY_VIEWS ."/Registrace.php");
        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
