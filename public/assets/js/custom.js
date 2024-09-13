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
        $('#linhaDocumentoForm')[0].reset();
        $('#quantidadeField').hide();
        $('#dataField').hide();
        $('#matriculaField').hide();
        $('#moradaField').hide();
        $('#horaCargaField').hide();
        $('#descargaField').hide();
        $('#totalField').hide();
        $('#novaMoradaField').hide();
        $('#dataEntregaField').hide();
        $('#dataRecolhaField').hide();
        $('#extraField').hide();
        $('#tipoPaleteField').hide();
        $('#artigoField').hide()

        if (tipoDocumentoId == 1) {
            $('#dataField').show();
            $('#dataEntregaField').show();
            $('#quantidadeField').show();
            $('#tipoPaleteField').show();
        }

        if (tipoDocumentoId == 2) {
            $('#horaCargaField').show();
        }

        if (tipoDocumentoId == 3) {
            $('#moradaField').show();
            $('#descargaField').show();
        }

    });
});

$(document).ready(function() {
    var documentoId;

    // Primeiro botão "Continuar" (primeiro modal)
    $('#continuarModalDocumentoBtn').click(function() {

        var tipoDocumentoId = $('#tipo_documento').val();
        var clienteId = $('#cliente').val();

        console.log('Tipo Documento ID:', tipoDocumentoId);
        console.log('Cliente ID:', clienteId);

        // Armazenar dados no localStorage
        localStorage.setItem('tipo_documento_id', tipoDocumentoId);
        localStorage.setItem('cliente_id', clienteId);

        $('#tipoDocumentoModal').modal('hide');
        $('#modalDocumento').modal('show');
    });

    // Segundo botão "Continuar" (segundo modal)
    $('#continuarModalLinhaDocumentoBtn').click(function() {
        var tipoDocumentoId = $('#tipo_documento').val();
        var clienteId = $('#cliente').val();
        var numero = $('#numero').val();
        var data = $('#data').val();
        var matricula = $('#matricula').val();
        var morada = $('#morada').val();
        var horaCarga = $('#hora_carga').val();
        var horaDescarga = $('#hora_descarga').val();
        var total = $('#total').val();

        $.ajax({
            url: '/documento',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                tipo_documento_id: tipoDocumentoId,
                cliente_id: clienteId,
                numero: numero,
                data: data,
                matricula: matricula,
                morada: morada,
                hora_carga: horaCarga,
                hora_descarga: horaDescarga,
                total: total,
            },
            success: function(response) {
                documentoId = response.documento_id;
                $('#modalDocumento').modal('hide');
                $('#modalLinhaDocumento').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Erro ao criar o documento: ' + xhr.responseText);
            }
        });
    });

    // Terceiro botão "Continuar" (terceiro modal)
    $('#criarDocumentoBtn').click(function() {
        var quantidade = $('#quantidade').val();
        var descricao = $('#descricao').val();
        var valor = $('#valor').val();
        var dataEntrega = $('#data_entrega').val();
        var tipoPaleteId = $('#tipo_palete_id').val();
        var artigoId = $('#artigo_id').val();

        $.ajax({
            url: '/linha-documento',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                documento_id: documentoId,
                quantidade: quantidade,
                descricao: descricao,
                valor: valor,
                data_entrega: dataEntrega,
                tipo_palete_id: tipoPaleteId,
                artigo_id: artigoId
            },
            success: function(response) {
                $('#modalLinhaDocumento').modal('hide');
                window.location.href = '/documento/' + documentoId + '/pdf';
            },
            error: function(xhr, status, error) {
                console.log('Erro ao criar a linha do documento: ' + xhr.responseText);
            }
        });
    });
});
