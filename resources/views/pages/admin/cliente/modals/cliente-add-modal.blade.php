<div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClienteModalLabel">{{ __('cliente.add_cliente') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('cliente.description') }}
                </div>

                <form class="ajax-form" method="POST" action="{{ route('cliente.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addClienteModalNome" class="form-label">{{ __('cliente.nome') }}</label>
                        <input id="addClienteModalNome" class="form-control" type="text" name="nome">
                    </div>

                    <div class="mb-3">
                        <label for="addClienteModalMorada" class="form-label">{{ __('cliente.morada') }}</label>
                        <input id="addClienteModalMorada" class="form-control" type="text" name="morada">
                    </div>

                    <div class="mb-3">
                        <label for="addClienteModalCodigoPostal" class="form-label">{{ __('cliente.codigo_postal') }}</label>
                        <input id="addClienteModalCodigoPostal" class="form-control" type="text" name="codigo_postal">
                    </div>

                    <div class="mb-3">
                        <label for="addTipoPaleteModalNif" class="form-label">{{ __('cliente.nif') }}</label>
                        <input id="addTipoPaleteModalNif" class="form-control" type="number" name="nif">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('tipo-palete.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('tipo-palete.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
