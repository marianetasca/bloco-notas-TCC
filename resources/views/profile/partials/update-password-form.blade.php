<div class="container">
    @if (session('status') === 'password-updated')
        <div class="alert alert-success">
            Senha atualizada com sucesso.
        </div>
    @endif

    <!-- exibi todos os erros do bag updatePassword -->
    @if ($errors->updatePassword->any())
        <div class="alert alert-danger">
            <strong>Erro na validação da senha:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

            <div class="position-relative">
                <input type="password" name="current_password" id="current_password"
                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                    autocomplete="current-password" required>

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle"
                    type="button" style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>
            </div>
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nova Senha</label>

            <div class="position-relative">
                <input type="password" name="password" id="password"
                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                    autocomplete="new-password" required>

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle"
                    type="button" style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>
            </div>

        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>

            <div class="position-relative">
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                    autocomplete="new-password" required>

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle"
                    type="button" style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>
            </div>
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <div class="mt-2">
            <small class="text-muted">Requisitos da senha:</small>
            <ul class="" style="font-size: 0.85em;">
                <li id="minlength" class="text-danger">Pelo menos 8 caracteres</li>
                <li id="lowercase" class="text-danger">Pelo menos uma letra minúscula</li>
                <li id="uppercase" class="text-danger">Pelo menos uma letra maiúscula</li>
                <li id="number" class="text-danger">Pelo menos um número</li>
                <li id="symbol" class="text-danger">Pelo menos um símbolo (@$!%*#?&)</li>
            </ul>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary-ed">
                Salvar Senha
            </button>
        </div>
    </form>
</div>
