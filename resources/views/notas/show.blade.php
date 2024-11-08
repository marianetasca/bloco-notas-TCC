<h1>{{ $nota->titulo }}</h1>
<p>{{ $nota->conteudo }}</p>

<h3>Tags:</h3>
<ul>
    @foreach($nota->tags as $tag)
        <li>{{ $tag->nome }}</li>
    @endforeach
</ul>
