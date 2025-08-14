@extends('layouts.app')

@section('slot')
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header textColor">
                <h4 class="mb-0">Nova Categoria</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categorias.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da categoria</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                            class="form-control @error('nome') is-invalid @enderror" required>
                        @error('nome')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="text-end">
                        <a href="{{ route('categorias.index') }}" class="btn btn-secondary px-3">Voltar</a>
                        <button type="submit" class="btn btn-primary-ed">Criar Categoria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
