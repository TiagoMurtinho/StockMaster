<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">{{ __('user.delete_user') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger">{{ __('user.confirm_delete') }} {{ $user->nome }}</p>
                <form id="deleteUserForm{{ $user->id }}" method="POST" action="{{ route('user.destroy', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="id" id="userId{{ $user->id }}">
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">{{ __('user.cancel') }}</button>
                        <button type="submit" class="btn btn-danger ajax-delete-btn" data-form-id="deleteUserForm{{ $user->id }}">{{ __('user.delete') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
