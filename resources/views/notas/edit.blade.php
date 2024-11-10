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
                            <label for="titulo" class="block text-sm font-medium">Título</label>
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
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600" required>
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
                                <label for="prioridade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prioridade</label>
                                <select name="prioridade" id="prioridade"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="baixa" {{ old('prioridade', $nota->prioridade) == 'baixa' ? 'selected' : '' }}>Baixa</option>
                                    <option value="media" {{ old('prioridade', $nota->prioridade) == 'media' ? 'selected' : '' }}>Média</option>
                                    <option value="alta" {{ old('prioridade', $nota->prioridade) == 'alta' ? 'selected' : '' }}>Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                            <div class="grid grid-cols-3 gap-4">
                                @foreach($tags as $tag)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}"
                                               {{ in_array($tag->id, $nota->tags->pluck('id')->toArray()) ? 'checked' : '' }}
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
                                                    <button type="button"
                                                            onclick="if(confirm('Tem certeza que deseja remover este anexo?')) document.getElementById('remove-anexo-{{ $anexo->id }}').submit()"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="anexos" class="block text-sm font-medium">
                                    Adicionar Novos Anexos
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label for="anexos" class="relative cursor-pointer rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Selecione os arquivos</span>
                                                <input id="anexos" name="anexos[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png">
                                            </label>
                                            <p class="pl-1">ou arraste e solte aqui</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            PDF, PNG, JPG, JPEG até 5MB cada
                                        </p>
                                    </div>
                                </div>
                                {{-- Preview dos arquivos selecionados --}}
                                <div id="fileList" class="mt-2 space-y-2"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Atualizar Nota
                            </button>
                        </div>
                    </form>

                    @foreach($nota->anexos as $anexo)
                        <form id="remove-anexo-{{ $anexo->id }}"
                              action="{{ route('notas.anexos.destroy', ['nota' => $nota->id, 'anexo' => $anexo->id]) }}"
                              method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('anexos').addEventListener('change', function(e) {
    const fileList = document.getElementById('fileList');
    fileList.innerHTML = '';

    Array.from(this.files).forEach(file => {
        const fileSize = (file.size / (1024 * 1024)).toFixed(2); // Converter para MB
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400';
        div.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>${file.name}</span>
            <span>(${fileSize} MB)</span>
        `;
        fileList.appendChild(div);
    });
});

// Suporte para drag and drop
const dropZone = document.querySelector('input[type="file"]').closest('div');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropZone.classList.add('border-indigo-500');
}

function unhighlight(e) {
    dropZone.classList.remove('border-indigo-500');
}

dropZone.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    const fileInput = document.getElementById('anexos');

    fileInput.files = files;
    fileInput.dispatchEvent(new Event('change'));
}
</script>
@endpush
