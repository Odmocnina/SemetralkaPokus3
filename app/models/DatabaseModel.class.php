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

    public function odebratRecenzenta($uzivatel, $clanek) {
        $this->pdo->beginTransaction();

        try {
            $q1 = "DELETE FROM ma_recenzovat WHERE uzivatel_id = '$uzivatel' AND clanek_id = '$clanek'";
            $this->pdo->query($q1);

            $q2 = "DELETE FROM recenze_clanku WHERE uzivatel_id = '$uzivatel' AND clanek_id = '$clanek'";
            $this->pdo->query($q2);

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollback();
            error_log("Chyba při odebrání recenzenta: " . $e->getMessage());
        }
    }

    public function odebratClanek($clanek) {
        $this->pdo->beginTransaction();

        try {
            $q1 = "DELETE FROM recenze_clanku WHERE clanek_id = :clanek";
            $q2 = "DELETE FROM ma_recenzovat WHERE clanek_id = :clanek";
            $q3 = "DELETE FROM clanek WHERE id = :clanek";

            $stmt1 = $this->pdo->prepare($q1);
            $stmt2 = $this->pdo->prepare($q2);
            $stmt3 = $this->pdo->prepare($q3);

            $stmt1->bindParam(':clanek', $clanek, PDO::PARAM_INT);
            $stmt2->bindParam(':clanek', $clanek, PDO::PARAM_INT);
            $stmt3->bindParam(':clanek', $clanek, PDO::PARAM_INT);

            $stmt1->execute();
            $stmt2->execute();
            $stmt3->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollback();
            error_log("Chyba při odebrání článku: " . $e->getMessage());
        }
    }

    public function getUzRole($cisloRole) {
        $q = "SELECT u.* from uzivatel u WHERE u.role_uzivatele_id = '$cisloRole'";

        return $this->pdo->query($q)->fetchAll();
    }


    public function getRecenztyNerecenzujiciClanek($clanek) {
        $q = "SELECT u.* from uzivatel u 
           WHERE u.role_uzivatele_id = 2 AND u.id NOT IN (SELECT mr.uzivatel_id FROM ma_recenzovat mr WHERE mr.clanek_id = $clanek)";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getUzivateleBezAkAdm($loginZadany) {
        $q = "SELECT u.* from uzivatel u WHERE u.id != '$loginZadany'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getRecenzentyClanku($clanek) {
        $q = "SELECT mr.* from ma_recenzovat mr WHERE mr.clanek_id = '$clanek'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getRecenzentyClankuJmena($clanek) {
        $q = "SELECT u.* from uzivatel u join ma_recenzovat mr on u.id = mr.uzivatel_id WHERE mr.clanek_id = '$clanek'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function zmenStavClanku($novyStav, $clanek) {
        $q = "UPDATE clanek SET stav_zrecenzovani_clanku_id = :novyStav WHERE id = :clanekID";
        $stmt = $this->pdo->prepare($q);

        $stmt->execute(['novyStav' => $novyStav, 'clanekID' => $clanek]);
    }

    public function pridejRecenzi($uzivatel, $hodnoceni, $clanek) {
        $tabulkovyVkladac = "INSERT INTO recenze_clanku (id, uzivatel_id, hodnoceni_clanku, clanek_id) 
                    VALUES (:hodnota1, :hodnota2, :hodnota3, :hodnota4)";

        $stmt = $this->pdo->prepare($tabulkovyVkladac);

        $stmt->bindParam(':hodnota1', $hodnota1);
        $stmt->bindParam(':hodnota2', $hodnota2);
        $stmt->bindParam(':hodnota3', $hodnota3);
        $stmt->bindParam(':hodnota4', $hodnota4);

        $hodnota1 = 0;
        $hodnota2 = $uzivatel;
        $hodnota3 = $hodnoceni;
        $hodnota4 = $clanek;

        $stmt->execute();
    }

    public function getClankyRecenzenta($recenzent) {
        $q = "SELECT c.* from clanek c join ma_recenzovat mr on c.id = mr.clanek_id WHERE mr.uzivatel_id = '$recenzent'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getClankyRecenzentaNezrecenzovane($recenzent) {
        $q = "SELECT c.* 
          FROM clanek c 
          LEFT JOIN ma_recenzovat mr ON c.id = mr.clanek_id 
          LEFT JOIN recenze_clanku r ON c.id = r.clanek_id AND r.uzivatel_id = '$recenzent'
          WHERE mr.uzivatel_id = '$recenzent' AND r.id IS NULL";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getClankyRecenzentaZrecenzovane($recenzent) {
        $q = "SELECT c.* 
          FROM clanek c 
          LEFT JOIN ma_recenzovat mr ON c.id = mr.clanek_id 
          LEFT JOIN recenze_clanku r ON c.id = r.clanek_id AND r.uzivatel_id = '$recenzent'
          WHERE mr.uzivatel_id = '$recenzent' AND r.id IS NOT NULL";

        return $this->pdo->query($q)->fetchAll();
    }

    public function getRecenzeClanku($clanek, $uzivatel) {
        $q = "SELECT r.* FROM recenze_clanku r WHERE r.clanek_id = '$clanek' AND r.uzivatel_id = '$uzivatel'";

        return $this->pdo->query($q)->fetch();
    }

    public function getVsechnyRecenzeClanku($clanek) {
        $q = "SELECT r.* FROM recenze_clanku r WHERE r.clanek_id = '$clanek'";

        return $this->pdo->query($q)->fetchAll();
    }

    public function priradRecenzenta($clanek, $recenzent) {
        $q = "INSERT INTO ma_recenzovat (uzivatel_id, clanek_id) VALUES (:uzivatel_id, :clanek_id);";
        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':uzivatel_id', $recenzent);
        $stmt->bindParam(':clanek_id', $clanek);

        $stmt->execute();
    }

    public function gotRecentyClanku($clanek) {
        $q = "SELECT u.* from uzivatel u join ma_recenzovat mr on u.id = mr.id_uzivatele WHERE mr.id_clanku = '$clanek'";

        return $this->pdo->query($q)->fetchAll();
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

        foreach($pole as $prvekPole) {
            $prvekPole = htmlspecialchars($prvekPole);
        }

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

        foreach($pole as $prvekPole) {
            $prvekPole = htmlspecialchars($prvekPole);
        }

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
}

?>