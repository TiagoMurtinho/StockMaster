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
                $('#modalLinhaDocumento').data('cliente-id', clienteId);
                $('#modalAddDocumento').modal('hide');
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

        var tipoPaleteIds = [];
        var quantidades = [];
        var artigoIds = [];

        $('select[name="tipo_palete_id[]"]').each(function() {
            tipoPaleteIds.push($(this).val());
        });

        $('input[name="quantidade[]"]').each(function() {
            quantidades.push($(this).val());
        });
        $('select[name="artigo_id[]"]').each(function() {
            artigoIds.push($(this).val());
        });

        var linhasData = tipoPaleteIds.map(function(tipoPaleteId, index) {
            return {
                tipo_palete_id: tipoPaleteId,
                quantidade: quantidades[index],
                artigo_id: artigoIds[index]
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
    // Função para carregar os artigos com base no cliente_id
    function loadArtigos(clienteId, selectElement) {
        $.ajax({
            url: `/artigos/${clienteId}`, // Endpoint para obter os artigos
            method: 'GET',
            success: function(response) {
                // Verifique se a resposta é um array
                if (Array.isArray(response)) {
                    const options = response.map(artigo =>
                        `<option value="${artigo.id}">${artigo.nome}</option>`
                    ).join('');
                    $(selectElement).html(`<option value="">Selecione um Artigo</option>${options}`);
                } else {
                    console.error('Resposta inválida para artigos:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar os artigos:', error);
            }
        });
    }

    // Quando o modalLinhaDocumento for mostrado
    $('#modalLinhaDocumento').on('show.bs.modal', function() {
        const clienteId = $(this).data('cliente-id');

        if (clienteId) {
            // Carregar os tipos de paletes
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

                    // Carregar artigos para cada linha de palete já existente
                    $('#paleteFields .palete-row').each(function() {
                        loadArtigos(clienteId, $(this).find('select[name="artigo_id[]"]'));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar os tipos de palete:', error);
                }
            });

            // Adicionar nova linha de palete
            $('#addPaleteRow').off('click').on('click', function() {
                const newRow = `
                    <div class="palete-row mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Tipo Palete</label>
                                <select name="tipo_palete_id[]" class="form-select" required>
                                    ${$('#tipoPaleteSelect').html()}
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Quantidade</label>
                                <input type="number" step="1" min="0" class="form-control" name="quantidade[]" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Artigo</label>
                                <select name="artigo_id[]" class="form-select" required>
                                    <option value="">Selecione um Artigo</option>
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a type="button" class="remove-palete-row">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>`;

                $('#paleteFields').append(newRow);

                // Carregar artigos para a nova linha adicionada
                loadArtigos(clienteId, '#paleteFields .palete-row:last select[name="artigo_id[]"]');
            });

            // Remover linha de palete
            $(document).on('click', '.remove-palete-row', function() {
                $(this).closest('.palete-row').remove();
            });
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

document.addEventListener('DOMContentLoaded', function() {
    // Adiciona um listener de clique a todas as linhas com a classe "clickable-row"
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function () {
            const documentoId = this.getAttribute('data-id');

            // Faz uma requisição AJAX para obter os dados do documento
            $.ajax({
                url: '/documento/' + documentoId, // Verifique se o URL está correto
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        populateModal(data);
                        $('#documentoModal').modal('show');
                    } else {
                        console.error('Erro ao carregar dados:', data.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Erro ao carregar dados:', textStatus, errorThrown);
                }
            });
        });
    });
});

function populateModal(data) {
        // Preenche os campos do modal com os dados do documento
    document.getElementById('modal-documento-numero').innerText = data.documento.numero;
    document.getElementById('modal-documento-data').innerText = data.documento.data;
        // Preencha outros campos conforme necessário
        // ...
        // Limpe e adicione as linhas ao modal
    const linhaContainer = document.getElementById('modal-linhas');
    linhaContainer.innerHTML = ''; // Limpa as linhas existentes
    data.linhas.forEach(linha => {
        const linhaElement = document.createElement('tr');
        linhaElement.innerHTML = `
            <td>${linha.tipo_palete}</td>
            <td>${linha.quantidade}</td>
            <td>${linha.artigo}</td>
            `;
        linhaContainer.appendChild(linhaElement);
    });

}
