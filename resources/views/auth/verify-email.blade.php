@extends('layouts.guest')

@section('slot')
    <div class="mb-4 text-muted">
        {{ __('Obrigado por se registar! Antes de começar, confirme o seu endereço de e-mail clicando no link presente no e-mail que acabamos de te enviar. Caso não tenha recebido o email, teremos o maior prazer em reenviar-lhe outro.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4">
        <!-- Reenviar e-mail de verificação -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none text-muted">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
@endsection 
