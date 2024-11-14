@extends('layouts.app')

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold mb-6">Editar Nota</h2>

                    <form method="POST" action="{{ route('notas.update', $nota->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $nota->titulo) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        </div>

                        <div class="mb-4">
                            <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo</label>
                            <textarea name="conteudo" id="conteudo" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" required>{{ old('conteudo', $nota->conteudo) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                                <select name="categoria_id" id="categoria_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id', $nota->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="data_vencimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Vencimento</label>
                                <input type="date" name="data_vencimento" id="data_vencimento"
                                       value="{{ old('data_vencimento', $nota->data_vencimento?->format('Y-m-d')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                            </div>

                            <div>
                                <label for="prioridade_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioridade</label>
                                <select name="prioridade_id" id="prioridade_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                    @foreach($prioridades as $prioridade)
                                        <option value="{{ $prioridade->id }}"
                                                style="background-color: {{ $prioridade->cor }}; color: {{ in_array($prioridade->cor, ['#FFC107', '#4CAF50']) ? '#000' : '#fff' }};"
                                                {{ old('prioridade_id', $nota->prioridade_id) == $prioridade->id ? 'selected' : '' }}>
                                            {{ $prioridade->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($tags as $tag)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}"
                                               {{ in_array($tag->id, old('tags', $nota->tags->pluck('id')->toArray())) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <label for="tag_{{ $tag->id }}" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $tag->nome }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-lg font-medium mb-4">Gerenciar Anexos</h3>

                            {{-- Anexos Existentes --}}
                            @if($nota->anexos->count() > 0)
                                <div class="mb-6">
                                    <h3 class="text-sm font-medium mb-2">Anexos Atuais</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($nota->anexos as $anexo)
                                            <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                <div class="flex-1">
                                                    <p class="text-sm font-medium truncate">{{ $anexo->nome_original }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ number_format($anexo->tamanho / 1024 / 1024, 2) }} MB
                                                    </p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <a href="{{ Storage::url($anexo->caminho) }}"
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                    <form method="POST"
                                                          action="{{ route('notas.anexos.destroy', ['nota' => $nota->id, 'anexo' => $anexo->id]) }}"
                                                          class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Tem certeza que deseja excluir este anexo?')"
                                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Upload de Novos Anexos --}}
                            <div class="mb-4">
                                <label for="anexos" class="block text-sm font-medium mb-2">
                                    Adicionar Novos Anexos
                                </label>

                                {{-- Input de arquivo simples --}}
                                <input type="file"
                                       id="anexos"
                                       name="anexos[]"
                                       multiple
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       class="block text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-md file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-500 file:text-white
                                              hover:file:bg-blue-600
                                              dark:file:bg-blue-500 dark:file:text-white">

                                {{-- Área de preview simples --}}
                                <div id="fileList" class="mt-4 space-y-2 text-sm">
                                    {{-- Arquivos aparecerão aqui --}}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Atualizar Nota
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('anexos').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = ''; // Limpa a lista

    if (this.files.length > 0) {
        // Mostra os arquivos selecionados
        Array.from(this.files).forEach(file => {
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            const div = document.createElement('div');
            div.className = 'p-2 bg-gray-100 dark:bg-gray-700 rounded flex justify-between items-center';
            div.innerHTML = `
                <span class="text-gray-700 dark:text-gray-300">${file.name} (${fileSize} MB)</span>
                <button type="button" onclick="this.parentElement.remove()"
                        class="text-red-500 hover:text-red-700">×</button>
            `;
            fileList.appendChild(div);
        });
    }
});

// Adicione este trecho para debug do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    console.log('Formulário sendo enviado...');
    // Remova esta linha depois de testar
    // e.preventDefault();
});
</script>
@endpush

@push('styles')
<style>
    #prioridade_id option {
        padding: 8px;
        margin: 2px;
        border-radius: 4px;
    }

    #prioridade_id {
        padding: 8px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
    }
</style>
@endpush
