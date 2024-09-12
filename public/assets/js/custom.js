$(document).ready(function() {
    $('.ajax-form').on('submit', function(event) {
        event.preventDefault();

        var $form = $(this);
        var formData = new FormData($form[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    });
});

function confirmDelete(formId, url) {
    document.getElementById(formId).action = url;
}

$(document).ready(function() {
    $('#tipo_documento').change(function() {
        var tipoDocumentoId = $(this).val();

        $('#documentoForm')[0].reset();

        $('#dataEntregaField').hide();
        $('#matriculaField').hide();
        $('#moradaField').hide();
        $('#horaCargaField').hide();
        $('#descargaField').hide();
        $('#totalField').hide();

        if (tipoDocumentoId == 1) {
            $('#dataEntregaField').show();
        }

        if (tipoDocumentoId == 2) {
            $('#horaCargaField').show();
        }

        if (tipoDocumentoId == 3) {
            $('#moradaField').show();
            $('#descargaField').show();
        }

    });

    $('#continuarBtn').click(function() {

    });
});
