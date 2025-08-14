@extends('layouts.app')

@section('slot')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ $nota->titulo }}</h4>
                    <div>
                        <span class="badge bg-{{ $nota->status_class }}">{{ $nota->status }}</span>
                        @if($nota->prioridade)
                            <span class="badge bg-info">{{ $nota->prioridade->nome }}</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Conteúdo:</strong>
                        <p class="mt-2">{{ $nota->conteudo }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Categoria:</strong>
                            <span class="badge bg-primary">{{ $nota->categoria->nome }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Data de Vencimento:</strong>
                            {{ $nota->data_vencimento ? $nota->data_vencimento->format('d/m/Y') : 'Não definida' }}
                        </div>
                    </div>

                    @if($nota->tags->count() > 0)
                    <div class="mb-3">
                        <strong>Tags:</strong>
                        @foreach($nota->tags as $tag)
                            <span class="badge bg-secondary me-1">{{ $tag->nome }}</span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Seção de Anexos --}}
                    @if($nota->anexos->count() > 0)
                    <div class="mb-3">
                        <strong>Anexos ({{ $nota->anexos->count() }}):</strong>
                        <div class="mt-2">
                            @foreach($nota->anexos as $anexo)
                            <div class="attachment-item d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="{{ $anexo->getIcone() }} fa-2x me-3"></i>
                                    <div>
                                        <div class="fw-bold">{{ $anexo->nome_original }}</div>
                                        <small class="text-muted">{{ $anexo->tamanho_formatado }}</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ $anexo->url }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-eye"></i> Visualizar
                                    </a>
                                    <a href="{{ $anexo->url }}" download="{{ $anexo->nome_original }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="text-muted">
                        <small>
                            Criada em: {{ $nota->created_at->format('d/m/Y H:i') }}
                            @if($nota->updated_at != $nota->created_at)
                                | Atualizada em: {{ $nota->updated_at->format('d/m/Y H:i') }}
                            @endif
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Ações</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('notas.edit', $nota) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>

                        <form method="POST" action="{{ route('notas.concluido', $nota->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn {{ $nota->concluido ? 'btn-secondary' : 'btn-success' }} w-100">
                                <i class="fas {{ $nota->concluido ? 'fa-undo' : 'fa-check' }}"></i>
                                {{ $nota->concluido ? 'Marcar Pendente' : 'Marcar Concluída' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('notas.destroy', $nota) }}"
                              onsubmit="return confirm('Tem certeza que deseja mover esta nota para a lixeira?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Mover para Lixeira
                            </button>
                        </form>

                        <a href="{{ route('notas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Card com informações adicionais --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Informações</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Prioridade:</strong><br>
                        <span class="badge bg-info">{{ $nota->prioridade->nome ?? 'Não definida' }}</span>
                    </div>

                    <div class="mb-2">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $nota->status_class }}">{{ $nota->status }}</span>
                    </div>

                    @if($nota->completed_at)
                    <div class="mb-2">
                        <strong>Concluída em:</strong><br>
                        <small>{{ $nota->completed_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @endif

                    <div class="mb-2">
                        <strong>Total de anexos:</strong><br>
                        <span class="badge bg-primary">{{ $nota->anexos->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .attachment-item {
        background: #f8f9fa;
        transition: all 0.2s ease;
    }

    .attachment-item:hover {
        background: #e9ecef;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
    }

    .badge {
        font-size: 0.8em;
    }
</style>
@endpush
