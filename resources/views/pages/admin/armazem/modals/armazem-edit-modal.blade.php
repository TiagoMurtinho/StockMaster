@foreach($armazens as $armazem)
    <div class="modal fade" id="editArmazemModal{{ $armazem->id }}" tabindex="-1" aria-labelledby="editArmazemModalLabel{{ $armazem->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editArmazemModalLabel{{ $armazem->id }}">{{ __('armazem.edit_armazem') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('armazem.description') }}
                    </div>

                    <form class="ajax-form formTabelaArmazem" method="POST" action="{{ route('armazem.update', ['armazem' => $armazem->id]) }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editArmazemModalNome{{ $armazem->id }}" class="form-label">{{ __('armazem.add_nome') }}</label>
                            <input id="editArmazemModalNome{{ $armazem->id }}" class="form-control" type="text" name="nome" value="{{ $armazem->nome }}">
                        </div>

                        <div class="mb-3">
                            <label for="editArmazemModalCapacidade{{ $armazem->id }}" class="form-label">{{ __('armazem.add_capacidade') }}</label>
                            <input id="editArmazemModalCapacidade{{ $armazem->id }}" class="form-control" type="number" name="capacidade" value="{{ $armazem->capacidade }}">
                        </div>

                        <div class="mb-3">
                            <label for="editArmazemModalTipoPalete" class="form-label">{{ __('armazem.add_tipo_palete') }}</label>
                            <select name="tipo_palete_id" id="editArmazemModalTipoPalete" class="form-select form-select-sm">
                                @foreach($tipoPaletes as $tipoPalete)
                                    <option value="{{ $tipoPalete->id }}"
                                        {{ $tipoPalete->id == $armazem->tipo_palete_id ? 'selected' : '' }}>
                                        {{ $tipoPalete->tipo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('armazem.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('armazem.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
