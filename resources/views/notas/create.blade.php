@extends('layouts.app')

@section('slot')
<div class="container">
    <h1>Criar Nova Nota</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
        </div>
        <div class="mb-3">
            <label for="conteudo" class="form-label">Conteúdo</label>
            <textarea class="form-control" id="conteudo" name="conteudo" rows="4">{{ old('conteudo') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoria</label>
            <select class="form-control" id="categoria_id" name="categoria_id" required>
                <option value="">Selecione uma categoria</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="prioridade_id" class="form-label">Prioridade</label>
            <select class="form-control" id="prioridade_id" name="prioridade_id">
                <option value="">Selecione uma prioridade</option>
                @foreach($prioridades as $prioridade)
                    <option value="{{ $prioridade->id }}"
                            {{ old('prioridade_id') == $prioridade->id ? 'selected' : '' }}
                            style="color: {{ $prioridade->cor }}">
                        {{ $prioridade->nome }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="data_entrega" class="form-label">Data de Entrega</label>
            <input type="date" class="form-control" id="data_entrega" name="data_entrega"
                   value="{{ old('data_entrega') }}">
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <select class="form-control" id="tags" name="tags[]" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                        {{ $tag->nome }}
                    </option>
                @endforeach
            </select>
            <small class="form-text text-muted">Pressione Ctrl (Cmd no Mac) para selecionar múltiplas tags</small>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
