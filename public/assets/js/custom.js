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

    function loadArtigos(clienteId, selectElement) {
        $.ajax({
            url: `/artigos/${clienteId}`,
            method: 'GET',
            success: function(response) {

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

    $('#modalLinhaDocumento').on('show.bs.modal', function() {
        const clienteId = $(this).data('cliente-id');

        if (clienteId) {

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

                    $('#paleteFields .palete-row').each(function() {
                        loadArtigos(clienteId, $(this).find('select[name="artigo_id[]"]'));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar os tipos de palete:', error);
                }
            });

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

                loadArtigos(clienteId, '#paleteFields .palete-row:last select[name="artigo_id[]"]');
            });

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
                $('#rececaoModal' + response.linha_id).modal('hide');

                // Solicita o PDF usando a rota nomeada
                $.ajax({
                    url: '/documento/' + response.documento_id + '/pdf',  // Usando a rota correta para o PDF
                    method: 'GET',
                    data: { paletes_criadas: response.paletes_criadas },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob) {
                        var link = document.createElement('a');
                        var url = window.URL.createObjectURL(blob);
                        link.href = url;
                        link.download = 'nota_recepcao_' + response.documento_id + '.pdf';  // Nome do arquivo PDF
                        document.body.appendChild(link);
                        link.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(link);
                    },
                    error: function(xhr) {
                        console.log(xhr);
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

    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function () {
            const documentoId = this.getAttribute('data-id');

            $.ajax({
                url: '/documento/' + documentoId,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        console.log(data)
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
    document.querySelectorAll('.no-click-propagation').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
});

function populateModal(data) {

    const clienteId = data.documento.cliente_id;

    document.querySelector('.modal-documento-numero').value = data.documento.numero || '';
    document.querySelector('.modal-documento-data').value = data.documento.data || '';
    document.querySelector('.modal-documento-id').value = data.documento.id || '';
    document.querySelector('.modal-documento-observacao').value = data.documento.linha_documento[0].observacao
    document.querySelector('.modal-documento-previsao').value = data.documento.linha_documento[0].previsao
    document.querySelector('.modal-documento-valor').value = data.documento.linha_documento[0].valor

    $.ajax({
        url: '/tipo-paletes',
        method: 'GET',
        success: function (tiposPalete) {

            $.ajax({
                url: `/artigos/${clienteId}`,
                method: 'GET',
                success: function (artigos) {
                    preencherLinhasModal(data.linhas, tiposPalete, artigos);
                },
                error: function (error) {
                    console.error('Erro ao buscar artigos:', error);
                }
            });
        },
        error: function (error) {
            console.error('Erro ao buscar tipos de palete:', error);
        }
    });
}

function preencherLinhasModal(linhas, tiposPalete, artigos) {
    const linhaContainer = document.querySelector('.modal-linhas');
    linhaContainer.innerHTML = '';

    linhas.forEach(linha => {
        const linhaElement = document.createElement('tr');
        linhaElement.classList.add('palete-row');

        let tipoPaleteOptions = '';
        tiposPalete.forEach(tipo => {
            const selected = tipo.id === linha.tipo_palete_id ? 'selected' : '';
            tipoPaleteOptions += `<option value="${tipo.id}" ${selected}>${tipo.tipo}</option>`;
        });

        let artigoOptions = '';
        artigos.forEach(artigo => {
            const selected = artigo.id === linha.artigo_id ? 'selected' : '';
            artigoOptions += `<option value="${artigo.id}" ${selected}>${artigo.nome}</option>`;
        });

        linhaElement.innerHTML = `
            <td>
                <select class="form-select">
                    ${tipoPaleteOptions}
                </select>
            </td>
            <td><input class="form-control" type="number" value="${linha.quantidade}" /></td>
            <td>
                <select class="form-select">
                    ${artigoOptions}
                </select>
            </td>
            <td class="col-md-1 d-flex align-items-end">
                <a type="button" class="remove-palete-row">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        `;

        linhaContainer.appendChild(linhaElement);
    });

    configurarRemocao();
}

function configurarRemocao() {
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('remove-palete-row')) {
            event.target.closest('.palete-row').remove();
        }
    });
}

document.addEventListener('click', function(event) {
    if (event.target && event.target.classList.contains('add-palete-row')) {
        adicionarNovaLinha();
    }
});

function adicionarNovaLinha() {
    // Lógica para adicionar uma nova linha de palete
    const linhaContainer = document.querySelector('.modal-linhas');
    const novaLinha = `
        <tr class="palete-row">
            <td>
                <select class="form-select">
                    <!-- Options serão preenchidas via JavaScript -->
                </select>
            </td>
            <td><input class="form-control" type="number" /></td>
            <td>
                <select class="form-select">
                    <!-- Options serão preenchidas via JavaScript -->
                </select>
            </td>
            <td class="col-md-1 d-flex align-items-end">
                <a type="button" class="remove-palete-row">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
    `;
    linhaContainer.insertAdjacentHTML('beforeend', novaLinha);
}

function saveChanges() {
    const documentoId = document.querySelector('.modal-documento-id').value;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    });

    // Obter os valores dos campos principais
    const documento = {
        numero: document.querySelector('.modal-documento-numero').value,
        data: document.querySelector('.modal-documento-data').value,
    };

    // Obter os valores das linhas
    const linhas = [];
    document.querySelectorAll('.modal-linhas tr').forEach(row => {
        const inputs = row.querySelectorAll('input, select, textarea');

        const linhaData = {
            observacao: document.querySelector('.modal-documento-observacao').value,
            previsao: document.querySelector('.modal-documento-previsao').value,
            valor: document.querySelector('.modal-documento-valor').value,
            tipo_palete: inputs[0].value,
            quantidade: inputs[1].value,
            artigo: inputs[2].value
        };

        linhas.push(linhaData);
    });

    $.ajax({
        url: `/documento/${documentoId}`,
        method: 'PUT',
        data: JSON.stringify({ documento, linhas }),
        contentType: 'application/json',
        success: function (response) {
            if (response.success) {
                $('#documentoModal').modal('hide');
            } else {
                console.error('Erro ao salvar dados:', response.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('Erro ao salvar dados:', textStatus, errorThrown);
        }
    });
}
