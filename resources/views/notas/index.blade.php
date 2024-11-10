@extends('layouts.app')

@section('slot')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between mb-6">
                    <h2 class="text-xl font-semibold">Notas</h2>
                    <a href="{{ route('notas.create') }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nova Nota
                    </a>
                </div>

                @foreach($notas as $nota)
                    <div class="mb-4 p-4 bg-white dark:bg-gray-700 rounded shadow">
                        <h3 class="text-lg font-bold">{{ $nota->titulo }}</h3>
                        <p class="mt-2">{{ $nota->conteudo }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                Categoria: {{ $nota->categoria->nome }}
                            </span>
                            <div class="flex space-x-2">
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
