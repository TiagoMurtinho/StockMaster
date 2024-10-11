@foreach($clientes as $cliente)
    <div class="modal fade" id="editClienteModal{{ $cliente->id }}" tabindex="-1" aria-labelledby="editClienteModalLabel{{ $cliente->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClienteModalLabel{{ $cliente->id }}">{{ __('cliente.edit_cliente') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('cliente.description') }}
                    </div>

                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <form class="ajax-form formTabelaCliente" id="editClienteForm{{ $cliente->id }}" method="POST" action="{{ route('cliente.update', ['cliente' => $cliente->id]) }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editClienteModalNome{{ $cliente->id }}" class="form-label">{{ __('cliente.add_nome') }}</label>
                            <input id="editClienteModalNome{{ $cliente->id }}" class="form-control" type="text" name="nome" value="{{ $cliente->nome }}">
                        </div>

                        <div class="mb-3">
                            <label for="editClienteModalMorada{{ $cliente->id }}" class="form-label">{{ __('cliente.add_morada') }}</label>
                            <input id="editClienteModalMorada{{ $cliente->id }}" class="form-control" type="text" name="morada" value="{{ $cliente->morada }}">
                        </div>

                        <div class="mb-3">
                            <label for="editClienteModalCodigoPostal{{ $cliente->id }}" class="form-label">{{ __('cliente.add_codigo_postal') }}</label>
                            <input id="editClienteModalCodigoPostal{{ $cliente->id }}" class="form-control" type="text" name="codigo_postal" value="{{ $cliente->codigo_postal }}">
                        </div>

                        <div class="mb-3">
                            <label for="editClienteModalNif{{ $cliente->id }}" class="form-label">{{ __('cliente.add_nif') }}</label>
                            <input id="editClienteModalNif{{ $cliente->id }}" class="form-control" type="number" name="nif" value="{{ $cliente->nif }}">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('cliente.cancel') }}</button>
                            <button type="submit" class="btn btn-primary submit-btn">
                                <span class="submit-btn-text">{{ __('cliente.save') }}</span>
                                <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
