@extends('layouts.app')

@section('slot')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">📎 Comprovante Compartilhado</h4>
                </div>
                <div class="card-body">
                    
                    @if(session('arquivo_temp'))
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Arquivo recebido: <strong>{{ session('arquivo_nome') }}</strong>
                        </div>
                    @endif

                    <p class="mb-4">Como você deseja salvar este comprovante?</p>

                    {{-- Opção 1: Nova Nota --}}
                    <div class="card mb-3 border-primary" style="cursor: pointer;" onclick="document.getElementById('opcao-nova').click()">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="opcao" id="opcao-nova" value="nova" checked>
                                <label class="form-check-label w-100" for="opcao-nova">
                                    <h5 class="mb-2">📝 Criar Nova Nota</h5>
                                    <p class="text-muted mb-0">Criar uma nova nota com este comprovante anexado</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Opção 2: Anexar a Nota Existente --}}
                    <div class="card border-success" style="cursor: pointer;" onclick="document.getElementById('opcao-existente').click()">
                        <div class="card-body">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="opcao" id="opcao-existente" value="existente">
                                <label class="form-check-label w-100" for="opcao-existente">
                                    <h5 class="mb-2">📌 Anexar a Nota Existente</h5>
                                    <p class="text-muted mb-0">Adicionar este comprovante a uma nota que já existe</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Formulário de Nova Nota --}}
                    <div id="form-nova" class="mt-4">
                        <hr>
                        <h5 class="mb-3">Criar Nova Nota</h5>
                        <form action="{{ route('compartilhar.salvar-nova') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Nota</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="{{ old('titulo', 'Comprovante ' . now()->format('d/m/Y')) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="conteudo" class="form-label">Descrição (opcional)</label>
                                <textarea class="form-control" id="conteudo" name="conteudo" rows="3">{{ old('conteudo') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="categoria_id" class="form-label">Categoria</label>
                                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                                        @foreach($categorias as $categoria)
                                            <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="prioridade_id" class="form-label">Prioridade</label>
                                    <select class="form-select" id="prioridade_id" name="prioridade_id" required>
                                        <option value="1">Baixa</option>
                                        <option value="2" selected>Média</option>
                                        <option value="3">Alta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Criar e Salvar
                                </button>
                                <a href="{{ route('notas.index') }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Formulário de Anexar a Existente --}}
                    <div id="form-existente" class="mt-4" style="display: none;">
                        <hr>
                        <h5 class="mb-3">Anexar a Nota Existente</h5>
                        <form action="{{ route('compartilhar.anexar-existente') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="nota_id" class="form-label">Selecione a Nota</label>
                                <select class="form-select" id="nota_id" name="nota_id" required>
                                    <option value="">Escolha uma nota...</option>
                                    @foreach($notas as $nota)
                                        <option value="{{ $nota->id }}">
                                            {{ $nota->titulo }} 
                                            @if($nota->categoria)
                                                ({{ $nota->categoria->nome }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-paperclip"></i> Anexar à Nota
                                </button>
                                <a href="{{ route('notas.index') }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Alternar entre formulários
    document.querySelectorAll('input[name="opcao"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'nova') {
                document.getElementById('form-nova').style.display = 'block';
                document.getElementById('form-existente').style.display = 'none';
            } else {
                document.getElementById('form-nova').style.display = 'none';
                document.getElementById('form-existente').style.display = 'block';
            }
        });
    });
</script>
@endsection
