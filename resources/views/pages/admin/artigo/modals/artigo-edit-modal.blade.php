@foreach($artigos as $artigo)
    <div class="modal fade" id="editArtigoModal{{ $artigo->id }}" tabindex="-1" aria-labelledby="editArtigoModalLabel{{ $artigo->id }}" aria-hidden="true" data-cliente-id="{{ $artigo->cliente_id }}">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editArtigoModalLabel{{ $artigo->id }}">{{ __('artigo.edit_artigo') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('artigo.description') }}
                    </div>

                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <form class="ajax-form formTabelaArtigo" method="POST" action="{{ route('artigo.update', ['artigo' => $artigo->id]) }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editArtigoModalNome{{ $artigo->id }}" class="form-label">{{ __('artigo.add_nome') }}</label>
                            <input id="editArtigoModalNome{{ $artigo->id }}" class="form-control" type="text" name="nome" value="{{ $artigo->nome }}">
                        </div>

                        <div class="mb-3">
                            <label for="editArtigoModalReferencia{{ $artigo->id }}" class="form-label">{{ __('artigo.add_referencia') }}</label>
                            <input id="editArtigoModalReferencia{{ $artigo->id }}" class="form-control" type="text" name="referencia" value="{{ $artigo->referencia }}">
                        </div>

                        <div class="mb-3">
                            <label for="editArtigoModalCliente{{ $artigo->id }}" class="form-label">Cliente</label>
                            <select name="cliente_id" id="editArtigoModalCliente{{ $artigo->id }}" class="form-select form-select"></select>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('artigo.cancel') }}</button>
                            <button type="submit" class="btn btn-primary submit-btn">
                                <span class="submit-btn-text">{{ __('artigo.save') }}</span>
                                <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
