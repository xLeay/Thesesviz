<div class="quick_thesis_container">
    <div class="quick_thesis_wrap">
        <div class="quick_thesis">
            <div class="title_content">
                <p class="thesis_title bold underline"> <a href="/q?id=<?= $thesis['id']; ?>"><?= $thesis['titre']; ?></a></p>
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