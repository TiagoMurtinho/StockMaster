<div class="modal fade" id="deleteTaxaModal{{ $taxa->id }}" tabindex="-1" aria-labelledby="deleteTaxaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaxaModalLabel">{{ __('tipo-palete.delete_tipo_palete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">{{ __('tipo-palete.confirm_delete') }} {{ $taxa->nome }}</p>
                <form id="deleteTaxaForm{{ $taxa->id }}" method="POST" action="{{ route('taxa.destroy', $taxa->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="taxaId{{ $taxa->id }}">
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('taxa.cancel') }}</button>
                        <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteTaxaForm{{ $taxa->id }}">
                            <span class="ajax-delete-btn-text">{{ __('taxa.delete') }}</span>
                            <span class="ajax-delete-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
