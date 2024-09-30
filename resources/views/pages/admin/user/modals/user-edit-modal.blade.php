<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">{{ __('user.edit_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('user.description') }}
                </div>

                <form class="ajax-form formTabelaUser" method="POST" action="{{ route('register.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="editUserModalNome" class="form-label">{{ __('user.add_nome') }}</label>
                        <input id="editUserModalNome" class="form-control" type="text" name="nome" value="{{ $user->nome }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="editUserModalEmail" class="form-label">{{ __('user.add_email') }}</label>
                        <input id="editUserModalEmail" class="form-control" type="email" name="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserModalPassword" class="form-label">{{ __('user.add_password') }}</label>
                            <input id="editUserModalPassword" class="form-control" type="password" name="password">
                            <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="editUserModalPasswordConfirmation" class="form-label">{{ __('user.confirm_password') }}</label>
                            <input id="editUserModalPasswordConfirmation" class="form-control" type="password" name="password_confirmation">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserModalContacto" class="form-label">{{ __('user.add_contacto') }}</label>
                            <input id="editUserModalContacto" class="form-control" type="text" name="contacto" value="{{ $user->contacto }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="editUserModalSalario" class="form-label">{{ __('user.add_salario') }}</label>
                            <input id="editUserModalSalario" class="form-control" type="number" min="0" step="0.01" name="salario" value="{{ $user->salario }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('user.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('user.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
