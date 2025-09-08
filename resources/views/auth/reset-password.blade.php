@extends('layouts.guest')

@section('slot')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token de redefinição -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                autocomplete="username" class="form-control @error('email') is-invalid @enderror">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Nova senha -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>

            <div class="position-relative">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="form-control password-input @error('password') is-invalid @enderror">

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle" type="button"
                    style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirmar senha -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <div class="position-relative">

                <input id="password_confirmation" type="password" name="password_confirmation" required
                    autocomplete="new-password"
                    class="form-control password-input @error('password_confirmation') is-invalid @enderror">

                <button class="btn position-absolute top-50 end-0 translate-middle-y me-2 password-toggle" type="button"
                    style="border: none; background: none; z-index: 10;">
                    <i class="bi bi-eye text-muted toggle-icon"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botão -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
@endsection
