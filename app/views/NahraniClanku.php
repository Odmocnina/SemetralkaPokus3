<?php
    global $login;

    if ($login->isUserLogged()) {
        if ($login->getRole() == 1) {

?>
    <section class="position-relative py-4 py-xl-5" style="background: var(--bs-warning-bg-subtle);">
        <div class="container">
            <section class="position-relative py-4 py-xl-5" style="height: 633.391px;">
                <div class="container position-relative">
                    <div class="row mb-5">
                        <div class="col-md-8 col-xl-6 text-center mx-auto">
                            <h2>
                                Nahrání vlastního článku
                            </h2>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6 col-lg-5 col-xl-4" style="width: 615px;height: 347px;">
                            <div>
                                <form class="p-3 p-xl-4" method="post" style="height: 258px;" enctype="multipart/form-data"><input class="form-control" required type="text" id="name-1" name="name" placeholder="Název článku">
                                    <p></p>
                                    <div class="mb-3">
                                        <input class="form-control" type="text" id="name-2" name="autori" required placeholder="Jména autorů">
                                    </div>
                                    <input class="border rounded-pill form-control" type="file" required accept=".pdf" name="soubor">
                                    <div class="mb-3"></div>
                                    <div class="mb-3">
                                        <textarea class="form-control" id="message-1" name="abstrakt" required rows="6" placeholder="Abstrakt vašeho článku..." style="height: 152px;"></textarea>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary d-block w-100" type="submit" required name="action" value="odeslatClanek">
                                            Odeslat
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
<?php
        }
    }
    ?>