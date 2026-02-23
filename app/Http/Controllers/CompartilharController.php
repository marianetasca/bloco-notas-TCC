<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Anexo;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CompartilharController extends Controller
{
    public function receberCompartilhamento(Request $request)
    {
        Log::info('Compartilhamento recebido', [
            'has_file' => $request->hasFile('arquivo'),
            'title' => $request->input('title'),
        ]);

        if (!Auth::check()) {
            if ($request->hasFile('arquivo')) {
                $arquivo = $request->file('arquivo');
                $nomeTemp = 'temp_' . time() . '_' . $arquivo->getClientOriginalName();
                $caminhoTemp = $arquivo->storeAs('temp', $nomeTemp, 'public');
                
                session([
                    'compartilhamento_pendente' => true,
                    'compartilhamento_title' => $request->input('title'),
                    'compartilhamento_text' => $request->input('text'),
                    'compartilhamento_arquivo_temp' => $caminhoTemp,
                    'compartilhamento_arquivo_nome' => $arquivo->getClientOriginalName(),
                ]);
            }
            
            return redirect()->route('login')
                ->with('info', 'Faça login para salvar o comprovante.');
        }

        // Salvar arquivo ANTES de redirecionar
        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');
            $nomeTemp = 'temp_' . time() . '_' . $arquivo->getClientOriginalName();
            $caminhoTemp = $arquivo->storeAs('temp', $nomeTemp, 'public');
            
            Log::info('Arquivo salvo', ['caminho' => $caminhoTemp]);
            
            session([
                'compartilhamento_arquivo_temp' => $caminhoTemp,
                'compartilhamento_arquivo_nome' => $arquivo->getClientOriginalName(),
            ]);
        }

        return redirect()->route('compartilhar.opcoes');
    }

    public function mostrarOpcoes()
    {
        $categorias = Categoria::where('user_id', Auth::id())->get();
        $notas = Nota::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->limit(50)
            ->get();

        if ($categorias->isEmpty()) {
            $categoria = Categoria::create([
                'user_id' => Auth::id(),
                'nome' => 'Geral'
            ]);
            $categorias = collect([$categoria]);
        }

        return view('compartilhar.escolher-opcao', compact('categorias', 'notas'));
    }

    public function salvarNovaNota(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:150',
            'conteudo' => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,id',
            'prioridade_id' => 'required|exists:prioridades,id',
        ]);

        try {
            $nota = new Nota();
            $nota->user_id = Auth::id();
            $nota->titulo = $validated['titulo'];
            $nota->conteudo = $validated['conteudo'] ?? 'Comprovante compartilhado via app.';
            $nota->categoria_id = $validated['categoria_id'];
            $nota->prioridade_id = $validated['prioridade_id'];
            $nota->save();

            Log::info('Nota criada', ['nota_id' => $nota->id]);

            if (session()->has('compartilhamento_arquivo_temp')) {
                $this->anexarArquivo($nota->id);
            }

            $this->limparSessao();

            // CORRIGIDO: redirecionar para notas.index sem ID
            return redirect()->route('notas.index')
                ->with('success', 'Comprovante salvo com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao salvar', ['erro' => $e->getMessage()]);
            
            return redirect()->route('notas.index')
                ->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function anexarExistente(Request $request)
    {
        $validated = $request->validate([
            'nota_id' => 'required|exists:notas,id',
        ]);

        try {
            $nota = Nota::where('id', $validated['nota_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();

            if (session()->has('compartilhamento_arquivo_temp')) {
                $this->anexarArquivo($nota->id);
            }

            $this->limparSessao();

            return redirect()->route('notas.index')
                ->with('success', 'Comprovante anexado com sucesso!');
                
        } catch (\Exception $e) {
            Log::error('Erro ao anexar', ['erro' => $e->getMessage()]);
            
            return redirect()->route('notas.index')
                ->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    private function anexarArquivo($notaId)
    {
        try {
            $caminhoTemp = session('compartilhamento_arquivo_temp');
            $nomeOriginal = session('compartilhamento_arquivo_nome');

            Log::info('Anexando', [
                'temp' => $caminhoTemp,
                'nome' => $nomeOriginal,
                'nota' => $notaId,
                'exists' => Storage::disk('public')->exists($caminhoTemp),
            ]);

            if (!$caminhoTemp || !Storage::disk('public')->exists($caminhoTemp)) {
                Log::warning('Arquivo não encontrado');
                return;
            }

            $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
            $nomeUnico = uniqid() . '.' . $extensao;
            $novoCaminho = 'anexos/' . $nomeUnico;

            Storage::disk('public')->copy($caminhoTemp, $novoCaminho);
            Storage::disk('public')->delete($caminhoTemp);

            Log::info('Arquivo copiado', ['para' => $novoCaminho]);

            $caminhoCompleto = Storage::disk('public')->path($novoCaminho);
            
            $anexo = new Anexo();
            $anexo->nota_id = $notaId;
            $anexo->nome_arquivo = $nomeOriginal;
            $anexo->caminho_arquivo = $novoCaminho;
            $anexo->tipo_arquivo = mime_content_type($caminhoCompleto);
            $anexo->tamanho = filesize($caminhoCompleto);
            $anexo->save();

            Log::info('Anexo salvo!', ['id' => $anexo->id]);

        } catch (\Exception $e) {
            Log::error('Erro anexo', [
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
            ]);
        }
    }

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