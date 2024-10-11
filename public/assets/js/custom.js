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
        initNoClickPropagation();
        initEditTipoPaleteModals();
        initEditClienteModals();
        initEditArmazemModals();
        initEditArtigoModals();
        initEditTaxaModals();
        initEditUserModals();
        initRececaoModals();
        initRetiradaModals();

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

        var $submitButton = $form.find('.submit-btn');
        var $submitButtonText = $form.find('.submit-btn-text');
        var $submitLoader = $form.find('.submit-btn-spinner');

        $submitLoader.removeClass('d-none');

        $submitButton.prop('disabled', true);
        $submitButtonText.addClass('d-none');


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

                    $form[0].reset();
                }
                $submitButton.prop('disabled', false);
                $submitButtonText.removeClass('d-none');
                $submitLoader.addClass('d-none');
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

                $submitButton.prop('disabled', false);
                $submitButtonText.removeClass('d-none');
                $submitLoader.addClass('d-none');
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

    var $form = $('#documentoForm');
    $('.error-messages').html('').addClass('d-none');
    var $submitButton = $form.find('.submit-btn');
    var $submitButtonText = $form.find('.submit-btn-text');
    var $submitLoader = $form.find('.submit-btn-spinner');

    $submitLoader.removeClass('d-none');

    $submitButton.prop('disabled', true);
    $submitButtonText.addClass('d-none');

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

            $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $('.mensagem-dinamica').show();

            initDynamicAlert();

            $form[0].reset();

            $submitButton.prop('disabled', false);
            $submitButtonText.removeClass('d-none');
            $submitLoader.addClass('d-none');
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

            $submitButton.prop('disabled', false);
            $submitButtonText.removeClass('d-none');
            $submitLoader.addClass('d-none');
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

        var $form = $('#documentoForm');
        var $form2 = $('#linhaDocumentoForm');

        var $submitButton = $('#criarDocumentoBtn');
        var $submitButtonText = $submitButton.find('.submit-btn-text');
        var $submitLoader = $submitButton.find('.submit-btn-spinner');

        $submitLoader.removeClass('d-none');
        $submitButton.prop('disabled', true);
        $submitButtonText.addClass('d-none');

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

                $form[0].reset();
                $form2[0].reset();

                $submitButton.prop('disabled', false);
                $submitButtonText.removeClass('d-none');
                $submitLoader.addClass('d-none');
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
                $submitButton.prop('disabled', false);
                $submitButtonText.removeClass('d-none');
                $submitLoader.addClass('d-none');
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

    $(document).on('submit', 'form[id^="modalRececaoForm"]', function(e) {
        e.preventDefault();

        var $form = $(this);
        var formData = $form.serialize();
        var documentoIdAntigo = $form.find('input[name="documento_id"]').val();
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        formData += '&_token=' + csrfToken;

        var $submitButton = $form.find('.submit-btn');
        var $submitButtonText = $submitButton.find('.submit-btn-text');
        var $submitLoader = $submitButton.find('.submit-btn-spinner');

        $submitLoader.removeClass('d-none');
        $submitButton.prop('disabled', true);
        $submitButtonText.addClass('d-none');

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
            },
            complete: function() {
                $submitButton.prop('disabled', false);
                $submitButtonText.removeClass('d-none');
                $submitLoader.addClass('d-none');
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

                        const tipoDocumentoId = data.documento.tipo_documento_id;

                        document.getElementById('rececaoData').style.display = 'none';
                        document.getElementById('guiaTransporteData').style.display = 'none';
                        document.getElementById('faturacaoData').style.display = 'none';

                        if (tipoDocumentoId === 2) {
                            document.getElementById('rececaoData').style.display = 'block';
                        } else if (tipoDocumentoId === 4) {
                            document.getElementById('guiaTransporteData').style.display = 'block';
                        } else if (tipoDocumentoId === 5) {
                            document.getElementById('faturacaoData').style.display = 'block';
                        }

                        $('#documentoModal').modal('show');
                        console.log(data);
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
}

function initNoClickPropagation() {
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
    const estado = data.documento.estado;

    document.querySelector('.modal-documento-numero').value = data.documento.numero || '';
    document.querySelector('.modal-documento-data').value = data.documento.data || '';
    document.querySelector('.modal-documento-id').value = data.documento.id || '';
    document.querySelector('.modal-documento-observacao').value = data.documento.observacao || '';
    document.querySelector('.modal-documento-previsao').value = data.documento.previsao || '';
    document.querySelector('.modal-documento-valor').value = data.documento.taxa_id || '';
    document.querySelector('.modal-documento-matricula').value = data.documento.matricula || '';
    document.querySelector('.modal-documento-morada').value = data.documento.morada || '';
    document.querySelector('.modal-documento-previsao-descarga').value = data.documento.previsao_descarga || '';
    document.querySelector('.modal-documento-data-entrada').value = data.documento.data_entrada || '';
    document.querySelector('.modal-documento-data-saida').value = data.documento.data_saida || '';
    document.querySelector('.modal-documento-extra').value = data.documento.extra || '';
    document.querySelector('.modal-documento-total').value = data.documento.total || '';
    document.querySelector('.modal-documento-estado').value = data.documento.estado || '';

    const modalContentInputs = document.querySelectorAll('.modal-content input, .modal-content textarea, .modal-content select');

    modalContentInputs.forEach(input => {
        input.removeAttribute('disabled');
    });

    if (estado === 'terminado') {

        modalContentInputs.forEach(input => {
            input.setAttribute('disabled', 'disabled');
        });
    }

    if (data.linhas && data.linhas.length > 0) {
        console.log("Linhas recebidas:", data.linhas);
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
            preencherLinhasModal(data.linhas, window.tiposPalete, window.artigos, estado === 'terminado');
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

function preencherLinhasModal(linhas, tiposPalete, artigos, isTerminado) {
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
                <input type="hidden" name="pivot_id[]" class="modal-linha-id" value="${linha.pivot_id || ''}" />
                <input type="hidden" name="deleted[]" value="0" />
            </td>
        `;

        linhaContainer.appendChild(linhaElement);

        if (isTerminado) {
            const selects = linhaElement.querySelectorAll('select');
            const inputs = linhaElement.querySelectorAll('input');
            const removeButton = linhaElement.querySelector('.remove-palete');

            selects.forEach(select => select.setAttribute('disabled', 'disabled'));
            inputs.forEach(input => input.setAttribute('disabled', 'disabled'));
            if (removeButton) {
                removeButton.setAttribute('disabled', 'disabled');
                removeButton.style.pointerEvents = 'none';
            }
        }
    });
}

function initPaleteRowEvents() {
    document.addEventListener('click', function(event) {
        const isTerminado = document.querySelector('.modal-documento-estado').value === 'terminado';

        if (event.target && event.target.classList.contains('add-palete-row')) {
            if (!isTerminado) {
                adicionarNovaLinha();
            } else {
                console.log('O documento está terminado. Não é possível adicionar novas linhas.');
            }
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
                <a type="button" class="remove-palete-row">
                    <i class="bi bi-trash"></i>
                </a>
            </td>
        </tr>
    `;
    linhaContainer.insertAdjacentHTML('beforeend', novaLinha);

}

function updateTableRow(documento) {

    const $row = $(`.documentoRow[data-id="${documento.id}"]`);

    if ($row.length === 0) {
        console.error('Nenhuma linha encontrada com o ID:', documento.id);
        return;
    }

    const fieldMap = {
        '.numero-cell': documento.numero,
        '.data-cell': documento.data,
        '.observacao-cell': documento.observacao || '',
        '.previsao-cell': documento.previsao || '',
        '.taxa-cell': documento.taxa_id || '',
        '.matricula-cell': documento.matricula || '',
        '.morada-cell': documento.morada || '',
        '.data-entrada-cell': documento.data_entrada || '',
        '.data-saida-cell': documento.data_saida || '',
        '.previsao-descarga-cell': documento.previsao_descarga || '',
        '.total-cell': documento.total || ''
    };

    for (let fieldClass in fieldMap) {
        const value = fieldMap[fieldClass];
        $row.find(fieldClass).text(value);
    }
}

function saveChanges() {
    const documentoId = document.querySelector('.modal-documento-id').value;

    const $submitButton = $('.submit-btn');
    const $submitButtonText = $submitButton.find('.submit-btn-text');
    const $submitLoader = $submitButton.find('.submit-btn-spinner');

    $submitLoader.removeClass('d-none');
    $submitButton.prop('disabled', true);
    $submitButtonText.addClass('d-none');

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
        taxa_id: document.querySelector('.modal-documento-valor').value,
        matricula: document.querySelector('.modal-documento-matricula').value,
        morada: document.querySelector('.modal-documento-morada').value,
        data_entrada: document.querySelector('.modal-documento-data-entrada').value,
        data_saida: document.querySelector('.modal-documento-data-saida').value,
        previsao_descarga: document.querySelector('.modal-documento-previsao-descarga').value,
        extra: document.querySelector('.modal-documento-extra').value,
        total: document.querySelector('.modal-documento-total').value
    };

    const documento_tipo_palete = [];

    document.querySelectorAll('.modal-linhas tr').forEach(row => {
        const inputs = row.querySelectorAll('input, select, textarea');

        const pivotIdField = row.querySelector('.modal-linha-id');
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
                console.log('Resposta recebida:', response);
                $('#documentoModal').modal('hide');
                $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                $('.mensagem-dinamica').show();

                initDynamicAlert();

                updateTableRow(response.documento);
            } else {
                console.error('Erro ao salvar dados:', response.message);
            }

            $submitButton.prop('disabled', false);
            $submitButtonText.removeClass('d-none');
            $submitLoader.addClass('d-none');
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

            $submitButton.prop('disabled', false);
            $submitButtonText.removeClass('d-none');
            $submitLoader.addClass('d-none');
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

                var $submitButton = $('#confirmarEnvio');
                var $submitButtonText = $submitButton.find('.submit-btn-text');
                var $submitLoader = $submitButton.find('.submit-btn-spinner');

                $submitLoader.removeClass('d-none');
                $submitButton.prop('disabled', true);
                $submitButtonText.addClass('d-none');

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

                    $submitButton.prop('disabled', false);
                    $submitButtonText.removeClass('d-none');
                    $submitLoader.addClass('d-none');

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

                        $submitButton.prop('disabled', false);
                        $submitButtonText.removeClass('d-none');
                        $submitLoader.addClass('d-none');
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
                'X-HTTP-Method-Override': 'DELETE',
            },
            success: function(response) {
                if (response.success) {
                    $form.closest('.modal').modal('hide');

                    var rowSelector = 'tr[data-id="' + id + '"]';
                    removeRow(rowSelector);

                    $('.mensagem-dinamica').html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                    $('.mensagem-dinamica').show();

                    initDynamicAlert();
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
        const url = '/notificacoes';

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const notificationItems = document.getElementById('notificationItems');
                const notificationCount = document.getElementById('notificationCount');
                const notificationBadge = document.querySelector('.badge-number');

                notificationItems.innerHTML = '';
                notificationCount.textContent = data.length;

                if (data.length > 0) {
                    notificationBadge.textContent = data.length;
                    notificationBadge.style.display = 'inline-block';
                } else {
                    notificationBadge.style.display = 'none';
                }

                data.forEach(function(notification) {
                    const notificationItem = document.createElement('li');

                    const redirectionUrl = notification.tipo_documento_id === 1
                        ? '/pedido-entrega'
                        : '/pedido-retirada';

                    notificationItem.innerHTML = `
                        <a class="dropdown-item text-center" href="${redirectionUrl}" data-notificacao-id="${notification.id}">
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

    fetchNotifications();

    setInterval(fetchNotifications, 5000);

    let notificationTimeout;

    const notificationIcon = document.querySelector('.nav-link.nav-icon');

    notificationIcon.addEventListener('click', function() {
        const notificationItems = document.getElementById('notificationItems');
        let notificationIds = [];

        notificationItems.querySelectorAll('a.dropdown-item').forEach(function(item) {
            notificationIds.push(item.getAttribute('data-notificacao-id'));
        });

        if (notificationIds.length > 0) {

            notificationTimeout = setTimeout(function() {
                fetch('/notificacoes/marcar-lidas', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ notification_ids: notificationIds })
                })
                    .then(response => {
                        if (response.ok) {
                            const notificationBadge = document.querySelector('.badge-number');
                            notificationBadge.textContent = '';
                            notificationBadge.style.display = 'none';
                            notificationItems.innerHTML = '';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao marcar notificações como lidas:', error);
                    });
            }, 10000);
        }
    });
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

    $('body').find('.modal').remove();

    if (clientes.length > 0) {
        clientes.forEach(function(cliente) {
            var clienteRow = `
                <tr class="clienteRow" data-id="${cliente.id}">
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

            var editModal = `
                <div class="modal fade" id="editClienteModal${cliente.id}" tabindex="-1" aria-labelledby="editClienteModalLabel${cliente.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editClienteModalLabel${cliente.id}">Editar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-4 text-sm text-gray-600">
                                    Descrição do cliente
                                </div>

                                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                                <form class="ajax-form formTabelaCliente" id="editClienteForm${cliente.id}" method="POST" action="/cliente/${cliente.id}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="mb-3">
                                        <label for="editClienteModalNome${cliente.id}" class="form-label">Nome</label>
                                        <input id="editClienteModalNome${cliente.id}" class="form-control" type="text" name="nome" value="${cliente.nome}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="editClienteModalMorada${cliente.id}" class="form-label">Morada</label>
                                        <input id="editClienteModalMorada${cliente.id}" class="form-control" type="text" name="morada" value="${cliente.morada}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="editClienteModalCodigoPostal${cliente.id}" class="form-label">Código Postal</label>
                                        <input id="editClienteModalCodigoPostal${cliente.id}" class="form-control" type="text" name="codigo_postal" value="${cliente.codigo_postal}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="editClienteModalNif${cliente.id}" class="form-label">NIF</label>
                                        <input id="editClienteModalNif${cliente.id}" class="form-control" type="number" name="nif" value="${cliente.nif}">
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary submit-btn">
                                            <span class="submit-btn-text">Salvar</span>
                                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            var deleteModal = `
                <div class="modal fade" id="deleteClienteModal${cliente.id}" tabindex="-1" aria-labelledby="deleteClienteModalLabel${cliente.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteClienteModalLabel${cliente.id}">Eliminar Cliente</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger">Tem certeza de que deseja eliminar o cliente ${cliente.nome}?</p>
                                <form id="deleteClienteForm${cliente.id}" method="POST" action="/cliente/${cliente.id}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="id" id="clienteId${cliente.id}">
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteClienteForm${cliente.id}">Eliminar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            tbody.append(clienteRow);
            $('body').append(editModal);
            $('body').append(deleteModal);
        });
        initNoClickPropagation();
    } else {
        tbody.append('<tr><td colspan="6" class="text-center">Nenhum cliente encontrado.</td></tr>');
    }
}
function initEditClienteModals() {
    $(document).on('click', '.clienteRow', function () {
        var id = $(this).data('id');
        var modal = $('#editClienteModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
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
        tipoPaletes.forEach(function (tipoPalete) {
            var userName = tipoPalete.user ? tipoPalete.user.name : 'Desconhecido';

            var tipoPaleteRow = `
                <tr class="tipoPaleteRow" data-id="${tipoPalete.id}">
                    <td class="align-middle text-center">${tipoPalete.tipo}</td>
                    <td class="align-middle text-center">${tipoPalete.valor}</td>
                    <td class="align-middle text-center">${userName}</td>
                    <td class="align-middle">
                        <a href="#" data-id="${tipoPalete.id}" data-bs-toggle="modal" data-bs-target="#deleteTipoPaleteModal${tipoPalete.id}">
                            <button class="btn btn-danger btn-sm ms-2 no-click-propagation">
                                Eliminar
                            </button>
                        </a>
                    </td>
                </tr>
            `;

            var editModal = `
                <div class="modal fade" id="editTipoPaleteModal${tipoPalete.id}" tabindex="-1" aria-labelledby="editTipoPaleteModalLabel${tipoPalete.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTipoPaleteModalLabel${tipoPalete.id}">Editar Tipo de Palete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-4 text-sm text-gray-600">
                                    Descrição do tipo de palete
                                </div>
                                <div class="alert alert-danger d-none error-messages" role="alert"></div>
                                <form class="ajax-form formTabelaTipoPalete" id="editTipoPaleteForm${tipoPalete.id}" method="POST" action="/tipo-palete/${tipoPalete.id}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="mb-3">
                                        <label for="editTipoPaleteModalTipo${tipoPalete.id}" class="form-label">Tipo</label>
                                        <input id="editTipoPaleteModalTipo${tipoPalete.id}" class="form-control" type="text" name="tipo" value="${tipoPalete.tipo}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="editTipoPaleteModalValor${tipoPalete.id}" class="form-label">Valor</label>
                                        <input id="editTipoPaleteModalValor${tipoPalete.id}" class="form-control" type="number" min="0" max="1000" step="0.01" name="valor" value="${tipoPalete.valor}">
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                         <button type="submit" class="btn btn-primary submit-btn">
                                            <span class="submit-btn-text">Salvar</span>
                                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            var deleteModal = `
                <div class="modal fade" id="deleteTipoPaleteModal${tipoPalete.id}" tabindex="-1" aria-labelledby="deleteTipoPaleteModalLabel${tipoPalete.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteTipoPaleteModalLabel${tipoPalete.id}">Excluir Tipo de Palete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger">Tem certeza que deseja excluir o tipo de palete ${tipoPalete.tipo}?</p>
                                <form id="deleteTipoPaleteForm${tipoPalete.id}" method="POST" action="/tipo-palete/${tipoPalete.id}">
                                    <input type="hidden" name="_method" value="DELETE">
                                     <input type="hidden" name="id" id="tipoPaleteId${tipoPalete.id}" value="${tipoPalete.id}">
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                         <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteTipoPaleteForm${tipoPalete.id}">Excluir</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            if ($('#deleteTipoPaleteModal' + tipoPalete.id).length === 0) {
                $('body').append(deleteModal);
            }

            tbody.append(tipoPaleteRow);
            $('body').append(editModal);
        });
        initNoClickPropagation();
    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Nenhum tipo de palete encontrado.</td></tr>');
    }
}

function initEditTipoPaleteModals() {
    $(document).on('click', '.tipoPaleteRow', function () {
        var id = $(this).data('id');
        var modal = $('#editTipoPaleteModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
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

            var editModal = `
                <div class="modal fade" id="editArmazemModal${armazem.id}" tabindex="-1" aria-labelledby="editArmazemModalLabel${armazem.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editArmazemModalLabel${armazem.id}">Editar Armazém</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-4 text-sm text-gray-600">
                                    Descrição do Armazém
                                </div>
                                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                                <form class="ajax-form formTabelaArmazem" method="POST" action="/armazem/${armazem.id}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="mb-3">
                                        <label for="editArmazemModalNome${armazem.id}" class="form-label">Nome</label>
                                        <input id="editArmazemModalNome${armazem.id}" class="form-control" type="text" name="nome" value="${armazem.nome}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="editArmazemModalCapacidade${armazem.id}" class="form-label">Capacidade</label>
                                        <input id="editArmazemModalCapacidade${armazem.id}" class="form-control" type="number" name="capacidade" value="${armazem.capacidade}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="editArmazemModalTipoPalete" class="form-label">Tipo de Palete</label>
                                        <select name="tipo_palete_id" id="editArmazemModalTipoPalete" class="form-select form-select-sm">
                                            <option value="1" ${armazem.tipo_palete_id === 1 ? 'selected' : ''}>Tipo 1</option>
                                            <option value="2" ${armazem.tipo_palete_id === 2 ? 'selected' : ''}>Tipo 2</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                         <button type="submit" class="btn btn-primary submit-btn">
                                            <span class="submit-btn-text">Salvar</span>
                                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            var deleteModal = `
                <div class="modal fade" id="deleteArmazemModal${armazem.id}" tabindex="-1" aria-labelledby="deleteArmazemModalLabel${armazem.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteArmazemModalLabel${armazem.id}">Excluir Armazém</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger">Tem certeza de que deseja excluir o armazém "${armazem.nome}"?</p>
                                <form id="deleteArmazemForm${armazem.id}" method="POST" action="/armazem/${armazem.id}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="id" value="${armazem.id}" id="armazemId${armazem.id}">
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-danger ajax-delete-btn" data-form-id="deleteArmazemForm${armazem.id}">Excluir</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            tbody.append(armazemRow);
            $('body').append(editModal);
            $('body').append(deleteModal);
        });
        initNoClickPropagation();
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum armazém encontrado.</td></tr>');
    }
}

function initEditArmazemModals() {
    $(document).on('click', '.armazemRow', function () {
        var id = $(this).data('id');
        var modal = $('#editArmazemModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
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

            var editModal = `
                <div class="modal fade" id="editArtigoModal${artigo.id}" tabindex="-1" aria-labelledby="editArtigoModalLabel${artigo.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editArtigoModalLabel${artigo.id}">Editar Artigo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="ajax-form formTabelaArtigo" method="POST" action="/artigo/${artigo.id}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="mb-3">
                                        <label for="editArtigoModalNome${artigo.id}" class="form-label">Nome</label>
                                        <input id="editArtigoModalNome${artigo.id}" class="form-control" type="text" name="nome" value="${artigo.nome}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="editArtigoModalReferencia${artigo.id}" class="form-label">Referência</label>
                                        <input id="editArtigoModalReferencia${artigo.id}" class="form-control" type="text" name="referencia" value="${artigo.referencia}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="editArtigoModalCliente${artigo.id}" class="form-label">Cliente</label>
                                        <select name="cliente_id" id="editArtigoModalCliente${artigo.id}" class="form-select form-select-sm">

                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                         <button type="submit" class="btn btn-primary submit-btn">
                                            <span class="submit-btn-text">Salvar</span>
                                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            var deleteModal = `
                <div class="modal fade" id="deleteArtigoModal${artigo.id}" tabindex="-1" aria-labelledby="deleteArtigoModalLabel${artigo.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteArtigoModalLabel${artigo.id}">Excluir Artigo</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger">Tem certeza que deseja excluir o artigo ${artigo.nome}?</p>
                                <form id="deleteArtigoForm${artigo.id}" method="POST" action="/artigo/delete/${artigo.id}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Excluir</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            tbody.append(artigoRow);
            $('body').append(editModal);
            $('body').append(deleteModal);
        });
        initNoClickPropagation();
        $('#artigoTable tbody').on('click', '.artigoRow', function() {
            var artigoId = $(this).data('id');
            var modalId = '#editArtigoModal' + artigoId;

            $.ajax({
                url: '/clientes',
                method: 'GET',
                success: function(data) {
                    var select = $(modalId).find('select[name="cliente_id"]');
                    select.empty();
                    select.append('<option value="">Selecione o Cliente</option>');
                    data.forEach(function(cliente) {
                        select.append(`<option value="${cliente.id}">${cliente.nome}</option>`);
                    });

                    var artigo = artigos.find(function(a) { return a.id === artigoId; });
                    if (artigo && artigo.cliente_id) {
                        select.val(artigo.cliente_id);
                    }
                },
                error: function() {
                    alert('Erro ao carregar clientes.');
                }
            });
        });

    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum artigo encontrado.</td></tr>');
    }
}

function initEditArtigoModals() {
    $(document).on('click', '.artigoRow', function () {
        var id = $(this).data('id');
        var modal = $('#editArtigoModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
}

function initTaxaSearch() {
    $('#taxaSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        $.ajax({
            url: '/taxas/search',
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

            var editModal = `
                <div class="modal fade" id="editTaxaModal${taxa.id}" tabindex="-1" aria-labelledby="editTaxaModalLabel${taxa.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editTaxaModalLabel${taxa.id}">Editar Taxa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="ajax-form formTabelaTaxa" id="editTaxaForm${taxa.id}" method="POST" action="/taxa/${taxa.id}">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="mb-3">
                                        <label for="editTaxaModalNome${taxa.id}" class="form-label">Nome</label>
                                        <input id="editTaxaModalNome${taxa.id}" class="form-control" type="text" name="nome" value="${taxa.nome}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="editTaxaModalValor${taxa.id}" class="form-label">Valor</label>
                                        <input id="editTaxaModalValor${taxa.id}" class="form-control" type="number" name="valor" min="0" step="0.01" value="${taxa.valor}">
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary submit-btn">
                                            <span class="submit-btn-text">Salvar</span>
                                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            var deleteModal = `
                <div class="modal fade" id="deleteTaxaModal${taxa.id}" tabindex="-1" aria-labelledby="deleteTaxaModalLabel${taxa.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteTaxaModalLabel${taxa.id}">Excluir Taxa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="text-danger">Tem certeza de que deseja excluir a taxa <strong>${taxa.nome}</strong>?</p>
                                <form id="deleteTaxaForm${taxa.id}" method="POST" action="/taxa/${taxa.id}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteTaxaForm${taxa.id}">Excluir</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(editModal);
            $('body').append(deleteModal);
            tbody.append(taxaRow);
        });
        initNoClickPropagation();
    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Nenhuma taxa encontrada.</td></tr>');
    }
}

function initEditTaxaModals() {
    $(document).on('click', '.taxaRow', function () {
        var id = $(this).data('id');
        var modal = $('#editTaxaModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
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
            tbody.append(documentoRow);

            var deleteModal = `
                <div class="modal fade" id="deleteDocumentoModal${documento.id}" tabindex="-1" aria-labelledby="deleteDocumentoModalLabel${documento.id}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteDocumentoModalLabel${documento.id}">Confirmar eliminação</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Tem a certeza que deseja eliminar este documento?
                            </div>
                            <div class="modal-footer">
                                <form id="deleteDocumentoForm${documento.id}" action="/documento/${documento.id}" method="POST">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="id" value="${documento.id}">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteDocumentoForm${documento.id}">Eliminar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(deleteModal);
        });
        initNoClickPropagation();
    } else {
        tbody.append('<tr><td colspan="7" class="text-center">Nenhum documento encontrado.</td></tr>');
    }

    initClickableRows();
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

            var userContacto = user.contacto || "";
            var userSalario = (user.salario != null) ? user.salario : "";

            var userRow = `
                <tr data-bs-toggle="modal" data-bs-target="#editUserModal${user.id}" class="userRow" data-id="${user.id}">
                    <td class="align-middle text-center">${user.name}</td>
                    <td class="align-middle text-center">${user.email}</td>
                    <td class="align-middle text-center">${userContacto}</td>
                    <td class="align-middle text-center">${userSalario}</td>
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

            if (!$('#editUserModal' + user.id).length) {
                var editModal = `
                    <div class="modal fade" id="editUserModal${user.id}" tabindex="-1" aria-labelledby="editUserModalLabel${user.id}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel${user.id}">Editar Usuário</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form class="ajax-form formTabelaUser" method="POST" action="/register/${user.id}">
                                        <input type="hidden" name="_method" value="PUT">
                                        <div class="mb-3">
                                            <label for="editUserModalNome${user.id}" class="form-label">Nome</label>
                                            <input id="editUserModalNome${user.id}" class="form-control" type="text" name="name" value="${user.name}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editUserModalEmail${user.id}" class="form-label">Email</label>
                                            <input id="editUserModalEmail${user.id}" class="form-control" type="email" name="email" value="${user.email}" required>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="editUserModalPassword${user.id}" class="form-label">Senha</label>
                                                <input id="editUserModalPassword${user.id}" class="form-control" type="password" name="password">
                                                <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha.</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="editUserModalPasswordConfirmation${user.id}" class="form-label">Confirmar Senha</label>
                                                <input id="editUserModalPasswordConfirmation${user.id}" class="form-control" type="password" name="password_confirmation">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="editUserModalContacto${user.id}" class="form-label">Contacto</label>
                                                <input id="editUserModalContacto${user.id}" class="form-control" type="text" name="contacto" value="${userContacto}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="editUserModalSalario${user.id}" class="form-label">Salário</label>
                                                <input id="editUserModalSalario${user.id}" class="form-control" type="number" min="0" step="0.01" name="salario" value="${userSalario}">
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary submit-btn">
                                                <span class="submit-btn-text">Salvar</span>
                                                <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('body').append(editModal);
            }

            if (!$('#deleteUserModal' + user.id).length) {
                var deleteModal = `
                    <div class="modal fade" id="deleteUserModal${user.id}" tabindex="-1" aria-labelledby="deleteUserModalLabel${user.id}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel${user.id}">Excluir Usuário</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-danger">Tem certeza que deseja excluir o usuário ${user.name}?</p>
                                    <form id="deleteUserForm${user.id}" method="POST" action="/user/${user.id}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id" value="${user.id}">
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteUserForm${user.id}">Excluir</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('body').append(deleteModal);
            }
        });
        initNoClickPropagation();
    } else {
        tbody.append('<tr><td colspan="5" class="text-center">Nenhum usuário encontrado.</td></tr>');
    }
}

function initEditUserModals() {
    $(document).on('click', '.userRow', function () {
        var id = $(this).data('id');
        var modal = $('#editUserModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
}

function initEntregaSearch() {
    var currentRequestId = 0;
    $('#entregaSearch').on('input', function() {
        var searchQuery = $(this).val();

        if (searchQuery.trim() === "") {
            location.reload();
            return;
        }

        currentRequestId++;
        var requestId = currentRequestId;

        $.ajax({
            url: '/entrega/search',
            method: 'GET',
            data: {
                query: searchQuery
            },
            success: function(response) {
                if (requestId === currentRequestId) {
                    updateEntregaTable(response);
                }
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

    var armazens = JSON.parse($('#armazem-options').html());

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

            var modalContent = `
    <div class="modal fade" id="rececaoModal${documento.id}" tabindex="-1" aria-labelledby="rececaoModalLabel${documento.id}" aria-hidden="true">
        <div class="modal-dialog modal-lg rececao-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rececaoModalLabel${documento.id}">Verificação de Paletes para o Pedido ${documento.numero}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <form id="modalRececaoForm${documento.id}" action="/palete" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="documento_id" value="${documento.id}">
                        <input type="hidden" name="cliente_id" value="${documento.cliente_id}">

                        <div class="mb-3">
                            <label for="observacao${documento.id}" class="form-label">Observação (opcional)</label>
                            <input type="text" name="observacao" id="observacao${documento.id}" class="form-control" placeholder="Escreva aqui as suas observações">
                        </div>

                        <div class="scrollable-palete-area">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Tipo de Palete</th>
                                        <th scope="col">Palete #</th>
                                        <th scope="col">Localização</th>
                                        <th scope="col">Armazém</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${documento.tipo_palete.map(function(tipoPalete) {
                                        return Array.from({ length: tipoPalete.pivot.quantidade }, function(_, i) {
                                        var armazemOptions = armazens.map(function(armazem) {
                                        return `<option value="${armazem.id}">${armazem.nome}</option>`;
                                    }).join('');

                                        return `
                                                <tr>
                                                    <td>${tipoPalete.tipo}</td>
                                                    <td>${i + 1}</td>
                                                    <td>
                                                        <input type="text" name="localizacao[${tipoPalete.id}][]" class="form-control" placeholder="Localização">
                                                    </td>
                                                    <td>
                                                        <select name="armazem_id[${tipoPalete.id}][]" class="form-control armazem-select" data-tipo-palete-id="${tipoPalete.id}">
                                                            ${armazemOptions}
                                                        </select>
                                                    </td>
                                                    <input type="hidden" name="tipo_palete_id[${tipoPalete.id}]" value="${tipoPalete.id}">
                                                </tr>
                                            `;
                                        }).join('');
                                        }).join('')}
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Confirmar Verificação</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
`;

            $('body').append(modalContent);
        });

    } else {
        tbody.append('<tr><td colspan="4" class="text-center">Nenhum Pedido de Entrega encontrado.</td></tr>');
    }
}

function initRececaoModals() {
    $(document).on('click', '.entregaRow', function () {
        var id = $(this).data('id');
        var modal = $('#rececaoModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
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

function updateRetiradaTable(data) {
    var tbody = $('#retiradaTable tbody');
    tbody.empty();

    if (data.documentos.length > 0) {
        data.documentos.forEach(function (documento) {
            var paleteQuantidade = 0;

            if (Array.isArray(documento.tipo_palete)) {
                paleteQuantidade = documento.tipo_palete.reduce(function (acc, tipoPalete) {
                    return acc + (tipoPalete.pivot && tipoPalete.pivot.quantidade ? tipoPalete.pivot.quantidade : 0);
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

            var tipoPaleteRows = '';
            if (Array.isArray(documento.tipo_palete)) {
                tipoPaleteRows = documento.tipo_palete.map(function (tipoPalete) {
                    var paletesRows = '';

                    if (data.paletesPorLinha[documento.id] && data.paletesPorLinha[documento.id][tipoPalete.id]) {
                        paletesRows = data.paletesPorLinha[documento.id][tipoPalete.id].map(function (palete) {
                            var artigoNome = data.artigos[palete.artigo_id] ? data.artigos[palete.artigo_id].nome : 'Desconhecido';
                            var tipoPaleteTipo = data.tipo_paletes[palete.tipo_palete_id] ? data.tipo_paletes[palete.tipo_palete_id].tipo : 'Desconhecido';

                            return `
                                <tr>
                                    <td>
                                        <label class="custom-checkbox">
                                            <input type="checkbox" name="paletes_selecionadas[]" value="${palete.id}"
                                                data-tipo-palete-id="${palete.tipo_palete_id}"
                                                data-artigo-id="${palete.artigo_id}"
                                                data-armazem-id="${palete.armazem_id}"
                                                data-localizacao="${palete.localizacao}">
                                            <span class="checkbox-box"></span>
                                        </label>
                                    </td>
                                    <td>${palete.localizacao}</td>
                                    <td>${artigoNome}</td>
                                    <td>${palete.data_entrada}</td>
                                    <td>${tipoPaleteTipo}</td>
                                </tr>
                            `;
                        }).join('');
                    }

                    return `
                        ${paletesRows}
                    `;
                }).join('');
            }

            var modalRetirada = `
                <div class="modal fade" id="retiradaModal${documento.id}" tabindex="-1" aria-labelledby="retiradaModalLabel${documento.id}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="retiradaModalLabel${documento.id}">Detalhes de Retirada</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h5>Documento ${documento.numero}</h5>
                                <p>Cliente: ${documento.cliente ? documento.cliente.nome : 'Desconhecido'}</p>
                                <p>Previsão de Saída: ${documento.previsao}</p>

                                <div class="alert alert-danger d-none error-messages-paletes" role="alert"></div>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Selecionar:</th>
                                            <th>Localização:</th>
                                            <th>Artigo:</th>
                                            <th>Data de Entrada:</th>
                                            <th>Tipo de Palete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${tipoPaleteRows}
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="continuarGuiaTransporteBtn btn btn-primary"
                                        data-documento-id="${documento.id}"
                                        data-documento-numero="${documento.numero}"
                                        data-documento-cliente-id="${documento.cliente_id}"
                                        data-linha-observacao="${documento.observacao}"
                                        data-linha-previsao="${documento.previsao}"
                                        data-linha-taxa-id="${documento.taxa_id}"
                                        data-documento-morada="${documento.morada}">
                                    Confirmar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalRetirada);
        });
    }
}

function initRetiradaModals() {
    $(document).on('click', '.retiradaRow', function () {
        var id = $(this).data('documento-id');
        var modal = $('#retiradaModal' + id);

        if (modal.length) {
            modal.modal('show');
        } else {
            console.log('Modal não encontrado para o ID:', id);
        }
    });
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

