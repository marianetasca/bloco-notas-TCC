@extends('layouts.app')

@section('slot')
    <div class="container py-5">
        <div class="container mt-4">
            <div class="card shadow rounded-4 h-100">
                <div class="card-header textColor d-flex justify-content-between align-items-center">
                    <h2 class="h4 textColor">Tags</h2>
                    <a href="{{ route('tags.create') }}" class="btn btn-primary-ed btn-sm">
                        Nova Tag
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row row-cols-1 row-cols-md-3 mx-1 mt-2">
                    @forelse ($tags as $tag)
                        <div class="col mb-2">
                            <div class="card h-100 border hover-shadow">
                                <div class="card-body d-flex flex-column justify-content-between bg-light rounded-2">
                                    <div>
                                        <h5 class="card-title">{{ $tag->nome }}</h5>
                                        <p class="card-text text-muted">{{ $tag->notas->count()}} notas</p>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2 mt-3">
                                        <a href="{{ route('tags.edit', $tag->id) }}"
                                            class="btn btn-primary-ed btn-sm" title="Editar">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta tag?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Excluir"><i
                                                    class="bi bi-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted text-center my-4">Nenhuma tag encontrada.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
