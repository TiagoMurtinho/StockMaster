@foreach($tipoPaletes as $tipoPalete)
    <div class="modal fade" id="editTipoPaleteModal{{ $tipoPalete->id }}" tabindex="-1" aria-labelledby="editTipoPaleteModalLabel{{ $tipoPalete->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTipoPaleteModalLabel{{ $tipoPalete->id }}">{{ __('tipo-palete.edit_tipo_palete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('tipo-palete.description') }}<br>
                        {{ __('tipo-palete.caracter') }}
                    </div>

                    <div class="alert alert-danger d-none error-messages" role="alert"></div>

                    <form class="ajax-form formTabelaTipoPalete" id="editTipoPaleteForm{{ $tipoPalete->id }}" method="POST" action="{{ route('tipo-palete.update', ['tipo_palete' => $tipoPalete->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="editTipoPaleteModalTipo{{ $tipoPalete->id }}" class="form-label">{{ __('tipo-palete.add_tipo') }}</label>
                            <input id="editTipoPaleteModalTipo{{ $tipoPalete->id }}" class="form-control" type="text" name="tipo" value="{{ $tipoPalete->tipo }}" placeholder="{{ __('Letras de a-z') }}">
                        </div>
                        <div class="mb-3">
                            <label for="editTipoPaleteModalValor{{ $tipoPalete->id }}" class="form-label">{{ __('tipo-palete.add_valor') }}</label>
                            <input id="editTipoPaleteModalValor{{ $tipoPalete->id }}" class="form-control" type="number" min="0" max="1000" step="0.01" name="valor" value="{{ $tipoPalete->valor }}" placeholder="{{ __('Campo numérico') }}">
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('tipo-palete.cancel') }}</button>
                            <button type="submit" class="btn btn-primary submit-btn">
                                <span class="submit-btn-text">{{ __('tipo-palete.save') }}</span>
                                <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
