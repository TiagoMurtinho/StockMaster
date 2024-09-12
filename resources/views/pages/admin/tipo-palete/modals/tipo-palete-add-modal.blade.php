<div class="modal fade" id="addTipoPaleteModal" tabindex="-1" aria-labelledby="addTipoPaleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTipoPaleteModalLabel">{{ __('tipo-palete.add_tipo_palete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('tipo-palete.description') }}
                </div>

                <form class="ajax-form" method="POST" action="{{ route('tipo-palete.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addTipoPaleteModalTipo" class="form-label">{{ __('tipo-palete.tipo') }}</label>
                        <input id="addTipoPaleteModalTipo" class="form-control" type="text" name="tipo">
                    </div>

                    <div class="mb-3">
                        <label for="addTipoPaleteModalValor" class="form-label">{{ __('tipo-palete.valor') }}</label>
                        <input id="addTipoPaleteModalValor" class="form-control" type="number" min="0" max="1000" step="0.01" name="valor">
                    </div>

                    <div class="mb-3">
                        <label for="addTipoPaleteModalUser" class="form-label">{{ __('tipo-palete.user') }}</label>
                        <select name="user_id" id="addTipoPaleteModalUser" class="form-select form-select-sm">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('tipo-palete.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('tipo-palete.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
