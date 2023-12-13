<?php

    global $login;

    $SpavaClankuController = new SpravaClankuAdminController();

    if ($login->isUserLogged()) {
        if ($login->getRole() == 3 || $login->getRole() == 4) {

?>
    <section class="position-  py-4 py-xl-5" style="background: var(--bs-warning-bg-subtle);">
        <h2 class="text-center">Správa článků a jejich recenzování</h2>
        <br>
        <h3 class="text-center">Přiřazené články</h3>
        <br>
        <div class="container-fluid">
            <div class="card" id="TableSorterCard">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table tablesorter" id="ipi-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-center">Název článku</th>
                                        <th class="text-center">Autoři</th>
                                        <th class="text-center">Odkaz na článek</th>
                                        <th class="text-center filter-false sorter-false">Recenze</th>
                                        <th class="text-center filter-false sorter-false">SCHVÁLENÍ</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                <?php
                                    if (isset($tplData)) {
                                        foreach($tplData as $vnitrniPole) {
                                            foreach($vnitrniPole as $clanek) {
                                ?>
                                    <tr>
                                        <td><?= $clanek["nazev_clanku"]?></td>
                                        <td><?= $clanek["autori"]?></td>
                                        <td class="text-center align-middle" style="max-height: 60px;height: 60px;">
                                            <a href="NahraneClanky/<?= $clanek["odkaz"]?>">
                                                <button class="btn btn-primary border rounded-pill" type="button">
                                                    Odkaz na článek
                                                </button>
                                            </a>
                                        </td>
                                        <td class="text-center align-middle" style="max-height: 60px;height: 60px;">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <?php
                                                            $poleRec = $SpavaClankuController->getRecenzentiClanku($clanek["id"]);
                                                            $i = 0;
                                                            $pocetRec = 0;
                                                            if (isset($poleRec)) {
                                                                foreach ($poleRec as $uzivatel) {
                                                                    $recenze = $SpavaClankuController->getRecenzeClanku($clanek["id"], $uzivatel["id"]);
                                                                    $pocetRec = $pocetRec + 1;
                                                                    if (!empty($recenze)) {
                                                                    ?>
                                                                    <td>
                                                                        <?= $uzivatel["jmeno_uzivatele"]?> <?= $uzivatel["prijmeni_uzivatele"]?> : <?= $recenze["hodnoceni_clanku"]?>
                                                                    </td>
                                                                    <?php
                                                                    } else {
                                                                        ?>
                                                                        <td>
                                                                            <?= $uzivatel["jmeno_uzivatele"]?> <?= $uzivatel["prijmeni_uzivatele"]?> : zatím nezhodnoceno
                                                                        </td>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>

                                                            <?php
                                                            for ($i = 0;$i < 3 - $pocetRec;$i = $i + 1) {
                                                                ?>
                                                                <td></td>
                                                                <?php
                                                            }
                                                            ?>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form class="text-center" method="post">
                                                    <button class="btn btn-success text-center" type="submit" name="Schvaleni" value="<?= $clanek["id"]?>">
                                                        Schválit
                                                    </button>
                                                </form>
                                                <form class="text-center" method="post">
                                                    <button class="btn btn-danger" type="submit" name="Zamitnuti" value="<?= $clanek["id"]?>">
                                                        Zamítnout
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <h3 class="text-center">Nepřiřazené články</h3>
        <br>

        <div class="container-fluid">
            <div class="card" id="TableSorterCard">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-striped table tablesorter" id="ipi-table">
                                <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Název článku</th>
                                    <th class="text-center">Autoři</th>
                                    <th class="text-center filter-false sorter-false">Recenzent 1</th>
                                    <th class="text-center filter-false sorter-false">Recenzent 2</th>
                                    <th class="text-center filter-false sorter-false">Recenzent 3</th>
                                    <th class="text-center filter-false sorter-false">Vybrání recenzenta</th>
                                    <th class="text-center filter-false sorter-false">Přiřazení recenzenta</th>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                <?php
                                if (isset($tplData)) {
                                    foreach($tplData as $vnitrniPoleR) {
                                        foreach($vnitrniPoleR as $clanek) {
                                            $poleRec = $SpavaClankuController->getRecenzentiClanku($clanek["id"]);
                                            $pocetRec = 0;
                                            $i = 0;
                                            ?>
                                            <tr>
                                                <td><?= $clanek["nazev_clanku"]?></td>
                                                <td><?= $clanek["autori"]?></td>
                                                <?php
                                                if (isset($poleRec)) {
                                                    foreach ($poleRec as $vnitrniPoleR) {
                                                        $pocetRec = $pocetRec + 1;
                                                    ?>
                                                <td>
                                                    <?= $vnitrniPoleR["jmeno_uzivatele"]?> <?= $vnitrniPoleR["prijmeni_uzivatele"]?>
                                                    <form class="text-center" method="post">
                                                        <button class="btn btn-danger d-block w-100" style="font-size: 12px" type="submit" name="OdebraniRecenzenta" value="<?= $vnitrniPoleR["id"]?>">
                                                            Odebrat
                                                        </button>
                                                        <input type="hidden" name="Clanek" value="<?=$clanek["id"]?>">
                                                    </form>
                                                </td>
                                                <?php
                                                    }
                                                }
                                                ?>

                                                <?php
                                                    for ($i = 0;$i < 3 - $pocetRec;$i = $i + 1) {
                                                ?>
                                                        <td></td>
                                                <?php
                                                    }
                                                ?>
                                                <form class="text-center" method="post">
                                                    <td>
                                                        <select name="zvolenyRecenzent">
                                                            <?php
                                                            $poleRecVol = $SpavaClankuController->getRecenzentyCoJesteNerecenzuji($clanek["id"]);
                                                            if (isset($poleRecVol)) {
                                                                foreach($poleRecVol as $uzivatel) {
                                                                        ?>
                                                                    <option value="<?= $uzivatel["id"] ?>">
                                                                        <?= $uzivatel["jmeno_uzivatele"]?> <?= $uzivatel["prijmeni_uzivatele"]?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                            <input type="hidden" name="zvolenyClanek" value="<?= $clanek["id"] ?>" />
                                                        </select>
                                                    </td>
                                                    <?php
                                                    if ($pocetRec != 3) {
                                                    ?>
                                                        <td>
                                                            <button class="btn btn-info text-center" type="submit">Zadat recenzenta</button>
                                                        </td>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <td>
                                                            Dosažen maximální počet recenzentů
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </form>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    <?php
    }
}
?>