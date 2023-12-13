<?php
require_once(DIRECTORY_CONTROLLERS."/IController.interface.php");

/**
 * Ovladac zajistujici vypsani stranky po uspesne registraci
 */
class UspesnaRegistraceController implements IController {

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
     * Vrati obsah stranky po uspesne registraci
     * @param string $pageTitle     Nazev stranky.
     * @return string               Vypis v sablone.
     */
    public function show():string {
        global $tplData;
        $tplData = [];
        global $login;

        ob_start();

        require(DIRECTORY_VIEWS ."/UspesnaRegistrace.php");

        $obsah = ob_get_clean();

        return $obsah;
    }

}

?>
