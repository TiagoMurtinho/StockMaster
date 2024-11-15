<div class="modal fade" id="addClienteModal" tabindex="-1" aria-labelledby="addClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClienteModalLabel">{{ __('cliente.add_cliente') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('cliente.description') }}<br>
                    {{ __('cliente.caracter') }}
                </div>

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form class="ajax-form formTabelaCliente" method="POST" action="{{ route('cliente.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addClienteModalNome" class="form-label">{{ __('cliente.add_nome') }}</label>
                        <input id="addClienteModalNome" class="form-control" type="text" name="nome" placeholder="{{ __('Letras de a-z') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addClienteModalMorada" class="form-label">{{ __('cliente.add_morada') }}</label>
                        <input id="addClienteModalMorada" class="form-control" type="text" name="morada"  placeholder="{{ __('Letras de a-z, numeros de 0-9 e vírgulas') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addClienteModalCodigoPostal" class="form-label">{{ __('cliente.add_codigo_postal') }}</label>
                        <input id="addClienteModalCodigoPostal" class="form-control" type="text" name="codigo_postal"  placeholder="{{ __('Codigo Postal no formato XXXX-XXX') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addTipoPaleteModalNif" class="form-label">{{ __('cliente.add_nif') }}</label>
                        <input id="addTipoPaleteModalNif" class="form-control" type="number" name="nif" placeholder="{{ __('Campo numérico') }}">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('cliente.cancel') }}</button>
                        <button type="submit" class="btn btn-primary submit-btn">
                            <span class="submit-btn-text">{{ __('cliente.add') }}</span>
                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
