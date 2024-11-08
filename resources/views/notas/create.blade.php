<!-- resources/views/notas/create.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nota</title>
</head>
<body>
    <h1>Criar uma nova nota</h1>
    <form action="{{ route('notas.store') }}" method="POST">
        @csrf
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" id="titulo" required>
        <br>
        <label for="conteudo">Conteúdo:</label>
        <textarea name="conteudo" id="conteudo" required></textarea>
        <br>
        <button type="submit">Salvar</button>
    </form>
</body>
</html>
