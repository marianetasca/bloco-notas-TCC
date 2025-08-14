@extends('layouts.app')

@section('slot')
    <div class="container mt-4">
        <h2 class="mb-4 textColor">Nova Nota</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('notas.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="conteudo" class="form-label">Conteúdo</label>
                <textarea name="conteudo" id="conteudo" rows="4" class="form-control" required></textarea>
            </div>

            <div class="row g-3"> {{-- para ocupar a mesma linha --}}
                <div class="col-md-4 w-auto"> {{-- Categoria --}}
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="form-select">
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}"
                                {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Data da Nota --}}
                <div class="col-md-4 w-auto">
                    <label for="data_vencimento" class="form-label">Data de vencimento</label>
                    <input type="date" name="data_vencimento" id="data_vencimento" class="form-control"
                        value="{{ old('data_vencimento') }}">
                </div>

                {{-- Prioridade --}}
                <div class="col-md-4 w-auto">
                    <label for="prioridade_id" class="form-label">Prioridade</label>
                    <select name="prioridade_id" id="prioridade_id" class="form-select" required>
                        @foreach ($prioridades as $prioridade)
                            <option value="{{ $prioridade->id }}"
                                {{ old('prioridade_id') == $prioridade->id ? 'selected' : '' }}>
                                {{ $prioridade->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Tags --}}
            <div class="mt-3">
                <label class="form-label">Tags</label><br>
                @foreach ($tags as $tag)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="tags[]" id="tag_{{ $tag->id }}"
                            value="{{ $tag->id }}">
                        <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->nome }}</label>
                    </div>
                @endforeach
            </div>

            {{-- Dropzone simples --}}
            <div class="mt-4">
                <label class="form-label">Anexos</label>
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

                {{-- Lista de anexos --}}
                <div id="anexos-preview" style="display: none;">
                    <h6 class="mt-3">Arquivos selecionados:</h6>
                    <div id="lista-anexos"></div>
                </div>


                <div id="anexos-inputs"></div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('notas.index') }}" class="btn btn-secondary px-3">Voltar</a>
                <button type="submit" class="btn btn-primary-ed" id="btnSalvar">
                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    Criar Nota
                </button>
            </div>
        </form>
    </div>
@endsection

<!-- Dropzone CSS -->
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Dropzone.autoDiscover = false;

            let anexosCarregados = [];

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
                    console.log('Response completo:', response); // DEBUG

                    if (response.success) {
                        const anexo = {
                            id: response.anexo_id,
                            nome: response.nome,
                            url: response.url,
                            tamanho: response.tamanho
                        };

                        console.log('Anexo criado:', anexo); // DEBUG
                        console.log('URL da imagem:', anexo.url); // DEBUG

                        anexosCarregados.push(anexo);
                        mostrarAnexos();
                        this.removeFile(file);
                    }
                },

                error: function(file, message) {
                    console.error('Erro upload:', message); // DEBUG
                    alert('Erro: ' + message);
                    this.removeFile(file);
                }
            });

            // Mostrar lista de anexos
            function mostrarAnexos() {
                console.log('Mostrando anexos:', anexosCarregados); // DEBUG

                const container = document.getElementById('anexos-preview');
                const lista = document.getElementById('lista-anexos');
                const inputs = document.getElementById('anexos-inputs');

                if (anexosCarregados.length > 0) {
                    container.style.display = 'block';
                    lista.innerHTML = '';
                    inputs.innerHTML = '';

                    anexosCarregados.forEach((anexo, index) => {
                        console.log(`Processando anexo ${index}:`, anexo); // DEBUG

                        // Item visual
                        const div = document.createElement('div');
                        div.className = 'border rounded p-2 mb-2';

                        let previewHtml = '';
                        const ehImagem = isImagem(anexo.nome);

                        if (ehImagem && anexo.url) {
                            previewHtml =
                                `<img src="${anexo.url}" style="max-width: 80px; max-height: 80px; margin-bottom: 5px;" class="d-block" onerror="console.log('Erro ao carregar imagem: ${anexo.url}')">`;
                        }

                        div.innerHTML = `
                    ${previewHtml}
                    <div class="d-flex justify-content-between align-items-center">
                        <span>${anexo.nome}</span>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removerAnexo(${index})">X</button>
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
                const resultado = /\.(jpg|jpeg|png|gif)$/i.test(nome);
                console.log(`Verificando se ${nome} é imagem: ${resultado}`); // DEBUG
                return resultado;
            }

            // Remover anexo
            window.removerAnexo = function(index) {
                console.log('=== DEBUG REMOÇÃO ===');
                console.log('Index:', index);
                console.log('Anexos array:', anexosCarregados);

                const anexo = anexosCarregados[index];
                console.log('Anexo selecionado:', anexo);

                if (!anexo || !anexo.id) {
                    console.error('Anexo inválido!');
                    alert('Erro: anexo não encontrado');
                    return;
                }

                if (confirm('Remover arquivo?')) {
                    fetch(`{{ url('/anexos') }}/${anexo.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => {
                            console.log('Status da resposta:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Resposta completa:', data);
                            if (data.success) {
                                anexosCarregados.splice(index, 1);
                                mostrarAnexos();
                                alert('Removido com sucesso!');
                            } else {
                                alert('Erro: ' + data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erro na requisição:', error);
                            alert('Erro de conexão');
                        });
                }
            }
        });
    </script>
@endpush
