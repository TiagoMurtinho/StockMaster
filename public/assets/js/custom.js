$(document).ready(function() {
    $('.ajax-form').on('submit', function(event) {
        event.preventDefault();

        var $form = $(this);
        var url = $form.attr('action');
        var method = $form.find('input[name="_method"]').val() || 'POST'; // Pega o método especificado ou usa POST como padrão

        $.ajax({
            url: url,
            method: method,
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                }
            }
        });
    });
});
