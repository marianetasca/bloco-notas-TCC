@extends('layouts.app')

@section('slot')
<div class="container py-5">
    <div class="card shadow">
        <div class="card-header">
            <h3 class="h5">Teste de /compartilhar-teste</h3>
        </div>
        <div class="card-body">
            <p>Use este formulário para enviar um POST com campos <code>title</code>, <code>text</code> e um arquivo <code>arquivo</code>.</p>

            <form method="POST" action="/compartilhar-teste" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input name="title" class="form-control" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Text</label>
                    <textarea name="text" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Arquivo</label>
                    <input type="file" name="arquivo" class="form-control" />
                </div>
                <button class="btn btn-primary">Enviar POST</button>
            </form>

            <hr />
            <p><strong>Nota:</strong> a rota de POST é sem CSRF para testes e gravará um log em <code>storage/logs/laravel.log</code> com a tag <code>compartilhar-teste received</code>.</p>
        </div>
    </div>
</div>
@endsection
