@foreach($taxas as $taxa)
    <div class="modal fade" id="editTaxaModal{{ $taxa->id }}" tabindex="-1" aria-labelledby="editTaxaModalLabel{{ $taxa->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTaxaModalLabel{{ $taxa->id }}">{{ __('taxa.edit_taxa') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('taxa.description') }}
                    </div>

                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <form class="ajax-form formTabelaTaxa" id="editTaxaForm{{ $taxa->id }}" method="POST" action="{{ route('taxa.update', ['taxon' => $taxa->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editTaxaModalNome{{ $taxa->id }}" class="form-label">{{ __('taxa.add_nome') }}</label>
                            <input id="editTaxaModalTipo{{ $taxa->id }}" class="form-control" type="text" name="nome" value="{{ $taxa->nome }}">
                        </div>
                        <div class="mb-3">
                            <label for="editTaxaModalValor{{ $taxa->id }}" class="form-label">{{ __('taxa.add_valor') }}</label>
                            <input id="editTaxaModalValor{{ $taxa->id }}" class="form-control" type="number" min="0" step="0.01" name="valor" value="{{ $taxa->valor }}">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('taxa.cancel') }}</button>
                            <button type="submit" class="btn btn-primary submit-btn">
                                <span class="submit-btn-text"> {{ __('taxa.save') }}</span>
                                <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
