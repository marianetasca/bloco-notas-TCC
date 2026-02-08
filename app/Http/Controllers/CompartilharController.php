<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Anexo;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompartilharController extends Controller
{
    /**
     * Recebe arquivos compartilhados de outros apps
     */
    public function receberCompartilhamento(Request $request)
    {
        // Verificar se usuário está autenticado
        if (!Auth::check()) {
            // Salvar na sessão para processar após login
            session([
                'compartilhamento_pendente' => true,
                'compartilhamento_title' => $request->input('title'),
                'compartilhamento_text' => $request->input('text'),
            ]);
            
            // Se tiver arquivo, salvar temporariamente
            if ($request->hasFile('arquivo')) {
                $arquivo = $request->file('arquivo');
                $caminhoTemp = $arquivo->store('temp', 'public');
                
                session([
                    'compartilhamento_arquivo_temp' => $caminhoTemp,
                    'compartilhamento_arquivo_nome' => $arquivo->getClientOriginalName(),
                ]);
            }
            
            return redirect()->route('login')
                ->with('info', 'Faça login para salvar o comprovante compartilhado.');
        }

        // Se já está autenticado, processar compartilhamento
        return $this->mostrarOpcoes($request);
    }

    /**
     * Mostra opções de como salvar o comprovante
     */
    public function mostrarOpcoes(Request $request)
    {
        // Se tiver arquivo, salvar temporariamente
        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');
            $caminhoTemp = $arquivo->store('temp', 'public');
            
            session([
                'compartilhamento_arquivo_temp' => $caminhoTemp,
                'compartilhamento_arquivo_nome' => $arquivo->getClientOriginalName(),
                'compartilhamento_title' => $request->input('title'),
                'compartilhamento_text' => $request->input('text'),
            ]);
        }

        // Buscar categorias e notas do usuário
        $categorias = Categoria::where('user_id', Auth::id())->get();
        $notas = Nota::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        // Se não tem categorias, criar uma padrão
        if ($categorias->isEmpty()) {
            $categorias = collect([
                Categoria::create([
                    'user_id' => Auth::id(),
                    'nome' => 'Geral'
                ])
            ]);
        }

        return view('compartilhar.escolher-opcao', compact('categorias', 'notas'));
    }

    /**
     * Salvar em nova nota
     */
    public function salvarNovaNota(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'conteudo' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridade_id' => 'required|exists:prioridades,id',
        ]);

        try {
            // Criar nota
            $nota = new Nota();
            $nota->user_id = Auth::id();
            $nota->titulo = $validated['titulo'];
            $nota->conteudo = $validated['conteudo'] ?? 'Comprovante compartilhado via app.';
            $nota->categoria_id = $validated['categoria_id'];
            $nota->prioridade_id = $validated['prioridade_id'];
            $nota->save();

            // Processar arquivo temporário se existir
            if (session()->has('compartilhamento_arquivo_temp')) {
                $this->anexarArquivo($nota->id);
            }

            // Limpar sessão
            $this->limparSessao();

            return redirect()->route('notas.show', $nota->id)
                ->with('success', 'Comprovante salvo em nova nota com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('notas.index')
                ->with('error', 'Erro ao salvar comprovante: ' . $e->getMessage());
        }
    }

    /**
     * Anexar a nota existente
     */
    public function anexarExistente(Request $request)
    {
        $validated = $request->validate([
            'nota_id' => 'required|exists:notas,id',
        ]);

        try {
            // Verificar se a nota pertence ao usuário
            $nota = Nota::where('id', $validated['nota_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Processar arquivo temporário
            if (session()->has('compartilhamento_arquivo_temp')) {
                $this->anexarArquivo($nota->id);
            }

            // Limpar sessão
            $this->limparSessao();

            return redirect()->route('notas.show', $nota->id)
                ->with('success', 'Comprovante anexado à nota com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('notas.index')
                ->with('error', 'Erro ao anexar comprovante: ' . $e->getMessage());
        }
    }

    /**
     * Anexar arquivo à nota
     */
    private function anexarArquivo($notaId)
    {
        $caminhoTemp = session('compartilhamento_arquivo_temp');
        $nomeOriginal = session('compartilhamento_arquivo_nome');

        if (!$caminhoTemp || !Storage::disk('public')->exists($caminhoTemp)) {
            return;
        }

        // Mover arquivo da pasta temp para anexos
        $novoCaminho = str_replace('temp/', 'anexos/', $caminhoTemp);
        Storage::disk('public')->move($caminhoTemp, $novoCaminho);

        // Obter informações do arquivo
        $caminhoCompleto = Storage::disk('public')->path($novoCaminho);
        $tamanho = filesize($caminhoCompleto);
        $mimeType = mime_content_type($caminhoCompleto);

        // Criar registro de anexo
        $anexo = new Anexo();
        $anexo->nota_id = $notaId;
        $anexo->nome_arquivo = $nomeOriginal;
        $anexo->caminho_arquivo = $novoCaminho;
        $anexo->tipo_arquivo = $mimeType;
        $anexo->tamanho = $tamanho;
        $anexo->save();
    }

    /**
     * Limpar dados da sessão
     */
    private function limparSessao()
    {
        session()->forget([
            'compartilhamento_pendente',
            'compartilhamento_title',
            'compartilhamento_text',
            'compartilhamento_arquivo_temp',
            'compartilhamento_arquivo_nome',
        ]);
    }
}
