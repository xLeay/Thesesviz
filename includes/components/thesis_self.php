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
                    <?php if (count($thesis['directeur']) == 1) : ?>
                        <p class="thesis_director">sous la direction de <span class="less_important_info"><?= $thesis['directeur'][0]; ?></span></p>
                    <?php elseif (count($thesis['directeur']) > 1) : ?>
                        <?php for ($j = 0; $j < (count($thesis['directeur']) > 2 ? 2 : count($thesis['directeur'])); $j++) : ?>
                            <p class="thesis_director">sous la direction de <span class="less_important_info"><?= $thesis['directeur'][$j]; ?></span> et <span class="less_important_info"><?= $thesis['directeur'][$j + 1]; ?></span></p>
                            <?php break; ?>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <!-- PRESIDENT(S) DU JURY -->
                    <!-- TODO: add link to president page -->
                    <?php if (count($thesis['president']) == 1) : ?>
                        <p class="thesis_president">présidée par <span class="less_important_info"><?= $thesis['president'][0]; ?></span></p>
                    <?php elseif (count($thesis['president']) > 1) : ?>
                        <?php for ($j = 0; $j < (count($thesis['president']) > 2 ? 2 : count($thesis['president'])); $j++) : ?>
                            <p class="thesis_president">présidée par <span class="less_important_info"><?= $thesis['president'][$j]; ?></span> et <span class="less_important_info"><?= $thesis['president'][$j + 1]; ?></span></p>
                            <?php break; ?>
                        <?php endfor; ?>
                    <?php endif; ?>

                    <!-- RAPPORTEUR(S) DE LA THESE -->
                    <!-- TODO: add link to rapporteur page -->
                    <?php if (count($thesis['rapporteur']) == 1) : ?>
                        <p class="thesis_rapporteur">rapportée par <span class="less_important_info"><?= $thesis['rapporteur'][0]; ?></span></p>
                    <?php elseif (count($thesis['rapporteur']) > 1) : ?>
                        <?php for ($j = 0; $j < (count($thesis['rapporteur']) > 2 ? 2 : count($thesis['rapporteur'])); $j++) : ?>
                            <p class="thesis_rapporteur">rapportée par <span class="less_important_info"><?= $thesis['rapporteur'][$j]; ?></span> et <span class="less_important_info"><?= $thesis['rapporteur'][$j + 1]; ?></span></p>
                            <?php break; ?>
                        <?php endfor; ?>
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