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
                        {{ __('armazem.description') }}<br>
                        {{ __('armazem.caracter') }}
                    </div>

                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <form class="ajax-form formTabelaArmazem" method="POST" action="{{ route('armazem.update', ['armazem' => $armazem->id]) }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="editArmazemModalNome{{ $armazem->id }}" class="form-label">{{ __('armazem.add_nome') }}</label>
                            <input id="editArmazemModalNome{{ $armazem->id }}" class="form-control" type="text" name="nome" value="{{ $armazem->nome }}" placeholder="{{ __('Letras de a-z') }}">
                        </div>

                        <div class="mb-3">
                            <label for="editArmazemModalCapacidade{{ $armazem->id }}" class="form-label">{{ __('armazem.add_capacidade') }}</label>
                            <input id="editArmazemModalCapacidade{{ $armazem->id }}" class="form-control" type="number" name="capacidade" value="{{ $armazem->capacidade }}" placeholder="{{ __('Campo numérico') }}">
                        </div>

                        <div class="mb-3">
                            <label for="editArmazemModalTipoPalete" class="form-label">{{ __('armazem.add_tipo_palete') }}</label>
                            <select name="tipo_palete_id" id="editArmazemModalTipoPalete" class="form-select form-select">
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
                            <button type="submit" class="btn btn-primary submit-btn">
                                <span class="submit-btn-text">{{ __('armazem.save') }}</span>
                                <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
