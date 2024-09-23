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
        var taxaId = $('#taxa_id').val();
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
                taxa_id: taxaId,
                previsao: previsao,
                linhas: linhasData
            },
            success: function(response) {

                atualizarTabelaDocumentos();

                $('#modalLinhaDocumento').modal('hide');
                window.location.href = '/documento/' + documentoId + '/pdf';
            },
            error: function(xhr, status, error) {
                console.log('Erro ao criar a linha do documento: ' + xhr.responseText);
            }
        });
    });
});

function atualizarTabelaDocumentos() {
    $.ajax({
        url: '/documento/json',
        method: 'GET',
        success: function(response) {
            var tbody = $('tbody');
            tbody.empty();

            response.forEach(function(documento) {
                var linhaHtml = `
                    <tr class="clickable-row" data-id="${documento.id}">
                        <td class="align-middle text-center">${documento.numero}</td>
                        <td class="align-middle text-center">${documento.data}</td>
                        <td class="align-middle text-center">${documento.tipo_documento.nome}</td>
                        <td class="align-middle text-center">${documento.cliente.nome}</td>
                        <td class="align-middle text-center">${documento.user.nome}</td>
                        <td class="text-center">
                            <a href="/documento/${documento.id}/pdf" class="btn btn-secondary btn-sm no-click-propagation">
                                Gerar PDF
                            </a>
                        </td>
                    </tr>
                `;
                tbody.append(linhaHtml);
            });
        },
        error: function(xhr, status, error) {
            console.log('Erro ao atualizar a tabela de documentos: ' + xhr.responseText);
        }
    });
}

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
        console.log('Cliente ID:', clienteId);

        if (clienteId) {

            $.ajax({
                url: '/tipo-paletes',
                method: 'GET',
                success: function(response) {
                    if (Array.isArray(response)) {
                        var tipoPaleteSelect = $('#paleteFields .palete-row select[name="tipo_palete_id[]"]');
                        tipoPaleteSelect.empty();
                        var options = response.map(tipoPalete =>
                            `<option value="${tipoPalete.id}">${tipoPalete.tipo}</option>`
                        ).join('');
                        tipoPaleteSelect.append(`<option value="">Selecione um Tipo de Palete</option>${options}`);

                        $('#paleteFields .palete-row').each(function() {
                            loadArtigos(clienteId, $(this).find('select[name="artigo_id[]"]'));
                        });
                    } else {
                        console.error('Resposta inválida para tipos de palete:', response);
                    }
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
                                <label for="tipo_palete_id" class="form-label">Tipo de Palete</label>
                                    <select name="tipo_palete_id[]" class="form-select" required>
                                        ${$('#paleteFields .palete-row select[name="tipo_palete_id[]"]').html()}
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <label for="quantidade" class="form-label">Quantidade</label>
                                <input type="number" step="1" min="0" class="form-control" name="quantidade[]" required>
                            </div>
                            <div class="col-md-4">
                                <label for="artigo_id" class="form-label">Artigo</label>
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

                $.ajax({
                    url: '/documento/' + response.documento_id + '/pdf',
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

window.tiposPalete = [];
window.artigos = [];

function populateModal(data) {
    const clienteId = data.documento.cliente_id;

    document.querySelector('.modal-documento-numero').value = data.documento.numero || '';
    document.querySelector('.modal-documento-data').value = data.documento.data || '';
    document.querySelector('.modal-documento-id').value = data.documento.id || '';

    const primeiraLinha = data.documento.linha_documento[0] || {};
    document.querySelector('.modal-documento-observacao').value = primeiraLinha.observacao || '';
    document.querySelector('.modal-documento-previsao').value = primeiraLinha.previsao || '';
    document.querySelector('.modal-documento-valor').value = primeiraLinha.taxa_id || '';

    document.querySelector('.modal-linha-id').value = primeiraLinha.id || '';

    $.ajax({
        url: '/taxas',
        method: 'GET',
        success: function(taxas) {
            const taxaSelect = document.querySelector('#taxaSelect');
            taxaSelect.innerHTML = '<option value="">Selecione uma taxa</option>';

            taxas.forEach(taxa => {
                const option = document.createElement('option');
                option.value = taxa.id;
                option.textContent = `${taxa.nome} - ${taxa.valor}`;
                taxaSelect.appendChild(option);
            });

            if (primeiraLinha.taxa_id) {
                taxaSelect.value = primeiraLinha.taxa_id;
            }
        },
        error: function(error) {
            console.error('Erro ao buscar as taxas:', error);
        }
    });

    $.ajax({
        url: '/tipo-paletes',
        method: 'GET',
        success: function(tiposPaleteResponse) {
            window.tiposPalete = tiposPaleteResponse;

            $.ajax({
                url: `/artigos/${clienteId}`,
                method: 'GET',
                success: function(artigosResponse) {
                    window.artigos = artigosResponse;
                    preencherLinhasModal(data.linhas, window.tiposPalete, window.artigos);
                },
                error: function(error) {
                    console.error('Erro ao buscar artigos:', error);
                }
            });
        },
        error: function(error) {
            console.error('Erro ao buscar tipos de palete:', error);
        }
    });
}

$(document).on('click', '.remove-palete-row', function() {

    const row = $(this).closest('tr');
    const deletedInput = row.find('input[name="deleted[]"]');

    if (deletedInput.length) {
        if (deletedInput.val() === '0') {
            deletedInput.val(1);

            row.hide();
        } else {
            console.log('A linha já está marcada como deletada.');
        }
    } else {
        console.error('Input "deleted[]" não encontrado na linha.');
    }
});

function preencherLinhasModal(linhas, tiposPalete, artigos) {
    const linhaContainer = document.querySelector('.modal-linhas');
    linhaContainer.innerHTML = '';

    linhas.forEach((linha, index) => {
        const linhaElement = document.createElement('tr');
        linhaElement.classList.add('palete-row');

        let tipoPaleteOptions = tiposPalete.map(tipo => {

            const selected = tipo.tipo === linha.tipo_palete ? 'selected' : '';
            return `<option value="${tipo.id}" ${selected}>${tipo.tipo}</option>`;
        }).join('');

        let artigoOptions = artigos.map(artigo => {

            const selected = artigo.nome === linha.artigo ? 'selected' : '';
            return `<option value="${artigo.id}" ${selected}>${artigo.nome}</option>`;
        }).join('');

        linhaElement.innerHTML = `
            <td>
                <select class="form-select modal-linha-tipo-palete">
                    ${tipoPaleteOptions}
                </select>
            </td>
            <td>
                <input class="form-control modal-linha-quantidade" type="number" value="${linha.quantidade || ''}" />
            </td>
            <td>
                <select class="form-select modal-linha-artigo">
                    ${artigoOptions}
                </select>
            </td>
            <td class="col-md-1 d-flex align-items-end">
                <a type="button" class="remove-palete-row">
                    <i class="bi bi-trash"></i>
                </a>
                <input type="hidden" name="pivot_id[]" class="modal-linha-pivot-id" value="${linha.pivot_id || ''}" />
                <input type="hidden" name="deleted[]" value="0" />
            </td>
        `;

        linhaContainer.appendChild(linhaElement);
    });
}

document.addEventListener('click', function(event) {
    if (event.target && event.target.classList.contains('add-palete-row')) {
        adicionarNovaLinha();
    }
});

document.addEventListener('click', function(event) {
    if (event.target && event.target.closest('.remove-palete-row')) {
        const row = event.target.closest('tr.palete-row');
        row.remove();
    }
});

function adicionarNovaLinha() {

    let tipoPaleteOptions = '';
    window.tiposPalete.forEach(tipo => {
        tipoPaleteOptions += `<option value="${tipo.id}">${tipo.tipo}</option>`;
    });

    let artigoOptions = '';
    window.artigos.forEach(artigo => {
        artigoOptions += `<option value="${artigo.id}">${artigo.nome}</option>`;
    });

    const linhaContainer = document.querySelector('.modal-linhas');
    const novaLinha = `
        <tr class="palete-row">
            <td>
                <select class="form-select">
                    ${tipoPaleteOptions}
                </select>
            </td>
            <td><input class="form-control" type="number" /></td>
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

    const documento = {
        numero: document.querySelector('.modal-documento-numero').value,
        data: document.querySelector('.modal-documento-data').value,
    };

    const linhaId = document.querySelector('.modal-linha-id');
    const linha_documento = {
        id: linhaId.value, // ID da linha
        observacao: document.querySelector('.modal-documento-observacao').value,
        previsao: document.querySelector('.modal-documento-previsao').value,
        taxa_id: document.querySelector('.modal-documento-valor').value,
    };

    const linha_documento_tipo_palete = [];
    document.querySelectorAll('.modal-linhas tr').forEach(row => {
        const inputs = row.querySelectorAll('input, select, textarea');

        const pivotIdField = row.querySelector('.modal-linha-pivot-id');
        const pivotId = pivotIdField ? pivotIdField.value : null;

        const deletedInput = row.querySelector('input[name="deleted[]"]');
        const deleted = deletedInput ? deletedInput.value === '1' : false;

        const linhaData = {
            linha_documento_id: linhaId.value,
            tipo_palete: inputs[0].value,
            quantidade: inputs[1].value,
            artigo: inputs[2].value,
            deleted: deleted
        };

        if (pivotId) {
            linhaData.id = pivotId;
        }

        linha_documento_tipo_palete.push(linhaData);
    });

    $.ajax({
        url: `/documento/${documentoId}`,
        method: 'PUT',
        data: JSON.stringify({ documento, linha_documento, linha_documento_tipo_palete }),
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
            console.log('Response:', jqXHR.responseText);
        }
    });
}
