@extends('layouts.app')

@section('slot')
    <div class="container mt-4">
        <h2 class="mb-4 textColor">Nova Nota</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('notas.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="conteudo" class="form-label">Conteúdo</label>
                <textarea name="conteudo" id="conteudo" rows="4" class="form-control" required></textarea>
            </div>

            <div class="row g-3"> {{-- para ocupar a mesma linha --}}
                <div class="col-md-4"> {{-- Categoria --}}
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Data da Nota --}}
                <div class="col-md-4">
                    <label for="data_vencimento" class="form-label">Data de vencimento</label>
                    <input type="date" name="data_vencimento" id="data_vencimento" class="form-control"
                        value="{{ old('data_vencimento') }}">
                </div>

                {{-- Prioridade --}}
                <div class="col-md-4">
                    <label for="prioridade_id" class="form-label">Prioridade</label>
                    <select name="prioridade_id" id="prioridade_id" class="form-select" required>
                        @foreach ($prioridades as $prioridade)
                            <option value="{{ $prioridade->id }}"
                                {{ old('prioridade_id') == $prioridade->id ? 'selected' : '' }}>
                                {{ $prioridade->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tags --}}
            <div class="mt-3">
                <label class="form-label">Tags</label><br>
                @foreach ($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tags[]" id="tag_{{ $tag->id }}"
                            value="{{ $tag->id }}">
                        <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->nome }}</label>
                    </div>
                @endforeach
            </div>


            <div class="mt-4 text-end">
                <a href="{{ route('notas.index') }}"class="btn btn-secondary px-3">Voltar</a>
                <button type="submit" class="btn btn-primary-ed">Criar Nota</button>
            </div>
        </form>
    </div>
@endsection
