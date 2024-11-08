@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Nota</h1>

    <form action="{{ route('notas.update', $nota->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $nota->titulo }}" required>
        </div>
        <div class="form-group">
            <label for="conteudo">Conteúdo</label>
            <textarea class="form-control" id="conteudo" name="conteudo" rows="5" required>{{ $nota->conteudo }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Atualizar</button>
    </form>
</div>
@endsection
