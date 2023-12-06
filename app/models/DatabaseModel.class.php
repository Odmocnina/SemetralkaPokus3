<?php

/**
 * Trida spravujici databazi.
 */
class DatabaseModel {

    /** @var PDO $pdo  Objekt pracujici s databazi prostrednictvim PDO. */
    private $pdo;

    /**
     * Inicializace pripojeni k databazi.
     */
    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->pdo->exec("set names utf8");
    }
    
    
    /**
     *  Vrati seznam vsech uzivatelu pro spravu uzivatelu.
     *  @return array Obsah spravy uzivatelu.
     */
    public function getAllUzivatele():array {
        $q = "SELECT * FROM ".TABLE_UZIVATEL;

        return $this->pdo->query($q)->fetchAll();
    }

    public function getAllClanky():array {
        // pripravim dotaz
        $q = "SELECT * FROM ".TABLE_CLANEK;


        // provedu a vysledek vratim jako pole
        return $this->pdo->query($q)->fetchAll();
    }

    public function getLogin($loginZadany) {
        $q = "SELECT u.*, ur.role_uzivatele from uzivatel u join uzivatel_role ur on u.role_uzivatele_id = ur.id 
                    WHERE u.login_uzivatele = '$loginZadany'";

        $q = "SELECT u.* from uzivatel u WHERE u.login_uzivatele = '$loginZadany'";

        return $this->pdo->query($q)->fetch();
    }

    public function getClankyUz($idPisare) {
        $q = "SELECT c.* from clanek c WHERE c.uzivatel_id = '$idPisare'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getClankyStav($cisloStavu) {
        $q = "SELECT c.* from clanek c WHERE c.stav_zrecenzovani_clanku_id = '$cisloStavu'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getUzRole($cisloRole) {
        $q = "SELECT u.* from uzivatel u WHERE u.role_uzivatele_id = '$cisloRole'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getUzivateleBezAkAdm($loginZadany) {
        $q = "SELECT u.* from uzivatel u WHERE u.id != '$loginZadany'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getRecenzentyClanku($id_clanku) {
        $q = "SELECT mr.* from ma_recenzovat mr WHERE mr.clanek_id = '$id_clanku'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function priradRecenzenta($clanek, $recenzent) {
        $q = "INSERT INTO ma_recenzovat (uzivatel_id, clanek_id) VALUES ($recenzent, $clanek);";
        $stmt = $this->pdo->prepare($q);

        $stmt->execute([$recenzent, $clanek]);
    }

    public function zmeneniRole($noveRole, $MenenyUzivatelID) {
        $q = "UPDATE uzivatel SET role_uzivatele_id = :novaRole WHERE id = :uzivatelID";
        $stmt = $this->pdo->prepare($q);

        $stmt->execute(['novaRole' => $noveRole, 'uzivatelID' => $MenenyUzivatelID]);
    }

    public function zmenenitZabanovani($noveBanovani, $MenenyUzivatelID) {
        $q = "UPDATE uzivatel SET stav_zabanovani = :noveBanovani WHERE id = :uzivatelID";
        $stmt = $this->pdo->prepare($q);

        $stmt->execute(['noveBanovani' => $noveBanovani, 'uzivatelID' => $MenenyUzivatelID]);
    }

    public function pridatUzivatele($pole) {

        $q = "SELECT * FROM ".TABLE_UZIVATEL;
        $tplData[] = $this->pdo->query($q)->fetchAll();

        foreach ($tplData as $vnitrniPole) {
            foreach ($vnitrniPole as $uzivatel) {
                if ($uzivatel["email_uzivatele"] == $pole["email"]) {
                    return false;
                }
                if ($uzivatel["login_uzivatele"] == $pole["prezdivka"]) {
                    return false;
                }
            }
        }

        if ($pole["heslo"] != $pole["heslo_znova"]) {
            return false;
        }

        $tabulkovyVkladac = $this->pdo->prepare("INSERT INTO uzivatel (email_uzivatele, prijmeni_uzivatele, jmeno_uzivatele, id, login_uzivatele, heslo_uzivatele, role_uzivatele_id) 
                    VALUES (:hodnota1, :hodnota2, :hodnota3, :hodnota4, :hodnota5, :hodnota6, :hodnota7)");

        $tabulkovyVkladac->bindParam(':hodnota1', $hodnota1);
        $tabulkovyVkladac->bindParam(':hodnota2', $hodnota2);
        $tabulkovyVkladac->bindParam(':hodnota3', $hodnota3);
        $tabulkovyVkladac->bindParam(':hodnota4', $hodnota4);
        $tabulkovyVkladac->bindParam(':hodnota5', $hodnota5);
        $tabulkovyVkladac->bindParam(':hodnota6', $hodnota6);
        $tabulkovyVkladac->bindParam(':hodnota7', $hodnota7);

        $hodnota1 = $pole["email"];
        $hodnota2 = $pole["prijmeni"];
        $hodnota3 = $pole["jmeno"];
        $hodnota4 = 0;
        $hodnota5 = $pole["prezdivka"];
        //$hodnota6 = password_hash($pole["heslo"], PASSWORD_BCRYPT);
        $hodnota6 = $pole["heslo"];
        $hodnota7 = 1;

        $tabulkovyVkladac->execute();

        return true;
    }

    public function pridatClanek($pole, $id_uzivatele):bool {

        $q = "SELECT * FROM ".TABLE_UZIVATEL;

        $tabulkovyVkladac = $this->pdo->prepare("INSERT INTO clanek (autori, nazev_clanku, id, stav_zrecenzovani_clanku_id, clanek_abstrakt, uzivatel_id, odkaz) 
                    VALUES (:hodnota1, :hodnota2, :hodnota3, :hodnota4, :hodnota5, :hodnota6, :hodnota7)");

        $tabulkovyVkladac->bindParam(':hodnota1', $hodnota1);
        $tabulkovyVkladac->bindParam(':hodnota2', $hodnota2);
        $tabulkovyVkladac->bindParam(':hodnota3', $hodnota3);
        $tabulkovyVkladac->bindParam(':hodnota4', $hodnota4);
        $tabulkovyVkladac->bindParam(':hodnota5', $hodnota5);
        $tabulkovyVkladac->bindParam(':hodnota6', $hodnota6);
        $tabulkovyVkladac->bindParam(':hodnota7', $hodnota7);

        $hodnota1 = $pole["autori"];
        $hodnota2 = $pole["name"];
        $hodnota3 = 0;
        $hodnota4 = 1;
        $hodnota5 = $pole["abstrakt"];
        $hodnota6 = $id_uzivatele;
        $hodnota7 = $pole["soubor"];

        $tabulkovyVkladac->execute();

        return true;
    }

    public function zmenRoliUzivatele($uzivatel, $novaRole) {
        //$uzivatel["role_uzivatele_id"]

    }
    
    /**
     *  Smaze daneho uzivatele z DB.
     *  @param int $userId  ID uzivatele.
     */
    public function deleteUser(int $userId):bool {
        // pripravim dotaz
        $q = "DELETE FROM ".TABLE_USER." WHERE id_user = $userId";
        // provedu dotaz
        $res = $this->pdo->query($q);
        // pokud neni false, tak vratim vysledek, jinak null
        if ($res) {
            // neni false
            return true;
        } else {
            // je false
            return false;
        }
    }
    
}

?>