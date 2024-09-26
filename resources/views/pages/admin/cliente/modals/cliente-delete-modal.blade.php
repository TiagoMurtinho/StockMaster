<div class="modal fade" id="deleteClienteModal{{ $cliente->id }}" tabindex="-1" aria-labelledby="deleteClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClienteModalLabel">{{ __('cliente.delete_cliente') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">{{ __('cliente.confirm_delete') }} {{ $cliente->nome }}</p>
                <form class="ajax-form" id="deleteClienteForm{{ $cliente->id }}" method="POST" action="{{ route('cliente.destroy', $cliente->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="clienteId{{ $cliente->id }}">
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('cliente.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('cliente.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
