@foreach($clientes as $cliente)
    <div class="modal fade" id="editClienteModal{{ $cliente->id }}" tabindex="-1" aria-labelledby="editClienteModalLabel{{ $cliente->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClienteModalLabel{{ $cliente->id }}">{{ __('tipo-palete.edit_tipo_palete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('tipo-palete.description') }}
                    </div>
                    <form class="ajax-form" id="editClienteForm{{ $cliente->id }}" method="POST" action="{{ route('cliente.update', ['cliente' => $cliente->id]) }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editClienteModalNome{{ $cliente->id }}" class="form-label">{{ __('tipo-palete.add_tipo') }}</label>
                            <input id="editClienteModalNome{{ $cliente->id }}" class="form-control" type="text" name="nome" value="{{ $cliente->nome }}">
                        </div>

                        <div class="mb-3">
                            <label for="editClienteModalMorada{{ $cliente->id }}" class="form-label">{{ __('tipo-palete.add_tipo') }}</label>
                            <input id="editClienteModalMorada{{ $cliente->id }}" class="form-control" type="text" name="morada" value="{{ $cliente->morada }}">
                        </div>

                        <div class="mb-3">
                            <label for="editClienteModalCodigoPostal{{ $cliente->id }}" class="form-label">{{ __('tipo-palete.add_tipo') }}</label>
                            <input id="editClienteModalCodigoPostal{{ $cliente->id }}" class="form-control" type="text" name="codigo_postal" value="{{ $cliente->codigo_postal }}">
                        </div>

                        <div class="mb-3">
                            <label for="editClienteModalNif{{ $cliente->id }}" class="form-label">{{ __('tipo-palete.add_valor') }}</label>
                            <input id="editClienteModalNif{{ $cliente->id }}" class="form-control" type="number" name="nif" value="{{ $cliente->nif }}">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('tipo-palete.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('tipo-palete.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
