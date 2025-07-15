@extends('layouts.app')

@section('slot')
<div class="container">
    <h2 class="form-title">Nova Nota</h2>

    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('notas.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-input" required>
        </div>

        <div class="form-group">
            <label for="conteudo" class="form-label">Conteúdo</label>
            <textarea name="conteudo" id="conteudo" rows="4" class="form-textarea" required></textarea>
        </div>


        <div class="form-row">
            <div class="form-group">
                <label for="categoria_id" class="form-label">Categoria</label>
                <select name="categoria_id" id="categoria_id" class="form-select">
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- data de vencimento --}}
            <div class="form-group">
                <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                <input type="date" name="data_vencimento" id="data_vencimento" value="{{ old('data_vencimento') }}" class="form-input">
            </div>

            {{-- prioridade --}}
            <div class="form-group">
                <label for="prioridade_id" class="form-label">Prioridade</label>
                <select name="prioridade_id" id="prioridade_id" class="form-select" required>
                    @foreach($prioridades as $prioridade)
                        <option value="{{ $prioridade->id }}">{{ $prioridade->nome }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{-- tags --}}
        <div class="form-group">
            <label class="form-label">Tags</label>
            <div class="tags-grid">
                @foreach($tags as $tag)
                    <div class="tag-checkbox">
                        <input type="checkbox" name="tags[]" id="tag_{{ $tag->id }}" value="{{ $tag->id }}">
                        <label for="tag_{{ $tag->id }}">{{ $tag->nome }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group">
            <label for="anexos" class="form-label">Anexos (múltiplos arquivos permitidos)</label>
            <div class="dropzone" id="dropzone">
                <div class="dropzone-text">
                    <svg class="upload-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 48 48" stroke="currentColor">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <label for="anexos" class="dropzone-label">
                        <span>Selecione arquivos</span> <br>
                        <input id="anexos" name="anexos[]" type="file" class="sr-only" multiple accept=".pdf,.jpg,.jpeg,.png">
                    </label>
                    <p>ou arraste e solte</p>
                    <p class="dropzone-note">PDF, PNG, JPG até 5MB cada</p>
                </div>
            </div>
            <div id="fileList" class="file-list"></div>
        </div>

        <div class="form-footer">
            <button type="submit" class="btn">Criar Nota</button> <br>
        </div>
    </form>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('anexos');
    const fileList = document.getElementById('fileList');
    const dropZone = document.querySelector('.dropzone');

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            fileList.innerHTML = '';

            Array.from(this.files).forEach(file => {
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                const div = document.createElement('div');
                div.className = 'file-item';
                div.innerHTML = `
                    <svg class="file-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span>${file.name}</span>
                    <span class="file-size">(${fileSize} MB)</span>
                `;
                fileList.appendChild(div);
            });
        });
    }

    if (dropZone) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, e => {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.classList.add('dropzone-hover');
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.classList.remove('dropzone-hover');
        });

        dropZone.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            const newData = new DataTransfer();

            Array.from(files).forEach(file => newData.items.add(file));
            fileInput.files = newData.files;
            fileInput.dispatchEvent(new Event('change'));
        });
    }
});
</script>
@endpush
