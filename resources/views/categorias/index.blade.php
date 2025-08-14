@extends('layouts.app')

@section('slot')
    <div class="container mt-4">
        <div class="card shadow rounded-4 h-100">
            <div class="card-header textColor d-flex justify-content-between align-items-center">
                <h2 class="h4 textColor">Categorias</h2>
                <a href="{{ route('categorias.create') }}" class="btn btn-primary-ed btn-sm">
                    Nova Categoria
                </a>
            </div>

            <div class="row row-cols-1 row-cols-md-3 mx-1 mt-2">
                @foreach ($categorias as $categoria)
                    <div class="col mb-2">
                        <div class="card h-100 border hover-shadow">
                            <div class="card-body d-flex flex-column justify-content-between bg-light rounded-2">
                                <div>
                                    <h5 class="card-title">{{ $categoria->nome }}</h5>
                                    <p class="card-text text-muted">{{ $categoria->notas->count() }} notas</p>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    @if (!$categoria->is_default)
                                        <a href="{{ route('categorias.edit', $categoria->id) }}"
                                            class="btn btn-primary-ed btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Excluir"><i
                                                    class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Categoria padrão</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
