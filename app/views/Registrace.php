<?php

    $database = new DatabaseModel();

    $povedlaSeRegistrace = false;
    global $login;

    if (isset($_POST["action"])) {
        if ($_POST["action"] == "registrace") {
            $povedlaSeRegistrace = $database->pridatUzivatele($_POST);
            if ($povedlaSeRegistrace) {
                header("Location:index.php?page=uspesnaregistrace");
            }
        }
    }
?>
    <section class="position-relative py-4 py-xl-5" style="background: var(--bs-warning-bg-subtle);">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h2>Registrace</h2>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-6 col-xl-4">
                    <div class="card mb-5">
                        <div class="card-body bg-primary-subtle d-flex flex-column align-items-center">
                            <form class="text-center" method="post">
                                <div class="mb-3"><input class="form-control" type="text" name="jmeno" placeholder="Jméno"></div>
                                <div class="mb-3"><input class="form-control" type="text" name="prijmeni" placeholder="Příjmení"></div>
                                <div class="mb-3"><input class="form-control" type="text" name="prezdivka" placeholder="Přezdívka"></div>
                                <div class="mb-3"><input class="form-control" type="Email" name="email" placeholder="Email">
                                <div class="mb-3"></div><input class="form-control" type="password" name="heslo" placeholder="Heslo">
                                <p></p><input class="form-control" type="password" name="heslo znova" placeholder="Heslo znova">
                                <p></p>
                                <p></p><button class="btn btn-primary d-block w-100" type="submit" name="action" value="registrace">Zaregistrovat se</button>
                                <a class="text-muted" href="index.php?page=prihlaseni">Jste přihlášeni? Přihlašte se zde.</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>