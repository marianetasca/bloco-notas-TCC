<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Tag;

use Illuminate\Http\Request;

class NotaController extends Controller
{

    public function index()
    {
        $notas = Nota::paginate(10);

        return view('notas.index', compact('notas'));
    }

    public function create()
    {
        return view('notas.create');
    }

    // Mostrar a página de detalhes da nota com as tags associadas
    public function show($id)
    {
        // Usando findOrFail para buscar a nota ou retornar erro 404
        $nota = Nota::with('tags')->findOrFail($id);

        return view('notas.show', compact('nota'));
    }

    public function store(Request $request)
    {


        // Validação dos dados
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'array|exists:tags,id',
        ]);

        // Criar a nova nota associada ao usuário autenticado
        $nota = Nota::create([
            'user_id' => $validated['user_id'],
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
        ]);

        // Associar as tags
        if (isset($validated['tags'])) {
            $nota->tags()->attach($validated['tags']);
        }

        return redirect()->route('notas.index')->with('success', 'Nota criada com sucesso.');
    }





    public function update(Request $request, Nota $nota)
    {
        // Validação dos dados
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'array|exists:tags,id', // Assumindo que você vai passar os IDs das tags no campo 'tags'
        ]);

        // Atualizar os dados da nota
        $nota->update([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
        ]);

        // Atualizar as tags associadas
        if (isset($validated['tags'])) {
            $nota->tags()->sync($validated['tags']); // Sincroniza as tags (remove as antigas e adiciona as novas)
        }

        return redirect()->route('notas.index')->with('success', 'Nota atualizada com sucesso.');
    }


    public function destroy(Nota $nota)
    {
        $nota->delete();

        return redirect()->route('notas.index')
            ->with('success', 'Nota excluída com sucesso.');
    }

}
