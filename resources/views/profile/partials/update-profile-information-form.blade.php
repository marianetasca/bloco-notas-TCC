<section class="">
    <header class="mb-4">
        <h2 class="h5 text-dark">
            {{ __('Informações do Perfil') }}
        </h2>
        <p class="text-muted small">
            {{ __("Atualize suas informações de perfil e o endereço de e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nome') }}</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2 text-secondary small">
                    {{ __('Seu e-mail ainda não foi verificado.') }}
                    <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline">
                        {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 text-success small">
                            {{ __('Um novo link de verificação foi enviado para seu e-mail.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-end gap-3">
            <button type="submit" class="btn btn-primary-ed">
                {{ __('Salvar') }}
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">
                    {{ __('Alterações salvas.') }}
                </span>
            @endif
        </div>
    </form>
</section>
