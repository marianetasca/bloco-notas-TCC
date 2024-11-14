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
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // Busca por título ou conteúdo
        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function($q) use ($busca) {
                $q->where('titulo', 'like', "%{$busca}%")
                  ->orWhere('conteudo', 'like', "%{$busca}%")
                  // Busca nas tags
                  ->orWhereHas('tags', function($q) use ($busca) {
                      $q->where('nome', 'like', "%{$busca}%");
                  })
                  // Busca nas categorias
                  ->orWhereHas('categoria', function($q) use ($busca) {
                      $q->where('nome', 'like', "%{$busca}%");
                  })
                  // Busca por múltiplas palavras
                  ->orWhere(function($q) use ($busca) {
                      foreach(explode(' ', $busca) as $palavra) {
                          if(strlen($palavra) >= 3) { // ignora palavras muito curtas
                              $q->orWhere('titulo', 'like', "%{$palavra}%")
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

        return view('notas.index', compact('notas', 'categorias', 'tags', 'prioridades', 'notasPorPrioridade', 'notasExcluidasCount'));
    }


    public function create()
    {
        $categorias = Categoria::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        $prioridades = Prioridade::orderBy('nivel')->get();

        return view('notas.create', compact('categorias', 'tags', 'prioridades'));
    }


    // Mostrar a página de detalhes da nota com as tags associadas
    public function show($id)
    {
        // Usando findOrFail para buscar a nota ou retornar erro 404
        $nota = Nota::with('tags')
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('notas.show', compact('nota'));
    }

    public function store(Request $request)
    {
        // Adicione este log para ver os dados recebidos
        Log::info('Dados recebidos:', $request->all());

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'data_vencimento' => 'nullable|date',
            'prioridade_id' => 'required|exists:prioridades,id',
            'tags' => 'array|nullable',
            'tags.*' => 'exists:tags,id',
            'anexos' => 'array|nullable',
            'anexos.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        try {
            $nota = Nota::create([
                'titulo' => $request->titulo,
                'conteudo' => $request->conteudo,
                'categoria_id' => $request->categoria_id,
                'data_vencimento' => $request->data_vencimento,
                'prioridade_id' => $request->prioridade_id,
                'user_id' => auth()->id()
            ]);

            if ($request->has('tags')) {
                $nota->tags()->attach($request->tags);
            }

            if ($request->hasFile('anexos')) {
                foreach ($request->file('anexos') as $arquivo) {
                    $caminho = $arquivo->store('anexos/' . auth()->id(), 'public');

                    $nota->anexos()->create([
                        'nome_original' => $arquivo->getClientOriginalName(),
                        'caminho' => $caminho,
                        'tipo_mime' => $arquivo->getMimeType(),
                        'tamanho' => $arquivo->getSize(),
                        'user_id' => auth()->id()
                    ]);
                }
            }

            return redirect()
                ->route('notas.index')
                ->with('success', 'Nota criada com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao criar nota', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile()
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar nota: ' . $e->getMessage()]);
        }
    }








    public function update(Request $request, Nota $nota)
    {
        dd('Chegou no update', $request->all(), $nota);

        // Adicione este log para debug
        Log::info('Método update chamado', ['request' => $request->all()]);

        // Verifica permissão
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        // Validação
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'conteudo' => 'required|string',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridade_id' => 'required|exists:prioridades,id',
            'data_vencimento' => 'nullable|date',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'anexos.*' => 'nullable|file|max:10240'
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

        // Processa os anexos
        if ($request->hasFile('anexos')) {
            foreach ($request->file('anexos') as $anexo) {
                $path = $anexo->store('anexos', 'public');
                $nota->anexos()->create([
                    'nome_original' => $anexo->getClientOriginalName(),
                    'caminho' => $path,
                    'tipo' => $anexo->getClientMimeType(),
                    'tamanho' => $anexo->getSize(),
                ]);
            }
        }

        return redirect()->route('notas.index')->with('success', 'Nota atualizada com sucesso!');
    }


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

        $categorias = Categoria::where('user_id', auth()->id())->get();
        $tags = Tag::where('user_id', auth()->id())->get();
        $prioridades = Prioridade::orderBy('nivel')->get();

        return view('notas.edit', compact('nota', 'categorias', 'tags', 'prioridades'));
    }

    public function destroyAnexo(Nota $nota, Anexo $anexo)
    {
        try {
            Storage::disk('public')->delete($anexo->caminho);
            $anexo->delete();
            return back()->with('success', 'Anexo removido com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover anexo.');
        }
    }

    public function lixeira()
    {
        $notasExcluidas = Nota::onlyTrashed()
            ->where('user_id', auth()->id())
            ->with(['categoria', 'tags', 'prioridade'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('notas.lixeira', compact('notasExcluidas'));
    }

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

    public function excluirPermanente($id)
    {
        $nota = Nota::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        // Exclui os anexos se existirem
        foreach($nota->anexos as $anexo) {
            Storage::disk('public')->delete($anexo->caminho);
            $anexo->delete();
        }

        $nota->forceDelete();

        return redirect()
            ->route('notas.lixeira')
            ->with('success', 'Nota excluída permanentemente.');
    }

}
