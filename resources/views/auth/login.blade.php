@extends('layouts.guest')

@section('slot')
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="exemplo@gmail.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Senha') }}</label>
            <div class="position-relative">

                <input id="password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password"
                    placeholder="********">

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle" type="button"
                    style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>

            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label text-muted">
                {{ __('Lembrar de mim') }}
            </label>
        </div>

        <!-- Links e Botão -->
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    {{ __('Esqueceu sua senha?') }}
                </a>
            @endif

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('register') }}" class="text-decoration-none small">
                    {{ __('Registrar-se') }}
                </a>

                <button type="submit" class="btn btn-primary-ed">
                    {{ __('Entrar') }}
                </button>
            </div>
        </div>
    </form>
@endsection
