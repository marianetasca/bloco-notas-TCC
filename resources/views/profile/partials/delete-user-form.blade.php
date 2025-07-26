<section class="container">
    <div class="">
        <h2 class="h5 text-dark">
            {{ __('Delete Account') }}
        </h2>

        <p class="text-muted small">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>

    <!-- Botão para abrir o modal -->
    <button class="btn btn-danger d-flex ms-auto" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        {{ __('Delete Account') }}
    </button>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" aria-labelledby="confirmUserDeletionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('profile.destroy') }}" class="modal-content">
                @csrf
                @method('delete')

                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUserDeletionLabel">
                        {{ __('Are you sure you want to delete your account?') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('Close') }}"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>

                    <div class="mb-3">
                        <label for="password" class="form-label visually-hidden">{{ __('Password') }}</label>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="{{ __('Password') }}">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('Cancelar') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        {{ __('Deletar Conta') }}
                    </button>

                </div>
            </form>
        </div>
    </div>
</section>
