<?php

require_once '/Laragon/www/Thesesviz/includes/auth/conf.php';
require_once '/Laragon/www/Thesesviz/includes/functions/decodeKey.php';

$key = htmlspecialchars($_GET['key']);
$recherche = reduce($key);

debug($key, $recherche);


$queries = decodeKey($key);

debug($queries);

$time_start = microtime(true); // Début du chronomètre

$selection1 = $conn->prepare($queries[0]);
$selection1->execute();
$theses = $selection1->fetchAll(PDO::FETCH_ASSOC);

$selection2 = $conn->prepare($queries[1]);
$selection2->execute();
$auteurs = $selection2->fetchAll(PDO::FETCH_ASSOC);

$selection3 = $conn->prepare($queries[2]);
$selection3->execute();
$sujets = $selection3->fetchAll(PDO::FETCH_ASSOC);

$selection4 = $conn->prepare($queries[3]);
$selection4->execute();
$annees = $selection4->fetchAll(PDO::FETCH_ASSOC);


$time_end = microtime(true);
$time = $time_end - $time_start; // Fin du chronomètre
$time = round($time, 4);


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/styles/app.css">
    <link rel="stylesheet" href="/src/styles/home.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title><?= $key ?> - Recherche de thèse</title>
</head>

<body>

    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <?php include '../includes/components/nav.php'; ?>


    <section class="thesis20">

        <?php if (count($theses) > 0) : ?>

            <p class="results_nb"><?= count($theses) ?> résultats pour <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes)</p>

            <!-- <div style="display: grid; height: 400px; margin-bottom: 20px;"> -->
            <div class="thesis_chart">
                <div class="btn_container">
                    <button id="UnAn" class="btn chartBtn filter_btn">Depuis 2015</button>
                </div>
                <div id="histo_container"></div>
            </div>

            <p class="section_title">Sélection des <?= count($theses) < 10 ? count($theses) : '10 premières' ?> thèses</p>

            <?php $i = 0; ?>
            <?php for ($i; $i < (count($theses) > 10 ? 10 : count($theses)); $i++) : ?>
                <?php
                $thesis = array(
                    'rank' => $theses[$i]['rank'],
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

                ?>

                <div class="quick_thesis_container">
                    <div class="quick_thesis_wrap">
                        <div class="quick_thesis">
                            <div class="title_content">
                                <p class="thesis_title bold underline"><?= $thesis['titre']; ?></p>
                            </div>
                            <div class="thesis_online">
                                <?php if ($thesis['accessible'] == 1) : ?>
                                    <div class="accessible__wrap">
                                        <p>en ligne</p>
                                        <span class="material-symbols-rounded online_icon">check_circle</span>
                                    </div>
                                <?php else : ?>
                                    <div class="accessible__wrap">
                                        <span class="material-symbols-rounded online_icon">cancel</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="small_wrapper">
                                <p class="thesis_author"><?= $thesis['auteurs']['prenom'] ?> <span class="important_info"><?= $thesis['auteurs']['nom']; ?></span></p>
                                <div class="quick_thesis_subjects">
                                    <?php for ($j = 0; $j < (count($thesis['sujets']) > 3 ? 3 : count($thesis['sujets'])); $j++) : ?>
                                        <div class="quick_thesis_subject">
                                            <p class="thesis_subject"><?= $thesis['sujets'][$j]; ?></p>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="quick_thesis_info">
                            <p class="quick_thesis_date"><?= strftime('%d %b', strtotime($thesis['date'])); ?> <span class="important_info"><?= strftime('%Y', strtotime($thesis['date'])); ?></span></p>
                            <p class="quick_thesis_academy important_info underline"><?= $thesis['etablissement']; ?> -- <?= $thesis['rank']; ?></p>
                        </div>
                    </div>
                </div>

            <?php endfor; ?>

        <?php else : ?>
            <p class="results_nb">Aucun résultats pour <span class="important_info"><?= $recherche ?></span> (<?= $time ?> secondes)</p>
            <p class="no_results">\(o_o)/</p>
        <?php endif; ?>

    </section>

    <div class="end" style="margin-top: 20vh; height: 1px;"></div>


    <script src="/src/scripts/app.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", async () => {


            // Graphique colonne

            let data = <?= json_encode($annees); ?>;

            console.log(data);
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
        });
    </script>
</body>

</html>