
<nav>
    <form action="/q" method="get" class="search_thesis" autocomplete="off" enctype="text/plain" onsubmit="submitForm(event)">
        <div class="search_wrap">
            <div class="search__editor" spellcheck="false">
                <div class="search__editor-placeholder" style="padding-left: 15px;">
                    <div class="search__editor-placeholder-inner" style="outline: none;" contenteditable="true" placeholder="Recherche de thèse..."></div>
                </div>
            </div>

            <div class="search__icon" style="margin-right: 15px;">
                <span class="material-symbols-rounded search_icon">search</span>
            </div>
        </div>

        <!-- input factice qui gère le submit à la place de la div contenteditable qui permet de faire une recherche avancée -->
        <input type="text" id="search_input" style="display: none;" name="key">

        <!-- options de recherche -->
        <div class="search_options">
            <p style="font-weight: 700; font-size: 18px; text-transform: uppercase;">Options de recherche</p>

            <div class="sep" style="width: 100%; height: 1px; background: #C7BEB5;"></div>

            <div class="search_options__list">
                <div class="search_options__item">
                    <p><span class="important_info">titre:</span> titre (par défaut)</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">auteur:</span> nom</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">sujet:</span> libellé</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">depuis:</span> année</p>
                </div>
                <div class="search_options__item">
                    <p><span class="important_info">de:</span> année <span class="important_info">à:</span> année</p>
                </div>
            </div>
        </div>
    </form>
</nav>