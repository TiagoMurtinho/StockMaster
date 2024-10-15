<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">{{ __('user.edit_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('user.description') }}<br>
                    {{ __('user.caracter') }}
                </div>

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form class="ajax-form formTabelaUser" method="POST" action="{{ route('register.update', $user->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="editUserModalNome{{ $user->id }}" class="form-label">{{ __('user.add_nome') }}</label>
                        <input id="editUserModalNome{{ $user->id }}" class="form-control" type="text" name="name" value="{{ $user->name }}" placeholder="{{ __('Letras de a-z') }}">
                    </div>

                    <div class="mb-3">
                        <label for="editUserModalEmail{{ $user->id }}" class="form-label">{{ __('user.add_email') }}</label>
                        <input id="editUserModalEmail{{ $user->id }}" class="form-control" type="email" name="email" value="{{ $user->email }}" placeholder="{{ __('Email num formato válido') }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserModalPassword{{ $user->id }}" class="form-label">{{ __('user.add_password') }}</label>
                            <input id="editUserModalPassword{{ $user->id }}" class="form-control" type="password" name="password">
                            <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="editUserModalPasswordConfirmation{{ $user->id }}" class="form-label">{{ __('user.confirm_password') }}</label>
                            <input id="editUserModalPasswordConfirmation{{ $user->id }}" class="form-control" type="password" name="password_confirmation">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editUserModalContacto{{ $user->id }}" class="form-label">{{ __('user.add_contacto') }}</label>
                            <input id="editUserModalContacto{{ $user->id }}" class="form-control" type="text" name="contacto" value="{{ $user->contacto }}" placeholder="{{ __('Contacto num formato válido') }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="editUserModalSalario{{ $user->id }}" class="form-label">{{ __('user.add_salario') }}</label>
                            <input id="editUserModalSalario{{ $user->id }}" class="form-control" type="number" min="0" step="0.01" name="salario" value="{{ $user->salario }}" placeholder="{{ __('Campo numérico') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('user.cancel') }}</button>
                        <button type="submit" class="btn btn-primary submit-btn">
                            <span class="submit-btn-text">{{ __('user.update') }}</span>
                            <span class="submit-btn-spinner spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
