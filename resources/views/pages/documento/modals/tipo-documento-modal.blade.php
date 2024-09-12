<div class="modal fade" id="tipoDocumentoModal" tabindex="-1" aria-labelledby="tipoDocumentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tipoDocumentoModalLabel">Novo Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('documento.store') }}" method="POST">
                @csrf
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo de Documento</label>
                        <select class="form-select" id="tipo_documento" name="tipo_documento_id" required>
                            <option selected disabled>Selecione um tipo de documento</option>
                            @foreach($tiposDocumento as $tipo)
                                <option value="{{ $tipo->id }}" data-modal="{{ $tipo->nome }}">{{ $tipo->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="cliente" name="cliente_id" required>
                            <option selected disabled>Selecione um cliente</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalDocumento">Continuar</button>
                </div>
            </form>
        </div>
    </div>
</div>
