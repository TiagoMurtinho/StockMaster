<div class="modal fade" id="addTaxaModal" tabindex="-1" aria-labelledby="addTaxaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaxaModalLabel">{{ __('taxa.add-taxa') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('taxa.description') }}
                </div>

                <form class="ajax-form formTabelaTaxa" method="POST" action="{{ route('taxa.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addTaxaModalTipo" class="form-label">{{ __('taxa.add_nome') }}</label>
                        <input id="addTaxaModalTipo" class="form-control" type="text" name="nome">
                    </div>

                    <div class="mb-3">
                        <label for="addTaxaModalValor" class="form-label">{{ __('taxa.add_valor') }}</label>
                        <input id="addTaxaModalValor" class="form-control" type="number" min="0" step="0.01" name="valor">
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('taxa.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('taxa.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
