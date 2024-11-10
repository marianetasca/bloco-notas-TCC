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

    public function index(Request $request)
    {
        $query = Nota::with(['categoria', 'tags'])
            ->where('user_id', auth()->id());

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Busca por título ou conteúdo
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                  ->orWhere('conteudo', 'like', "%{$busca}%");
            });
        }

        // Status (concluído/pendente)
        if ($request->filled('status')) {
            $query->where('concluido', $request->status === 'concluido');
        }

        // Ordenação
        $ordem = $request->input('ordem', 'desc');
        $query->orderBy('created_at', $ordem);

        // Paginação (10 itens por página)
        $notas = $query->paginate(10)->withQueryString();
        $categorias = Categoria::all();
        $tags = Tag::all();

        return view('notas.index', compact('notas', 'categorias', 'tags'));
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
        // Log dos dados recebidos
        Log::info('Dados recebidos:', $request->all());

        try {
            $validated = $request->validate([
                'titulo' => 'required|string|max:255',
                'conteudo' => 'required|string',
                'categoria_id' => 'required|exists:categorias,id',
                'data_vencimento' => 'nullable|date',
                'prioridade' => 'required|in:baixa,media,alta',
                'tags' => 'array|nullable',
                'tags.*' => 'exists:tags,id'
            ]);

            // Log dos dados validados
            Log::info('Dados validados:', $validated);

            $nota = Nota::create([
                'titulo' => $validated['titulo'],
                'conteudo' => $validated['conteudo'],
                'categoria_id' => $validated['categoria_id'],
                'data_vencimento' => $validated['data_vencimento'] ?? null,
                'prioridade' => $validated['prioridade'],
                'user_id' => auth()->id()
            ]);

            // Log da nota criada
            Log::info('Nota criada:', $nota->toArray());

            if ($request->has('tags')) {
                $nota->tags()->attach($request->tags);
                // Log das tags anexadas
                Log::info('Tags anexadas:', $request->tags);
            }

            return redirect()->route('notas.index')
                ->with('success', 'Nota criada com sucesso.');

        } catch (\Exception $e) {
            // Log de erro
            Log::error('Erro ao criar nota:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar nota: ' . $e->getMessage()]);
        }
    }








    public function update(Request $request, Nota $nota)
    {
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'data_vencimento' => 'nullable|date',
            'prioridade' => 'required|in:baixa,media,alta',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id'
        ]);

        $nota->update([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'data_vencimento' => $validated['data_vencimento'],
            'prioridade' => $validated['prioridade']
        ]);

        $nota->tags()->sync($request->input('tags', []));

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

        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $nota->concluido = !$nota->concluido;
        $nota->save();

        return redirect()->route('notas.index')
            ->with('success', 'Status da nota atualizado com sucesso.');
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
