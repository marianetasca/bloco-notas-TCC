@extends('layouts.app')

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <h2 class="text-2xl font-semibold">{{ $nota->titulo }}</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('notas.edit', $nota->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Editar
                            </a>
                            <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Tem certeza que deseja excluir esta nota?')"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 dark:bg-red-500 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                        <div class="flex flex-wrap gap-4 text-sm mb-4">
                            <span class="px-3 py-1 bg-{{ $nota->prioridade == 'alta' ? 'red' : ($nota->prioridade == 'media' ? 'yellow' : 'blue') }}-100 dark:bg-{{ $nota->prioridade == 'alta' ? 'red' : ($nota->prioridade == 'media' ? 'yellow' : 'blue') }}-900 text-{{ $nota->prioridade == 'alta' ? 'red' : ($nota->prioridade == 'media' ? 'yellow' : 'blue') }}-800 dark:text-{{ $nota->prioridade == 'alta' ? 'red' : ($nota->prioridade == 'media' ? 'yellow' : 'blue') }}-200 rounded-full">
                                Prioridade: {{ ucfirst($nota->prioridade) }}
                            </span>
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full">
                                Categoria: {{ $nota->categoria->nome }}
                            </span>
                            @if($nota->data_vencimento)
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-full">
                                    Vence em: {{ \Carbon\Carbon::parse($nota->data_vencimento)->format('d/m/Y') }}
                                </span>
                            @endif
                        </div>

                        @if($nota->tags->count() > 0)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($nota->tags as $tag)
                                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-xs">
                                        {{ $tag->nome }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="prose dark:prose-invert max-w-none">
                            {!! nl2br(e($nota->conteudo)) !!}
                        </div>

                        @if($nota->anexos->count() > 0)
                            <div class="mt-6 border-t dark:border-gray-600 pt-4">
                                <h3 class="text-lg font-semibold mb-3">Anexos</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($nota->anexos as $anexo)
                                        <div class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg shadow">
                                            <div class="flex-1">
                                                <div class="flex items-center">
                                                    @if(in_array($anexo->tipo_mime, ['image/jpeg', 'image/png', 'image/jpg']))
                                                        <svg class="w-8 h-8 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-8 h-8 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-medium truncate">{{ $anexo->nome_original }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ number_format($anexo->tamanho / 1024 / 1024, 2) }} MB
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ Storage::url($anexo->caminho) }}"
                                               target="_blank"
                                               class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
