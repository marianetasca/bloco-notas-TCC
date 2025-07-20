@extends('layouts.app')

@section('slot')
    <div class="container mt-4">
        <div class="card shadow rounded-4">
            <div class="card-header textColor d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detalhes da Nota</h4>
            </div>

            <div class="card-body">
                <h5 class="card-title">{{ $nota->titulo }}</h5>
                <p class="card-text">{{ $nota->conteudo }}</p>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Categoria:</strong> {{ $nota->categoria->nome ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Data de vencimento:</strong> {{ \Carbon\Carbon::parse($nota->data_vencimento)->format('d/m/Y') }}
                    </div>
                    <div class="col-md-4">
                        <strong>Prioridade:</strong> {{ $nota->prioridade->nome ?? 'N/A' }}
                    </div>
                </div>

                @if ($nota->tags->count())
                    <p><strong>Tags:</strong>
                        @foreach ($nota->tags as $tag)
                            <span class="badge bg-secondary me-1">{{ $tag->nome }}</span>
                        @endforeach
                    </p>
                @endif

                @if ($nota->anexo)
                    <hr>
                    <p><strong>Anexo:</strong></p>
                    @if (Str::startsWith($nota->anexo, ['jpg', 'jpeg', 'png', 'gif']))
                        <img src="{{ asset('storage/' . $nota->anexo) }}" class="img-fluid rounded shadow-sm mb-3" alt="Anexo da nota">
                    @else
                        <a href="{{ asset('storage/' . $nota->anexo) }}" target="_blank" class="btn btn-outline-primary">
                            Visualizar Anexo
                        </a>
                    @endif
                @endif
            </div>

            <div class="card-footer text-end">
                <a href="{{ route('notas.edit', $nota->id) }}" class="btn btn-sm btn-primary-ed">
                    <i class="bi bi-pencil"></i> Editar
                </a>
                <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Tem certeza que deseja excluir esta nota?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </form>
                <a href="{{ route('notas.index') }}" class="btn btn-sm btn-secondary px-3">Voltar</a>
            </div>
        </div>
    </div>
@endsection
