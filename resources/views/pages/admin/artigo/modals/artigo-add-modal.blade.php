<div class="modal fade" id="addArtigoModal" tabindex="-1" aria-labelledby="addArtigoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addArtigoModalLabel">{{ __('artigo.add_artigo') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('artigo.description') }}
                </div>

                <form class="ajax-form" method="POST" action="{{ route('artigo.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addArtigoModalNome" class="form-label">{{ __('artigo.add_nome') }}</label>
                        <input id="addArtigoModalNome" class="form-control" type="text" name="nome">
                    </div>

                    <div class="mb-3">
                        <label for="addArtigoModalReferencia" class="form-label">{{ __('artigo.add_referencia') }}</label>
                        <input id="addArtigoModalReferencia" class="form-control" type="text" name="referencia">
                    </div>

                    <div class="mb-3">
                        <label for="addArtigoModalCliente" class="form-label">{{ __('artigo.add_cliente') }}</label>
                        <select name="cliente_id" id="addArtigoModalCliente" class="form-select form-select-sm">
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('artigo.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('artigo.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
