$(document).ready(function() {
    let guiaTransporteInitialized = false;
    let formHandlingInitialized = false;
    let tipoDocumentoInitialized = false;
    let deleteInitialized = false;
    let captureInitialized = false;

    function initContentHandlers() {
        initContinuarModal();
        initLinhaDocumentoModal();
        initVoltarAoDocumentoModal();
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
        initVoltarAoPedidoRetiradaModal();
        loadNotifications();
        initializeClientSearch();
        initTipoPaleteSearch();
        initArmazemSearch();
        initArtigoSearch();
        initTaxaSearch();
        initDocumentoSearch();
        initUserSearch();
        initEntregaSearch();
        initRetiradaSearch();
        initializeUnseenMessagesCounter();

        if (!captureInitialized) {
            captureId();
            captureInitialized = true;
        }

        if (!guiaTransporteInitialized) {
            initGuiaTransporteModalEvents();
            guiaTransporteInitialized = true;
        }

        if (!deleteInitialized) {
            initDeleteHandler();
            deleteInitialized = true;
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

    function initSidebar() {

        $('#sidebar').addClass('open');

        var activeDropdown = localStorage.getItem('activeDropdown');
        if (activeDropdown) {
            $('#' + activeDropdown).addClass('show');
        }

        var activeSidebarLink = localStorage.getItem('activeSidebarLink');
        if (activeSidebarLink) {
            $('a[data-ajax="true"]').removeClass('active');
            $('a[href="' + activeSidebarLink + '"]').addClass('active');
        }
    }

    function toggleDropdown($dropdown) {
        var isOpen = $dropdown.hasClass('show');

        if (isOpen) {
            $dropdown.removeClass('show');
            localStorage.removeItem('activeDropdown');
        } else {
            $dropdown.addClass('show');
            localStorage.setItem('activeDropdown', $dropdown.attr('id'));
        }
    }

    initSidebar();

    function initDynamicContent() {
        $(document).off('click', 'a[data-ajax="true"]').on('click', 'a[data-ajax="true"]', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            localStorage.setItem('activeSidebarLink', url);

            $('#main').load(url + ' #main > *', function(response, status, xhr) {
                if (status === "error") {
                    console.log("Erro ao carregar o conteúdo: " + xhr.status + " " + xhr.statusText);
                } else {
                    initContentHandlers();
                    initSidebar();
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
                initSidebar();
            }
        });
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');

        $('#main').load(url + ' #main > *', function(response, status, xhr) {
            if (status === "error") {
                console.log("Erro ao carregar o conteúdo: " + xhr.status + " " + xhr.statusText);
            } else {
                initContentHandlers();
                initSidebar();
            }
        });

        window.history.pushState({path: url}, '', url);
    });

    $(document).on('click', '.dropdown-toggle', function(e) {
        e.preventDefault();
        var $dropdown = $(this).next('.dropdown-menu');
        toggleDropdown($dropdown);
    });
});

function adicionarLinhaNaTabela(formClass, response) {

    if (!response.data) {
        console.error('O campo "data" está ausente na resposta:', response);
        return;
    }

    var newRow = '';

    if (formClass.includes('formTabelaArmazem')) {

        newRow = `
             <tr data-bs-toggle="modal" data-bs-target="#editArmazemModal${response.data.id}" class="armazemRow" data-id="${response.data.id}">
                <td class="align-middle text-center">${response.data.nome}</td>
                <td class="align-middle text-center">${response.data.capacidade}</td>
                <td class="align-middle text-center">${response.data.tipo_palete.tipo}</td>
                <td class="align-middle text-center">${response.data.user.name}</td>
                <td class="align-middle">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteArmazemModal${response.data.id}">
                        <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                            Eliminar
                        </button>
                    </a>
                </td>
            </tr>
        `;

        var existingRow = $('#armazemTable tbody').find(`tr[data-id="${response.data.id}"]`);

        if (existingRow.length) {
            existingRow.replaceWith(newRow);
        } else {
            $('#armazemTable tbody').append(newRow);
        }

    } else if (formClass.includes('formTabelaArtigo')) {

        newRow = `
             <tr data-bs-toggle="modal" data-bs-target="#editArtigoModal${response.data.id}" class="artigoRow" data-id="${response.data.id}">
                <td class="align-middle text-center">${response.data.nome}</td>
                <td class="align-middle text-center">${response.data.referencia}</td>
                <td class="align-middle text-center">${response.data.cliente.nome}</td>
                <td class="align-middle text-center">${response.data.user.name}</td>
                <td class="align-middle">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteArtigoModal${response.data.id}">
                        <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                            Eliminar
                        </button>
                    </a>
                </td>
            </tr>
        `;

        var existingRow = $('#artigoTable tbody').find(`tr[data-id="${response.data.id}"]`);

        if (existingRow.length) {
            existingRow.replaceWith(newRow);
        } else {
            $('#artigoTable tbody').append(newRow);
        }

    } else if (formClass.includes('formTabelaCliente')) {

        newRow = `
            <tr data-bs-toggle="modal" data-bs-target="#editClienteModal${response.data.id}" class="clienteRow" data-id="${response.data.id}">
                <td class="align-middle text-center">${response.data.nome}</td>
                <td class="align-middle text-center">${response.data.morada}</td>
                <td class="align-middle text-center">${response.data.codigo_postal}</td>
                <td class="align-middle text-center">${response.data.nif}</td>
                <td class="align-middle text-center">${response.data.user.name}</td>
                <td class="align-middle">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteClienteModal${response.data.id}">
                        <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                            Eliminar
                        </button>
                    </a>
                </td>
            </tr>
        `;

        var existingRow = $('#clienteTable tbody').find(`tr[data-id="${response.data.id}"]`);

        if (existingRow.length) {
            existingRow.replaceWith(newRow);
        } else {
            $('#clienteTable tbody').append(newRow);
        }
    } else if (formClass.includes('formTabelaTaxa')) {

        newRow = `
            <tr data-bs-toggle="modal" data-bs-target="#editTaxaModal${response.data.id}" class="taxaRow" data-id="${response.data.id}">
                <td class="align-middle text-center">${response.data.nome}</td>
                <td class="align-middle text-center">${response.data.valor}</td>
                <td class="align-middle text-center">${response.data.user.name}</td>
                <td class="align-middle">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTaxaModal${response.data.id}">
                        <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                            Eliminar
                        </button>
                    </a>
                </td>
            </tr>
        `;

        var existingRow = $('#taxaTable tbody').find(`tr[data-id="${response.data.id}"]`);

        if (existingRow.length) {
            existingRow.replaceWith(newRow);
        } else {
            $('#taxaTable tbody').append(newRow);
        }
    } else if (formClass.includes('formTabelaTipoPalete')) {

        newRow = `
            <tr data-bs-toggle="modal" data-bs-target="#editTipoPaleteModal${response.data.id}" class="TipoPaleteRow" data-id="${response.data.id}">
                <td class="align-middle text-center">${response.data.tipo}</td>
                <td class="align-middle text-center">${response.data.valor}</td>
                <td class="align-middle text-center">${response.data.user.name}</td>
                <td class="align-middle">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTipoPaleteModal${response.data.id}">
                        <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                            Eliminar
                        </button>
                    </a>
                </td>
            </tr>
        `;

        var existingRow = $('#tipoPaleteTable tbody').find(`tr[data-id="${response.data.id}"]`);

        if (existingRow.length) {
            existingRow.replaceWith(newRow);
        } else {
            $('#tipoPaleteTable tbody').append(newRow);
        }

    } else if (formClass.includes('formTabelaUser')) {

        newRow = `
            <tr data-bs-toggle="modal" data-bs-target="#editUserModal${response.data.id}" class="userRow" data-id="${response.data.id}">
                <td class="align-middle text-center">${response.data.name}</td>
                <td class="align-middle text-center">${response.data.email}</td>
                <td class="align-middle text-center">${response.data.contacto}</td>
                <td class="align-middle text-center">${response.data.salario}</td>
                <td class="align-middle">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal${response.data.id}">
                        <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                            Eliminar
                        </button>
                    </a>
                </td>
            </tr>
        `;

        var existingRow = $('#userTable tbody').find(`tr[data-id="${response.data.id}"]`);

        if (existingRow.length) {
            existingRow.replaceWith(newRow);
        } else {
            $('#userTable tbody').append(newRow);
        }
    }
}

function initFormHandling() {
    $(document).on('submit', '.ajax-form', function(event) {
        event.preventDefault();

        var $form = $(this);
        var formData = new FormData($form[0]);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        $('.error-messages').html('').addClass('d-none');

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {

                    $form.closest('.modal').modal('hide');

                    adicionarLinhaNaTabela($form.attr('class'), response);

                    $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                    $('.mensagem-dinamica').show();

                    initDynamicAlert();
                }
            },
            error: function(xhr) {

                var errors = xhr.responseJSON.errors;
                var errorHtml = '<ul>';
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errors[key].forEach(function(error) {
                            errorHtml += '<li>' + error + '</li>';
                        });
                    }
                }
                errorHtml += '</ul>';
                $('.error-messages').html(errorHtml).removeClass('d-none');
            }
        });
    });
}

function initTipoDocumentoChangeHandling() {
    $(document).on('change', '#tipo_documento', function() {
        var tipoDocumentoId = $(this).val();
        var clienteId = $('#cliente').val();

        $('#linhaDocumentoForm')[0].reset();
        $('#moradaOculta').hide();
        $('#faturacaoOculta').hide();
        $('#datasOcultas').hide();

        if (tipoDocumentoId == 1) {
            $('#taxaOculta').show();
            $('#previsaoOculta').show();
        }

        if (tipoDocumentoId == 3) {
            $('#moradaOculta').show();
            $('#taxaOculta').show();
            $('#previsaoOculta').show();
        }

        if (tipoDocumentoId == 5) {
            $('#taxaOculta').hide();
            $('#previsaoOculta').hide();
            $('#datasOcultas').show();

            $('#total').val('');

            if (clienteId) {

                var dataInicio = $('#data_inicio').val();
                var dataFim = $('#data_fim').val();

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
            atualizarTabelaDocumentos(response.documento);
            window.location.href = '/documento/' + response.documento_id + '/pdf';
        },
        error: function(xhr) {

            $('#modalAddDocumento').modal('show');
            var errors = xhr.responseJSON.errors;
            var errorHtml = '<ul>';
            for (var key in errors) {
                if (errors.hasOwnProperty(key)) {
                    errors[key].forEach(function(error) {
                        errorHtml += '<li>' + error + '</li>';
                    });
                }
            }
            errorHtml += '</ul>';
            $('.error-messages').html(errorHtml).removeClass('d-none');
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

function initVoltarAoDocumentoModal() {
    $('#voltarAoDocumentoModal').off('click').on('click', function() {
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

function initVoltarAoPedidoRetiradaModal() {
    $('#voltarAoPedidoRetiradaModal').off('click').on('click', function() {
        $('#modalGuiaTransporte').modal('hide');

        const documentoId = $(this).data('documento-id');
        const PedidoRetiradaModal = $('#retiradaModal' + documentoId);

        PedidoRetiradaModal.modal('show');

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
                loadNotifications();
                atualizarTabelaDocumentos(response.documento);
                $('#modalLinhaDocumento').modal('hide');
                window.location.href = '/documento/' + response.documento_id + '/pdf';

                $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                $('.mensagem-dinamica').show();

                initDynamicAlert();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;

                    $('.error-messages').html('').addClass('d-none');

                    var hasDocumentoErrors = Object.keys(errors.documento).length > 0;
                    var hasLinhasErrors = Object.keys(errors.linhas).length > 0;

                    if (hasDocumentoErrors) {
                        $('#modalLinhaDocumento').modal('hide');
                        $('#modalAddDocumento').modal('show');

                        var errorHtml = '<ul>';
                        for (var key in errors.documento) {
                            errors.documento[key].forEach(function(error) {
                                errorHtml += '<li>' + error + '</li>';
                            });
                        }
                        errorHtml += '</ul>';
                        $('.error-messages').html(errorHtml).removeClass('d-none');
                    }

                    if (hasLinhasErrors) {
                        $('#modalAddDocumento').modal('hide');
                        $('#modalLinhaDocumento').modal('show');

                        var errorHtml = '<ul>';
                        for (var key in errors.linhas) {
                            errors.linhas[key].forEach(function(error) {
                                errorHtml += '<li>' + error + '</li>';
                            });
                        }
                        errorHtml += '</ul>';
                        $('.error-messages').html(errorHtml).removeClass('d-none');
                    }
                }
            }
        });
    });
}

function atualizarTabelaDocumentos(documento) {
    var tbody = $('tbody');

    var tipoDocumento = documento.tipo_documento ? documento.tipo_documento.nome : 'N/A';
    var clienteNome = documento.cliente ? documento.cliente.nome : 'N/A';
    var userName = documento.user ? documento.user.name : 'N/A';

    var linhaHtml = `
        <tr class="clickable-row" data-id="${documento.id}">
            <td class="align-middle text-center">${documento.numero}</td>
            <td class="align-middle text-center">${documento.data}</td>
            <td class="align-middle text-center">${tipoDocumento}</td>
            <td class="align-middle text-center">${clienteNome}</td>
            <td class="align-middle text-center">${userName}</td>
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
}

let armazemOptions = [];

function initArmazemOptions() {
    const armazemOptionsElement = document.getElementById('armazem-options');
    if (armazemOptionsElement) {
        armazemOptions = JSON.parse(armazemOptionsElement.textContent) || [];
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
                    removeRow(`tr[data-id="${documentoIdAntigo}"]`);

                    generateRececaoPDF(response.documento_id, response.paletes_criadas);

                    $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                    $('.mensagem-dinamica').show();

                    initDynamicAlert();
                } else {
                    alert('Erro ao criar documento.');
                }
            },
            error: function(xhr) {

                var errors = xhr.responseJSON.errors;
                var errorHtml = '<ul>';
                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errors[key].forEach(function(error) {
                            errorHtml += '<li>' + error + '</li>';
                        });
                    }
                }
                errorHtml += '</ul>';
                $('.error-messages').html(errorHtml).removeClass('d-none');
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
    var alert = $('.mensagem-dinamica');

    if (alert.length) {
        setTimeout(function() {
            alert.fadeOut();
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

                if (paletesDados.length === 0) {

                    guiaTransporteModal.hide();

                    retiradaModal.show();

                    var errorHtml = '<ul><li>Por favor, selecione pelo menos uma palete.</li></ul>';
                    $('.error-messages-paletes').html(errorHtml).removeClass('d-none');

                    return;
                }

                let formData = new FormData(guiaForm);
                formData.append('paletes_dados', JSON.stringify(paletesDados));

                fetch(guiaForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => {
                                // Exibir erros se existirem
                                var errors = err.errors;
                                var errorHtml = '<ul>';
                                for (var key in errors) {
                                    if (errors.hasOwnProperty(key)) {
                                        errors[key].forEach(function(error) {
                                            errorHtml += '<li>' + error + '</li>';
                                        });
                                    }
                                }
                                errorHtml += '</ul>';
                                $('.error-messages').html(errorHtml).removeClass('d-none');
                                throw new Error('Erro ao enviar o formulário');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {

                        const documentoId = data.documento.id;

                        return fetch('/paletes/retirar', {
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
                        });
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro ao atualizar as paletes');
                        }
                        return response.json();
                    })
                    .then(data => {
                        $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + data.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('.mensagem-dinamica').show();

                        initDynamicAlert();

                        const newdocumentoId = data.documento_id;

                        $.ajax({
                            url: '/documento/' + newdocumentoId + '/pdf',
                            method: 'GET',
                            xhrFields: {
                                responseType: 'blob'
                            },
                            success: function(blob) {
                                var link = document.createElement('a');
                                var url = window.URL.createObjectURL(blob);
                                link.href = url;
                                link.download = 'guia_transporte_' + newdocumentoId + '.pdf';
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

                        removeRow(`tr[data-documento-id="${documentoId}"]`);

                        $('#modalGuiaTransporte').modal('hide');
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                    });
            };
        }
    });
}

function initDeleteHandler() {

    $(document).on('click', '.ajax-delete-btn', function(event) {
        var formId = $(this).data('form-id');
        var $form = $('#' + formId);

        event.preventDefault();

        var formData = new FormData($form[0]);

        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'DELETE'
            },
            success: function(response) {
                if (response.success) {
                    $form.closest('.modal').modal('hide');

                    var rowSelector = 'tr[data-id="' + id + '"]';
                    removeRow(rowSelector);
                } else {
                    console.log('Erro:', response.message || 'Erro desconhecido');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    });
}

function removeRow(selector) {
    const row = document.querySelector(selector);
    if (row) {
        row.remove();
    }
}
let id = null;
function captureId() {
    $(document).on('show.bs.modal', '.modal', function (event) {
        var button = $(event.relatedTarget);
        var $row = button.closest('tr');

        if ($row.length) {
            id = $row.data('id');

            $(this).find('input[name="id"]').val(id);
        }
    });
}

function loadNotifications() {
    function fetchNotifications() {
        // URL da rota que busca as notificações
        const url = '/notificacoes';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const notificationItems = document.getElementById('notificationItems');
                const notificationCount = document.getElementById('notificationCount');
                const notificationBadge = document.querySelector('.badge-number');

                // Atualiza a lista de notificações
                notificationItems.innerHTML = ''; // Limpa as notificações antigas
                notificationCount.textContent = data.length; // Atualiza o contador
                notificationBadge.textContent = data.length; // Atualiza o badge

                // Adiciona as notificações no dropdown
                data.forEach(function(notification) {
                    const notificationItem = document.createElement('li');
                    notificationItem.innerHTML = `
                        <a class="dropdown-item text-center" href="#" data-notificacao-id="${notification.id}">
                            ${notification.message}
                        </a>
                    `;
                    notificationItems.appendChild(notificationItem);
                });
            })
            .catch(error => {
                console.error('Erro ao carregar notificações:', error);
            });
    }

    // Chama a função imediatamente ao carregar a página
    fetchNotifications();

    // Atualiza as notificações a cada 5 segundos
    setInterval(fetchNotifications, 5000);
}

function initializeClientSearch() {
    $('#clienteSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/clientes/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function(response) {
                updateClienteTable(response);
            },
            error: function(xhr, status, error) {
                console.log('Erro ao realizar a pesquisa:', error);
            }
        });
    });
}

function updateClienteTable(clientes) {
    var tbody = $('#clienteTable tbody');
    tbody.empty();

    if (clientes.length > 0) {
        clientes.forEach(function(cliente) {
            var clienteRow = `
                <tr data-bs-toggle="modal" data-bs-target="#editClienteModal${cliente.id}" class="clienteRow" data-id="${cliente.id}">
                    <td class="align-middle text-center">${cliente.nome}</td>
                    <td class="align-middle text-center">${cliente.morada}</td>
                    <td class="align-middle text-center">${cliente.codigo_postal}</td>
                    <td class="align-middle text-center">${cliente.nif}</td>
                    <td class="align-middle text-center">${cliente.user.name}</td>
                    <td class="align-middle">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteClienteModal${cliente.id}">
                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                Eliminar
                            </button>
                        </a>
                    </td>
                </tr>
            `;
            tbody.append(clienteRow);
        });
    } else {
        tbody.append('<tr><td colspan="6" class="text-center">Nenhum cliente encontrado.</td></tr>');
    }
}

function initTipoPaleteSearch() {

    $('#tipoPaleteSearch').on('input', function () {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/tipoPalete/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function (response) {
                updateTipoPaleteTable(response);
            },
            error: function (xhr, status, error) {
                console.log('Erro na pesquisa de tipos de paletes: ', error);
            }
        });
    });
}

function updateTipoPaleteTable(tipoPaletes) {
    var tbody = $('#tipoPaleteTable tbody');
    tbody.empty();

    if (tipoPaletes.length > 0) {
        tipoPaletes.forEach(function(tipoPalete) {

            var userName = tipoPalete.user ? tipoPalete.user.name : 'Desconhecido';

            var tipoPaleteRow = `
                <tr data-bs-toggle="modal" data-bs-target="#editTipoPaleteModal${tipoPalete.id}" class="tipoPaleteRow" data-id="${tipoPalete.id}">
                    <td class="align-middle text-center">${tipoPalete.tipo}</td>
                    <td class="align-middle text-center">${tipoPalete.valor}</td>
                    <td class="align-middle text-center">${userName}</td>
                    <td class="align-middle">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTipoPaleteModal${tipoPalete.id}">
                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                Eliminar
                            </button>
                        </a>
                    </td>
                </tr>
            `;
            tbody.append(tipoPaleteRow);
        });
    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Nenhum tipo de palete encontrado.</td></tr>');
    }
}

function initArmazemSearch() {
    $('#armazemSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/armazens/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function (response) {
                updateArmazemTable(response);
            },
            error: function (xhr, status, error) {
                console.log('Erro na pesquisa de armazéns: ', error);
            }
        });
    });
}

function updateArmazemTable(armazens) {
    var tbody = $('#armazemTable tbody');
    tbody.empty();

    if (armazens.length > 0) {
        armazens.forEach(function(armazem) {
            var armazemRow = `
                    <tr data-bs-toggle="modal" data-bs-target="#editArmazemModal${armazem.id}" class="armazemRow" data-id="${armazem.id}">
                        <td class="align-middle text-center">${armazem.nome}</td>
                        <td class="align-middle text-center">${armazem.capacidade}</td>
                        <td class="align-middle text-center">${armazem.tipo_palete ? armazem.tipo_palete.tipo : 'Desconhecido'}</td>
                        <td class="align-middle text-center">${armazem.user ? armazem.user.name : 'Desconhecido'}</td>
                        <td class="align-middle">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteArmazemModal${armazem.id}">
                                <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                    Eliminar
                                </button>
                            </a>
                        </td>
                    </tr>
                `;
            tbody.append(armazemRow);
        });
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum armazém encontrado.</td></tr>');
    }
}

function initArtigoSearch() {
    var currentRequestId = 0;

    $('#artigoSearch').on('input', function() {
        var searchQuery = $(this).val();

        currentRequestId++;
        var requestId = currentRequestId;

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/Artigo/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            cache: false,
            success: function(response) {
                if (requestId === currentRequestId) {
                    updateArtigoTable(response);
                }
            },
            error: function(xhr, status, error) {
                if (status !== 'abort') {
                    console.log('Erro na pesquisa de artigos: ', error);
                } else {
                    console.log('Requisição abortada com sucesso.');
                }
            }
        });
    });
}

function updateArtigoTable(artigos) {
    var tbody = $('#artigoTable tbody');
    tbody.empty();

    if (artigos.length > 0) {
        artigos.forEach(function(artigo) {
            var artigoRow = `
                    <tr data-bs-toggle="modal" data-bs-target="#editArtigoModal${artigo.id}" class="artigoRow" data-id="${artigo.id}">
                        <td class="align-middle text-center">${artigo.nome}</td>
                        <td class="align-middle text-center">${artigo.referencia}</td>
                        <td class="align-middle text-center">${artigo.cliente ? artigo.cliente.nome : 'Desconhecido'}</td>
                        <td class="align-middle text-center">${artigo.user ? artigo.user.name : 'Desconhecido'}</td>
                        <td class="align-middle">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteArtigoModal${artigo.id}">
                                <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                    Eliminar
                                </button>
                            </a>
                        </td>
                    </tr>
                `;
            tbody.append(artigoRow);
        });
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum artigo encontrado.</td></tr>');
    }
}

function initTaxaSearch() {
    $('#taxaSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/taxas/search',  // A rota para a busca
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function (response) {
                updateTaxaTable(response);
            },
            error: function (xhr, status, error) {
                console.log('Erro na pesquisa de taxas: ', error);
            }
        });
    });
}

function updateTaxaTable(taxas) {
    var tbody = $('#taxaTable tbody');
    tbody.empty();

    if (taxas.length > 0) {
        taxas.forEach(function(taxa) {
            var taxaRow = `
                    <tr data-bs-toggle="modal" data-bs-target="#editTaxaModal${taxa.id}" class="taxaRow" data-id="${taxa.id}">
                        <td class="align-middle text-center">${taxa.nome}</td>
                        <td class="align-middle text-center">${taxa.valor}</td>
                        <td class="align-middle text-center">${taxa.user ? taxa.user.name : 'Desconhecido'}</td>
                        <td class="align-middle">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteTaxaModal${taxa.id}">
                                <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                    Eliminar
                                </button>
                            </a>
                        </td>
                    </tr>
                `;
            tbody.append(taxaRow);
        });
    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Nenhuma taxa encontrada.</td></tr>');
    }
}

function initDocumentoSearch() {
    var currentRequestId = 0;

    $('#documentoSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        currentRequestId++;
        var requestId = currentRequestId;

        $.ajax({
            url: '/documentos/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            cache: false,
            success: function(response) {
                if (requestId === currentRequestId) {
                    updateDocumentoTable(response);
                }
            },
            error: function(xhr, status, error) {
                if (status !== 'abort') {
                    console.log('Erro na pesquisa de documentos: ', error);
                } else {
                    console.log('Requisição abortada com sucesso.');
                }
            }
        });
    });
}

function updateDocumentoTable(documentos) {
    var tbody = $('#documentoTableBody');
    tbody.empty();

    if (documentos.length > 0) {
        documentos.forEach(function(documento) {
            var documentoRow = `
                    <tr class="clickable-row documentoRow" data-id="${documento.id}">
                        <td class="align-middle text-center">${documento.numero}</td>
                        <td class="align-middle text-center">${documento.data}</td>
                        <td class="align-middle text-center">${documento.tipo_documento ? documento.tipo_documento.nome : 'Desconhecido'}</td>
                        <td class="align-middle text-center">${documento.cliente ? documento.cliente.nome : 'Desconhecido'}</td>
                        <td class="align-middle text-center">${documento.user ? documento.user.name : 'Desconhecido'}</td>
                        <td class="align-middle text-center">${documento.estado}</td>
                        <td class="text-center">
                            <a href="/documento/pdf/${documento.id}" class="btn btn-secondary btn-sm no-click-propagation">
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
            tbody.append(documentoRow);
        });
    } else {
        tbody.append('<tr><td colspan="7" class="text-center">Nenhum documento encontrado.</td></tr>');
    }
}

function initUserSearch() {
    $('#userSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/users/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function(response) {
                updateUserTable(response);
            },
            error: function(xhr, status, error) {
                console.log('Erro na pesquisa de usuários: ', error);
            }
        });
    });
}

function updateUserTable(users) {
    var tbody = $('#userTable tbody');
    tbody.empty();

    if (users.length > 0) {
        users.forEach(function(user) {
            var userRow = `
                    <tr data-bs-toggle="modal" data-bs-target="#editUserModal${user.id}" class="userRow" data-id="${user.id}">
                        <td class="align-middle text-center">${user.name}</td>
                        <td class="align-middle text-center">${user.email}</td>
                        <td class="align-middle text-center">${user.contacto}</td>
                        <td class="align-middle text-center">${user.salario}</td>
                        <td class="align-middle">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteUserModal${user.id}">
                                <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                    Eliminar
                                </button>
                            </a>
                        </td>
                    </tr>
                `;
            tbody.append(userRow);
        });
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum usuário encontrado.</td></tr>');
    }
}

function initEntregaSearch() {
    $('#entregaSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/entrega/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function(response) {
                updateEntregaTable(response);
            },
            error: function(xhr, status, error) {
                console.log('Erro na pesquisa de entregas: ', error);
            }
        });
    });
}

function updateEntregaTable(documentos) {
    var tbody = $('#entregaTable tbody');
    tbody.empty();

    if (documentos.length > 0) {
        documentos.forEach(function(documento) {
            var totalQuantidade = 0;
            documento.tipo_palete.forEach(function(tipoPalete) {
                totalQuantidade += tipoPalete.pivot.quantidade;
            });

            var entregaRow = `
                    <tr data-bs-toggle="modal" data-bs-target="#rececaoModal${documento.id}" class="entregaRow" data-id="${documento.id}">
                        <td class="align-middle text-center">${documento.cliente ? documento.cliente.nome : 'Desconhecido'}</td>
                        <td class="align-middle text-center">${documento.numero}</td>
                        <td class="align-middle text-center">${documento.previsao}</td>
                        <td class="align-middle text-center">${totalQuantidade} Paletes</td>
                    </tr>
                `;
            tbody.append(entregaRow);
        });
    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Nenhum Pedido de Entrega encontrado.</td></tr>');
    }
}

function initRetiradaSearch() {
    $('#retiradaSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/retirada/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function(response) {
                updateRetiradaTable(response);
            },
            error: function(xhr, status, error) {
                console.log('Erro na pesquisa de retiradas: ', error);
            }
        });
    });
}

function updateRetiradaTable(documentos) {
    var tbody = $('#retiradaTable tbody');
    tbody.empty();

    if (documentos.length > 0) {
        documentos.forEach(function(documento) {

            var paleteQuantidade = 0;
            if (Array.isArray(documento.tipo_palete)) {
                paleteQuantidade = documento.tipo_palete.reduce(function(acc, tipoPalete) {
                    return acc + tipoPalete.pivot.quantidade;
                }, 0);
            }

            var documentRow = `
                <tr data-bs-toggle="modal" data-bs-target="#retiradaModal${documento.id}" class="retiradaRow" data-documento-id="${documento.id}">
                    <td class="align-middle text-center">${documento.cliente ? documento.cliente.nome : 'Desconhecido'}</td>
                    <td class="align-middle text-center">${documento.numero}</td>
                    <td class="align-middle text-center">${documento.previsao}</td>
                    <td class="align-middle text-center">${paleteQuantidade} Paletes</td>
                </tr>
            `;
            tbody.append(documentRow);
        });
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum pedido encontrado.</td></tr>');
    }
}

function initializeUnseenMessagesCounter() {
    function checkUnseenMessages() {
        const url = '/unseen-messages';

        fetch(url)
            .then(response => response.json())
            .then(data => {

                const unseenCounter = data.unseenCounter;

                const counterElement = document.getElementById('unseen-counter');

                if (unseenCounter > 0) {
                    counterElement.innerHTML = `<b>${unseenCounter}</b>`;
                } else {
                    counterElement.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Erro ao buscar mensagens não lidas:', error);
            });
    }

    setInterval(checkUnseenMessages, 5000);

    window.onload = checkUnseenMessages;
}

