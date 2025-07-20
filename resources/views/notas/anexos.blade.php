@extends('layouts.app')

@section('slot')
<div class="container mt-5">
    <h3 class="mb-4">Anexar arquivos à nota: <strong>{{ $nota->titulo }}</strong></h3>

    <form action="{{ route('notas.upload', $nota->id) }}" method="POST" class="dropzone" id="anexo-dropzone" enctype="multipart/form-data">
        @csrf
        <div class="dz-message">
            Arraste e solte os arquivos aqui ou clique para selecionar.
        </div>
    </form>

    <div class="mt-4">
        <a href="{{ route('notas.index') }}" class="btn btn-secondary">Voltar às notas</a>
    </div>
</div>
@endsection

@section('scripts')
<!-- Dropzone.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<script>
Dropzone.options.anexoDropzone = {
    paramName: 'file', // O nome do campo no FormData
    maxFilesize: 5, // MB
    acceptedFiles: '.jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt',
    timeout: 5000,
    dictDefaultMessage: 'Arraste os arquivos ou clique aqui',
    success: function (file, response) {
        console.log('Arquivo enviado com sucesso', response);
    },
    error: function (file, response) {
        console.error('Erro ao enviar:', response);
    }
};
</script>
@endsection
