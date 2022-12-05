<div class="quick_thesis_container">
    <div class="quick_thesis_wrap">
        <div class="quick_thesis">
            <div class="title_content">
                <p class="thesis_title semibold underline"> <a href="/q?id=<?= $thesis['id']; ?>"><?= $thesis['titre']; ?></a></p>
            </div>
            <div class="thesis_online">
                <?php if ($thesis['accessible'] == 1) : ?>
                    <div class="accessible__wrap">
                        <a style="display: inline-flex; align-items: center; gap: 5px;" href="https://www.theses.fr/<?= $thesis['nnt']; ?>/document" target="_blank">
                            <p>en ligne</p>
                            <span class="material-symbols-rounded online_icon">check_circle</span>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="accessible__wrap">
                        <span class="material-symbols-rounded online_icon">cancel</span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="small_wrapper">
                <p class="thesis_author underline">
                    <a href="/q?auteur=<?= $thesis['auteurs']['prenom'] ?>+<?= $thesis['auteurs']['nom'] ?>"> <?= $thesis['auteurs']['prenom'] ?> <span class="important_info"><?= $thesis['auteurs']['nom']; ?></span>
                    </a>
                </p>
                <div class="quick_thesis_subjects">
                    <?php for ($j = 0; $j < (count($thesis['sujets']) > 3 ? 3 : count($thesis['sujets'])); $j++) : ?>
                        <div class="quick_thesis_subject">
                            <p class="thesis_subject"> <a href="/q?sujet=<?= $thesis['sujets'][$j]; ?>"> <?= $thesis['sujets'][$j]; ?></a> </p>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div class="quick_thesis_info">
            <p class="quick_thesis_date underline">
                <a href="/q?depuis=<?= DateTime::createFromFormat('Y-m-d', $thesis['date'])->format('Y'); ?>"><?= DateTime::createFromFormat('Y-m-d', $thesis['date'])->format('d M'); ?>
                    <span class="important_info"><?= DateTime::createFromFormat('Y-m-d', $thesis['date'])->format('Y'); ?></span>
                </a>
            </p>
            <p class="quick_thesis_academy important_info underline">
                <a href="/q?etablissement=<?= $thesis['etablissement']; ?>"> <?= $thesis['etablissement']; ?> </a>
            </p>
        </div>
    </div>
</div>