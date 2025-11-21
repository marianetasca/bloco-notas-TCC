@extends('layouts.app')

@section('slot')
    <div class="container py-5">

        {{-- Filtros (data, mês, ano) --}}
        <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-end gap-2 mb-4">
            <div class="row g-3">
                {{-- Filtro por (vencimento/ criação) --}}
                <div class="col-md-2 col-6 w-auto">
                    <select name="filtro_por" class="form-select">
                        <option value="vencimento"
                            {{ (request('filtro_por') ?? 'vencimento') == 'vencimento' ? 'selected' : '' }}>Data de
                            vencimento</option>
                        <option value="criacao" {{ request('filtro_por') == 'criacao' ? 'selected' : '' }}>Data de criação
                        </option>
                    </select>
                </div>

                {{-- Data --}}
                <div class="col-md-2 col-6 min-w-4 w-auto">
                    <input type="date" name="data" class="form-control" value="{{ request('data') }}">
                </div>

                {{-- Mês --}}
                <div class="col-md-3 col-6 w-auto">
                    <select name="mes" class="form-select">
                        <option value="">Todos os meses</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                {{-- Ano --}}
                <div class="col-md-3 col-6 w-auto">
                    <select name="ano" class="form-select">
                        <option value="">Todos os anos</option>
                        @for ($a = now()->year; $a >= now()->year - 5; $a--)
                            <option value="{{ $a }}" {{ request('ano') == $a ? 'selected' : '' }}>
                                {{ $a }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 col-6 w-auto">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Limpar</a>
                    <button type="submit" class="btn btn-primary-ed">Filtrar</button>
                </div>
            </div>
        </form>
        {{-- Cards principais --}}
        <div class="row g-4 mb-5 mt-2">
            @foreach ([
            'Total de Notas' => 'total_notas',
            'Concluídas' => 'notas_concluidas',
            'Pendentes' => 'notas_pendentes',
            'Vencidas' => 'notas_vencidas',
        ] as $label => $key)
                <div class="col-sm-6 col-md-3">
                    <div class="card shadow-sm border-0 text-center">
                        <div class="card-body hover-shadow rounded-2">
                            <h6 class="card-title">{{ $label }}</h6>
                            <h3
                                class="fw-bold
                            {{ $key == 'notas_concluidas' ? 'text-success' : '' }}
                            {{ $key == 'notas_pendentes' ? 'text-warning' : '' }}
                            {{ $key == 'notas_vencidas' ? 'text-danger' : '' }}
                            {{ $key == 'total_notas' ? 'text-primary' : '' }}
                        ">
                                {{ $stats[$key] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Gráficos --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <h4 class="card-header">Notas por Categoria</h4>
                    <div class="card-body">
                        <canvas id="graficoCategorias"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <h4 class="card-header">Notas por Prioridade</h4>
                    <div class="card-body">
                        <canvas id="graficoPrioridades"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Gráficos --}}
    <script>
        const categoriasLabels = @json(array_keys($graficoCategorias->toArray()));
        const categoriasData = @json(array_values($graficoCategorias->toArray()));

        const prioridadesLabels = @json(array_keys($graficoPrioridades->toArray()));
        const prioridadesData = @json(array_values($graficoPrioridades->toArray()));

        const ctxCategorias = document.getElementById('graficoCategorias').getContext('2d');
        const chartCategorias = new Chart(ctxCategorias, {
            type: 'bar',
            data: {
                labels: categoriasLabels,
                datasets: [{
                    label: 'Notas por Categoria',
                    data: categoriasData,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true
            }
        });

        const ctxPrioridades = document.getElementById('graficoPrioridades').getContext('2d');
        const chartPrioridades = new Chart(ctxPrioridades, {
            type: 'bar',
            data: {
                labels: prioridadesLabels,
                datasets: [{
                    label: 'Notas por Prioridade',
                    data: prioridadesData,
                    backgroundColor: 'rgba(234, 179, 8, 0.7)',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>

    {{-- Lista de notas filtradas --}}
    <div class="container py-4">
        <div class="card">
            <h5 class="card-header">Notas</h5>
            <div class="card-body">
                @if ($notas->isEmpty())
                    <p class="text-muted">Nenhuma nota encontrada com os filtros selecionados.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Categoria</th>
                                    <th>Prioridade</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Tags</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notas as $nota)
                                    <tr
                                        class="{{ isset($highlightId) && $highlightId == $nota->id ? 'highlight-nota' : '' }}">
                                        <td id="nota-{{ $nota->id }}" class="td-title" title="{{ $nota->titulo }}">
                                            <a href="{{ route('notas.index', ['highlight' => $nota->id]) }}"
                                                class="d-inline-block text-truncate" style="max-width:240px;">
                                                {{ $nota->titulo }}
                                            </a>
                                        </td>
                                        <td>{{ $nota->categoria->nome ?? '-' }}</td>
                                        <td>{{ $nota->prioridade->nome ?? '-' }}</td>
                                        <td>{{ $nota->data_vencimento ? $nota->data_vencimento->format('d/m/Y') : '-' }}
                                        </td>
                                        <td>
                                            @if ($nota->concluido)
                                                <span class="badge bg-success">Concluída</span>
                                            @elseif($nota->data_vencimento && $nota->data_vencimento < now())
                                                <span class="badge bg-danger">Vencida</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Pendente</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($nota->tags && $nota->tags->isNotEmpty())
                                                {{ $nota->tags->pluck('nome')->join(', ') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $notas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
