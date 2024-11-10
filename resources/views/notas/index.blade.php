@extends('layouts.app')

@section('slot')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
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

                {{-- Botão Nova Nota --}}
                <div class="flex justify-between mb-6">
                    <h2 class="text-xl font-semibold">Minhas Notas</h2>
                    <a href="{{ route('notas.create') }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nova Nota
                    </a>
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
                                    {{ $nota->prioridade === 'alta' ? 'bg-red-200 text-red-800' :
                                       ($nota->prioridade === 'media' ? 'bg-orange-200 text-orange-800' : 'bg-blue-200 text-blue-800') }}">
                                    Prioridade {{ ucfirst($nota->prioridade) }}
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

                        <div class="mt-4 flex justify-end space-x-2">
                            <form action="{{ route('notas.complete', $nota->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
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
@endsection
