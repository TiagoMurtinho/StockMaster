<div class="modal fade" id="modalAddDocumento" tabindex="-1" aria-labelledby="modalAddDocumentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAddDocumentoModalLabel">{{__('documento.novo_documento')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="documentoForm" action="{{ route('documento.store') }}" method="POST">
                @csrf
                <div class="modal-body modal-documento">

                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">{{__('documento.tipo')}}</label>
                        <select class="form-select" id="tipo_documento" name="tipo_documento_id" required>
                            <option selected disabled>{{__('documento.selecione_tipo')}}</option>
                            @foreach($tiposDocumento as $tipo)
                                <option value="{{ $tipo->id }}" data-modal="{{ $tipo->nome }}">{{ $tipo->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente" name="cliente_id" required>
                            <option selected disabled>{{__('documento.selecione_cliente')}}</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="numero" class="form-label">{{__('documento.add_numero')}}</label>
                        <input type="number" class="form-control" id="numero" name="numero" required>
                    </div>

                    <div id="camposOcultos">

                        <div class="mb-3">
                            <label for="morada" class="form-label">{{__('documento.add_morada')}}</label>
                            <input type="text" class="form-control" id="morada" name="morada">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('documento.cancelar')}}</button>
                    <button type="button" id="continuarModalLinhaDocumentoBtn" class="btn btn-primary">{{__('documento.continuar')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
