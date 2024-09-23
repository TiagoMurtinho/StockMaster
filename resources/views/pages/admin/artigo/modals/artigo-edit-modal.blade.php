@foreach($artigos as $artigo)
    <div class="modal fade" id="editArtigoModal{{ $artigo->id }}" tabindex="-1" aria-labelledby="editArtigoModalLabel{{ $artigo->id }}" aria-hidden="true">
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

                    <form class="ajax-form" method="POST" action="{{ route('artigo.update', ['artigo' => $artigo->id]) }}">

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
                            <label for="editArtigoModalCliente" class="form-label">{{ __('artigo.add_cliente') }}</label>
                            <select name="cliente_id" id="editArtigoModalCliente" class="form-select form-select-sm">
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ $cliente->id == $artigo->cliente_id ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('artigo.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('artigo.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
