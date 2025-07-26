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
@endsection
