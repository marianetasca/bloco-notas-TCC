@extends('layouts.guest')

@section('slot')
    <div class="mb-4 text-muted">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Senha -->
        <div class="mb-3 position-relative">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="form-control password-input @error('password') is-invalid @enderror">

            <!-- Botão toggle senha -->
            <button type="button"
                class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y password-toggle">
                <i class="bi bi-eye toggle-icon"></i>
            </button>

            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Botão confirmar -->
        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Confirm') }}
            </button>
        </div>
    </form>
@endsection
