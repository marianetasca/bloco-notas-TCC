<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Categoria;
use App\Models\Tag;
use App\Models\Prioridade;
use App\Models\Anexo;
use Illuminate\Support\Facades\Log; // Adicione esta linha
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'data_vencimento' => 'nullable|date',
            'prioridade' => 'required|in:baixa,media,alta',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'anexos' => 'array|nullable', // Validação para array de arquivos
            'anexos.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB max por arquivo
        ]);

        $nota = Nota::create([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'data_vencimento' => $validated['data_vencimento'],
            'prioridade' => $validated['prioridade'],
            'user_id' => auth()->id()
        ]);

        if ($request->has('tags')) {
            $nota->tags()->attach($request->tags);
        }

        // Processamento dos anexos
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $arquivo) {
                $caminho = $arquivo->store('anexos/' . auth()->id(), 'public');

                $nota->anexos()->create([
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho' => $caminho,
                    'tipo_mime' => $arquivo->getMimeType(),
                    'tamanho' => $arquivo->getSize()
                ]);
            }
        }

        return redirect()->route('notas.index')->with('success', 'Nota criada com sucesso!');
    }








    public function update(Request $request, Nota $nota)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'data_vencimento' => 'nullable|date',
            'prioridade' => 'required|in:baixa,media,alta',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'anexos' => 'array|nullable', // Validação para array de arquivos
            'anexos.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB max por arquivo
        ]);

        $nota->update([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'data_vencimento' => $validated['data_vencimento'],
            'prioridade' => $validated['prioridade']
        ]);

        if ($request->has('tags')) {
            $nota->tags()->sync($request->tags);
        }

        // Processamento dos novos anexos
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $arquivo) {
                $caminho = $arquivo->store('anexos/' . auth()->id(), 'public');

                $nota->anexos()->create([
                    'nome_original' => $arquivo->getClientOriginalName(),
                    'caminho' => $caminho,
                    'tipo_mime' => $arquivo->getMimeType(),
                    'tamanho' => $arquivo->getSize()
                ]);
            }
        }

        return redirect()->route('notas.index')->with('success', 'Nota atualizada com sucesso!');
    }


    public function destroy(Nota $nota)
    {
        $nota->delete();

        return redirect()->route('notas.index')
            ->with('success', 'Nota excluída com sucesso!');
    }

    public function complete($id)
    {
        $nota = Nota::findOrFail($id);

        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $nota->concluido = !$nota->concluido;
        $nota->save();

        return redirect()->back()
            ->with('success', $nota->concluido ? 'Nota marcada como concluída!' : 'Nota marcada como pendente!');
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

    public function destroyAnexo(Nota $nota, Anexo $anexo)
    {
        // Verifica se o anexo pertence à nota
        if ($anexo->nota_id !== $nota->id) {
            abort(403);
        }

        // Verifica se o usuário é dono da nota
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        // Remove o arquivo do storage
        Storage::disk('public')->delete($anexo->caminho);

        // Remove o registro do banco de dados
        $anexo->delete();

        return back()->with('success', 'Anexo removido com sucesso!');
    }
}
