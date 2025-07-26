@extends('layouts.app')

@section('slot')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h4 text-danger">Lixeira</h2>
                <a href="{{ route('notas.index') }}" class="btn btn-outline-secondary btn-sm">
                    Voltar para notas
                </a>
            </div>

            {{-- aviso --}}
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                As notas na lixeira são automaticamente excluídas após 30 dias.
            </div>

            @if($notasExcluidas->isEmpty())
                <p class="text-center text-muted py-5">Nenhuma nota excluída temporáriamente.</p>
            @else
                <div class="row row-cols-1 g-3">
                    @foreach($notasExcluidas as $nota)
                        <div class="col">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $nota->titulo }}</h5>
                                    <p class="card-subtitle text-muted mb-1">
                                        Excluída em: {{ $nota->deleted_at->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="text-danger small mb-2">
                                        Será excluída permanentemente em: {{ $nota->deleted_at->addDays(1)->format('d/m/Y') }}
                                    </p>
                                    <p class="card-text text-muted">{{ Str::limit($nota->conteudo, 100) }}</p>

                                    <div class="mt-3 d-flex gap-2">
                                        <form action="{{ route('notas.restaurar', $nota->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-undo-alt me-1"></i> Restaurar
                                            </button>
                                        </form>

                                        <form action="{{ route('notas.excluir-permanente', $nota->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza? Esta ação não pode ser desfeita!')">
                                                <i class="fas fa-trash me-1"></i> Excluir Permanentemente
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $notasExcluidas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
