<div class="container">
    @if (session('status') === 'password-updated')
        <div class="alert alert-success">
            Senha atualizada com sucesso.
        </div>
    @endif

    <p class="text-muted mb-4">
        Certifique-se de utilizar uma senha longa e segura para manter sua conta protegida.
    </p>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="current_password" class="form-label">Senha Atual</label>
            <input type="password" name="current_password" id="current_password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                autocomplete="current-password" required>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nova Senha</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password" required>
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password" required>
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary-ed">
                Salvar Senha
            </button>
        </div>
    </form>
</div>

