<?php
// nactu rozhrani kontroleru
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky se spravou osobnich udaju
 */
class SpravaOsobnichUdajuController implements IController {

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
     * metoda co zajistuje meneni osobnich udaju uzivatele pokud uzivatel zmeni sve udaje
     */
    public function zmenaUdaju() {
        global $login;
        if (isset($_POST["zmenaJmena"])) {
            $this->db->zmenJmenoUzivatele($_POST["jmeno"], $login->getID());
            header(header("Location:index.php?page=spravavlastnichudaju"));
        }
        else if (isset($_POST["zmenaPrijmeni"])) {
            $this->db->zmenPrijmeniUzivatele($_POST["prijmeni"], $login->getID());
            header(header("Location:index.php?page=spravavlastnichudaju"));
        }
        else if (isset($_POST["zmenaPrezdivky"])) {
            $this->db->zmenPrezdivkuUzivatele($_POST["prezdivka"], $login->getID());
            $login->obnoveniLoginu($_POST["prezdivka"]);
            header(header("Location:index.php?page=spravavlastnichudaju"));
        }
        else if (isset($_POST["zmenaEmailu"])) {
            $this->db->zmenEmailUzivatele($_POST["email"], $login->getID());
            header(("Location:index.php?page=spravavlastnichudaju"));
        }
    }

    /**
     * Vrati obsah stranky se spravou osobnich udaju
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        global $tplData;
        global $login;

        if ($login->isUserLogged()) {
            $tplData = $this->db->getUzivatele($login->getID());

            foreach ($tplData as $prvek) {
                $prvek = htmlspecialchars($prvek);
            }
        }

        $this->zmenaUdaju();

        ob_start();
        require(DIRECTORY_VIEWS ."/SpravaOsobnichUdaju.php");
        $obsah = ob_get_clean();

        // vratim sablonu naplnenou daty
        return $obsah;
    }

}

?>