<div class="thesis_self__title">
    <p><?= $thesis['titre']; ?></p>
</div>


<div class="thesis_self__subjects" style="<?php if (count($thesis['sujets']) > 0) : ?>padding-bottom: 10px;<?php endif; ?>">
    <?php for ($j = 0; $j < (count($thesis['sujets'])); $j++) : ?>
        <div class="thesis_self__subject">
            <p class="thesis_subject"><?= $thesis['sujets'][$j]; ?></p> <!-- TODO: add link to subject page -->
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
                    <!-- TODO: add link to author page -->
                    <p class="thesis_author">par <span class="important_info"><?= $thesis['auteur']['prenom'] ?> <?= $thesis['auteur']['nom']; ?></span></p>


                    <!-- DIRECTEUR(S) DE LA THESE -->
                    <!-- TODO: add link to director page -->
                    <?php if (count($thesis['directeur']) > 0) : ?>
                        <p class="thesis_directeur">sous la direction de
                            <?php foreach ($thesis['directeur'] as $key => $directeur) : ?>
                                <span class="less_important_info"><?= $directeur; ?></span>
                                <?php if ($key != count($thesis['directeur']) - 1 && $key != count($thesis['directeur']) - 2) : ?>
                                    ,
                                <?php elseif ($key == count($thesis['directeur']) - 2) : ?> et <?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>


                    <!-- PRESIDENT(S) DU JURY -->
                    <!-- TODO: add link to president page -->
                    <?php if ($thesis['president']['prenom'] != NULL) : ?>
                        <p class="thesis_president">présidée par <span class="less_important_info"><?= $thesis['president']['prenom'] ?> <?= $thesis['president']['nom']; ?></span></p>
                    <?php endif; ?>


                    <!-- MEMBRE(S) DU JURY -->
                    <!-- TODO: add link to member page -->
                    <?php if (count($thesis['membre']) > 0) : ?>
                        <p class="thesis_membre">le jury est composé de
                            <?php foreach ($thesis['membre'] as $key => $membre) : ?>
                                <span class="less_important_info"><?= $membre; ?></span>
                                <?php if ($key != count($thesis['membre']) - 1 && $key != count($thesis['membre']) - 2) : ?>
                                    ,
                                <?php elseif ($key == count($thesis['membre']) - 2) : ?> et <?php endif; ?>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>


                    <!-- RAPPORTEUR(S) DE LA THESE -->
                    <!-- TODO: add link to rapporteur page -->
                    <?php if (count($thesis['rapporteur']) > 0) : ?>
                        <p class="thesis_rapporteur">rapportée par
                            <?php foreach ($thesis['rapporteur'] as $key => $rapporteur) : ?>
                                <span class="less_important_info"><?= $rapporteur; ?></span>
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
                <!-- TODO: add link to year page -->
                <p>soutenue le <span class="important_info"><?= strftime('%d %b %Y', strtotime($thesis['date'])); ?></span></p>

                <!-- TODO: add link to school page -->
                <div class="prevent_overflow">
                    <p class="list_overflow">à <span class="less_important_info"><?= $thesis['etablissement']; ?></span></p>
                </div>

                <!-- TODO: add link to discipline page -->
                <div class="prevent_overflow">
                    <p class="list_overflow">discipline <span class="less_important_info"><?= $thesis['discipline']; ?></span></p>
                </div>

                <!-- TODO: add link to thesis target blank -->
                <?php if ($thesis['accessible'] == 1) : ?>
                    <p class="self_accessible">accessible <span class="less_important_info">&nbsp;en ligne</span>&nbsp;<span class="material-symbols-rounded less_important_info open_new_icon">open_in_new</span>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="thesis_self__summary summary_closed card">
        <p class="card_title">Description de la thèse</p>
        <div class="list">
            <p id="js-no_wrap"><?= $thesis['resume']; ?></p>
        </div>
        <p class="see_more_summary">Voir plus</p>
    </div>


</div>