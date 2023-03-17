<?php

// Si on tape l'url à la main, le router ne sera pas chargé et cela pose problème. On redirige donc vers la page d'accueil si il ne provient pas de ce dernier.
require_once dirname(__DIR__, 1) . "/includes/fromRouter.php";

?>

<div class="main_container">
    <h1>À propros du projet</h1>
    <br>

    <p>Ce projet a été réalisé dans le cadre d'un travail de seconde année dans la formation "BUT Informatique, réalisation d'applications" de l'IUT de Champs sur Marne, en partenariat avec l'<a class="important_info" href="https://abes.fr/" target="_blank">Abes</a>.</p>
    <br>

    <p>Sur la base d’une extraction ou de la totalité de la base de données et métadonnées des thèses soutenues inscrites au catalogue des thèses de <a class="important_info" href="https://theses.fr/" target="_blank">theses.fr</a>, le but était de réaliser une application web présentant un tableau de bord permettant une nouvelle expérience de visualisation des données que la version actuelle du catalogue des thèses de theses.fr.</p>
    <br>
    
    <p>L’intérêt du projet est de proposer une nouvelle expérience de navigation de ces données.</p>
    <br>

    <h2>Navigation et Cookies</h2>
    <br>

    <p>Ce site ne récupère aucune trace de navigation des utilisateurs ou de cookies. Il n'a seulement pour objectif que de traiter de données open sources librement utilisables.</p>
    <br>

    <p>Toutes les données disponibles sur <span class="important_info">theses.yaelcoeffier.com</span> sont des données publiques mises à disposition par <a class="important_info" href="https://www.data.gouv.fr/fr/datasets/theses-soutenues-en-france-depuis-1985/" target="_blank">data.gouv.fr</a> et ne contiennent aucune donnée sensibles des personnes citées.</p>
    <br>

    <h2>Remerciements</h2> <br>

    <p>Ce projet a été réalisé sous la direction de Mr. <a class="important_info" href="https://tfressin.fr" target="_blank">Thomas Fressin</a>, je le remercie pour son aide et son expertise sur le sujet.</p>
    <br>
    
    <br>
    <p>Dernière version du site <span class="highlight_text">Samedi 17 décembre 2022</span>. </p>
    <br>

</div>

<script>
    document.querySelector("nav").classList.add("nav_invisible");
</script>