
    <section class="position-relative py-4 py-xl-5" style="background: var(--bs-warning-bg-subtle);">
        <div class="container py-4 py-xl-5">
            <div class="row mb-5" style="padding-bottom: 0px;">
                <div class="col-md-8 col-xl-6 text-center mx-auto">
                    <h2 class="text-dark-emphasis" style="color: var(--bs-tertiary-bg);">Nejnovější články</h2>
                </div>
            </div>
            <div class="row gy-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
                <?php
                    foreach ($tplData as $vnitrniPole) {
                        foreach($vnitrniPole as $clanek) {
                ?>
                <div class="col">
                    <div class="p-4" style="background: var(--bs-success-text-emphasis);color: var(--bs-body-bg);font-size: 15px;"><span class="badge rounded-pill bg-primary mb-2"></span>
                        <h4><?= $clanek["nazev_clanku"]?></h4>
                        <div class="d-flex">
                            <div>
                                <p class="fw-bold mb-0"><?= $clanek["autori"]?></p>
                                <div class="kontent">
                                    <p><?= $clanek["clanek_abstrakt"]?></p>
                                </div>
                            </div>
                        </div>
                        <a href="NahraneClanky/<?= $clanek["odkaz"]?>">
                            <button class="btn btn-outline-primary btn-sm" type="button" style="backdrop-filter: brightness(100%);--bs-primary: #bdbde8;--bs-primary-rgb: 189,189,232;">
                                Zobrazit celý článek
                            </button>
                        </a>
                    </div>
                    <br>
                </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
