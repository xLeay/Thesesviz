<?php

$data = new Search();
// debug($_GET);

// debug le premier array de GET
// debug(key($_GET));

$option = key($_GET);
// debug($option);

if (isset($_GET[$option]) && ($option != 'id') && ($option != 'personne')) {

    $key = htmlspecialchars($_GET[$option]);


    // debug($key);

    $recherche = $data->reduce($key);
    $queries = $data->decodeKey($key);

    // debug($key, $recherche);
    // debug($queries);

    $time_start = microtime(true); // Début du chronomètre

    $theses = $data->exe($queries[0]);
    $auteurs = $data->exe($queries[1]);
    $sujets = $data->exe($queries[2]);
    $annees = $data->exe($queries[3]);

    $time_end = microtime(true);
    $time = $time_end - $time_start; // Fin du chronomètre
    $time = round($time, 3);

    // debug($theses);
    // debug($auteurs);
    // debug($sujets);
    // debug($annees);

} elseif (isset($_GET['id'])) {

    $id = htmlspecialchars($_GET['id']);

    $recherche = $data->reduce($id);
    $queries = $data->decodeKey($id);

    // debug($queries);

    $time_start = microtime(true); // Début du chronomètre

    $theses = $data->exe($queries[0]);
    $personnes = $data->exe($queries[1]);
    $sujets = $data->exe($queries[2]);

    $time_end = microtime(true);
    $time = $time_end - $time_start; // Fin du chronomètre
    $time = round($time, 3);

    // debug('theses ---------------------', $theses, '<br>');
    // debug('auteurs ----------------------', $personnes, '<br>');
    // debug('sujets -----------------------', $sujets, '<br>');

} elseif (isset($_GET['personne'])) {

    $personne = htmlspecialchars($_GET['personne']);

    $recherche = $data->reduce($personne);
    $queries = $data->decodeKey($personne);

    // debug($queries);

    $time_start = microtime(true); // Début du chronomètre

    $theses = $data->exe($queries[0]);
    $personnes = $data->exe($queries[1]);
    $sujets = $data->exe($queries[2]);
    $annees = $data->exe($queries[3]);

    $time_end = microtime(true);
    $time = $time_end - $time_start; // Fin du chronomètre
    $time = round($time, 3);

    // debug($theses);
    // debug($personnes);
    // debug($sujets);
    // debug($annees);
    // debug(count($theses));
    // debug(count($personnes));
    // debug(count($sujets));
    // debug(count($annees));

}


?>

<script type="text/javascript">
    <?php if (isset($_GET[$option]) && ($option != 'id') && ($option != 'personne')) : ?>
        document.title = "<?= $key ?> - Recherche de thèse";
    <?php elseif (isset($_GET['personne'])) : ?>
        document.title = "<?= $personne ?> - Recherche de thèse";
    <?php else : ?>
        document.title = "Thèse - <?= $id ?>";
    <?php endif; ?>
</script>

<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<!-- < ?php require ROOT . "../includes/components/nav.php"; ?> -->

<div class="main_container">
    <section class="thesis20">

        <?php if (isset($id)) : ?>
            <?php
            $thesis = array(
                'nnt' => $theses[0]['nnt'],
                'date' => $theses[0]['date_soutenance'],
                'etablissement' => $theses[0]['nom'],
                'titre' => $theses[0]['titre'],
                'accessible' => $theses[0]['these_accessible'],
                'id' => $theses[0]['idThese'],
                'discipline' => $theses[0]['discipline'],
                'resume' => $theses[0]['resume'],
                'auteur' => array(
                    'nom' => '',
                    'prenom' => '',
                ),
                'directeur' => array(),
                'president' => array(
                    'nom' => '',
                    'prenom' => '',
                ),
                'membre' => array(),
                'rapporteur' => array(),
                'sujets' => array()
            );

            foreach ($sujets as $sujet) {
                if ($sujet['idThese'] == $theses[0]['idThese']) {
                    array_push($thesis['sujets'], $sujet['libelle']);
                }
            }

            foreach ($personnes as $personne) {

                if ($personne['role'] == 'auteur') {
                    $thesis['auteur']['nom'] = $personne['nom'];
                    $thesis['auteur']['prenom'] = $personne['prenom'];
                } else if ($personne['role'] == 'directeur') {
                    array_push($thesis['directeur'], $personne['nom'] . ' ' . $personne['prenom']);
                } else if ($personne['role'] == 'president') {
                    $thesis['president']['nom'] = $personne['nom'];
                    $thesis['president']['prenom'] = $personne['prenom'];
                } else if ($personne['role'] == 'membre') {
                    array_push($thesis['membre'], $personne['nom'] . ' ' . $personne['prenom']);
                } else if ($personne['role'] == 'rapporteur') {
                    array_push($thesis['rapporteur'], $personne['nom'] . ' ' . $personne['prenom']);
                }
            }

            include ROOT . '../includes/components/thesis_self.php';
            ?>

        <?php elseif (isset($personne)) : ?>
            <?php if (count($theses) > 0) : ?>

                <?php if ($option == 'personne') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour la personnalité <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php endif; ?>

                <!-- <div style="display: grid; height: 400px; margin-bottom: 20px;"> -->
                <div class="thesis_chart">
                    <div class="btn_container">
                        <a href="/q?depuis=<?= date('Y') - 5 ?>" id="UnAn" class="btn chartBtn filter_btn">Depuis 5 ans</a>
                    </div>
                    <div id="histo_container"></div>
                </div>

                <?= count($theses) > 1 ? '<p class="section_title">Sélection des ' . (count($theses) < 10 ? count($theses) : '10 premières') . ' thèses</p>' : '<p class="section_title">Sélection de la thèse</p>' ?>

                <?php $i = 0; ?>
                <?php for ($i; $i < (count($theses) > 10 ? 10 : count($theses)); $i++) : ?>
                    <?php
                    $thesis = array(
                        'nnt' => $theses[$i]['nnt'],
                        'date' => $theses[$i]['date_soutenance'],
                        'etablissement' => $theses[$i]['nom'],
                        'titre' => $theses[$i]['titre'],
                        'accessible' => $theses[$i]['these_accessible'],
                        'id' => $theses[$i]['idThese'],
                        'auteurs' => array(
                            'nom' => '',
                            'prenom' => '',
                        ),
                        'sujets' => array()
                    );

                    foreach ($sujets as $sujet) {
                        if ($sujet['idThese'] == $theses[$i]['idThese']) {
                            array_push($thesis['sujets'], $sujet['libelle']);
                        }
                    }

                    foreach ($personnes as $personne) {
                        if ($personne['idThese'] == $theses[$i]['idThese']) {
                            if ($personne['role'] == 'auteur') {
                                $thesis['auteurs']['nom'] = $personne['nom'];
                                $thesis['auteurs']['prenom'] = $personne['prenom'];
                            }
                        }
                    }

                    include ROOT . '../includes/components/thesis_batch.php';
                    ?>
                <?php endfor; ?>
            <?php endif; ?>

        <?php elseif (isset($key)) : ?>
            <?php if (count($theses) > 0) : ?>

                <?php if ($option == 'key') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php elseif ($option == 'titre') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour le titre <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php elseif ($option == 'auteur') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour l'auteur <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php elseif ($option == 'sujet') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour le sujet <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php elseif ($option == 'depuis') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour les thèses depuis <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php elseif ($option == 'etablissement') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour l'établissement <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php elseif ($option == 'discipline') : ?>
                    <p class="results_nb"><?= count($theses) ?> résultats pour la discipline <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes) à l'aide d'une recherche par pertinence</p>
                <?php endif; ?>


                <!-- <div style="display: grid; height: 400px; margin-bottom: 20px;"> -->
                <div class="thesis_chart">
                    <div class="btn_container">
                        <a href="/q?depuis=<?= date('Y') - 5 ?>" id="UnAn" class="btn chartBtn filter_btn">Depuis 5 ans</a>
                    </div>
                    <div id="histo_container"></div>
                </div>

                <?= count($theses) > 1 ? '<p class="section_title">Sélection des ' . (count($theses) < 10 ? count($theses) : '10 premières') . ' thèses</p>' : '<p class="section_title">Sélection de la thèse</p>' ?>

                <?php $i = 0; ?>
                <?php for ($i; $i < (count($theses) > 10 ? 10 : count($theses)); $i++) : ?>
                    <?php

                    $thesis = array(
                        'nnt' => $theses[$i]['nnt'],
                        'id' => $theses[$i]['idThese'],
                        'titre' => $theses[$i]['titre'],
                        'date' => $theses[$i]['date_soutenance'],
                        'etablissement' => $theses[$i]['nom'],
                        'auteurs' => array(
                            'prenom' => $auteurs[$i]['prenom'],
                            'nom' => $auteurs[$i]['nom']
                        ),
                        'accessible' => $theses[$i]['these_accessible'],
                        'sujets' => array()
                    );

                    foreach ($sujets as $sujet) {
                        if ($sujet['idThese'] == $theses[$i]['idThese']) {
                            array_push($thesis['sujets'], $sujet['libelle']);
                        }
                    }

                    include ROOT . '../includes/components/thesis_batch.php';
                    ?>
                <?php endfor; ?>

            <?php else : ?>
                <p class="results_nb">Aucun résultats pour <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes)</p>
                <div class="no_results">
                    <span class="material-symbols-rounded">search</span>
                    <p>N'hésitez pas à élargir la recherche ou retirer les filtres.</p>
                </div>

            <?php endif; ?>
        <?php endif; ?>

    </section>
</div>

<div class="end" style="margin-top: 20vh; height: 1px;"></div>


<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", async () => {

        <?php if (isset($id)) : ?>

            const unwrap_summary = document.getElementById('js-no_wrap');
            const summary = unwrap_summary.innerText;
            const summaryHeight = unwrap_summary.clientHeight;
            const see_more_summary = document.querySelector('.see_more_summary');
            const thesis_self__summary = document.querySelector('.thesis_self__summary');

            if (summary.length > 400) {
                unwrap_summary.innerHTML = summary.substring(0, 400) + '...';

                see_more_summary.addEventListener('click', () => {
                    revealSummary();
                    see_more_summary.style.display = 'none';
                    thesis_self__summary.classList.remove('summary_closed');
                });
            }

            if (summary.length < 200) {
                thesis_self__summary.classList.remove('summary_closed');
                see_more_summary.parentNode.removeChild(see_more_summary);
            }

            function revealSummary() {
                unwrap_summary.style.height = unwrap_summary.clientHeight + 'px';
                unwrap_summary.innerHTML = summary;
                unwrap_summary.style.height = summaryHeight + 'px';

                unwrap_summary.style.height = 'auto';
            }


            // ----------------------------
            // Graphique colonne

        <?php elseif (isset($key) || isset($option)) : ?>


            // affiche les résultats de la recherche en gras
            let recherche = "<?= $recherche ?>";
            let search_array = recherche.split(" ");
            let search_array_length = search_array.length;

            let title = document.querySelectorAll(".thesis_title a");

            title.forEach((element) => {
                element.innerHTML = element.innerHTML.replace(new RegExp(recherche, "gi"), '<span class="extrabold">' + recherche + '</span>');
            });


            // console.log('graphique');
            let data = <?= json_encode($annees); ?>;

            // console.log(data);
            let year = [];
            let count = [];

            for (let i = 1984; i <= 2022; i++) {
                // si l'année est dans le tableau, on push l'année et le nombre de thèses, sinon on push l'année et 0
                if (data.find((element) => element.year == i)) {
                    year.push(parseInt(i));
                    count.push(parseInt(data.find((element) => element.year == i).count));
                } else {
                    year.push(parseInt(i));
                    count.push(0);
                }
            }

            const chartColors = Highcharts.setOptions({
                colors: ['#C9AC90'],
                chart: {
                    style: {
                        fontFamily: 'Poppins',
                        fontSize: 20

                    }
                }
            });

            Highcharts.seriesTypes.line.prototype.getPointSpline = Highcharts.seriesTypes.spline.prototype.getPointSpline;

            const chart = Highcharts.chart('histo_container', {

                credits: {
                    enabled: false
                },
                title: {
                    text: null,
                },
                subtitle: {
                    text: null
                },
                tooltip: {
                    enabled: false
                },
                yAxis: {
                    title: {
                        text: 'Soutenances'
                    },
                    maxPadding: 0.2
                },
                xAxis: {
                    title: {
                        text: null
                    },
                    categories: year,
                    labels: {
                        step: 2
                    }
                },
                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                        states: {
                            hover: {
                                enabled: false
                            }
                        },
                        marker: {
                            enabled: false
                        },
                    }
                },
                series: [{
                    name: 'Soutenues',
                    data: count,
                    showInLegend: false
                }]
            });
        <?php endif; ?>
    });
</script>