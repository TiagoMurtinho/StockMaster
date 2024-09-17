<div class="modal fade" id="deleteArtigoModal{{ $artigo->id }}" tabindex="-1" aria-labelledby="deleteArtigoModalLabel{{ $artigo->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteArtigoModalLabel{{ $artigo->id }}">{{ __('artigo.delete_artigo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">{{ __('artigo.confirm_delete') }} {{ $artigo->nome }}</p>
                <form class="ajax-form" id="deleteArtigoForm{{ $artigo->id }}" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="artigoId{{ $artigo->id }}">
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('artigo.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('artigo.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
