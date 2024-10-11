<div class="modal fade" id="deleteTipoPaleteModal{{ $tipoPalete->id }}" tabindex="-1" aria-labelledby="deleteTipoPaleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTipoPaleteModalLabel">{{ __('tipo-palete.delete_tipo_palete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">{{ __('tipo-palete.confirm_delete') }} {{ $tipoPalete->tipo }}</p>
                <form id="deleteTipoPaleteForm{{ $tipoPalete->id }}" method="POST" action="{{ route('tipo-palete.destroy', $tipoPalete->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="tipoPaleteId{{ $tipoPalete->id }}">
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('tipo-palete.cancel') }}</button>
                        <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteTipoPaleteForm{{ $tipoPalete->id }}">
                            <span class="ajax-delete-btn-text">{{ __('tipo-palete.delete') }}</span>
                            <span class="ajax-delete-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
