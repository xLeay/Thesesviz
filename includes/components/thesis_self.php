<?php

$t = $data->getEstabWikipediaPage($thesis['etablissement']);

// Récupérer l'URL de la page Wikipédia de l'établissement
$pageUrl = $t[0]['Page Wikipédia en français'];

if (isset($pageUrl)) {
    $ogImageUrl = getOgImageUrl($pageUrl);
}

?>
<div class="thesis_self__title">
    <p><?= $thesis['titre']; ?></p>
</div>


<div class="thesis_self__subjects" style="<?php if (count($thesis['sujets']) > 0) : ?>padding-bottom: 10px;<?php endif; ?>">
    <?php for ($j = 0; $j < (count($thesis['sujets'])); $j++) : ?>
        <div class="thesis_self__subject">
            <p class="thesis_subject"><a href="/q?sujet=<?= $thesis['sujets'][$j]; ?>"><?= $thesis['sujets'][$j]; ?></a></p>
        </div>
    <?php endfor; ?>
</div>


<div class="thesis_self__wrap">
    <div class="grid2_1">
        <div class="thesis_self__people card">

            <p class="card_title">Personnalités pertinentes sur la thèse</p>

            <div class="people_list list">
                <?php if (empty($thesis['auteur']) && empty($thesis['directeur']) && empty($thesis['president']) && empty($thesis['rapporteur'])) : ?>
                    <p class="no_result">Aucune donnée sur les personnalités de cette thèse.</p>
                <?php else : ?>

                    <!-- AUTEUR DE LA THESE -->
                    <p class="thesis_author">par
                        <a class="underline" href="/q?auteur=<?= $thesis['auteur']['prenom'] ?>+<?= $thesis['auteur']['nom'] ?>">
                            <span class="important_info"><?= $thesis['auteur']['prenom'] ?> <?= $thesis['auteur']['nom']; ?></span>
                        </a>
                    </p>


                    <!-- DIRECTEUR(S) DE LA THESE -->
                    <?php if (count($thesis['directeur']) > 0) : ?>
                        <p class="thesis_directeur">sous la direction de
                            <?php foreach ($thesis['directeur'] as $key => $directeur) : ?>
                                <a class="underline" href="/q?personne=<?= $directeur; ?>">
                                    <span class="less_important_info"><?= $directeur; ?></span>
                                </a>
                                <?php if ($key != count($thesis['directeur']) - 1 && $key != count($thesis['directeur']) - 2) : ?>
                                    ,
                                <?php elseif ($key == count($thesis['directeur']) - 2) : ?> et <?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>


                    <!-- PRESIDENT(S) DU JURY -->
                    <?php if ($thesis['president']['prenom'] != NULL) : ?>
                        <p class="thesis_president">présidée par
                            <a class="underline" href="/q?personne=<?= $thesis['president']['prenom'] ?> <?= $thesis['president']['nom']; ?>">
                                <span class="less_important_info"><?= $thesis['president']['prenom'] ?> <?= $thesis['president']['nom']; ?></span>
                            </a>
                        </p>
                    <?php endif; ?>


                    <!-- MEMBRE(S) DU JURY -->
                    <?php if (count($thesis['membre']) > 0) : ?>
                        <p class="thesis_membre">le jury est composé de
                            <?php foreach ($thesis['membre'] as $key => $membre) : ?>
                                <a class="underline" href="/q?personne=<?= $membre; ?>">
                                    <span class="less_important_info"><?= $membre; ?></span>
                                </a>
                                <?php if ($key != count($thesis['membre']) - 1 && $key != count($thesis['membre']) - 2) : ?>
                                    ,
                                <?php elseif ($key == count($thesis['membre']) - 2) : ?> et <?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>


                    <!-- RAPPORTEUR(S) DE LA THESE -->
                    <?php if (count($thesis['rapporteur']) > 0) : ?>
                        <p class="thesis_rapporteur">rapportée par
                            <?php foreach ($thesis['rapporteur'] as $key => $rapporteur) : ?>
                                <a class="underline" href="/q?personne=<?= $rapporteur; ?>">
                                    <span class="less_important_info"><?= $rapporteur; ?></span>
                                </a>
                                <?php if ($key != count($thesis['rapporteur']) - 1 && $key != count($thesis['rapporteur']) - 2) : ?>
                                    ,
                                <?php elseif ($key == count($thesis['rapporteur']) - 2) : ?> et <?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>


                <?php endif; ?>
            </div>
        </div>

        <div class="thesis_self__info card">
            <p class="card_title">Informations sur la thèse</p>

            <div class="info_list list">
                <p>soutenue le
                    <a class="underline" href="/q?depuis=<?= DateTime::createFromFormat('Y-m-d', $thesis['date'])->format('Y'); ?>">
                        <span class="important_info"><?= DateTime::createFromFormat('Y-m-d', $thesis['date'])->format('d M Y'); ?></span>
                    </a>
                </p>

                <div class="prevent_overflow">
                    <p class="list_overflow">à
                        <a class="underline" href="/q?etablissement=<?= $thesis['etablissement']; ?>">
                            <span class="less_important_info"><?= $thesis['etablissement']; ?></span>
                        </a>
                    </p>
                    <?php if (isset($pageUrl)) : ?>
                        <div class="estab__img" style="margin-top: 5px;">
                            <a href="<?= $pageUrl; ?>" target="_blank" rel="noopener noreferrer">
                                <img class="card" src="<?= getOgImageUrl($pageUrl); ?>" alt="og_image">
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="prevent_overflow estab__img">
                            <p class="list_overflow" style="margin-top: 5px; color: var(--secondary_info);">Aucun visuel pour l'établissement</p>
                        </div>
                    <?php endif; ?>
                </div>



                <div class="prevent_overflow">
                    <p class="list_overflow">discipline
                        <a class="underline" href="/q?discipline=<?= $thesis['discipline']; ?>">
                            <span class="less_important_info"><?= $thesis['discipline']; ?></span>
                        </a>
                    </p>
                </div>

                <?php if ($thesis['accessible'] == 1) : ?>
                    <p class="self_accessible">accessible
                        <a style="display: flex;" class="underline" href="https://www.theses.fr/<?= $thesis['nnt']; ?>/document" target="_blank">
                            <span class="less_important_info">&nbsp;en ligne</span>&nbsp;<span class="material-symbols-rounded less_important_info open_new_icon">open_in_new</span>
                        </a>
                    </p>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <?php if ($thesis['resume'] != NULL) : ?>
        <div class="thesis_self__summary summary_closed card">
            <p class="card_title">Description de la thèse</p>
            <div class="list">
                <p id="js-no_wrap"><?= $thesis['resume']; ?></p>
            </div>
            <p class="see_more_summary">Voir plus</p>
        </div>

    <?php else : ?>
        <div class="thesis_self__summary card">
            <p class="card_title">Description de la thèse</p>
            <div class="list">
                <p id="js-no_wrap">Aucune description n'a été fournie pour cette thèse.</p>
            </div>
        </div>
    <?php endif; ?>


</div>