@extends('layouts.guest')

@section('slot')
    <h4 class="mb-4 text-center">{{ __('Registrar-se') }}</h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Nome') }}</label>
            <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" required autocomplete="username" placeholder="exemplo@gmail.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Senha') }}</label>
            <div class="position-relative">

                <input id="password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password"
                    placeholder="********">

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle" type="button"
                    style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>

            </div>
            @if ($errors->has('password'))
                <div class="invalid-feedback d-block">
                    @foreach ($errors->get('password') as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirmar Senha') }}</label>

                <div class="position-relative">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror" required
                        autocomplete="new-password" placeholder="*******">

                    <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle"
                        type="button" style="border: none; background: none; z-index: 10;">
                        <i class="bi bi-eye text-muted toggle-icon"></i>
                    </button>

                </div>
                @error('password_confirmation')
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

            <!-- Links e Botão -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    {{ __('Já registrado?') }}
                </a>
                <button type="submit" class="btn btn-primary-ed">
                    {{ __('Registrar') }}
                </button>
            </div>
    </form>
@endsection
