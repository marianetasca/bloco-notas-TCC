<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\Prioridade;
use Illuminate\Support\Facades\Log; // Adicione esta linha
use Illuminate\Http\Request;

class NotaController extends Controller
{

    public function index()
    {
        $notas = Nota::where('user_id', auth()->id())->get();

        return view('notas.index', compact('notas'));
    }


    public function create()
    {
        $categorias = Categoria::all();
        $tags = Tag::all();
        $prioridades = Prioridade::orderBy('nivel')->get();

        return view('notas.create', compact('categorias', 'tags', 'prioridades'));
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
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridade_id' => 'nullable|exists:prioridades,id',
            'data_entrega' => 'nullable|date',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id'
        ]);

        $nota = Nota::create([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'prioridade_id' => $validated['prioridade_id'],
            'data_entrega' => $validated['data_entrega'],
            'user_id' => auth()->id()
        ]);

        if ($request->has('tags')) {
            $nota->tags()->attach($request->tags);
        }

        return redirect()->route('notas.index')
            ->with('success', 'Nota criada com sucesso.');
    }








    public function update(Request $request, Nota $nota)
    {
        // Validação dos dados
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        // Verifica se o usuário é dono da nota
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        // Atualiza a nota
        $nota->titulo = $validated['titulo'];
        $nota->conteudo = $validated['conteudo'];
        $nota->categoria_id = $validated['categoria_id'];
        $nota->save();

        return redirect()->route('notas.index')
            ->with('success', 'Nota atualizada com sucesso.');
    }


    public function destroy(Nota $nota)
    {
        $nota->delete();

        return redirect()->route('notas.index')
            ->with('success', 'Nota excluída com sucesso.');
    }

    public function complete($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->concluido = true;
        $nota->save();

        return redirect()->route('notas.index')->with('success', 'Anotação marcada como concluída.');
    }
    public function edit(Nota $nota)
    {
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $categorias = Categoria::all();
        $tags = Tag::all();
        return view('notas.edit', compact('nota', 'categorias', 'tags'));
    }
}
