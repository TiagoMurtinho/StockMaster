<div class="modal fade" id="addArmazemModal" tabindex="-1" aria-labelledby="addArmazemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addArmazemModalLabel">{{ __('armazem.add_armazem') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('armazem.description') }}<br>
                    {{ __('armazem.caracter') }}
                </div>

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form class="ajax-form formTabelaArmazem" method="POST" action="{{ route('armazem.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addArmazemModalNome" class="form-label">{{ __('armazem.add_nome') }}</label>
                        <input id="addArmazemModalNome" class="form-control" type="text" name="nome" placeholder="{{ __('Letras de a-z') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addArmazemModalCapacidade" class="form-label">{{ __('armazem.add_capacidade') }}</label>
                        <input id="addArmazemModalCapacidade" class="form-control" type="number" name="capacidade" placeholder="{{ __('Campo numérico') }}">
                    </div>

                    <div class="mb-3">
                        <label for="addArmazemModalTipoPalete" class="form-label">{{ __('armazem.add_tipo_palete') }}</label>
                        <select name="tipo_palete_id" id="addArmazemModalTipoPalete" class="form-select form-select">
                            <option value="" disabled selected hidden>{{ __('armazem.select') }}</option>
                            @foreach($tipoPaletes as $tipoPalete)
                                <option value="{{ $tipoPalete->id }}">{{ $tipoPalete->tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('armazem.cancel') }}</button>
                        <button type="submit" class="btn btn-primary submit-btn">
                            <span class="submit-btn-text">{{ __('armazem.add') }}</span>
                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
