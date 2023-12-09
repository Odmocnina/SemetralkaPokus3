<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani uvodni stranky.
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
     * Vrati obsah uvodni stranky.
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {

        $this->pridejUzivatele();


        ob_start();
        // pripojim sablonu, cimz ji i vykonam
        require(DIRECTORY_VIEWS ."/Registrace.php");
        // ziskam obsah output bufferu, tj. vypsanou sablonu
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>
