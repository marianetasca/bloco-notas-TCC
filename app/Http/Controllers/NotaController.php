<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\Categoria;
use App\Models\Nota;
use App\Models\Prioridade;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotaController extends Controller
{
    protected $anexoController;

    public function __construct()
    {
        $this->anexoController = new AnexoController();
    }

    public function index(Request $request)
    {
        $highlightId = $request->get('highlight');


        $notasExcluidasCount = Nota::onlyTrashed()
            ->where('user_id', auth()->id())
            ->count();

        $query = Nota::with(['categoria', 'tags', 'prioridade'])
            ->where('user_id', auth()->id());

        // Filtro por categoria
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // Filtro por prioridade
        if ($request->filled('prioridade')) {
            $query->where('prioridade_id', $request->prioridade);
        }

        // Filtro por tag
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Busca por título ou conteúdo
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q
                    ->where('titulo', 'like', "%{$busca}%")
                    ->orWhere('conteudo', 'like', "%{$busca}%")
                    // Busca nas tags
                    ->orWhereHas('tags', function ($q) use ($busca) {
                        $q->where('nome', 'like', "%{$busca}%");
                    })
                    // Busca nas categorias
                    ->orWhereHas('categoria', function ($q) use ($busca) {
                        $q->where('nome', 'like', "%{$busca}%");
                    })
                    // Busca por múltiplas palavras
                    ->orWhere(function ($q) use ($busca) {
                        foreach (explode(' ', $busca) as $palavra) {
                            if (strlen($palavra) >= 3) {  // ignora palavras muito curtas
                                $q
                                    ->orWhere('titulo', 'like', "%{$palavra}%")
                                    ->orWhere('conteudo', 'like', "%{$palavra}%");
                            }
                        }
                    });
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
        $categorias = Categoria::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        $prioridades = Prioridade::orderBy('nivel')->get();

        // Exemplo básico
        $notasPorPrioridade = Nota::where('user_id', auth()->id())
            ->select('prioridade_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('prioridade_id')
            ->with('prioridade')
            ->get();

        return view('notas.index', compact('notas', 'highlightId','categorias', 'tags', 'prioridades', 'notasPorPrioridade', 'notasExcluidasCount'));
    }

    /*======== Método create ========*/
    public function create()
    {
        $categorias = Categoria::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        $prioridades = Prioridade::orderBy('nivel')->get();

        return view('notas.create', compact('categorias', 'tags', 'prioridades'));
    }

    /*======== Método show ========*/
    // Mostrar a página de detalhes da nota com as tags associadas
    public function show($id)
    {
        $nota = Nota::with(['categoria', 'prioridade', 'tags', 'anexos'])->findOrFail($id);

        // Verifica permissão
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        return view('notas.show', compact('nota'));
    }

    /*======== Método store ========*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'data_vencimento' => 'nullable|date',
            'prioridade_id' => 'required|exists:prioridades,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'anexos_temp' => 'array|nullable', // IDs dos anexos temporários
            'anexos_temp.*' => 'integer'
        ]);

        // Cria a nota no banco com os dados validados
        $nota = Nota::create([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'data_vencimento' => $validated['data_vencimento'],
            'prioridade_id' => $validated['prioridade_id'],
            'user_id' => auth()->id()
        ]);

        // Associar tags se existirem
        if (!empty($validated['tags'])) {
            $nota->tags()->attach($validated['tags']);
        }

        // Associar anexos temporários à nota criada usando o AnexoController
        if (!empty($validated['anexos_temp'])) {
            $this->anexoController->associarANota($nota->id, $validated['anexos_temp']);
        }

        return redirect()->route('notas.index')->with('success', 'Nota criada com sucesso!');
    }

    /*======== Método update ========*/
    public function update(Request $request, Nota $nota)
    {
        // Verifica permissão
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        // Validação
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridade_id' => 'required|exists:prioridades,id',
            'data_vencimento' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'anexos_temp' => 'array|nullable',
            'anexos_temp.*' => 'integer',
            'anexos_removidos' => 'array|nullable', // IDs dos anexos a serem removidos
            'anexos_removidos.*' => 'integer'
        ]);

        // Atualiza a nota
        $nota->update([
            'titulo' => $validated['titulo'],
            'conteudo' => $validated['conteudo'],
            'categoria_id' => $validated['categoria_id'],
            'prioridade_id' => $validated['prioridade_id'],
            'data_vencimento' => $validated['data_vencimento'],
        ]);

        // Atualiza as tags
        if (isset($validated['tags'])) {
            $nota->tags()->sync($validated['tags']);
        } else {
            $nota->tags()->detach();
        }

        // Remove anexos marcados para remoção usando o AnexoController
        if (!empty($validated['anexos_removidos'])) {
            $this->anexoController->removerAnexosIds($validated['anexos_removidos'], $nota->id);
        }

        // Associar novos anexos temporários à nota usando o AnexoController
        if (!empty($validated['anexos_temp'])) {
            $this->anexoController->associarANota($nota->id, $validated['anexos_temp']);
        }

        return redirect()->route('notas.index')->with('success', 'Nota atualizada com sucesso!');
    }

    /*======== Método concluido ========*/
    public function concluido($id)
    {
        $nota = Nota::findOrFail($id);

        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $nota->concluido = !$nota->concluido;

        // Se marcou como concluída, salva o timestamp
        $nota->completed_at = $nota->concluido ? now() : null;

        $nota->save();

        return redirect()
            ->back()
            ->with('success', $nota->concluido ? 'Nota marcada como concluída!' : 'Nota marcada como pendente!');
    }

    /*======== Método edit ========*/
    public function edit(Nota $nota)
    {
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $categorias = Categoria::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        $prioridades = Prioridade::orderBy('nivel')->get();

        return view('notas.edit', compact('nota', 'categorias', 'tags', 'prioridades'));
    }

    /*======== Método destroy ========*/
    public function destroy(Nota $nota)
    {
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        $nota->delete();

        return redirect()
            ->route('notas.index')
            ->with('success', 'Nota movida para a lixeira.');
    }

    /*======== Método lixeira ========*/
    public function lixeira()
    {
        $notasExcluidas = Nota::onlyTrashed()
            ->where('user_id', auth()->id())
            ->with(['categoria', 'tags', 'prioridade'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('notas.lixeira', compact('notasExcluidas'));
    }

    /*======== Método restaurar ========*/
    public function restaurar($id)
    {
        $nota = Nota::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $nota->restore();

        return redirect()
            ->route('notas.lixeira')
            ->with('success', 'Nota restaurada com sucesso!');
    }

    /*======== Método excluirPermanentemente ========*/
    public function excluirPermanente($id)
    {
        $nota = Nota::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Exclui os anexos se existirem
        foreach ($nota->anexos as $anexo) {
            Storage::disk('public')->delete($anexo->caminho);
            $anexo->delete();
        }

        $nota->forceDelete();

        return redirect()
            ->route('notas.lixeira')
            ->with('success', 'Nota excluída permanentemente.');
    }
}
