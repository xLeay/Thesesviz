<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
    .highcharts-figure #histo_container {
        padding: 30px 40px 0px 40px;
    }
</style>

<div class="main_container">
    <h1>Reporting</h1>
    <br>

    <p>Le graphique ci-dessous montre un à peu près du temps passé sur chaque grand chantier pour la création de ce site internet.</p>
    <br>

    <figure class="highcharts-figure">
        <div id="histo_container"></div>
    </figure>
    <br>

    <p>Ce graphique n'est pas immuable étant donné que le projet est toujours en cours, il est voué à changer dans le futur et ses données à être mises à jour.</p>
    <br>

    <h2>Les grands chantiers</h2>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Conception du MCD</h3>
    <br>

    <p>Pour la réalisation sur MCD, il a été fait sur <a class="important_info" href="https://www.looping-mcd.fr/" target="_blank">Looping</a>.</p>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/2fN8DcK.png" alt="MCD sur Looping">
        <figcaption>
            <div class="text_caption">MCD sur Looping</div>
        </figcaption>
    </div>
    <br>

    <p>Ce n'était pas si long à réaliser mais il fallait quand même penser aux données et à comment elles allaient être récupérées pour leur insertions dans la base de données.</p>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Réalisation BDD</h3>
    <br>

    <p>La base de données a été réalisée sur <a class="important_info" href="https://www.phpmyadmin.net/" target="_blank">phpMyAdmin</a> avec <a class="important_info" href="https://www.mysql.com/fr/" target="_blank">MySQL</a>. <br> Elle est composée des 7 tables ci-dessous :</p>
    <br>

    <div class="img_container" id="structBDD">
        <img src="https://i.imgur.com/uU2wX0U.png" alt="Structure de la base de données">
        <figcaption>
            <div class="text_caption">Structure de la base de données</div>
        </figcaption>
    </div>
    <br>

    <p>C'était plutôt simple à mettre en place car Looping avait déjà fait une bonne partie du travail, le temps réel était plus long à cause des convertions entre les types de Looping et ceux de MySQL qui différaient pour certains. Pour permettre des résultats de meilleure qualité et plus 'naturels', j'ai ajouté des index <a class="important_info" href="https://dev.mysql.com/doc/refman/8.0/en/fulltext-search.html" target="_blank">Fulltext</a> sur certaines tables.</p>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Réalisation du design</h3>
    <br>

    <p>Le design a été réalisé sur <a class="important_info" href="https://www.figma.com/" target="_blank">Figma</a>.</p>
    <br>

    <p>J'ai fait le choix de 4 couleurs principales sur le site :
    <ul>
        <div class="li_colors">
            <li>Le #C9AC90 pour les éléments importants.</li>
            <div class="li_color color1"></div>
        </div>
        <div class="li_colors">
            <li>Le #C7BEB5 pour ceux qui le sont moins.</li>
            <div class="li_color color2"></div>
        </div>
        <div class="li_colors">
            <li>Le #F9F9F9 pour les fonds principaux.</li>
            <div class="li_color color3"></div>
        </div>
        <div class="li_colors">
            <li>Et le #FFFFFF pour le fond des <a class="important_info" href="https://m2.material.io/components/cards" target="_blank">cards</a>.</li>
            <div class="li_color color4"></div>
        </div>
    </ul>
    </p>
    <br>

    <p>J'ai par la suite réalisé plusieurs <a class="important_info" href="https://help.figma.com/hc/fr/articles/360041539473-Cadres-dans-Figma" target="_blank">frames</a> pour présenter le style à adopter pour les pages :</p>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/IqQxK6p.png" alt="Frames de design">
        <figcaption>
            <div class="text_caption">Page d'accueil</div>
        </figcaption>
    </div>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/h0b1yQH.png" alt="Frames de design">
        <figcaption>
            <div class="text_caption">Page de recherche</div>
        </figcaption>
    </div>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/2DCcWah.png" alt="Frames de design">
        <figcaption>
            <div class="text_caption">Page de thèse</div>
        </figcaption>
    </div>
    <br><br>

    <p>Certaines des maquettes ont beaucoup changé, d'autres moins.</p>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Script d'import PHP Objet</h3>
    <br>

    <p>Le script d'import était assez délicat à mettre en place, je pensais que cela serait plus rapide à faire et avec moins de bugs. Ce qui m'a également fait perdre du temps comparé au temps estimé en amont, c'est les classes PHP. En effet, mon script d'import n'était à la base pas du tout orienté objet, l'ayant commencé avoir d'avoir réalisé que c'était demandé dans le sujet. J'ai donc du revoir quelque peu la structure de mon script, tout en corrigeant certains bugs et en ajoutant certaines choses, comme des présidents du jury manquants ou les membres du jury, qui n'étaient au départ pas inclus dans l'import.</p>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/RuZQcmr.png" alt="Exemple d'insertion d'une thèse" style="width: auto;">
        <figcaption>
            <div class="text_caption">Exemple d'insertion d'une thèse</div>
        </figcaption>
    </div>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/OYhKaHU.png" alt="Taille du fichier d'import" style="width: auto;">
        <figcaption>
            <div class="text_caption">Taille du fichier d'import</div>
        </figcaption>
    </div>
    <br><br>

    <p>Le script d'import permet donc de retrouver les données visibles sur <a class="important_info" href="#structBDD">cette image</a> avec le nombre de lignes insérées dans la bdd. Le script est plutôt rapide et insère en 30s sur un <span class="bold">SSD</span> les 1362 thèses, et en environ une minute sur un <span class="bold">HDD</span>.</p>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Développement du site</h3>
    <br>

    <p>Le site est développé à l'aide de PHP, HTML, CSS et JavaScript sans l'aide de framework JS ou de librairie CSS tel que <a class="important_info" href="https://getbootstrap.com/" target="_blank">Bootstrap</a>. Le site est responsive et fonctionne sur tous les navigateurs récents. La librairie <a class="important_info" href="https://www.highcharts.com/" target="_blank">Highcharts</a> est utilisée pour les graphiques.</p>
    <br>

    <p>Le développement du site est la partie la plus longue du projet, il faut gérer les bugs, réfléchir à l'intégration de la maquette, penser au responsive ou simplement écrire l'algorithmie du PHP et du JavaScript.</p>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/IlvOEwW.png" alt="Arborescence des fichiers" style="width: auto;">
        <figcaption>
            <div class="text_caption">Arborescence des fichiers</div>
        </figcaption>
    </div>
    <br>

    <p>La fonctionnalité de recherche est la fonctionnalité ayant pris le plus de temps dans le développement du site, elle ne contient pas encore toutes les options de recherche désirées tel que le <span class="highlight_text semibold"><span class="search__editor-option">de:</span> année <span class="search__editor-option">à:</span> année</span> , mais elle est déjà assez conséquente.
    </p>
    <br>

    <div class="img_container">
        <img src="https://i.imgur.com/R2eUswW.png" alt="Taille du fichier de recherche" style="width: auto;">
        <figcaption>
            <div class="text_caption">Taille du fichier de recherche</div>
        </figcaption>
    </div>
    <br>

    <p>Le site n'est pas terminé et il manque certaines données tel que les thèses par établissements ou encore des graphiques en nuage de points pour les sujets, un camembert des thèses en ligne / pas en ligne etc.</p>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Réalisation des tests</h3>
    <br>

    <p>J'ai réalisé les tests en essayant des recherches différentes et en comparant les résultats comme avec le texte <span class="highlight_text semibold">in vitro</span> qui donne <span class="bold">18</span> résultats dus au <a class="important_info" href="#structBDD">Fulltext</a> qui prends les résultats de <span class="highlight_text semibold">in vitro</span> puis de <span class="highlight_text semibold">vitro</span> et les additionne pour un total de <span class="highlight_text semibold">17 + 1</span> résultats.</p>
    <br>

    <p>J'ai également du testé mes fonctions JS que j'ai créé pour me permettre les options de recherche ou encore mon bouton <span class="highlight_text semibold"><a class="important_info" href="https://www.w3schools.com/howto/howto_js_scroll_to_top.asp" target="_blank">scroll to top</a></span>.</p>
    <br>


    <!-- ---------------------------- -->
    <!-- ---------------------------- -->
    <h3>Conclusion</h3>
    <br>

    <p>Le projet est très intéressant à réaliser, j'ai pu apprendre beaucoup de choses sur le développement web et sur la gestion de base de données et j'en apprends encore beaucoup.</p>
    <br>

    <br><br>
</div>

<script>
    document.querySelector("nav").classList.add("nav_invisible");

    // redirection au clic de toutes les images vers leur source en target="_blank"
    const images = document.querySelectorAll("img");
    images.forEach(image => {
        image.addEventListener("click", () => {
            window.open(image.src, "_blank");
        });
    });

    // charts de Highcharts
    const chartColors = Highcharts.setOptions({
        colors: ['#C9AC90', '#C7BEB5'],
        chart: {
            style: {
                fontFamily: 'Poppins',
                fontSize: 20

            }
        }
    });

    const chart = Highcharts.chart('histo_container', {
        chart: {
            type: 'bar'
        },
        credits: {
            enabled: false
        },
        title: {
            text: null,
        },
        subtitle: {
            text: null
        },
        xAxis: {
            categories: [
                'Conception du MCD',
                'Réalisation BDD',
                'Réalisation du design',
                'Script d\'import PHP Objet',
                'Développement du site',
                'Réalisations des tests',
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Heures',
            }
        },
        plotOptions: {
            series: {
                grouping: false,
                borderWidth: 1,
                borderColor: 'black'
            }
        },
        legend: {
            reversed: true
        },
        series: [{
            pointPlacement: -0.2,
            name: 'Temps réel',
            data: [5, 2, 12, 10, 26, 4],
        }, {
            name: 'Temps estimé',
            data: [4, 1, 10, 6, 24, 3],
        }],
    });
</script>