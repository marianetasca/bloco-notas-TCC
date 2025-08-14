@extends('layouts.app')

@section('slot')
    <div class="container mt-4">
        <h2 class="mb-4 textColor">Editar Nota</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('notas.update', $nota->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- titulo --}}
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control"
                    value="{{ old('titulo', $nota->titulo) }}" required>
            </div>

            {{-- conteudo --}}
            <div class="mb-3">
                <label for="conteudo" class="form-label">Conteúdo</label>
                <textarea name="conteudo" id="conteudo" class="form-control" rows="4" required>{{ old('conteudo', $nota->conteudo) }}</textarea>
            </div>

            <div class="row g-3"> {{-- para ocupar a mesma linha --}}
                {{-- categoria --}}
                <div class="col-md-4 w-auto">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id', $nota->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- data --}}
                <div class="col-md-4 w-auto">
                    <label for="data_vencimento" class="form-label">Data de vencimento</label>
                    <input type="date" name="data_vencimento" id="data_vencimento" class="form-control"
                        value="{{ old('data_vencimento', \Carbon\Carbon::parse($nota->data_vencimento)->format('Y-m-d')) }}">
                </div>

                {{-- prioridades --}}
                <div class="col-md-4 w-auto">
                    <label for="prioridade" class="form-label">Prioridade</label>
                    <select name="prioridade_id" id="prioridade_id" class="form-select" required>
                        @foreach ($prioridades as $prioridade)
                            <option value="{{ $prioridade->id }}"
                                {{ old('prioridade_id', $nota->prioridade_id) == $prioridade->id ? 'selected' : '' }}>
                                {{ $prioridade->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- tags --}}
            <div class="mt-3">
                <label class="form-label">Tags</label><br>
                @foreach ($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tags[]" id="tag_{{ $tag->id }}"
                            value="{{ $tag->id }}" {{ $nota->tags->contains($tag->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->nome }}</label>
                    </div>
                @endforeach
            </div>
            {{-- Anexos Existentes --}}
            @if ($nota->anexos->count() > 0)
                <div class="mt-4">
                    <label class="form-label">Anexos Atuais</label>
                    <div id="anexos-existentes">
                        @foreach ($nota->anexos as $anexo)
                            <div class="border rounded p-3 mb-2" data-anexo-id="{{ $anexo->id }}">
                                {{-- Preview simples para imagens --}}
                                @if (in_array(strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ $anexo->url }}"
                                        style="max-width: 100px; max-height: 100px; margin-bottom: 10px;" class="d-block">
                                @elseif(strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION)) === 'pdf')
                                    {{-- PDF --}}
                                    <a href="{{ $anexo->url }}" class="text-decoration-none">
                                        <div class="anexo-pdf-preview d-flex align-items-center justify-content-center"
                                            style="max-width: 100px; max-height: 100px; background: #f8f9fa; cursor: pointer;">
                                            <div class="text-center">
                                                <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                                                <div class="small text-muted">PDF</div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $anexo->nome_original }}</strong>
                                        <small class="text-muted d-block">{{ $anexo->tamanho_formatado }}</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ $anexo->url }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">Ver</a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="removerAnexoExistente({{ $anexo->id }})"><i
                                            class="bi bi-trash"></i>Remover</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Dropzone simples --}}
            <div class="mt-4">
                <label class="form-label">Adicionar Novos Anexos</label>
                <div id="dropzone-anexos" class="dropzone">
                    <div class="dz-message">
                        <div class="dz-button">
                            <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i><br>
                            Arraste arquivos aqui ou clique para selecionar
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                Arquivos aceitos: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (máx. 10MB cada)
                            </small>
                        </div>
                    </div>
                </div>
                {{-- Lista de novos anexos --}}
                <div id="novos-anexos" style="display: none;">
                    <h6 class="mt-3">Novos arquivos:</h6>
                    <div id="lista-novos-anexos"></div>
                </div>

                <div id="anexos-inputs"></div>
                <div id="anexos-removidos"></div>
            </div>


            <div class="text-end mt-4">
                <a href="{{ route('notas.index') }}" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-primary-ed">
                    Atualizar Nota
                </button>
            </div>
        </form>
    </div>

@endsection
{{-- Dropzone --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Dropzone.autoDiscover = false;

            let novosAnexos = [];
            let anexosRemovidos = [];

            // Dropzone simples
            const dropzone = new Dropzone("#dropzone-anexos", {
                url: "{{ route('anexos.upload') }}",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                maxFilesize: 10,
                acceptedFiles: ".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.txt",

                success: function(file, response) {
                    if (response.success) {
                        novosAnexos.push({
                            id: response.anexo_id,
                            nome: response.nome,
                            url: response.url
                        });
                        mostrarNovosAnexos();
                        this.removeFile(file);
                    }
                },

                error: function(file, message) {
                    alert('Erro: ' + message);
                    this.removeFile(file);
                }
            });

            // Mostrar lista de novos anexos
            function mostrarNovosAnexos() {
                const container = document.getElementById('novos-anexos');
                const lista = document.getElementById('lista-novos-anexos');
                const inputs = document.getElementById('anexos-inputs');

                if (novosAnexos.length > 0) {
                    container.style.display = 'block';
                    lista.innerHTML = '';
                    inputs.innerHTML = '';

                    novosAnexos.forEach((anexo, index) => {
                        // Item visual
                        const div = document.createElement('div');
                        div.className = 'border rounded p-2 mb-2';
                        div.innerHTML = `
                    ${isImagem(anexo.nome) ? `<img src="${anexo.url}" style="max-width: 80px; max-height: 80px; margin-bottom: 5px;" class="d-block">` : ''}
                    <div class="d-flex justify-content-between align-items-center">
                        <span>${anexo.nome}</span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removerNovo(${index})">X</button>
                    </div>
                `;
                        lista.appendChild(div);

                        // Input hidden
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'anexos_temp[]';
                        input.value = anexo.id;
                        inputs.appendChild(input);
                    });
                } else {
                    container.style.display = 'none';
                }
            }

            // Verificar se é imagem
            function isImagem(nome) {
                return /\.(jpg|jpeg|png|gif)$/i.test(nome);
            }
            // Função para obter ícone do arquivo
            function getFileIcon(fileName) {
                const extension = fileName.split('.').pop().toLowerCase();
                const icons = {
                    'pdf': 'fas fa-file-pdf text-danger',
                    'doc': 'fas fa-file-word text-primary',
                    'docx': 'fas fa-file-word text-primary',
                    'xls': 'fas fa-file-excel text-success',
                    'xlsx': 'fas fa-file-excel text-success',
                    'jpg': 'fas fa-file-image text-info',
                    'jpeg': 'fas fa-file-image text-info',
                    'png': 'fas fa-file-image text-info',
                    'txt': 'fas fa-file-alt text-secondary'
                };
                return icons[extension] || 'fas fa-file text-secondary';
            }

            // Remover novo anexo
            // Remover novo anexo (temporário)
            window.removerNovo = function(index) {
                if (confirm('Remover arquivo?')) {
                    const anexo = novosAnexos[index];

                    // URL com ID do anexo na rota DELETE
                    fetch(`{{ url('/anexos') }}/${anexo.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                                // ❌ Removido Content-Type e body - DELETE não precisa
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                novosAnexos.splice(index, 1);
                                mostrarNovosAnexos();
                                console.log('Anexo removido com sucesso!');
                            } else {
                                console.error('Erro:', data.message);
                                alert('Erro ao remover: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erro na requisição:', error);
                            alert('Erro de conexão');
                        });
                }
            };

            window.removerAnexoExistente = function(id) {
                if (confirm('Remover anexo?')) {
                    // Marca visualmente como removido
                    const elemento = document.querySelector(`[data-anexo-id="${id}"]`);
                    if (elemento) {
                        elemento.style.opacity = '0.5';
                        elemento.style.textDecoration = 'line-through';
                    }

                    // Adiciona à lista de removidos
                    anexosRemovidos.push(id);

                    // Cria input hidden para enviar no formulário
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'anexos_removidos[]';
                    input.value = id;
                    document.getElementById('anexos-removidos').appendChild(input);

                    console.log('Anexo marcado para remoção:', id);
                }
            };
        });
    </script>
@endpush
