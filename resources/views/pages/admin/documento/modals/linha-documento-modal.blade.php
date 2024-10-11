<div class="modal fade" id="modalLinhaDocumento" tabindex="-1" aria-labelledby="modalLinhaDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLinhaDocumentoLabel">{{__('documento.adicionar_paletes')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form id="linhaDocumentoForm">

                    <div class="scrollable-palete-area">
                        <div id="paleteFields">
                            <div class="palete-row mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="tipo_palete_id" class="form-label">{{__('documento.tipo_palete')}}</label>
                                        <select name="tipo_palete_id[]" class="form-select" required>
                                            @foreach($tipoPaletes as $tipoPalete)
                                                <option value="{{ $tipoPalete->id }}">{{ $tipoPalete->tipo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="quantidade" class="form-label">{{__('documento.quantidade')}}</label>
                                        <input type="number" step="1" min="0" class="form-control" name="quantidade[]" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="artigo_id" class="form-label">{{ __('documento.artigo') }}</label>
                                        <select name="artigo_id[]" class="form-select" required>
                                            <option value="">{{ __('Selecione um Artigo') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <a type="button" class="remove-palete-row">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" id="addPaleteRow" class="btn btn-success">{{__('documento.adicionar_tipo_palete')}}</button>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <button type="button" class="btn btn-secondary" id="voltarAoDocumentoModal">{{__('documento.voltar')}}</button>
                </div>
                <div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('documento.cancelar')}}</button>
                    <button type="button" id="criarDocumentoBtn" class="btn btn-primary submit-btn">
                        <span class="submit-btn-text">{{__('documento.continuar')}}</span>
                        <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
