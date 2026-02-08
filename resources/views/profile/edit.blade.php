@extends('layouts.app')

@section('slot')
    <div class="container py-5">

        {{-- Atualizar Informações do Perfil --}}
        <div class="card shadow rounded-4 mb-4">
            <div class="card-header">
                <h2 class="h5 textColor">Atualizar Informações do Perfil</h2>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Botão de Instalação PWA --}}
        <button id="install-button" style="display: none;" class="btn btn-primary mb-4">
            📱 Instalar App
        </button>

        {{-- Atualizar Senha --}}
        <div class="card shadow rounded-4 mb-4">
            <div class="card-header">
                <h2 class="h5 textColor">Atualizar Senha</h2>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Excluir Conta --}}
        <div class="card shadow rounded-4">
            <div class="card-header">
                <h2 class="h5 textColor">Excluir Conta</h2>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>

    {{-- Script direto aqui, sem @push --}}
    <script>
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('PWA pode ser instalado!');
            e.preventDefault();
            deferredPrompt = e;
            
            const btn = document.getElementById('install-button');
            if (btn) btn.style.display = 'block';
        });

        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('install-button');
            
            if (btn) {
                btn.addEventListener('click', async () => {
                    if (!deferredPrompt) {
                        alert('App já está instalado ou não pode ser instalado.');
                        return;
                    }
                    
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log('Resultado:', outcome);
                    
                    deferredPrompt = null;
                    btn.style.display = 'none';
                });
            }
        });

        window.addEventListener('appinstalled', () => {
            console.log('App instalado!');
            const btn = document.getElementById('install-button');
            if (btn) btn.style.display = 'none';
        });
    </script>
@endsection