@extends('layouts.app')

@section('slot')
    {{-- para correção de erro --}}
    @php
        $notasExcluidasCount = $notasExcluidasCount ?? 0;
    @endphp
    <div class="container pt-4">

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h2 class="mb-0 textColor">Minhas Notas</h2>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('notas.lixeira') }}" class="btn btn-outline-danger position-relative">
                    <i class="bi bi-trash"></i> Lixeira
                    @if ($notasExcluidasCount > 0)
                        <span class="position-absolute top-0 start-98 translate-middle badge rounded-pill bg-danger">
                            {{ $notasExcluidasCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('notas.create') }}" class="btn btn-primary-ed">
                    <i class="bi bi-plus-lg"></i> Nova Nota
                </a>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('notas.index') }}">
                    <div class="row g-3">

                        {{-- Busca --}}
                        <div class="col-md-4 col-sm-6">
                            <label for="busca" class="form-label">Buscar</label>
                            <input type="text" id="busca" name="busca" value="{{ request('busca') }}"
                                class="form-control" placeholder="Pesquisar...">
                        </div>

                        {{-- Categoria --}}
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="categoria" class="form-label">Categoria</label>
                            <select id="categoria" name="categoria" class="form-select">
                                <option value="">Todas</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tag --}}
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="tag" class="form-label">Tag</label>
                            <select id="tag" name="tag" class="form-select">
                                <option value="">Todas</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Ordenação --}}
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="ordem" class="form-label">Ordenar por</label>
                            <select id="ordem" name="ordem" class="form-select">
                                <option value="desc" {{ request('ordem') == 'desc' ? 'selected' : '' }}>Mais recentes
                                </option>
                                <option value="asc" {{ request('ordem') == 'asc' ? 'selected' : '' }}>Mais antigas
                                </option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente
                                </option>
                                <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>
                                    Concluído</option>
                            </select>
                        </div>

                        {{-- Prioridade --}}
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="prioridade" class="form-label">Prioridade</label>
                            <select id="prioridade" name="prioridade" class="form-select">
                                <option value="">Todas</option>
                                @foreach ($prioridades as $prioridade)
                                    <option value="{{ $prioridade->id }}"
                                        {{ request('prioridade') == $prioridade->id ? 'selected' : '' }}>
                                        {{ $prioridade->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Limpar</a>
                        <button type="submit" class="btn btn-primary-ed">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Lista de Notas --}}
        @forelse($notas as $nota)
            <div class="mb-4">
                <div class="card h-100 shadow-sm w-100">
                    <div class="card-body d-flex flex-column position-relative">
                        <h5 class="card-title text-truncate pb-1" title="{{ $nota->titulo }}">{{ $nota->titulo }}</h5>
                        <small class="text-muted">Criada em:
                            {{ $nota->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</small>
                        <!--data de criacao-->
                        <p class="card-text mt-2">{{ Str::limit($nota->conteudo, 150) }}</p>

                        {{-- badges no canto superior direito --}}
                        {{-- concluido/pendente --}}
                        <div class="position-absolute top-0 end-0 text-end p-2">
                            <span class="badge rounded-pill {{ $nota->concluido ? 'bg-success' : 'bg-warning-ed ' }}">
                                {{ $nota->concluido ? 'Concluído' : 'Pendente' }}
                            </span>
                            <br>
                            {{-- prioridade --}}
                            <span
                                class="badge rounded-pill
                                {{ $nota->prioridade->nivel === 3
                                    ? ' border border-danger text-danger'
                                    : ($nota->prioridade->nivel === 2
                                        ? 'bg-medi border-warning-ed text-warning-ed'
                                        : 'text-primary border border-primary') }}">
                                Prioridade: {{ $nota->prioridade->nome }}
                            </span>
                            <br>
                            {{-- vencimento --}}
                            @if ($nota->data_vencimento)
                                <span
                                    class="badge rounded-pill {{ $nota->data_vencimento->isPast() && !$nota->concluido ? 'bg-danger' : 'bg-secondary' }}">
                                    {{ $nota->data_vencimento->isPast() && !$nota->concluido ? 'Vencida' : 'Vence' }}
                                    em {{ $nota->data_vencimento->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        {{-- Categoria --}}
                        <div class="mb-2">
                            <span class="">Categoria: {{ $nota->categoria->nome }}</span>
                        </div>

                        {{-- Tags --}}
                        @if ($nota->tags->isNotEmpty())
                            <div class="mb-3">
                                <span>Tags:</span>
                                @foreach ($nota->tags as $tag)
                                    <span
                                        class="badge rounded-pill px-2 py-1 me-1 {{ $tag->classe_cor ?? 'text-secondary border border-secondary' }}">
                                        {{ $tag->nome }}
                                    </span>
                                @endforeach
                            </div>
                        @endif


                        {{-- Anexos --}}
                        @if ($nota->anexos->count() > 0)
                            <div class="mb-3 border-top pt-2">
                                <h6 class="text-muted mb-2">Anexos:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($nota->anexos as $anexo)
                                        <div class="position-relative" style="width: 80px;">
                                            @if (Str::contains($anexo->tipo_mime, 'image'))
                                                <a href="{{ Storage::url($anexo->caminho) }}" target="_blank"
                                                    class="d-block">
                                                    <img src="{{ Storage::url($anexo->caminho) }}"
                                                        alt="{{ $anexo->nome_original }}" class="img-fluid rounded"
                                                        style="height: 60px; width: 100%; object-fit: cover;">
                                                </a>
                                            @else
                                                <a href="{{ Storage::url($anexo->caminho) }}" target="_blank"
                                                    class="d-flex align-items-center justify-content-center border rounded p-2 text-decoration-none text-secondary"
                                                    style="height: 60px;">
                                                    <i class="fas fa-file-alt fa-lg"></i>
                                                </a>
                                            @endif
                                            <small class="d-block text-truncate"
                                                style="max-width: 100%;">{{ Str::limit($anexo->nome_original, 15) }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Vinculação com o show --}}
                    <a href="{{ route('notas.show', $nota->id) }}"
                        class="btn btn-outline-secondary btn-sm m-2">Visualizar nota</a>


                    {{-- Ações --}}
                    <div class="mt-auto d-flex justify-content-end gap-2 me-2 mb-2">
                        <form method="POST" action="{{ route('notas.complete', $nota->id) }}" class="m-0">
                            @csrf
                            {{-- concluido ou desfazer --}}
                            <button type="submit"
                                class="btn btn-sm {{ $nota->concluido ? 'btn-warning' : 'btn-success' }}">
                                @if ($nota->concluido)
                                    <i class="bi bi-arrow-counterclockwise"></i> Desfazer
                                @else
                                    <i class="bi bi-check-circle"></i> Concluir
                                @endif
                            </button>
                        </form>
                        {{-- editar --}}
                        <a href="{{ route('notas.edit', $nota->id) }}" class="btn btn-sm btn-primary-ed2">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        {{-- deletar --}}
                        <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Tem certeza que deseja excluir esta nota?')" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">Nenhuma nota encontrada.</div>
            </div>
        @endforelse
    </div>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $notas->links() }}
    </div>

@endsection
