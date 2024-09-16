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
        $('#dataEntradaField').hide()
        $('#taxaField').hide()


        if (tipoDocumentoId == 1) {
            $('#dataField').show();
            $('#dataEntregaField').show();
            $('#quantidadeField').show();
            $('#tipoPaleteField').show();
            $('#taxaField').show()
        }

        if (tipoDocumentoId == 2) {
            $('#dataField').show();
            $('#artigoField').show()
            $('#tipoPaleteField').show();
            $('#dataEntradaField').show();
        }

        if (tipoDocumentoId == 3) {
            $('#moradaField').show();
            $('#descargaField').show();
        }

    });
});

$(document).ready(function() {
    var documentoId;

    $('#continuarModalDocumentoBtn').click(function() {

        var tipoDocumentoId = $('#tipo_documento').val();
        var clienteId = $('#cliente').val();

        localStorage.setItem('tipo_documento_id', tipoDocumentoId);
        localStorage.setItem('cliente_id', clienteId);

        $('#tipoDocumentoModal').modal('hide');
        $('#modalDocumento').modal('show');
    });

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

    $('#criarDocumentoBtn').click(function() {
        var descricao = $('#descricao').val();
        var valor = $('#valor').val();
        var dataEntrega = $('#data_entrega').val();
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
                descricao: descricao,
                valor: valor,
                data_entrega: dataEntrega,
                artigo_id: artigoId,
                linhas: linhasData // Enviar o array de linhas
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

            // Remover a linha ao clicar no botão de remover
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
        console.log('Armazéns:', armazemOptions);

        document.querySelectorAll('.armazem-select').forEach(select => {
            const tipoPaleteId = select.getAttribute('data-tipo-palete-id');
            console.log('Tipo Palete ID:', tipoPaleteId);

            const defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Selecione um Armazém';
            defaultOption.disabled = true;
            defaultOption.selected = true;
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
