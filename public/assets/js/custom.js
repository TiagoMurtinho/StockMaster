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

        $('#linhaDocumentoForm')[0].reset();
        $('#artigoField').hide()
        $('#camposOcultos').hide()

        if (tipoDocumentoId == 3) {
            $('#moradaField').show();
            $('#descargaField').show();
        }

    });
});

$(document).ready(function() {
    /*var documentoId;*/

    $('#continuarModalLinhaDocumentoBtn').click(function() {
        var tipoDocumentoId = $('#tipo_documento').val();
        var clienteId = $('#cliente').val();
        var numero = $('#numero').val();
        var data = $('#data').val();
        var matricula = $('#matricula').val();
        var morada = $('#morada').val();
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

    $('#criarDocumentoBtn').click(function() {
        var observacao = $('#observacao').val();
        var valor = $('#valor').val();
        var previsao = $('#previsao').val();
        var artigoId = $('#artigo_id').val();

        var tipoPaleteIds = [];
        var quantidades = [];

        $('select[name="tipo_palete_id[]"]').each(function() {
            tipoPaleteIds.push($(this).val());
        });

        $('input[name="quantidade[]"]').each(function() {
            quantidades.push($(this).val());
        });

        var linhasData = tipoPaleteIds.map(function(tipoPaleteId, index) {
            return {
                tipo_palete_id: tipoPaleteId,
                quantidade: quantidades[index]
            };
        });

        $.ajax({
            url: '/linha-documento',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                documento_id: documentoId,
                observacao: observacao,
                valor: valor,
                previsao: previsao,
                artigo_id: artigoId,
                linhas: linhasData
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

$(document).ready(function() {

    $.ajax({
        url: '/tipo-paletes',
        method: 'GET',
        success: function(response) {
            var tipoPaleteSelect = $('#tipoPaleteSelect');
            var options = '';

            response.forEach(function(tipoPalete) {
                options += `<option value="${tipoPalete.id}">${tipoPalete.tipo}</option>`;
            });

            tipoPaleteSelect.html(options);

            $('#addPaleteRow').click(function() {
                var newRow = `
                    <div class="palete-row mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tipo_palete_id" class="form-label">Tipo Palete</label>
                                <select name="tipo_palete_id[]" class="form-select" required>
                                    ${options}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" step="1" min="0" class="form-control" name="quantidade[]" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <a type="button" class="remove-palete-row">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>`;

                $('#paleteFields').append(newRow);
            });

            $(document).on('click', '.remove-palete-row', function() {
                $(this).closest('.palete-row').remove();
            });
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar os tipos de palete:', error);
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const armazemOptionsElement = document.getElementById('armazem-options');
    if (armazemOptionsElement) {
        const armazemOptions = JSON.parse(armazemOptionsElement.textContent);

        document.querySelectorAll('.armazem-select').forEach(select => {
            const tipoPaleteId = select.getAttribute('data-tipo-palete-id');

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Nenhum armazém';
            select.appendChild(defaultOption);

            armazemOptions.forEach(armazem => {
                const option = document.createElement('option');
                option.value = armazem.id;
                option.textContent = armazem.nome;

                if (armazem.tipo_palete_id === parseInt(tipoPaleteId)) {
                    option.selected = true;
                }

                select.appendChild(option);
            });
        });
    }
});

$('#modalForm').on('submit', function(e) {
    e.preventDefault();

    var $form = $(this);
    var formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: $form.attr('action'),
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#modalForm').modal('hide');

                // Solicita o PDF
                $.ajax({
                    url: '/gerar-pdf/' + response.documento_id,
                    method: 'GET',
                    data: { paletes_criadas: response.paletes_criadas },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob) {
                        var link = document.createElement('a');
                        var url = window.URL.createObjectURL(blob);
                        link.href = url;
                        link.download = 'nota_recepcao_' + response.documento_id + '.pdf';
                        document.body.appendChild(link);
                        link.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(link);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Erro ao gerar o PDF.');
                    }
                });
            } else {
                alert('Erro ao criar documento.');
            }
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Erro ao processar o pedido.');
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {

    var alert = document.getElementById('mensagem-dinamica');

    if (alert) {
        setTimeout(function() {
            alert.style.display = 'none';
        }, 3000);
    }
});
