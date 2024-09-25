$(document).ready(function() {
    $('a[data-ajax="true"]').on('click', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $('#main').load(url + ' #main > *', function(response, status, xhr) {
            if (status === "error") {
                console.log("Erro ao carregar o conteúdo: " + xhr.status + " " + xhr.statusText);
            }

            $('a[data-ajax="true"]').removeClass('active');
            $('a[data-ajax="true"] i').removeClass('active-icon');

            $('a[href="' + url + '"]').addClass('active');
            $('a[href="' + url + '"] i').addClass('active-icon');
        });

        window.history.pushState({path: url}, '', url);
    });

    $(window).on('popstate', function() {
        $('#main').load(location.href + ' #main > *', function() {

            $('a[data-ajax="true"]').removeClass('active');
            $('a[data-ajax="true"] i').removeClass('active-icon');

            $('a[href="' + location.href + '"]').addClass('active');
            $('a[href="' + location.href + '"] i').addClass('active-icon');
        });
    });
});
