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
    public function getAllUzivatele(): array {
        $q = "SELECT * FROM " . TABLE_UZIVATEL;
        $stmt = $this->pdo->prepare($q);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  Vrati daneho uzivatele z databaze pod zadaneho id
     *  @parm $uzivatel id hledaneho uzivatele
     *  @return array dany uzivatel
     */
    public function getUzivatele($uzivatel):array {
        $q = "SELECT u.* FROM uzivatel u WHERE u.id = '$uzivatel'";

        return $this->pdo->query($q)->fetch();
    }

    /**
     *  Vrati uzivatele s login (prezdivku) zadanou
     *  @parm $uzivatel id uzivatel jehoz login zjistujem
     *  @return array dany uzivatel
     */
    public function getLogin($loginZadany) {
        $q = "SELECT u.*, ur.role_uzivatele from uzivatel u join uzivatel_role ur on u.role_uzivatele_id = ur.id 
                    WHERE u.login_uzivatele = '$loginZadany'";

        $q = "SELECT u.* from uzivatel u WHERE u.login_uzivatele = '$loginZadany'";

        return $this->pdo->query($q)->fetch();
    }

    /**
     *  Vrati clanky daneho uzivatele
     *  @parm $uzivatel id uzivatel jehoz clanky chceme
     *  @return array clanky daneho uzivatele
     */
    public function getClankyUz($idPisare) {
        $stmt = $this->pdo->prepare("SELECT c.* FROM clanek c WHERE c.uzivatel_id = :idPisare");

        $stmt->bindParam(':idPisare', $idPisare);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  Vrati clanky v danem stavu
     *  @parm $cisloStavu stav ve kterem chceme clanky
     *  @return array clanky v danem stavu
     */
    public function getClankyStav($cisloStavu) {
        $stmt = $this->pdo->prepare("SELECT c.* FROM clanek c WHERE c.stav_zrecenzovani_clanku_id = :cisloStavu");

        $stmt->bindParam(':cisloStavu', $cisloStavu);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  odebere z tabulky ma_recenzovat pravek, odebere prirazeni recenzovani recenzentovi
     *  @parm $uzivatel id uzivatele ktereho odebirame od recenzovani
     *  @parm $uzivatel id clanek kteremu rusime recenzovani
     */
    public function odebratRecenzenta($uzivatel, $clanek) {
        $this->pdo->beginTransaction();

        try {
            $q1 = "DELETE FROM ma_recenzovat WHERE uzivatel_id = :uzivatel AND clanek_id = :clanek";
            $stmt1 = $this->pdo->prepare($q1);
            $stmt1->bindParam(':uzivatel', $uzivatel);
            $stmt1->bindParam(':clanek', $clanek);
            $stmt1->execute();

            $q2 = "DELETE FROM recenze_clanku WHERE uzivatel_id = :uzivatel AND clanek_id = :clanek";
            $stmt2 = $this->pdo->prepare($q2);
            $stmt2->bindParam(':uzivatel', $uzivatel);
            $stmt2->bindParam(':clanek', $clanek);
            $stmt2->execute();

            $this->pdo->commit();
        } catch (PDOException $e) {
            $this->pdo->rollback();
            error_log("Chyba při odebrání recenzenta: " . $e->getMessage());
        }
    }

    /**
     *  odebere clanek z databaze
     *  @parm $clanek id clanku co odebirame
     */
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

    /**
     *  zmeni v databazi jmeno uzivatele
     *  @parm $noveJmeno nove jmeno uzivatele
     *  @parm $uzivatel id uzivatele kteremu menime jmeno
     */
    public function zmenJmenoUzivatele($noveJmeno, $uzivatel) {
        $noveJmeno = htmlspecialchars($noveJmeno);
        $uzivatel = htmlspecialchars($uzivatel);

        $q = "UPDATE uzivatel SET jmeno_uzivatele = :noveJmeno WHERE id = :uzivatelID";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':noveJmeno', $noveJmeno);
        $stmt->bindParam(':uzivatelID', $uzivatel);

        //$hodnota1 = $noveJmeno;
        //$hodnota2 = $uzivatel;

        $stmt->execute();
    }

    /**
     *  zmeni v databazi prijmeni uzivatele
     *  @parm $novePrijmeni nove prijmeni uzivatele
     *  @parm $uzivatel id uzivatele kteremu menime prijmeni
     */
    public function zmenPrijmeniUzivatele($novePrijmeni, $uzivatel) {
        $novePrijmeni = htmlspecialchars($novePrijmeni);
        $uzivatel = htmlspecialchars($uzivatel);

        $q = "UPDATE uzivatel SET prijmeni_uzivatele = :novePrijmeni WHERE id = :uzivatelID";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':novePrijmeni', $novePrijmeni);
        $stmt->bindParam(':uzivatelID', $uzivatel);

        //$hodnota1 = $novePrijmeni;
        //$hodnota2 = $uzivatel;

        $stmt->execute();
    }

    /**
     *  zmeni v databazi prezdivku uzivatele
     *  @parm $novaPrezdivku nova prezdivka uzivatele
     *  @parm $uzivatel id uzivatele kteremu menime prezdivku
     */
    public function zmenPrezdivkuUzivatele($novaPrezdivka, $uzivatel) {
        $novaPrezdivka = htmlspecialchars($novaPrezdivka);
        $uzivatel = htmlspecialchars($uzivatel);

        $q = "UPDATE uzivatel SET login_uzivatele = :novaPrezdivka WHERE id = :uzivatelID";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':novaPrezdivka', $novaPrezdivka);
        $stmt->bindParam(':uzivatelID', $uzivatel);

        //$hodnota1 = $novaPrezdivka;
        //$hodnota2 = $uzivatel;

        $stmt->execute();
    }

    /**
     *  zmeni v databazi email uzivatele
     *  @parm $noveEmail novy email uzivatele
     *  @parm $uzivatel id uzivatele kteremu menime email
     */
    public function zmenEmailUzivatele($novyEmail, $uzivatel) {
        $novyEmail = htmlspecialchars($novyEmail);
        $uzivatel = htmlspecialchars($uzivatel);

        $q = "UPDATE uzivatel SET email_uzivatele = :novyEmail WHERE id = :uzivatelID";

        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':novyEmail', $novyEmail);
        $stmt->bindParam(':uzivatelID', $uzivatel);

        //$hodnota1 = $novyEmail;
        //$hodnota2 = $uzivatel;

        $stmt->execute();
    }

    /**
     *  varti uzivatele zadane role
     *  @parm $cisloRole role od ktere chceme zjistit vsechny uzivatele
     *  @return array pole uzivatelu zadane role
     */
    public function getUzRole($cisloRole) {
        $stmt = $this->pdo->prepare("SELECT u.* FROM uzivatel u WHERE u.role_uzivatele_id = :cisloRole");

        $stmt->bindParam(':cisloRole', $cisloRole);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  varti uzivatele (recenzenty) co nerecenzuji dany clanek
     *  @parm $clanek id clanku u ktereho zjistujem nerecenzujici recenzenty
     *  @return array pole recenzentu nerecenzujici dany clanek
     */
    public function getRecenztyNerecenzujiciClanek($clanek) {
        $stmt = $this->pdo->prepare("SELECT u.* FROM uzivatel u 
        WHERE u.role_uzivatele_id = 2 AND u.id NOT IN (SELECT mr.uzivatel_id FROM ma_recenzovat mr WHERE mr.clanek_id = :clanek)");

        $stmt->bindParam(':clanek', $clanek);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  varti uzivatele (recenzenty) co recenzuji dany clanek
     *  @parm $clanek id clanku u ktereho zjistujem nerecenzujici recenzenty
     *  @return array pole recenzentu recenzujici dany clanek
     */
    public function getRecenzentyClankuJmena($clanek) {
        $stmt = $this->pdo->prepare("SELECT u.* FROM uzivatel u JOIN ma_recenzovat mr ON u.id = mr.uzivatel_id WHERE mr.clanek_id = :clanek");

        $stmt->bindParam(':clanek', $clanek);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  zmeni stav clanku, spok byl schvalen, ci zamitnut
     *  @parm $novyStav stav na ktery dany clanek menime
     *  @parm $clanek id daneho clanku
     */
    public function zmenStavClanku($novyStav, $clanek) {
        $q = "UPDATE clanek SET stav_zrecenzovani_clanku_id = :novyStav WHERE id = :clanekID";
        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':novyStav', $novyStav);
        $stmt->bindParam(':clanekID', $clanek);

        $stmt->execute();
    }

    /**
     *  prida do databze novou recenzi
     *  @parm $uzivatel id uzviatele (recenzenta) co udelal recenzi
     *  @parm $hodnoceni soucet bodu co recenzent clanku dal
     *  @parm $clanek id clanku co byl recenzovan
     */
    public function pridejRecenzi($uzivatel, $hodnoceni, $clanek) {
        $tabulkovyVkladac = "INSERT INTO recenze_clanku (id, uzivatel_id, hodnoceni_clanku, clanek_id) 
                    VALUES (:hodnota1, :hodnota2, :hodnota3, :hodnota4)";

        $stmt = $this->pdo->prepare($tabulkovyVkladac);

        $i = 0;
        $stmt->bindParam(':hodnota1', $i);
        $stmt->bindParam(':hodnota2', $uzivatel);
        $stmt->bindParam(':hodnota3', $hodnoceni);
        $stmt->bindParam(':hodnota4', $clanek);

        //$hodnota1 = 0;
        //$hodnota2 = $uzivatel;
        //$hodnota3 = $hodnoceni;
        //$hodnota4 = $clanek;

        $stmt->execute();
    }

    /**
     *  vrati pole clanku co ma uzivatel (recenzent) zrecenzovat a jeste je nezrecenzoval
     *  @parm $recenzent id recenzenta
     *  @return array pole nezrecovanych clanku daneho recenzenta
     */
    public function getClankyRecenzentaNezrecenzovane($recenzent) {
        $stmt = $this->pdo->prepare("SELECT c.* 
                                    FROM clanek c 
                                    LEFT JOIN ma_recenzovat mr ON c.id = mr.clanek_id 
                                    LEFT JOIN recenze_clanku r ON c.id = r.clanek_id AND r.uzivatel_id = :recenzent
                                    WHERE mr.uzivatel_id = :recenzent AND r.id IS NULL");

        $stmt->bindParam(':recenzent', $recenzent);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  vrati pole clanku co ma uzivatel (recenzent) zrecenzovat a uz je zrecenzoval
     *  @parm $recenzent id recenzenta
     *  @return array pole zrecovanych clanku daneho recenzenta
     */
    public function getClankyRecenzentaZrecenzovane($recenzent) {
        $stmt = $this->pdo->prepare("SELECT c.* 
                            FROM clanek c 
                            LEFT JOIN ma_recenzovat mr ON c.id = mr.clanek_id 
                            LEFT JOIN recenze_clanku r ON c.id = r.clanek_id AND r.uzivatel_id = :recenzent
                            WHERE mr.uzivatel_id = :recenzent AND r.id IS NOT NULL");

        $stmt->bindParam(':recenzent', $recenzent);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  vrati danou recenzi ktery napsal zadany uzivel na zadany clanek
     *  @parm $clanek id clanku
     *  @parm $uzivatel id uzivatele (recenzenta)
     *  @return recenze daneho clanku napsana danym uzivatelem
     */
    public function getRecenzeClanku($clanek, $uzivatel) {
        $stmt = $this->pdo->prepare("SELECT r.* FROM recenze_clanku r WHERE r.clanek_id = :clanek AND r.uzivatel_id = :uzivatel");
        $stmt->bindParam(':clanek', $clanek);
        $stmt->bindParam(':uzivatel', $uzivatel);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     *  vrati vsechny recenze daneho clanku
     *  @parm $clanek id clanku
     *  @return array pole recenzi ktere byly na dany clanek napsany
     */
    public function getVsechnyRecenzeClanku($clanek) {
        $stmt = $this->pdo->prepare("SELECT r.* FROM recenze_clanku r WHERE r.clanek_id = :clanek");
        $stmt->bindParam(':clanek', $clanek);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     *  prida do tabulky ma_recenzovat novy prvek vytvoreny ze zadaneho recenzata a clanku
     *  @parm $clanek id clanku
     *  @parm $recenzent id recenzenta
     */
    public function priradRecenzenta($clanek, $recenzent) {
        $q = "INSERT INTO ma_recenzovat (uzivatel_id, clanek_id) VALUES (:uzivatel_id, :clanek_id);";
        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':uzivatel_id', $recenzent);
        $stmt->bindParam(':clanek_id', $clanek);

        $stmt->execute();
    }

    /**
     *  zmeni roli daneho uzivatele podle zadane hodnotz role
     *  @parm $novaRole int hodnota vyjadrujici novou roli uzivatele
     *  @parm $MenenyUzivatelID id meneneho uzivatele
     */
    public function zmeneniRole($noveRole, $MenenyUzivatelID) {
        $q = "UPDATE uzivatel SET role_uzivatele_id = :novaRole WHERE id = :uzivatelID";
        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':novaRole', $noveRole);
        $stmt->bindParam(':uzivatelID', $MenenyUzivatelID);

        $stmt->execute();
    }

    /**
     *  zmeni zabanovani daneho uzivatele podle zadane hodnoty zabanovani
     *  @parm $noveBanovani int hodnota vyjadrujici novou hodnotu zabanovani
     *  @parm $MenenyUzivatelID id meneneho uzivatele
     */
    public function zmenenitZabanovani($noveBanovani, $MenenyUzivatelID) {
        $q = "UPDATE uzivatel SET stav_zabanovani = :noveBanovani WHERE id = :uzivatelID";
        $stmt = $this->pdo->prepare($q);

        $stmt->bindParam(':noveBanovani', $noveBanovani);
        $stmt->bindParam(':uzivatelID', $MenenyUzivatelID);

        $stmt->execute();
    }

    /**
     *  prida novy prvek do tabulky uzivatel v databazi
     *  @parm $pole pole s parametrama zadanych pri vytvareni noveho uzivatele
     */
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

        /*$tabulkovyVkladac->bindParam(':hodnota1', $hodnota1);
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
        $hodnota6 = password_hash($pole["heslo"], PASSWORD_BCRYPT);
        //$hodnota6 = $pole["heslo"];
        $hodnota7 = 1;*/

        $tabulkovyVkladac->bindParam(':hodnota1', $pole["email"]);
        $tabulkovyVkladac->bindParam(':hodnota2', $pole["prijmeni"]);
        $tabulkovyVkladac->bindParam(':hodnota3', $pole["jmeno"]);
        $i = (int)0;
        $tabulkovyVkladac->bindParam(':hodnota4', $i);
        $tabulkovyVkladac->bindParam(':hodnota5', $pole["prezdivka"]);
        $heslo = password_hash($pole["heslo"], PASSWORD_BCRYPT);
        $tabulkovyVkladac->bindParam(':hodnota6', $heslo);
        $j = 1;
        $tabulkovyVkladac->bindParam(':hodnota7', $j);

        $tabulkovyVkladac->execute();

        return true;
    }

    /**
     *  prida novy prvek do tabulky clanek v databazi
     *  @parm $pole pole s parametrama zadanych pri vytvareni noveho clanku
     *  @parm $nazevSouboru nazev pdf souboru co byl nahran pri vytvareni noveho souboru
     *  @parm $id_uzivatele id uzivatele co pridal clanek
     */
    public function pridatClanek($pole, $nazevSouboru, $id_uzivatele):bool {

        $tabulkovyVkladac = $this->pdo->prepare("INSERT INTO clanek (autori, nazev_clanku, id, stav_zrecenzovani_clanku_id, clanek_abstrakt, uzivatel_id, odkaz) 
                    VALUES (:hodnota1, :hodnota2, :hodnota3, :hodnota4, :hodnota5, :hodnota6, :hodnota7)");

        foreach($pole as $prvekPole) {
            $prvekPole = htmlspecialchars($prvekPole);
        }

        $tabulkovyVkladac->bindParam(':hodnota1', $pole["autori"]);
        $tabulkovyVkladac->bindParam(':hodnota2', $pole["name"]);
        $i = 0;
        $tabulkovyVkladac->bindParam(':hodnota3', $i);
        $j = 1;
        $tabulkovyVkladac->bindParam(':hodnota4', $j);
        $tabulkovyVkladac->bindParam(':hodnota5', $pole["abstrakt"]);
        $tabulkovyVkladac->bindParam(':hodnota6', $id_uzivatele);
        $tabulkovyVkladac->bindParam(':hodnota7', $nazevSouboru);

        /*$hodnota1 = $pole["autori"];
        $hodnota2 = $pole["name"];
        $hodnota3 = 0;
        $hodnota4 = 1;
        $hodnota5 = $pole["abstrakt"];
        $hodnota6 = $id_uzivatele;
        $hodnota7 = $nazevSouboru;*/

        $tabulkovyVkladac->execute();

        return true;
    }
}

?>