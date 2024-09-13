<div class="modal fade" id="modalLinhaDocumento" tabindex="-1" aria-labelledby="modalLinhaDocumentoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLinhaDocumentoLabel">{{__('documento.novo_documento')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="linhaDocumentoForm">

                    <div id="paleteFields">
                        <div class="palete-row mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="tipo_palete_id" class="form-label">{{__('documento.tipo_palete')}}</label>
                                    <select name="tipo_palete_id[]" class="form-select" required>
                                        @foreach($tipoPaletes as $tipoPalete)
                                            <option value="{{ $tipoPalete->id }}">{{ $tipoPalete->tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="quantidade" class="form-label">{{__('documento.quantidade')}}</label>
                                    <input type="number" step="1" min="0" class="form-control" name="quantidade[]" required>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <a type="button" class="remove-palete-row">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="button" id="addPaleteRow" class="btn btn-success">{{__('documento.adicionar_tipo_palete')}}</button>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label">{{__('documento.descricao')}}</label>
                        <input type="text" class="form-control" id="descricao" name="descricao">
                    </div>

                    <div class="mb-3">
                        <label for="valor" class="form-label">{{__('documento.taxa')}}</label>
                        <input type="number" step="0.01" min="0" class="form-control" id="valor" name="valor" required>
                    </div>

                    <div class="mb-3" id="novaMoradaField">
                        <label for="morada" class="form-label">{{__('documento.nova_morada')}}</label>
                        <input type="text" class="form-control" id="morada" name="morada">
                    </div>

                    <div class="mb-3" id="dataEntregaField">
                        <label for="data_entrega" class="form-label">{{__('documento.data_entrega')}}</label>
                        <input type="datetime-local" class="form-control" id="data_entrega" name="data_entrega" required>
                    </div>

                    <div class="mb-3" id="dataRecolhaField">
                        <label for="data_recolha" class="form-label">{{__('documento.data_recolha')}}</label>
                        <input type="datetime-local" class="form-control" id="data_recolha" name="data_recolha">
                    </div>

                    <div class="mb-3" id="extraField">
                        <label for="extra" class="form-label">{{__('documento.extra')}}</label>
                        <input type="text" class="form-control" id="extra" name="extra" required>
                    </div>

                    <div class="mb-3" id="artigoField">
                        <label for="artigo_id" class="form-label">{{__('documento.artigo')}}</label>
                        <input type="text" class="form-control" id="artigo_id" name="artigo_id" required>
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

<select id="tipoPaleteSelect" class="form-select d-none"></select>
