@extends('layouts.app')

@section('slot')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Lixeira</h2>
                    <a href="{{ route('notas.index') }}" class="text-gray-400 hover:text-gray-300">
                        Voltar para notas
                    </a>
                </div>

                <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-lg p-4 mb-6">
                    <p class="text-yellow-500 text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        As notas na lixeira são automaticamente excluídas após 30 dias.
                    </p>
                </div>

                @if($notasExcluidas->isEmpty())
                    <p class="text-gray-500 text-center py-8">Nenhuma nota excluída.</p>
                @else
                    <div class="space-y-4">
                        @foreach($notasExcluidas as $nota)
                            <div class="bg-gray-700/50 p-4 rounded-lg">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-medium">{{ $nota->titulo }}</h3>
                                        <p class="text-sm text-gray-400">
                                            Excluída em: {{ $nota->deleted_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-sm text-red-400">
                                            Será excluída permanentemente em: {{ $nota->deleted_at->addDays(30)->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-400 mt-2">{{ Str::limit($nota->conteudo, 100) }}</p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <form action="{{ route('notas.restaurar', $nota->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-blue-500 hover:text-blue-400">
                                                <i class="fas fa-undo-alt mr-1"></i> Restaurar
                                            </button>
                                        </form>

                                        <form action="{{ route('notas.excluir-permanente', $nota->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Tem certeza? Esta ação não pode ser desfeita!')"
                                                    class="text-red-500 hover:text-red-400">
                                                <i class="fas fa-trash mr-1"></i> Excluir Permanentemente
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notasExcluidas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
