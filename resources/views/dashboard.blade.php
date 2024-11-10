@extends('layouts.app')

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Cards principais --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total de Notas</div>
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_notas'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Concluídas</div>
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $stats['notas_concluidas'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pendentes</div>
                        <div class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['notas_pendentes'] }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Vencidas</div>
                        <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $stats['notas_vencidas'] }}</div>
                    </div>
                </div>
            </div>

            {{-- Gráficos detalhados --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Notas por Categoria --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Notas por Categoria</h3>
                        <div class="space-y-2">
                            @foreach($notasPorCategoria as $categoria)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 dark:text-gray-300">{{ $categoria->nome }}</span>
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                                        {{ $categoria->notas_count }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Notas por Prioridade --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Notas por Prioridade</h3>
                        <div class="space-y-2">
                            @foreach($notasPorPrioridade as $prioridade)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700 dark:text-gray-300">{{ ucfirst($prioridade->prioridade) }}</span>
                                    <span class="px-2 py-1 rounded-full text-sm
                                        {{ $prioridade->prioridade === 'alta' ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' :
                                           ($prioridade->prioridade === 'media' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' :
                                           'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200') }}">
                                        {{ $prioridade->total }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
