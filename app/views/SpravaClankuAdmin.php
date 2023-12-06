<?php

    require_once (DIRECTORY_MODELS ."/DatabaseModel.class.php");
    $this->db = new DatabaseModel();

    global $login;

    if ($login->isUserLogged()) {
        if ($login->getRole() == 3) {

?>
    <section class="position-  py-4 py-xl-5" style="background: var(--bs-warning-bg-subtle);">
        <h2 class="text-center">Správa článků a jejich recenzování</h2>
        <br>
        <h3 class="text-center">Přiřazené články</h3>
        <br>
        <div class="container-fluid">
            <!-- Start: TableSorter -->
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
                                                            <th>jméno prvního recenzenta</th>
                                                            <th>jméno druhého recenzenta</th>
                                                            <th>jméno třetího recenzenta</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Cell 1</td>
                                                            <td>Cell 2</td>
                                                            <td>Cell 2</td>
                                                        </tr>
                                                        <tr></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group"><button class="btn btn-success text-center" type="button">Schválit</button><button class="btn btn-danger" type="button">Zamítnout</button></div>
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
                                    <th class="text-center">Odkaz na článek</th>
                                    <th class="text-center filter-false sorter-false">Přiřazení recenzenti</th>
                                    <th class="text-center filter-false sorter-false">Vybrání recenzenta</th>
                                    <th class="text-center filter-false sorter-false">Přiřazení recenzenta</th>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                <?php
                                if (isset($tplData2)) {
                                    foreach($tplData2 as $vnitrniPole) {
                                        foreach($vnitrniPole as $clanek) {
                                            ?>
                                            <tr>
                                                <td><?= $clanek["nazev_clanku"]?></td>
                                                <td><?= $clanek["autori"]?></td>
                                                <td class="text-center align-middle" style="max-height: 60px;height: 60px;">
                                                    <a href="NahraneClanky/<?= $clanek["odkaz"]?>"><button class="btn btn-primary border rounded-pill" type="button">
                                                        Odkaz na článek
                                                    </button></a>
                                                </td>
                                                <td>

                                                </td>

                                                <td>
                                                    <select name="zvolenyRec">
                                                        <?php
                                                        if (isset($tplDataRec)) {
                                                            foreach($tplDataRec as $vnitrniPole) {
                                                                foreach($vnitrniPole as $uzivatel) {
                                                                    ?>
                                                                    <option value="<?= $uzivatel["id"] ?>" valueC = "<?= $clanek["id"] ?>"><?= $uzivatel["jmeno_uzivatele"]?> <?= $uzivatel["prijmeni_uzivatele"]?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </td>

                                                <td>
                                                    <button class="btn btn-success text-center" type="button" name="odeslaniRec">Poslat recenzentům</button>
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
                    <?php
    }
}
?>