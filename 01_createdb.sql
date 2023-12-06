DROP TABLE IF EXISTS UZIVATEL CASCADE;

DROP TABLE IF EXISTS recenze_clanku CASCADE;

DROP TABLE IF EXISTS stav_zrecenzovani_clanku CASCADE;

DROP TABLE IF EXISTS clanek CASCADE;

DROP TABLE IF EXISTS uzivatel_role CASCADE;

DROP TABLE IF EXISTS ma_recenzovat CASCADE;

CREATE TABLE clanek (
                        autori      VARCHAR(500),
                        nazev_clanku            VARCHAR(1000),
                        id               INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                        odkaz           VARCHAR(300) NOT NULL,
                        stav_zrecenzovani_clanku_id INTEGER NOT NULL,
                        clanek_abstrakt         VARCHAR(10000),
                        uzivatel_id          INTEGER NOT NULL
);

CREATE TABLE recenze_clanku (
                                id             INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                uzivatel_id        INTEGER NOT NULL,
                                hodnoceni_clanku      INTEGER,
                                clanek_id      INTEGER
);

CREATE TABLE ma_recenzovat (
                               uzivatel_id        INTEGER NOT NULL ,
                               clanek_id      INTEGER NOT NULL
);

ALTER TABLE ma_recenzovat
    ADD CONSTRAINT ma_recenzovat_pk PRIMARY KEY (uzivatel_id, clanek_id);

CREATE TABLE stav_zrecenzovani_clanku (
                                          id     INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                                          stav_zrecenzovani VARCHAR(100)
);

CREATE TABLE uzivatel_role (
                               id   INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                               role_uzivatele VARCHAR(50)
);

CREATE TABLE UZIVATEL (
                          email_uzivatele         VARCHAR(100),
                          prijmeni_uzivatele      VARCHAR(100),
                          jmeno_uzivatele         VARCHAR(100),
                          id            INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
                          login_uzivatele         VARCHAR(50),
                          heslo_uzivatele         VARCHAR(300),
                          role_uzivatele_id INTEGER NOT NULL
);

ALTER TABLE ma_recenzovat
    ADD CONSTRAINT ma_recenzovat_pk PRIMARY KEY (uzivatel_id, clanek_id);

ALTER TABLE UZIVATEL
    ADD CONSTRAINT user_uzivatel_role_fk FOREIGN KEY ( role_uzivatele_id )
        REFERENCES uzivatel_role ( id );

ALTER TABLE clanek
    ADD CONSTRAINT clanek_stav_zrecenzovani_clanku_fk FOREIGN KEY ( stav_zrecenzovani_clanku_id )
        REFERENCES stav_zrecenzovani_clanku ( id );

ALTER TABLE clanek
    ADD CONSTRAINT clanek_user_fk FOREIGN KEY ( uzivatel_id )
        REFERENCES UZIVATEL ( id );

ALTER TABLE ma_recenzovat
    ADD CONSTRAINT ma_recenzovat_clanek_fk FOREIGN KEY ( clanek_id )
        REFERENCES clanek ( id );

ALTER TABLE ma_recenzovat
    ADD CONSTRAINT ma_recenzovat_user_fk FOREIGN KEY ( uzivatel_id )
        REFERENCES UZIVATEL ( id );

ALTER TABLE recenze_clanku
    ADD CONSTRAINT recenze_clanku_clanek_fk FOREIGN KEY ( clanek_id)
        REFERENCES clanek ( id);

ALTER TABLE recenze_clanku
    ADD CONSTRAINT recenze_clanku_user_fk FOREIGN KEY ( uzivatel_id )
        REFERENCES UZIVATEL ( id );

ALTER TABLE UZIVATEL
    ADD CONSTRAINT user_uzivatel_role_fk FOREIGN KEY ( role_uzivatele_id )
        REFERENCES uzivatel_role ( id );
