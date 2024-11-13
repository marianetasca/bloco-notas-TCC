@extends('layouts.app')

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Adicione esta seção de alertas --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Minhas Notas</h2>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('notas.lixeira') }}"
                               class="text-gray-400 hover:text-gray-300">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Lixeira
                                @if($notasExcluidasCount > 0)
                                    <span class="ml-2 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                                        {{ $notasExcluidasCount }}
                                    </span>
                                @endif
                            </a>

                            <a href="{{ route('notas.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-1"></i>
                                Nova Nota
                            </a>
                        </div>
                    </div>

                    {{-- Filtros --}}
                    <div class="mb-6 p-4 bg-gray-100 dark:bg-gray-700 rounded">
                        <form method="GET" action="{{ route('notas.index') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Busca --}}
                                <div>
                                    <label for="busca" class="block text-sm font-medium">Buscar</label>
                                    <input type="text" name="busca" id="busca" value="{{ request('busca') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                                </div>

                                {{-- Filtro por categoria --}}
                                <div>
                                    <label for="categoria" class="block text-sm font-medium">Categoria</label>
                                    <select name="categoria" id="categoria"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                                        <option value="">Todas</option>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                                {{ $categoria->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Filtro por tag --}}
                                <div>
                                    <label for="tag" class="block text-sm font-medium">Tag</label>
                                    <select name="tag" id="tag"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                                        <option value="">Todas</option>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                                {{ $tag->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Ordenação --}}
                                <div>
                                    <label for="ordem" class="block text-sm font-medium">Ordenar por</label>
                                    <select name="ordem" id="ordem"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                                        <option value="desc" {{ request('ordem') == 'desc' ? 'selected' : '' }}>Mais recentes</option>
                                        <option value="asc" {{ request('ordem') == 'asc' ? 'selected' : '' }}>Mais antigas</option>
                                    </select>
                                </div>

                                {{-- Status --}}
                                <div>
                                    <label for="status" class="block text-sm font-medium">Status</label>
                                    <select name="status" id="status"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                                        <option value="">Todos</option>
                                        <option value="pendente" {{ request('status') == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                                    </select>
                                </div>

                                {{-- Prioridade --}}
                                <div>
                                    <label for="prioridade" class="block text-sm font-medium">Prioridade</label>
                                    <select name="prioridade" id="prioridade"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600">
                                        <option value="">Todas as Prioridades</option>
                                        @foreach($prioridades as $prioridade)
                                            <option value="{{ $prioridade->id }}"
                                                    {{ request('prioridade') == $prioridade->id ? 'selected' : '' }}
                                                    style="background-color: {{ $prioridade->cor }}; color: {{ in_array($prioridade->cor, ['#FFC107', '#4CAF50']) ? '#000' : '#fff' }};">
                                                {{ $prioridade->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                {{-- Botões --}}
                                <div class="flex items-end space-x-2">
                                    <button type="submit"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        Filtrar
                                    </button>
                                    <a href="{{ route('notas.index') }}"
                                       class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                        Limpar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Lista de Notas --}}
                    @foreach($notas as $nota)
                        <div class="mb-4 p-4 bg-white dark:bg-gray-700 rounded shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $nota->titulo }}</h3>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Criada em: {{ $nota->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}
                                    </div>
                                    <p class="mt-2">{{ $nota->conteudo }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-2 py-1 text-xs {{ $nota->concluido ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }} rounded-full">
                                        {{ $nota->concluido ? 'Concluído' : 'Pendente' }}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $nota->prioridade->nivel === 3 ? 'bg-red-200 text-red-800' :
                                           ($nota->prioridade->nivel === 2 ? 'bg-orange-200 text-orange-800' : 'bg-blue-200 text-blue-800') }}">
                                        Prioridade: {{ $nota->prioridade->nome }}
                                    </span>
                                    @if($nota->data_vencimento)
                                        <span class="px-2 py-1 text-xs rounded-full
                                            {{ $nota->data_vencimento->isPast() && !$nota->concluido ? 'bg-red-200 text-red-800' : 'bg-gray-200 text-gray-800' }}">
                                            {{ $nota->data_vencimento->isPast() && !$nota->concluido ? 'Vencida' : 'Vence' }} em {{ $nota->data_vencimento->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    Categoria: {{ $nota->categoria->nome }}
                                </span>

                                @if($nota->tags->isNotEmpty())
                                    <div class="mt-2">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Tags:</span>
                                        @foreach($nota->tags as $tag)
                                            <span class="ml-1 px-2 py-1 bg-gray-200 dark:bg-gray-600 rounded-full text-xs">
                                                {{ $tag->nome }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            @if($nota->anexos->count() > 0)
                                <div class="mt-4 border-t border-gray-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-400 mb-2">Anexos:</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                        @foreach($nota->anexos as $anexo)
                                            <div class="flex items-center p-2 bg-gray-700 rounded-lg group">
                                                <div class="flex-1 min-w-0">
                                                    @if(Str::contains($anexo->tipo_mime, 'image'))
                                                        <a href="{{ Storage::url($anexo->caminho) }}"
                                                           target="_blank"
                                                           class="block relative">
                                                            <img src="{{ Storage::url($anexo->caminho) }}"
                                                                 alt="{{ $anexo->nome_original }}"
                                                                 class="w-full h-20 object-cover rounded">
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-opacity rounded"></div>
                                                        </a>
                                                    @else
                                                        <a href="{{ Storage::url($anexo->caminho) }}"
                                                           target="_blank"
                                                           class="flex items-center p-2 hover:bg-gray-600 rounded transition-colors">
                                                            <svg class="w-8 h-8 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    <div class="mt-1 px-2">
                                                        <p class="text-sm truncate" title="{{ $anexo->nome_original }}">
                                                            {{ Str::limit($anexo->nome_original, 20) }}
                                                        </p>
                                                        <p class="text-xs text-gray-400">
                                                            {{ number_format($anexo->tamanho / 1024, 1) }} KB
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 flex justify-end space-x-2">
                                <form method="POST" action="{{ route('notas.complete', $nota->id) }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="text-green-600 hover:text-green-900">
                                        {{ $nota->concluido ? 'Desfazer' : 'Concluir' }}
                                    </button>
                                </form>
                                <a href="{{ route('notas.edit', $nota->id) }}"
                                   class="text-blue-600 hover:text-blue-900">Editar</a>
                                <form action="{{ route('notas.destroy', $nota->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4">
                        {{ $notas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
