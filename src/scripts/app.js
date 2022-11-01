
const search_thesis = document.querySelector('.search_thesis');
const search = document.getElementById('search');
const close_icon = document.querySelector('.close_icon');

search.addEventListener('input', function() {
    if (search.value != '') {
        close_icon.style.display = 'block';
    } else {
        close_icon.style.display = 'none';
    }
});

close_icon.addEventListener('click', function() {
    search.value = '';
    close_icon.style.display = 'none';
});