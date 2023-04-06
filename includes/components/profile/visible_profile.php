<h1 class="section_title" style="margin-bottom: 10px">Page de profil</h1>

<div class="main_wrapper">
    <section class="profile_page card">

        <div class="profile_picture__container">
            <img src="<?= $big_profile_pic ?>" class="profile_picture card" alt="Photo de profil">
        </div>

        <div class="profile_info__container">
            <div class="profile_info__user">
                <p class="profile_name" id="profile_info"><?= $user_result['pseudo'] ?></p>
            </div>
        </div>

    </section>
</div>