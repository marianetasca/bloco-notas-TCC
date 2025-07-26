@extends('layouts.app')

@section('slot')
    <div class="container py-5">

        {{-- Filtros (data, mês, ano) --}}

        <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-end gap-2 mb-4">
            <div class="row g-3">
                {{-- Data --}}
                <div class="col-md-3 col-6 w-auto">
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

                <div class="col-md-3 col-6 w-auto">
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
@endsection
