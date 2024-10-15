<div class="modal fade" id="addArtigoModal" tabindex="-1" aria-labelledby="addArtigoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addArtigoModalLabel">{{ __('artigo.add_artigo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('artigo.description') }}<br>
                    {{ __('artigo.caracter') }}
                </div>

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form class="ajax-form formTabelaArtigo" method="POST" action="{{ route('artigo.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addArtigoModalNome" class="form-label">{{ __('artigo.add_nome') }}</label>
                        <input id="addArtigoModalNome" class="form-control" type="text" name="nome" placeholder="{{ __('Letras de a-z') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addArtigoModalReferencia" class="form-label">{{ __('artigo.add_referencia') }}</label>
                        <input id="addArtigoModalReferencia" class="form-control" type="text" name="referencia" placeholder="{{ __('Letras de A-Z com letra maiuscula') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addArtigoModalCliente" class="form-label">{{ __('artigo.add_cliente') }}</label>
                        <select name="cliente_id" id="addArtigoModalCliente" class="form-select form-select">
                            <option value="">{{ __('artigo.select_cliente') }}</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('artigo.cancel') }}</button>
                        <button type="submit" class="btn btn-primary submit-btn">
                            <span class="submit-btn-text">{{ __('artigo.add') }}</span>
                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
