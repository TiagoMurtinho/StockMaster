<div class="modal fade" id="deleteArmazemModal{{ $armazem->id }}" tabindex="-1" aria-labelledby="deleteArmazemModalLabel{{ $armazem->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteArmazemModalLabel{{ $armazem->id }}">{{ __('armazem.delete_armazem') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">{{ __('armazem.confirm_delete') }} {{ $armazem->nome }}</p>
                <form id="deleteArmazemForm{{ $armazem->id }}" method="POST" action="{{ route('armazem.destroy', $armazem->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="armazemId{{ $armazem->id }}">
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('armazem.cancel') }}</button>
                        <button type="button" class="btn btn-danger ajax-delete-btn" data-form-id="deleteArmazemForm{{ $armazem->id }}">{{ __('armazem.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
