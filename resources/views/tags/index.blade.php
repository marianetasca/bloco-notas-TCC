@extends('layouts.app')

@section('slot')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="flex justify-between mb-6">
                    <h2 class="text-xl font-semibold">Tags</h2>
                    <a href="{{ route('tags.create') }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Nova Tag
                    </a>
                </div>

                @foreach($tags as $tag)
                    <div class="mb-4 p-4 bg-white dark:bg-gray-700 rounded shadow">
                        <div class="flex justify-between items-center">
                            <span class="text-lg">{{ $tag->nome }}</span>
                            <div class="flex space-x-2">
                                <a href="{{ route('tags.edit', $tag->id) }}"
                                   class="text-blue-600 hover:text-blue-900">Editar</a>

                                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="inline">
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
