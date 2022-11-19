<nav>
    <form action="" class="search_thesis" onclick="document.getElementById('search').focus();" autocomplete="off">
        <span class="material-symbols-rounded search_icon">search</span>
        <input type="text" name="keyword" id="search" placeholder="Recherche de thèse par titre, année, auteur...">
        <span class="material-symbols-rounded close_icon">close</span>

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