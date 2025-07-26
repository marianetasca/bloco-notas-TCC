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

            <div class="row g-4"> {{-- para ocupar a mesma linha --}}
                {{-- categoria --}}
                <div class="col-md-4 w-auto">
                    <label for="categoria_id" class="form-label">Categoria</label>
                    <select name="categoria_id" id="categoria_id" class="form-select" required>
                        <option value="">Selecione uma categoria</option>
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
            {{-- anexos --}}
            <div class="mb-3">
                <label for="anexo" class="form-label">Anexo</label>
                <input type="file" name="anexo" id="anexo" class="form-control">
                @if ($nota->anexo)
                    <p class="mt-2">Anexo atual: <a href="{{ asset('storage/' . $nota->anexo) }}"
                            target="_blank">{{ basename($nota->anexo) }}</a></p>
                @endif
            </div>


            <div class="text-end">
                <a href="{{ route('notas.index') }}" class="btn btn-secondary px-3">Voltar</a>
                <button type="submit" class="btn btn-primary-ed">
                    Atualizar Nota
                </button>
            </div>
        </form>
    </div>
@endsection
