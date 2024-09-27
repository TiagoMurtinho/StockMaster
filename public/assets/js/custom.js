$(document).ready(function() {
    let guiaTransporteInitialized = false;
    let formHandlingInitialized = false;
    let tipoDocumentoInitialized = false;

    function initContentHandlers() {

        initContinuarModal();
        initLinhaDocumentoModal();
        initVoltarAoPrimeiroModal();
        initAddPaleteRow();
        initRemovePaleteRow();
        initCriarDocumentoBtn();
        initArmazemOptions();
        fillArmazemSelects();
        initRececaoFormHandler();
        initDynamicAlert();
        initClickableRows();
        removePalete();
        initPaleteRowEvents();

        if (!guiaTransporteInitialized) {
            initGuiaTransporteModalEvents();
            guiaTransporteInitialized = true;
        }

        if (!formHandlingInitialized) {
            initFormHandling();
            formHandlingInitialized = true;
        }

        if (!tipoDocumentoInitialized) {
            initTipoDocumentoChangeHandling();
            tipoDocumentoInitialized = true;
        }
    }

    initContentHandlers();

    function initDynamicContent() {

        $(document).off('click', 'a[data-ajax="true"]').on('click', 'a[data-ajax="true"]', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            $('#main').load(url + ' #main > *', function(response, status, xhr) {
                if (status === "error") {
                    console.log("Erro ao carregar o conteúdo: " + xhr.status + " " + xhr.statusText);
                } else {

                    initContentHandlers();

                    $('a[data-ajax="true"]').removeClass('active');
                    $('a[href="' + url + '"]').addClass('active');
                }
            });

            window.history.pushState({path: url}, '', url);
        });
    }

    initDynamicContent();

    $(window).on('popstate', function() {
        $('#main').load(location.href + ' #main > *', function(response, status, xhr) {
            if (status === "error") {
                console.log("Erro ao carregar o conteúdo: " + xhr.status + " " + xhr.statusText);
            } else {
                initContentHandlers();
            }
        });
    });
});

function initFormHandling() {
    $(document).on('submit', '.ajax-form', function(event) {
        event.preventDefault();

        var $form = $(this);
        var formData = new FormData($form[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {

                    $form.closest('.modal').modal('hide');

                    $form.closest('tr').remove();
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    });
}

function initTipoDocumentoChangeHandling() {
    $(document).on('change', '#tipo_documento', function() {
        var tipoDocumentoId = $(this).val();
        var clienteId = $('#cliente').val();

        // Resetar o formulário
        $('#linhaDocumentoForm')[0].reset();
        $('#moradaOculta').hide();
        $('#faturacaoOculta').hide();
        $('#datasOcultas').hide(); // Ocultar inicialmente as datas de faturação

        // Mostrar campos de taxa e previsão para tipos específicos
        if (tipoDocumentoId == 1) {
            $('#taxaOculta').show();
            $('#previsaoOculta').show();
        }

        if (tipoDocumentoId == 3) {
            $('#moradaOculta').show();
            $('#taxaOculta').show();
            $('#previsaoOculta').show();
        }

        // Mostrar campos de data para faturação
        if (tipoDocumentoId == 5) {
            $('#taxaOculta').hide();
            $('#previsaoOculta').hide();
            $('#datasOcultas').show(); // Mostrar o container das datas

            // Limpar valores anteriores de faturação
            $('#total').val('');

            if (clienteId) {
                // Capturar as datas
                var dataInicio = $('#data_inicio').val();
                var dataFim = $('#data_fim').val();

                // Enviar dados para o backend
                $.ajax({
                    url: '/documento/faturacao/' + clienteId,
                    method: 'GET',
                    data: {
                        data_inicio: dataInicio,
                        data_fim: dataFim
                    },
                    success: function(response) {
                        $('#total').val(response.total);
                        $('#faturacaoOculta').show();
                    },
                    error: function(xhr, status, error) {
                        console.log('Erro ao obter faturação: ' + xhr.responseText);
                    }
                });
            } else {
                console.log('Nenhum cliente selecionado para faturação.');
            }
        }
    });
}

let documentoData = {};

function initContinuarModal() {
    $('#continuarModalLinhaDocumentoBtn').off('click').on('click', function() {

        documentoData.tipo_documento_id = $('#tipo_documento').val();
        documentoData.cliente_id = $('#cliente').val();
        documentoData.numero = $('#numero').val();
        documentoData.morada = $('#morada').val();
        documentoData.observacao = $('#observacao').val();
        documentoData.taxa_id = $('#taxa_id').val();
        documentoData.previsao = $('#previsao').val();
        documentoData.total = $('#total').val();
        documentoData.extra = $('#extra').val();

        $('#modalAddDocumento').modal('hide');

        if (documentoData.tipo_documento_id == 5) {

            criarDocumentoSemLinha();
        } else {
            $('#modalLinhaDocumento').data('cliente-id', documentoData.cliente_id);
            $('#modalLinhaDocumento').modal('show');
        }

        initCriarDocumentoBtn(documentoData);
    });
}

function criarDocumentoSemLinha() {

    var extraValue = $('#extra').val();

    var totalValue = $('#total').val();

    $.ajax({
        url: '/documento',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            documento: {
                ...documentoData,
                total: totalValue,
                extra: extraValue
            },
            linhas: []
        },
        success: function(response) {
            atualizarTabelaDocumentos();
            window.location.href = '/documento/' + response.documento_id + '/pdf';
        },
        error: function(xhr, status, error) {
            console.log('Erro ao criar o documento: ' + xhr.responseText);
        }
    });
}

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

function loadTipoPaletes() {
    return $.ajax({
        url: '/tipo-paletes',
        method: 'GET',
    });
}

function initVoltarAoPrimeiroModal() {
    $('#voltarAoPrimeiroModal').off('click').on('click', function() {
        $('#modalLinhaDocumento').modal('hide');
        $('#modalAddDocumento').modal('show');

        $('#tipo_documento').val(documentoData.tipo_documento_id);
        $('#cliente').val(documentoData.cliente_id);
        $('#numero').val(documentoData.numero);
        $('#morada').val(documentoData.morada);
        $('#observacao').val(documentoData.observacao);
        $('#taxa_id').val(documentoData.taxa_id);
        $('#previsao').val(documentoData.previsao);
    });
}

function initLinhaDocumentoModal() {
    $('#modalLinhaDocumento').off('show.bs.modal').on('show.bs.modal', function() {
        const clienteId = $('#modalLinhaDocumento').data('cliente-id');

        if (clienteId) {
            loadTipoPaletes().done(function(response) {
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
            });
        }
    });
}

function initAddPaleteRow() {
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

        const clienteId = $('#modalLinhaDocumento').data('cliente-id');
        loadArtigos(clienteId, '#paleteFields .palete-row:last select[name="artigo_id[]"]');
    });
}

function initRemovePaleteRow() {
    $(document).off('click', '.remove-palete-row').on('click', '.remove-palete-row', function() {
        $(this).closest('.palete-row').remove();
    });
}

function initCriarDocumentoBtn() {
    $('#criarDocumentoBtn').off('click').on('click', function() {
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
            url: '/documento',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                documento: documentoData,
                linhas: linhasData
            },
            success: function(response) {
                atualizarTabelaDocumentos();
                $('#modalLinhaDocumento').modal('hide');
                window.location.href = '/documento/' + response.documento_id + '/pdf';
            },
            error: function(xhr, status, error) {
                console.log('Erro ao criar o documento: ' + xhr.responseText);
            }
        });
    });
}

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
                        <td class="align-middle text-center">${documento.estado}</td>
                        <td class="text-center">
                            <a href="/documento/${documento.id}/pdf" class="btn btn-secondary btn-sm no-click-propagation">
                                Gerar PDF
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteDocumentoModal${documento.id}">
                                <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                    Eliminar
                                </button>
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

let armazemOptions = [];

function initArmazemOptions() {
    const armazemOptionsElement = document.getElementById('armazem-options');
    if (armazemOptionsElement) {

        armazemOptions = JSON.parse(armazemOptionsElement.textContent) || [];
        console.log("Armazem Options Carregados:", armazemOptions);
    }
}

function fillArmazemSelects() {
    document.querySelectorAll('.armazem-select').forEach(select => {

        select.innerHTML = '';

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

        select.querySelectorAll('option').forEach(option => {
            console.log(` - ${option.textContent} (value: ${option.value})`);
        });
    });
}

function initRececaoFormHandler() {
    $('#modalRececaoForm').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var formData = $form.serialize();
        var documentoIdAntigo = $form.find('input[name="documento_id"]').val();

        $.ajax({
            type: 'POST',
            url: $form.attr('action'),
            data: formData,
            success: function(response) {
                if (response.success) {

                    $('#rececaoModal' + documentoIdAntigo).modal('hide');

                    generateRececaoPDF(response.documento_id, response.paletes_criadas);
                } else {
                    alert('Erro ao criar documento.');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                alert('Erro ao processar o pedido: ' + xhr.responseText);
            }
        });
    });
}

function generateRececaoPDF(documentoId, paletesCriadas) {
    $.ajax({
        url: '/documento/' + documentoId + '/pdf',
        method: 'GET',
        data: { paletes_criadas: paletesCriadas },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(blob) {
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(blob);
            link.href = url;
            link.download = 'nota_recepcao_' + documentoId + '.pdf';
            document.body.appendChild(link);
            link.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(link);
        },
        error: function(xhr) {
            console.error(xhr);
            alert('Erro ao gerar o PDF.');
        }
    });
}

function initDynamicAlert() {
    var alert = document.getElementById('mensagem-dinamica');

    if (alert) {
        setTimeout(function() {
            alert.style.display = 'none';
        }, 3000);
    }
}

function initClickableRows() {
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', function () {
            const documentoId = this.getAttribute('data-id');

            $.ajax({
                url: '/documento/' + documentoId,
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

    document.querySelectorAll('.no-click-propagation').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
}

window.tiposPalete = [];
window.artigos = [];

function populateModal(data) {
    const clienteId = data.documento.cliente_id;

    document.querySelector('.modal-documento-numero').value = data.documento.numero || '';
    document.querySelector('.modal-documento-data').value = data.documento.data || '';
    document.querySelector('.modal-documento-id').value = data.documento.id || '';
    document.querySelector('.modal-documento-observacao').value = data.documento.observacao || '';
    document.querySelector('.modal-documento-previsao').value = data.documento.previsao || '';
    document.querySelector('.modal-documento-valor').value = data.documento.taxa_id || '';

    if (data.linhas && data.linhas.length > 0) {
        const primeiraLinha = data.linhas[0];

        const linhaIdInput = document.querySelector('.modal-linha-id');
        if (linhaIdInput) {
            linhaIdInput.value = primeiraLinha.pivot_id || '';
        } else {
            console.error('Elemento .modal-linha-id não encontrado.');
        }
    } else {
        console.warn('Nenhuma linha encontrada no documento.');
    }

    fetch('/taxas')
        .then(response => response.json())
        .then(taxas => {
            const taxaSelect = document.querySelector('#taxaSelect');
            taxaSelect.innerHTML = '<option value="">Selecione uma taxa</option>';

            taxas.forEach(taxa => {
                const option = document.createElement('option');
                option.value = taxa.id;
                option.textContent = `${taxa.nome} - ${taxa.valor}`;
                taxaSelect.appendChild(option);
            });

            if (data.documento.taxa_id) {
                taxaSelect.value = data.documento.taxa_id;
            }
        })
        .catch(error => {
            console.error('Erro ao buscar as taxas:', error);
        });

    fetch('/tipo-paletes')
        .then(response => response.json())
        .then(tiposPaleteResponse => {
            window.tiposPalete = tiposPaleteResponse;

            return fetch(`/artigos/${clienteId}`);
        })
        .then(response => response.json())
        .then(artigosResponse => {
            window.artigos = artigosResponse;
            preencherLinhasModal(data.linhas, window.tiposPalete, window.artigos);
        })
        .catch(error => {
            console.error('Erro ao buscar tipos de palete ou artigos:', error);
        });
}

function removePalete() {

    $(document).on('click', '.remove-palete', function() {
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
}

function preencherLinhasModal(linhas, tiposPalete, artigos) {
    const linhaContainer = document.querySelector('.modal-linhas');
    linhaContainer.innerHTML = '';

    linhas.forEach((linha) => {
        const linhaElement = document.createElement('tr');
        linhaElement.classList.add('palete-row');

        let tipoPaleteOptions = tiposPalete.map(tipo => {
            const selected = tipo.id === linha.tipo_palete_id ? 'selected' : '';
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
                <a type="button" class="remove-palete">
                    <i class="bi bi-trash"></i>
                </a>
                <input type="hidden" name="pivot_id[]" class="modal-linha-pivot-id" value="${linha.pivot_id || ''}" />
                <input type="hidden" name="deleted[]" value="0" />
            </td>
        `;

        linhaContainer.appendChild(linhaElement);
    });
}

function initPaleteRowEvents() {
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('add-palete-row')) {
            adicionarNovaLinha();
        } else if (event.target && event.target.closest('.remove-palete-row')) {
            const row = event.target.closest('tr.palete-row');
            if (row) {
                row.remove();
            }
        }
    });
}

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
                <a type="button" class="remove-palete">
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
        observacao: document.querySelector('.modal-documento-observacao').value,
        previsao: document.querySelector('.modal-documento-previsao').value,
        taxa_id: document.querySelector('.modal-documento-valor').value
    };

    const documento_tipo_palete = [];

    document.querySelectorAll('.modal-linhas tr').forEach(row => {
        const inputs = row.querySelectorAll('input, select, textarea');

        const pivotIdField = row.querySelector('.modal-linha-pivot-id');
        const pivotId = pivotIdField ? pivotIdField.value : null;

        const deletedInput = row.querySelector('input[name="deleted[]"]');
        const deleted = deletedInput ? deletedInput.value === '1' : false;

        const linhaData = {
            documento_id: documentoId,
            tipo_palete: inputs[0].value,
            quantidade: inputs[1].value,
            artigo: inputs[2].value,
            deleted: deleted
        };

        if (pivotId) {
            linhaData.id = pivotId;
        }

        documento_tipo_palete.push(linhaData);
    });

    $.ajax({
        url: `/documento/${documentoId}`,
        method: 'PUT',
        data: JSON.stringify({ documento, documento_tipo_palete }),
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

function initGuiaTransporteModalEvents() {

    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('continuarGuiaTransporteBtn')) {
            event.preventDefault();

            const button = event.target;

            const numero = button.getAttribute('data-documento-numero');
            const clienteId = button.getAttribute('data-documento-cliente-id');
            const observacao = button.getAttribute('data-linha-observacao');
            const previsao = button.getAttribute('data-linha-previsao');
            const taxaId = button.getAttribute('data-linha-taxa-id');
            const morada = button.getAttribute('data-documento-morada');

            document.getElementById('numero').value = numero;
            document.getElementById('cliente_id').value = clienteId;
            document.getElementById('observacao').value = observacao;
            document.getElementById('previsao').value = previsao;
            document.getElementById('taxa_id').value = taxaId;
            document.getElementById('morada').value = morada;

            const documentoId = button.getAttribute('data-documento-id');
            const retiradaModal = bootstrap.Modal.getInstance(document.getElementById('retiradaModal' + documentoId));
            if (retiradaModal) {
                retiradaModal.hide();
            }

            const guiaTransporteModal = new bootstrap.Modal(document.getElementById('modalGuiaTransporte'));
            guiaTransporteModal.show();

            const guiaForm = document.getElementById('documentoForm');

            document.getElementById('confirmarEnvio').onclick = function() {
                let paletesDados = [];
                const selectedPaletes = document.querySelectorAll('input[name="paletes_selecionadas[]"]:checked');

                selectedPaletes.forEach(palete => {
                    paletesDados.push({
                        tipo_palete_id: palete.getAttribute('data-tipo-palete-id'),
                        artigo_id: palete.getAttribute('data-artigo-id'),
                        armazem_id: palete.getAttribute('data-armazem-id'),
                        localizacao: palete.getAttribute('data-localizacao'),
                        id: palete.value
                    });
                });

                fetch('/paletes/retirar', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        paletes_selecionadas: paletesDados.map(p => p.id),
                        documento_id: documentoId
                    }),
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao atualizar as paletes');
                        }
                        return response.json();
                    })
                    .then(data => {

                        let formData = new FormData(guiaForm);
                        formData.append('paletes_dados', JSON.stringify(paletesDados));

                        return fetch(guiaForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao enviar o formulário');
                        }
                        return response.json();
                    })
                    .then(data => {

                        const documentoId = data.documento.id;

                        $.ajax({
                            url: '/documento/' + documentoId + '/pdf',
                            method: 'GET',
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function(blob) {
                                var link = document.createElement('a');
                                var url = window.URL.createObjectURL(blob);
                                link.href = url;
                                link.download = 'guia_transporte_' + documentoId + '.pdf';
                                document.body.appendChild(link);
                                link.click();
                                window.URL.revokeObjectURL(url);
                                document.body.removeChild(link);
                            },
                            error: function(xhr) {
                                console.error(xhr);
                                alert('Erro ao gerar o PDF.');
                            }
                        });

                        $('#modalGuiaTransporte').modal('hide');

                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
            };
        }
    });
}
