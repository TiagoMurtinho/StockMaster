<div class="modal fade" id="modalLinhaDocumento" tabindex="-1" aria-labelledby="modalLinhaDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLinhaDocumentoLabel">{{__('documento.novo_documento')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="linhaDocumentoForm">

                    <!-- Campo Observação como textarea -->
                    <div class="mb-3">
                        <label for="observacao" class="form-label">{{__('documento.observacao')}}</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3"></textarea>
                    </div>

                    <!-- Campos Previsão e Taxa lado a lado -->
                    <div class="row">
                        <div class="col-md-6 mb-3" id="previsaoField">
                            <label for="previsao" class="form-label">{{__('documento.previsao')}}</label>
                            <input type="date" class="form-control" id="previsao" name="previsao" required>
                        </div>
                        <div class="col-md-6 mb-3" id="taxaField">
                            <label for="taxa_id" class="form-label">{{__('documento.taxa')}}</label>
                            <select class="form-select" id="taxa_id" name="taxa_id">
                                <option value="">{{ __('Selecione uma taxa') }}</option>
                                @foreach($taxas as $taxa)
                                    <option value="{{ $taxa->id }}">{{ $taxa->nome }} - {{ $taxa->valor }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('documento.cancelar')}}</button>
                <button type="button" id="criarDocumentoBtn" class="btn btn-primary">{{__('documento.continuar')}}</button>
            </div>
        </div>
    </div>
</div>
