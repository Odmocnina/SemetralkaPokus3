<?php
global $login;

if ($login->isUserLogged()) {
?>
<section class="position-relative py-4 py-xl-5" style="background: var(--bs-warning-bg-subtle);">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h2>Správa osobních údajů</h2>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-5">
                        <div class="card-body bg-primary-subtle d-flex flex-column align-items-center">
                            <?php
                            if (isset($tplData)) {
                            ?>
                            <form class="text-center" method="post">
                                <p>Vaše jméno: <?=$tplData["jmeno_uzivatele"]?></p>
                                <div class="mb-3">
                                    Změna jména: <input class="form-control" type="text" name="jmeno" required placeholder="Vaše nové jméno">
                                </div>
                                <button class="btn btn-warning d-block w-100" type="submit" name="zmenaJmena">Změnit jméno</button>
                            </form>
                                <p></p>
                            <form class="text-center" method="post">
                                <p>Vaše příjmení: <?=$tplData["prijmeni_uzivatele"]?></p>
                                <div class="mb-3">
                                    Změna příjmení: <input class="form-control" type="text" name="prijmeni" required placeholder="Vaše nové příjmení">
                                </div>
                                <button class="btn btn-warning d-block w-100" type="submit" name="zmenaPrijmeni">Změnit přijmení</button>
                            </form>
                                <p></p>
                            <form class="text-center" method="post">
                                <p>Vaše přezdívka: <?=$tplData["login_uzivatele"]?></p>
                                <div class="mb-3">
                                    Změna přezdívky: <input class="form-control" type="text" name="prezdivka" required placeholder="Vaše nová přezdívka">
                                </div>
                                <button class="btn btn-warning d-block w-100" type="submit" name="zmenaPrezdivky">Změnit přezdívku</button>
                            </form>
                                <p></p>
                            <form class="text-center" method="post">
                                <p>Váš email: <?=$tplData["email_uzivatele"]?></p>
                                <div class="mb-3">
                                    Změna emailu: <input class="form-control" type="Email" name="email" required placeholder="Váš nový email">
                                </div>
                                <button class="btn btn-warning d-block w-100" type="submit" name="zmenaEmailu">Změnit email</button>
                            </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
}
        ?>