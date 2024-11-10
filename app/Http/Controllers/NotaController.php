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
        $notas = Nota::with(['categoria', 'tags'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        // Debug para verificar as tags de cada nota
        foreach ($notas as $nota) {
            Log::info("Nota {$nota->id}:", [
                'titulo' => $nota->titulo,
                'tags' => $nota->tags->pluck('nome')->toArray()
            ]);
        }

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
        // Log inicial dos dados recebidos
        Log::info('Dados recebidos:', [
            'request' => $request->all(),
            'tags' => $request->input('tags', [])
        ]);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id'
        ]);

        // Criar a nota
        $nota = Nota::create([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'user_id' => auth()->id()
        ]);

        // Anexar tags
        if ($request->has('tags')) {
            $tags = $request->input('tags', []);

            // Log antes de anexar as tags
            Log::info('Tentando anexar tags:', [
                'nota_id' => $nota->id,
                'tags' => $tags
            ]);

            try {
                // Usar attach em vez de sync para novas notas
                $nota->tags()->attach($tags);

                // Verificar se as tags foram anexadas
                $notaAtualizada = Nota::with('tags')->find($nota->id);
                Log::info('Tags anexadas com sucesso:', [
                    'nota_id' => $nota->id,
                    'tags' => $notaAtualizada->tags->pluck('nome', 'id')->toArray()
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao anexar tags:', [
                    'nota_id' => $nota->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return redirect()->route('notas.index')
            ->with('success', 'Nota criada com sucesso.');
    }








    public function update(Request $request, Nota $nota)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id'
        ]);

        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $nota->update([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
        ]);

        // Atualizar tags
        $tags = $request->input('tags', []);
        $nota->tags()->sync($tags);

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
