@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Minhas Notas</h1>
    <a href="{{ route('notas.create') }}" class="btn btn-primary mb-3">Criar Nova Nota</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notas as $nota)
            <tr>
                <td>{{ $nota->id }}</td>
                <td>{{ $nota->titulo }}</td>
                <td>
                    <a href="{{ route('notas.edit', $nota->id) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
