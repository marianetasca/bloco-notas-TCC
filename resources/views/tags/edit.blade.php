@extends('layouts.app')

@section('slot')
    <div class="container py-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow rounded-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h2 class="h4 textColor">Editar Tag</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('tags.update', $tag->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome', $tag->nome) }}"
                            class="form-control @error('nome') is-invalid @enderror" required>
                        @error('nome')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <a href="{{ route('tags.index') }}" class="btn btn-secondary px-3">Voltar</a>
                        <button type="submit" class="btn btn-primary-ed">
                            Atualizar Tag
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
