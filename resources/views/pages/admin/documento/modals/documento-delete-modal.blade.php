<div class="modal fade" id="deleteDocumentoModal{{ $documento->id }}" tabindex="-1" aria-labelledby="deleteDocumentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDocumentoModalLabel">{{__('documento.confirm_delete')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{__('documento.sure_delete')}}
            </div>
            <div class="modal-footer">
                <form id="deleteDocumentoForm{{ $documento->id }}" action="{{ route('documento.destroy', $documento->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" value="{{ $documento->id }}" id="documentoId{{ $documento->id }}">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('documento.cancelar')}}</button>
                    <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteDocumentoForm{{ $documento->id }}">
                        <span class="ajax-delete-btn-text"> {{__('documento.delete')}}</span>
                        <span class="ajax-delete-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
