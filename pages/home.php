<?php
$data = new Search();
?>

<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<div class="main_container">
    <section>
        <p class="section_title">Statistiques de thèses</p>

        <div class="stats_wrap">
            <div class="stats">
                <p class="stats_title">Thèses répertoriées</p>
                <p class="stats_data"> <?= $data->loadData()[0]; ?> </p>
            </div>
            <div class="stats">
                <p class="stats_title">Directeurs de thèses</p>
                <p class="stats_data"><?= $data->loadData()[4]; ?></p>
            </div>
            <div class="stats">
                <p class="stats_title">Thèses en ligne</p>
                <p class="stats_data"> <?= $data->loadData()[1]; ?> </p>
            </div>
            <div class="stats">
                <p class="stats_title">Établissements</p>
                <p class="stats_data"> <?= $data->loadData()[2]; ?> </p>
            </div>
            <div class="stats">
                <p class="stats_title">Sujets</p>
                <p class="stats_data"><?= $data->loadData()[3]; ?></p>
            </div>
        </div>
    </section>

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


    <section class="thesis20">
        <p class="section_title">Les 20 dernières thèses</p>

        <?php $i = 0; ?>
        <?php for ($i; $i < 5; $i++) : ?>
            <?php
            $dernieres = $data->loadData()[6];
            $auteurs = $data->loadData()[7];
            $sujets = $data->loadData()[8];
            $thesis = array(
                'nnt' => $dernieres[$i]['nnt'],
                'id' => $dernieres[$i]['idThese'],
                'rank' => $dernieres[$i]['rank'],
                'titre' => $dernieres[$i]['titre'],
                'date' => $dernieres[$i]['date_soutenance'],
                'etablissement' => $dernieres[$i]['nom'],
                'auteurs' => array(
                    'prenom' => $auteurs[$i]['prenom'],
                    'nom' => $auteurs[$i]['nom']
                ),
                'accessible' => $dernieres[$i]['these_accessible'],
                'sujets' => array()
            );

            foreach ($sujets as $sujet) {
                if ($sujet['idThese'] == $dernieres[$i]['idThese']) {
                    array_push($thesis['sujets'], $sujet['libelle']);
                }
            }


            include ROOT . '../includes/components/thesis_batch.php';
            ?>
        <?php endfor; ?>

        <!-- <div class="btn_container" id="see_more__container">
            <button id="see_more" class="btn">Voir plus</button>
        </div> -->
    </section>
</div>

<!-- TODO: Voir plus (les thèses) en ajax -->





<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", async () => {
        // Graphique colonne
        const UnAn = document.getElementById('UnAn');
        const CinqAns = document.getElementById('CinqAns');
        const DixAns = document.getElementById('DixAns');

        CinqAns.classList.add('btn-active');

        let data = <?= json_encode($data->loadData()[5]); ?>;

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
                backgroundColor: '#FFFFFF',
                borderRadius: 10,
                borderWidth: 2,
                borderColor: "#C7BEB5",
                followPointer: true,
                padding: 10,
                formatter: function() {
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
            credits: {
                enabled: false
            },
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