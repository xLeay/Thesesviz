<?php

require '../includes/functions/app.php';
require '/Laragon/www/Thesesviz/includes/auth/conf.php';

// seléction des thèses répertoriées
$sqlrepertorie = "SELECT idThese FROM these";
$selection1 = $conn->prepare($sqlrepertorie);
$selection1->execute();

// sélection des thèses en ligne
$sqlenligne = "SELECT these_accessible FROM these WHERE these_accessible = 1";
$selection2 = $conn->prepare($sqlenligne);
$selection2->execute();

// sélection des établissements concernés
$sqletablissement = "SELECT idref FROM etablissement";
$selection3 = $conn->prepare($sqletablissement);
$selection3->execute();

// sélection des sujets de thèses
$sqlsujet = "SELECT idSujet FROM sujet";
$selection4 = $conn->prepare($sqlsujet);
$selection4->execute();

// sélection des années de soutenance
$sqlannee = "SELECT DATE_FORMAT(date_soutenance, '%Y') as 'year', COUNT(*) as count FROM soutenir GROUP BY DATE_FORMAT(date_soutenance, '%Y')";
$selection5 = $conn->prepare($sqlannee);
$selection5->execute();
$annees = $selection5->fetchALL(PDO::FETCH_ASSOC);


// sélection des 20 dernières thèses ajoutées
$sql20dernieres = "SELECT s.date_soutenance, e.nom, t.titre, t.these_accessible
FROM etablissement e, these t NATURAL JOIN soutenir s 
WHERE s.idEtablissement = e.idEtablissement 
ORDER BY s.date_soutenance DESC LIMIT 1";
$selection6 = $conn->prepare($sql20dernieres);
$selection6->execute();
$dernieres = $selection6->fetchALL(PDO::FETCH_ASSOC);

// sélection des auteurs des 20 dernières thèses ajoutées
$sqlauteurs20 = "SELECT a.role, p.nom, p.prenom
FROM soutenir s 
INNER JOIN assister a ON a.idThese = s.idThese
INNER JOIN personne p ON p.idPersonne = a.idPersonne
WHERE a.role = 'auteur de la these'
ORDER BY s.date_soutenance DESC LIMIT 1;";
$selection7 = $conn->prepare($sqlauteurs20);
$selection7->execute();
$auteurs = $selection7->fetchALL(PDO::FETCH_ASSOC);

// sélection des sujets des 20 dernières thèses ajoutées à l'aide de l'idThese
$sqlsujets20 = "SELECT s.libelle
FROM reposer r
INNER JOIN sujet s ON s.idSujet = r.idSujet
INNER JOIN (SELECT idThese, date_soutenance FROM soutenir ORDER BY date_soutenance DESC LIMIT 1) so ON so.idThese = r.idThese";
$selection8 = $conn->prepare($sqlsujets20);
$selection8->execute();
$sujets = $selection8->fetchALL(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/styles/app.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <title>Thesesviz - Acceuil</title>
</head>

<body>

    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <!-- <script src="https://code.highcharts.com/highcharts-more.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/export-data.js"></script> -->
    <script src="https://code.highcharts.com/maps/highmaps.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <nav>
        <form action="" class="search_thesis" onclick="document.getElementById('search').focus()">
            <span class="material-symbols-rounded search_icon">search</span>
            <input type="text" name="" id="search" placeholder="Recherche de thèse par titre, année, auteur...">
            <span class="material-symbols-rounded close_icon">close</span>
        </form>
    </nav>

    <section>
        <p class="section_title">Statistiques de thèses</p>

        <div class="stats_wrap">
            <div class="stats">
                <p class="stats_title">Thèses répertoriées</p>
                <p class="stats_data"> <?= $selection1->rowCount(); ?> </p>
            </div>
            <div class="stats">
                <p class="stats_title">Thèses en ligne</p>
                <p class="stats_data"> <?= $selection2->rowCount(); ?> </p>
            </div>
            <div class="stats">
                <p class="stats_title">Établissements concernés</p>
                <p class="stats_data"> <?= $selection3->rowCount(); ?> </p>
            </div>
            <div class="stats">
                <p class="stats_title">Sujets de thèses</p>
                <p class="stats_data"> <?= $selection4->rowCount(); ?> </p>
            </div>
        </div>
    </section>


    <!-- <section style="outline: 1px solid #000;"> -->
    <section>
        <p class="section_title">Graphiques sur les thèses</p>
        <div class="btn_container">
            <button id="UnAn" class="btn chartBtn">un an</button>
            <button id="CinqAns" class="btn chartBtn">5 ans</button>
            <button id="DixAns" class="btn chartBtn">10 ans</button>
        </div>

        <div class="graphs_container">
            <div id="histo_container"></div>
            <div class="map_wrap">
                <p>Thèses sur le territoire</p>
                <div id="map_container"></div>
            </div>
        </div>
    </section>


    <section>
        <p class="section_title">Les 20 dernières thèses</p>
        <div class="quick_thesis_container">
            <div class="quick_thesis_wrap">
                <div class="quick_thesis">
                    <?php foreach ($dernieres as $these) : ?>
                        <p class="thesis_title"><?= $these['titre']; ?></p>
                        <p class="thesis_etablissement"><?= $these['these_accessible']; ?></p>
                    <?php endforeach; ?>

                    <?php foreach ($auteurs as $auteur) : ?>
                        <p class="thesis_author"><?= $auteur['prenom'] ?> <span class="important_info"><?= $auteur['nom']; ?></span></p>
                    <?php endforeach; ?>
                    <div class="quick_thesis_subjects">
                        <?php foreach ($sujets as $sujet) : ?>
                            <div class="quick_thesis_subject">
                                <p class="thesis_title"><?= $sujet['libelle']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="quick_thesis_info">
                    <p class="quick_thesis_date"><?= $these['date_soutenance']; ?></p>
                    <p class="quick_thesis_academy"><?= $these['nom']; ?></p>
                </div>
            </div>
        </div>
    </section>


    <section>


        <div class="thesis_container" style="border: 1px solid #000;">
            <?php foreach ($dernieres as $these) : ?>
                <div class="thesis">
                    <!-- <p class="thesis_title"><?= $these['titre']; ?></p>
                    <p class="thesis_author"><?= $these['auteur']; ?></p>
                    <p class="thesis_date"><?= $these['date_soutenance']; ?></p> -->
                    <?php debug($these); ?>
                </div>
            <?php endforeach; ?>
            <?php foreach ($auteurs as $auteur) : ?>
                <div class="thesis">
                    <?php debug($auteur); ?>
                </div>
            <?php endforeach; ?>
            <?php foreach ($sujets as $sujet) : ?>
                <div class="thesis">
                    <?php debug($sujet); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <br><br><br><br><br><br><br><br><br><br>












    <!-- <?php
            $json = file_get_contents('../includes/extract_theses.json');
            $data = json_decode($json, true);

            $i = 0;
            foreach ($data as $key => $value) {
                if ($i < 1) {
                    debug($value);
                    $i++;
                }
            }
            ?> -->





    <script src="../src/scripts/app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            // Graphique colonne

            const UnAn = document.getElementById('UnAn');
            const CinqAns = document.getElementById('CinqAns');
            const DixAns = document.getElementById('DixAns');

            CinqAns.classList.add('btn-active');

            let data = <?= json_encode($annees); ?>;

            const chartColors = Highcharts.setOptions({
                colors: ['#C9AC90'],
                chart: {
                    style: {
                        fontFamily: 'Poppins',
                        fontSize: 20

                    }
                }
            });

            const chart = Highcharts.chart('histo_container', {

                title: {
                    text: null,
                },
                subtitle: {
                    text: null
                },

                tooltip: {
                    backgroundColor: '#FFFFFF',
                    borderRadius: 10,
                    borderWidth: 2,
                    borderColor: "#C7BEB5",
                    followPointer: true,
                    padding: 10,
                    formatter: function() {
                        // console.log(this);
                        return `<b>${this.x}</b> <br><b style="color: var(--primary); font-size: 18px;">${this.y}</b> thèses soutenues`;
                    },
                    style: {
                        fontSize: 14
                    }
                },
                yAxis: {
                    title: {
                        text: 'Soutenances'
                    },
                    maxPadding: 0.2
                },
                xAxis: {
                    title: {
                        text: 'Années'
                    },
                    categories: ['1985', '1990', '1995', '2000', '2005', '2010', '2015', '2020']
                },
                series: [{
                    type: 'column',
                    name: 'Soutenues',
                    data: [parseInt(data[0].count), parseInt(data[5].count), parseInt(data[10].count), parseInt(data[15].count), parseInt(data[20].count), parseInt(data[25].count), parseInt(data[30].count), parseInt(data[34].count)],
                    showInLegend: false
                }]
            });

            UnAn.addEventListener('click', () => {
                UnAn.classList.add('btn-active');
                CinqAns.classList.remove('btn-active');
                DixAns.classList.remove('btn-active');

                chart.update({
                    xAxis: {
                        categories: ['1985', '1986', '1987', '1988', '1989', '1990', '1991', '1992', '1993', '1994', '1995', '1996', '1997', '1998', '1999', '2000', '2001', '2002', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2018', '2019', '2020', '2021', '2022']
                    },
                    series: [{
                        type: 'column',
                        name: 'Soutenues',
                        data: [parseInt(data[0].count), parseInt(data[1].count), parseInt(data[2].count), parseInt(data[3].count), parseInt(data[4].count), parseInt(data[5].count), parseInt(data[6].count), parseInt(data[7].count), parseInt(data[8].count), parseInt(data[9].count), parseInt(data[10].count), parseInt(data[11].count), parseInt(data[12].count), parseInt(data[13].count), parseInt(data[14].count), parseInt(data[15].count), parseInt(data[16].count), parseInt(data[17].count), parseInt(data[18].count), parseInt(data[19].count), parseInt(data[20].count), parseInt(data[21].count), parseInt(data[22].count), parseInt(data[23].count), parseInt(data[24].count), parseInt(data[25].count), parseInt(data[26].count), parseInt(data[27].count), parseInt(data[28].count), parseInt(data[29].count), parseInt(data[30].count), parseInt(data[31].count), parseInt(data[32].count), parseInt(data[33].count), parseInt(data[34].count), parseInt(data[35].count), parseInt(data[36].count)],
                        showInLegend: false
                    }]
                });
            });

            CinqAns.addEventListener('click', () => {
                UnAn.classList.remove('btn-active');
                CinqAns.classList.add('btn-active');
                DixAns.classList.remove('btn-active');

                chart.update({
                    xAxis: {
                        categories: ['1985', '1990', '1995', '2000', '2005', '2010', '2015', '2020']
                    },
                    series: [{
                        type: 'column',
                        name: 'Soutenues',
                        data: [parseInt(data[0].count), parseInt(data[5].count), parseInt(data[10].count), parseInt(data[15].count), parseInt(data[20].count), parseInt(data[25].count), parseInt(data[30].count), parseInt(data[34].count)],
                        showInLegend: false
                    }]
                });
            });

            DixAns.addEventListener('click', () => {
                UnAn.classList.remove('btn-active');
                CinqAns.classList.remove('btn-active');
                DixAns.classList.add('btn-active');

                chart.update({
                    xAxis: {
                        categories: ['1985', '1995', '2005', '2015', '2020']
                    },
                    series: [{
                        type: 'column',
                        name: 'Soutenues',
                        data: [parseInt(data[0].count), parseInt(data[10].count), parseInt(data[20].count), parseInt(data[30].count), parseInt(data[34].count)],
                        showInLegend: false
                    }]
                });
            });


            // Graphique carte

            const topology = await fetch(
                'https://code.highcharts.com/mapdata/countries/fr/fr-all.topo.json'
            ).then(response => response.json());

            const data_region = [
                ['fr-cor', 10],
                ['fr-bre', 11],
                ['fr-pdl', 12],
                ['fr-pac', 13],
                ['fr-occ', 14],
                ['fr-naq', 15],
                ['fr-bfc', 16],
                ['fr-cvl', 17],
                ['fr-idf', 18],
                ['fr-hdf', 19],
                ['fr-ara', 20],
                ['fr-ges', 21],
                ['fr-nor', 22],
                ['fr-lre', 23],
                ['fr-may', 24],
                ['fr-gf', 25],
                ['fr-mq', 26],
                ['fr-gua', 27]
            ];

            const map_Font = Highcharts.setOptions({
                chart: {
                    style: {
                        fontFamily: 'Poppins'
                    }
                }
            });

            // Create the chart
            const map_chart = Highcharts.mapChart('map_container', {
                chart: {
                    map: topology
                },

                title: {
                    text: null
                },

                subtitle: {
                    text: null
                },

                legend: {
                    enabled: false
                },

                tooltip: {
                    backgroundColor: '#FFFFFF',
                    borderRadius: 10,
                    borderWidth: 2,
                    borderColor: "#C7BEB5",
                    followPointer: true,
                    padding: 10,
                    formatter: function() {
                        // console.log(this);
                        return `<b style="color: var(--primary); font-size: 18px;">${this.point.value}</b> Thèses soutenues<br> <b>${this.point.name}</b>`;
                    },
                    style: {
                        fontSize: 14
                    }
                },

                mapView: {
                    insetOptions: {
                        borderColor: "#FFF"
                    }
                },

                colorAxis: {
                    min: 0,
                    minColor: "#F9F9F9",
                    maxColor: "#C9AC90"
                },

                series: [{
                    data: data_region,
                    name: 'Nombre de thèses',
                    borderColor: '#FFF',
                    nullColor: "#F9F9F9",
                    states: {
                        hover: {
                            color: null,
                            brightness: 0
                        }
                    },
                    dataLabels: {
                        enabled: false
                    }
                }]
            });
        });
    </script>

</body>

</html>

<!-- 026369125
026374889
026375060
02637515X
026375249
026375273
026388766
026388804
026388812
026388820
026394944 <-----------------------
026402823
026402882
026402920
026402971
026403005
026403021
026403064	
026403102
026403145
026403153
026403188	
026403250
026403315
02640334X
026403366
026403390
026403412
026403447
026403498
026403587
026403633
026403668
026403692
026403714
026403765
026403781
026403838
026403919
026403994
026404079
026404184
026404214
026404311
02640432X
026404354
026404389
026404435
026404451
026404478
026404540
02640463X
026404664
026404672
026404702
026404788
026404796
026418401
026420023
026430819
026431025
026431122
026436930
026438763
02645856X
026523477
026523493
026550520
026569477
026570564
026603136
026610566
027309320
027321118
027361802
027361837
027456528
027542084
027548325
027548341
027787087
027787109
02778715X
027941426
027960250
028021037
028024400
028028694
028032829
028032837
028035747
02803967X
02819005X
028209966
028232224
028237080
029080371
029473284
030327202
03063525X
030679206
030820529
03082057X
030969379
031122337
031308570
033894221
034348867
03463486X
034747982
034755837
035022116
035375043
05017746X
050228064
050522604
052444724
054447658
058570993
05989136X
060275456
060921234
061498637
061558842
067331149
074251880
077414217
08401430X
095130160
109164970
112859585
131056549
131642863
139267263
139408088
146733819
147800374
149154992
151770697
157779092
165900032
175206562
18098814X
183316401
184668794
185583210
190187433
19077990X
190915757
200716271
221333754
223446556
236453505
238327159
240648315
241035694
241345251
241597595
241871808
252404955
259274127 -->