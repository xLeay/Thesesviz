
document.addEventListener('DOMContentLoaded', function () {

    const search_thesis = document.querySelector('.search_thesis');
    const search = document.getElementById('search');
    const close_icon = document.querySelector('.close_icon');
    const search_options = document.querySelector('.search_options');
    const search_option_item = document.querySelectorAll('.search_options__item');

    search.addEventListener('input', function () {
        if (search.value != '') {
            close_icon.style.display = 'block';
        } else {
            close_icon.style.display = 'none';
        }
    });

    close_icon.addEventListener('click', function () {
        search.value = '';
        close_icon.style.display = 'none';
        search_options.style.display = 'none';
    });

    // On affiche les options de recherche lorsqu'on clique sur le formulaire de recherche
    document.addEventListener('click', function (e) {
        if (e.target == search_thesis || search_thesis.contains(e.target) && e.target != search_options && search_options.contains(e.target) == false) {
            if (search.value == '') {
            search_options.style.display = 'flex';
            }
        } else {
            search_options.style.display = 'none';
        }
    });

    search_options.addEventListener('click', function (e) {
        search_option_item.forEach(function (item) {
            if (item.contains(e.target)) {
                // console.log(e.target.parentElement.querySelector('span').textContent);
                search.value = e.target.parentElement.querySelector('span').textContent + ' ';
                search_options.style.display = 'none';
                close_icon.style.display = 'block';
                
                
            }
        });
    });



});