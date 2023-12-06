<?php

/**
 *  Trida pro spravu prihlaseni uzivatele.
 *  @author Michal Nykl
 */
class MujLogin {

    /**Instance tridy MaSession udelana pro praci se session**/
    public $ses;


    /** @var string SESSION_KEY  Klic pro ulozeni uzivatele do session */
    const SESSION_KEY = "usr";

    /** @var string KEY_NAME  Klic pro ulozeni jmena do pole.  */
    const KEY_NAME = "jm";

    const ID_KLIC = "klicId";

    const ROLE_KLIC = "roleKlic";

    /**
     *  Pri vytvoreni objektu zahajim session.
     */
    public function __construct(){
        require_once("MaSession.class.php");
        // vytvorim instanci vlastni tridy pro praci se session (objekt)
        $this->ses = new MaSession();
    }

    /**
     *  Otestuje, zda je uzivatel prihlasen.
     *  @return bool
     */
    public function isUserLogged():bool {
        return $this->ses->isSessionSet(self::SESSION_KEY);
    }

    /**
     *  Nastavi do session jmeno uzivatele a datum prihlaseni.
     *  @param string $userName Jmeno uzivatele.
     */
    public function login(string $userName, $id_uzivatele, $role){
        self::KEY_NAME;
        $data = [ self::KEY_NAME => $userName, self::ID_KLIC => $id_uzivatele,
            self::ROLE_KLIC => $role];

        $this->ses->setSession(self::SESSION_KEY, $data);
    }

    /**
     *  Odhlasi uzivatele.
     */
    public function logout(){
        $this->ses->removeSession(self::SESSION_KEY);
    }

    /**
     *  Vrati informace o uzivateli.
     *  @return string|null  Informace o uzivateli.
     */
    public function getJmeno():string {
        $d = $this->ses->readSession(self::SESSION_KEY);
        return $d[self::KEY_NAME];
    }

    public function getID():string {
        $d = $this->ses->readSession(self::SESSION_KEY);
        return $d[self::ID_KLIC];
    }

    public function ROLE_KLIC():string {
        $d = $this->ses->readSession(self::SESSION_KEY);
        return $d[self::ROLE_KLIC];
    }

    public function getRole():string {
        $d = $this->ses->readSession(self::SESSION_KEY);
        return $d[self::ROLE_KLIC];
    }

    public function getUserInfo() {
        if(!$this->isUserLogged()) {
            return null;
        }
        $d = $this->ses->readSession(self::SESSION_KEY);
        return "Jméno: " . $d[self::KEY_NAME] . "<br>";
    }

}
?>
