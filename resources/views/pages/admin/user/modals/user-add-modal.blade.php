<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">{{ __('user.add_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('user.description') }}
                </div>

                <div class="alert alert-danger d-none error-messages" role="alert"></div>

                <form class="ajax-form formTabelaUser" method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="addUserModalNome" class="form-label">{{ __('user.add_nome') }}</label>
                        <input id="addUserModalNome" class="form-control" type="text" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="addUserModalEmail" class="form-label">{{ __('user.add_email') }}</label>
                        <input id="addUserModalEmail" class="form-control" type="email" name="email" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="addUserModalPassword" class="form-label">{{ __('user.add_password') }}</label>
                            <input id="addUserModalPassword" class="form-control" type="password" name="password" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="addUserModalPasswordConfirmation" class="form-label">{{ __('user.confirm_password') }}</label>
                            <input id="addUserModalPasswordConfirmation" class="form-control" type="password" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="addUserModalContacto" class="form-label">{{ __('user.add_contacto') }}</label>
                            <input id="addUserModalContacto" class="form-control" type="text" name="contacto">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="addUserModalSalario" class="form-label">{{ __('user.add_salario') }}</label>
                            <input id="addUserModalSalario" class="form-control" type="number" min="0" step="0.01" name="salario">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('user.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('user.add') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
