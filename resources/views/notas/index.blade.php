@extends('layouts.app')

@section('slot')
    <div class="container pt-4">

        {{-- Cabeçalho --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h2 class="mb-0 textColor">Minhas Notas</h2>
            <div class="d-flex gap-2 mt-2 mt-sm-0 flex-wrap">
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

                    {{-- Filtros de vencimento --}}
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap">
                        <div class="d-flex gap-2">
                            <a href="{{ route('notas.index') }}"
                                class="badge rounded-pill px-2 py-1 me-1 {{ request('filtro') == null ? 'bg-white' : 'bg-white' }} text-black border border-dark text-decoration-none">
                                Todas
                            </a>

                            <a href="{{ route('notas.index', ['filtro' => 'vencidas']) }}"
                                class="badge rounded-pill px-2 py-1 me-1 {{ request('filtro') == 'vencidas' ? 'bg-danger' : 'bg-white' }} text-black border border-dark text-decoration-none">
                                <i class="bi bi-exclamation-circle"></i> Vencidas
                            </a>

                            <a href="{{ route('notas.index', ['filtro' => 'ativas']) }}"
                                class="badge rounded-pill px-2 py-1 me-1 {{ request('filtro') == 'ativas' ? 'bg-success' : 'bg-white' }} text-black border border-dark text-decoration-none">
                                <i class="bi bi-check-circle"></i> Ativas
                            </a>
                        </div>
                        <div class="d-flex gap-2 mt-1">
                            <a href="{{ route('notas.index') }}" class="btn btn-secondary">Limpar</a>
                            <button type="submit" class="btn btn-primary-ed">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Lista de Notas --}}
        @forelse($notas as $nota)
            <div class="mb-4">
                <div
                    class="card h-100 shadow-sm w-100  {{ isset($highlightId) && $highlightId == $nota->id ? 'highlight-nota' : '' }}">
                    <div class="card-body d-flex flex-column position-relative">
                        <h5 class="card-title pb-1 pe-5 text-truncate" id="tituloResumido{{ $nota->id }}"
                            title="{{ $nota->titulo }}" style="max-width: calc(100% - 100px);">
                            {!! nl2br(e(Str::limit($nota->titulo, 100))) !!}
                        </h5>

                        {{-- Título completo (oculto inicialmente) --}}
                        <h5 class="card-title pb-1 d-none pe-5"
                            id="tituloCompleto{{ $nota->id }}"style="max-width: calc(100% - 100px);">
                            {!! nl2br(e($nota->titulo)) !!}
                        </h5>

                        <!--data de criacao-->
                        <small class="text-muted">Criada em:
                            {{ $nota->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</small>

                        {{-- Versão resumida --}}
                        <div class="conteudo-nota mt-3">
                            {{-- CSS limitando altura --}}
                            <div class="conteudo-resumido" id="resumido{{ $nota->id }}">
                                <div class="conteudo-truncado text-truncate ">
                                    {!! $nota->conteudo !!}
                                </div>
                            </div>

                            <div class="conteudo-completo d-none" id="conteudoCompleto{{ $nota->id }}">
                                <div class="">
                                    {!! $nota->conteudo !!}
                                </div>
                            </div>

                            {{-- Botão único para alternar visualização --}}
                            @if (strlen($nota->conteudo) > 200 || strlen($nota->titulo) > 100)
                                <div class="text-end mt-2">
                                    <button class="btn btn-outline-secondary btn-sm toggle-btn"
                                        onclick="toggleNota({{ $nota->id }})" id="toggle-btn{{ $nota->id }}">
                                        <i class="bi bi-eye" id="icon{{ $nota->id }}"></i>
                                        <span id="text{{ $nota->id }}">Visualizar nota completa</span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- badges no canto superior direito --}}
                        <div class="badges position-absolute top-0 end-0 text-end p-2">
                            {{-- concluido/pendente --}}
                            <span
                                class="position badge rounded-pill {{ $nota->concluido ? 'bg-success' : 'bg-warning-ed ' }}">
                                {{ $nota->concluido ? 'Concluído' : 'Pendente' }}
                            </span>
                            <br>

                            {{-- prioridade --}}
                            <span
                                class="badge rounded-pill
                                {{ $nota->prioridade->nivel == 3
                                    ? ' border border-danger text-danger'
                                    : ($nota->prioridade->nivel === 2
                                        ? 'bg-medi border-orange text-orange'
                                        : 'text-primary border border-primary') }}">
                                Prioridade: {{ $nota->prioridade->nome }}
                            </span>
                            <br>
                            {{-- vencimento --}}
                            @if ($nota->data_vencimento)
                                @php
                                    $hoje = now()->copy()->startOfDay();
                                    $vencimento = $nota->data_vencimento->copy()->startOfDay();
                                    $diasRestantes = $hoje->diffInDays($vencimento, false);

                                    $completed = $nota->completed_at?->copy()->startOfDay();
                                    $diasConclusao = $completed ? $hoje->diffInDays($completed, false) : null;
                                @endphp

                                @if ($nota->concluido && $nota->completed_at)
                                    <span class="badge rounded-pill bg-secondary">
                                        Vencimento {{ $nota->data_vencimento->format('d/m/Y') }}
                                    </span><br>
                                    <span class="badge rounded-pill bg-success">
                                        {{ match (true) {
                                            $diasConclusao == 0 => 'Concluída hoje',
                                            $diasConclusao == 1 => 'Concluída há 1 dia',
                                            default => "Concluída há {$diasConclusao} dias",
                                        } }}
                                        ({{ $nota->completed_at->format('d/m/Y H:i') }})
                                    </span>
                                @else
                                    <span
                                        class="badge rounded-pill
                                        @if ($diasRestantes < 0) bg-danger
                                        @elseif ($diasRestantes == 0) bg-orange
                                        @elseif ($diasRestantes <= 3) bg-warning-ed
                                        @else bg-secondary @endif">
                                        @if ($diasRestantes < 0)
                                            Vencida em {{ $nota->data_vencimento->format('d/m/Y') }}
                                        @elseif ($diasRestantes == 0)
                                            Vence hoje ({{ $nota->data_vencimento->format('d/m/Y') }})
                                        @else
                                            Vence em {{ $diasRestantes }} {{ $diasRestantes == 1 ? 'dia' : 'dias' }}
                                            ({{ $nota->data_vencimento->format('d/m/Y') }})
                                        @endif
                                    </span>
                                @endif
                            @endif
                        </div>

                        {{-- Categoria --}}
                        <div class="mb-2 mt-3">
                            <span>Categoria: {{ $nota->categoria->nome }}</span>
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

                        {{-- Anexos com preview simples --}}
                        @if ($nota->anexos->count() > 0)
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-paperclip me-2"></i>
                                    <span class="fw-bold">Anexos ({{ $nota->anexos->count() }})</span>
                                    @if ($nota->anexos->count() > 4)
                                        <button class="btn btn-link btn-sm" onclick="toggleAnexos({{ $nota->id }})"
                                            id="btn-anexos-{{ $nota->id }}">
                                            Ver todos
                                        </button>
                                    @endif
                                </div>

                                <div class="row">
                                    @foreach ($nota->anexos as $index => $anexo)
                                        <div
                                            class="col-md-6 col-6 col-lg-3 mb-2 anexo-item {{ $index >= 4 ? 'd-none extra-anexo-' . $nota->id : '' }}">
                                            <div class="anexo-card border rounded p-2">
                                                {{-- Preview da Imagem (se for imagem) --}}
                                                @if (str_starts_with($anexo->tipo_mime, 'image/'))
                                                    <div class="text-center mb-2">
                                                        <img src="{{ $anexo->url }}" alt="{{ $anexo->nome_original }}"
                                                            class="img-preview rounded"
                                                            onclick="abrirImagem('{{ $anexo->url }}', '{{ $anexo->nome_original }}')">
                                                    </div>
                                                @else
                                                    {{-- Ícone para outros tipos --}}
                                                    <div class="text-center mb-2 py-3">
                                                        <i class="{{ $anexo->getIcone() }} fa-3x"></i>
                                                    </div>
                                                @endif

                                                {{-- Info do arquivo --}}
                                                <div class="anexo-info">
                                                    <div class="fw-bold text-truncate small"
                                                        title="{{ $anexo->nome_original }}">
                                                        {{ $anexo->nome_original }}
                                                    </div>
                                                    <small class="text-muted">{{ $anexo->tamanho_formatado }}</small>

                                                    {{-- Ações --}}
                                                    <div class="mt-2">
                                                        <a href="{{ $anexo->url }}" target="_blank"
                                                            class="btn btn-sm btn-outline-primary me-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ $anexo->url }}"
                                                            download="{{ $anexo->nome_original }}"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr>
                    {{-- Ultima atualização --}}
                    <div class="ps-3">
                        <p class="mb-1"><strong>Última atualização:</strong>
                            {{ $nota->updated_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                        </p>
                        @if ($nota->data_vencimento)
                            <p class="mb-1"><strong>Data de vencimento:</strong>
                                {{ $nota->data_vencimento->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                    {{-- Informações adicionais (aparecem apenas quando expandido) --}}
                    <div class="info-adicional d-none" id="info{{ $nota->id }}">

                    </div>


                    {{-- Ações --}}
                    <div class="mt-auto d-flex justify-content-end gap-2 me-2 mb-2">
                        <form method="POST" action="{{ route('notas.concluido', $nota->id) }}" class="m-0">
                            @csrf
                            <button type="submit"
                                class="btn btn-sm {{ $nota->concluido ? 'btn-warning-ed' : 'btn-success' }}">
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
                </div> {{-- fim do card-body --}}
            </div>
            <!-- Botão Voltar ao Topo -->
            <button id="backToTop" type="button"
                class="btn btn-light position-fixed bottom-0 end-0 m-3 rounded-circle shadow-lg d-none"
                aria-label="Voltar ao topo" style="width: 48px; height: 48px; z-index: 1030;">
                <i class="bi bi-arrow-up"></i> </button>

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

    {{-- JavaScript --}}
    <script>
        document.addEventListener("DomContentLoaded", function() {
            const alert = document.getElementById("successAlert");
            if (alert) {
                setTimeout(() => {
                    alert.classList.remove("show");
                    alert.classList.add("fade");
                    setTimeout(() => alert.remove(), 500);
                }, 4000);
            }
        });

        function toggleNota(notaId) {
            // Elementos do conteúdo
            const resumido = document.getElementById('resumido' + notaId);
            const conteudoCompleto = document.getElementById('conteudoCompleto' + notaId);

            // Elementos do título
            const tituloResumido = document.getElementById('tituloResumido' + notaId);
            const tituloCompleto = document.getElementById('tituloCompleto' + notaId);

            // Elementos de controle
            const info = document.getElementById('info' + notaId);
            const toggleBtn = document.getElementById('toggle-btn' + notaId);
            const icon = document.getElementById('icon' + notaId);
            const text = document.getElementById('text' + notaId);

            // Verifica se está fechado (resumido está visível)
            const isFechado = conteudoCompleto.classList.contains('d-none');

            if (isFechado) {
                // Expandir - mostrar conteúdo e título completos
                if (resumido) resumido.classList.add('d-none');
                conteudoCompleto.classList.remove('d-none');

                // Mostrar título completo
                if (tituloResumido) tituloResumido.classList.add('d-none');
                if (tituloCompleto) tituloCompleto.classList.remove('d-none');


                // Atualizar botão
                icon.className = 'bi bi-eye-slash';
                text.textContent = 'Recolher nota';
                toggleBtn.classList.remove('btn-outline-secondary');
                toggleBtn.classList.add('btn-secondary');
            } else {
                // Recolher - mostrar conteúdo e título resumidos
                if (resumido) resumido.classList.remove('d-none');
                conteudoCompleto.classList.add('d-none');

                // Mostrar título resumido
                if (tituloResumido) tituloResumido.classList.remove('d-none');
                if (tituloCompleto) tituloCompleto.classList.add('d-none');

                // Ocultar informações adicionais
                if (info) info.classList.add('d-none');

                // Atualizar botão
                icon.className = 'bi bi-eye';
                text.textContent = 'Visualizar nota completa';
                toggleBtn.classList.remove('btn-secondary');
                toggleBtn.classList.add('btn-outline-secondary');
            }
        }


        // Função em modal
        function abrirImagem(url, nome) {
            const modal = document.createElement('div');
            modal.innerHTML = `
                <div class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${nome}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="${url}" alt="${nome}" class="img-fluid rounded">
                            </div>
                            <div class="modal-footer">
                                <a href="${url}" download="${nome}" class="btn btn-success">
                                    <i class="fas fa-download"></i> Baixar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);
            const bootstrapModal = new bootstrap.Modal(modal.querySelector('.modal'));
            bootstrapModal.show();

            // Remove modal quando fechado
            modal.querySelector('.modal').addEventListener('hidden.bs.modal', function() {
                modal.remove();
            });
        }

        function toggleAnexos(notaId) {
            const extras = document.querySelectorAll('.extra-anexo-' + notaId);
            const btn = document.getElementById('btn-anexos-' + notaId);

            extras.forEach(el => el.classList.toggle('d-none'));

            const isShowing = extras[0] && !extras[0].classList.contains('d-none');
            btn.textContent = isShowing ? 'Ver menos anexos' : 'Ver mais anexos';
        }

        // DE VOLTA AO TOPO
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.getElementById("backToTop");
            const showAt = 300; // px: quando o botão aparece

            function toggleBtn() {
                if (window.scrollY > showAt) {
                    btn.classList.remove("d-none");
                } else {
                    btn.classList.add("d-none");
                }
            }

            // Atualiza estado ao carregar e ao rolar
            toggleBtn();
            window.addEventListener("scroll", toggleBtn, {
                passive: true
            });

            // Rola para o topo (respeitando prefers-reduced-motion)
            btn.addEventListener("click", function() {
                const prefersReduced = window.matchMedia(
                    "(prefers-reduced-motion: reduce)"
                ).matches;
                window.scrollTo({
                    top: 0,
                    behavior: prefersReduced ? "auto" : "smooth",
                });
            });
        });
    </script>

@endsection
