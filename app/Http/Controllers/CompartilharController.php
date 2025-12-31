<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Anexo;
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
            // Salvar dados na sessão e redirecionar para login
            session([
                'compartilhamento_pendente' => [
                    'title' => $request->input('title'),
                    'text' => $request->input('text'),
                    'arquivo' => $request->file('arquivo')
                ]
            ]);
            
            return redirect()->route('login')
                ->with('info', 'Faça login para salvar o comprovante compartilhado.');
        }

        try {
            // Criar nova nota
            $nota = new Nota();
            $nota->user_id = Auth::id();
            
            // Definir título
            $titulo = $request->input('title', 'Comprovante compartilhado');
            if (empty($titulo)) {
                $titulo = 'Comprovante ' . now()->format('d/m/Y H:i');
            }
            $nota->titulo = $titulo;
            
            // Definir conteúdo se houver texto
            $texto = $request->input('text', '');
            if (!empty($texto)) {
                $nota->conteudo = $texto;
            }
            
            $nota->save();

            // Processar arquivo anexado
            if ($request->hasFile('arquivo')) {
                $arquivo = $request->file('arquivo');
                
                // Validar arquivo
                $request->validate([
                    'arquivo' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx'
                ]);
                
                // Salvar arquivo
                $nomeOriginal = $arquivo->getClientOriginalName();
                $caminhoArquivo = $arquivo->store('anexos', 'public');
                
                // Criar registro de anexo
                $anexo = new Anexo();
                $anexo->nota_id = $nota->id;
                $anexo->nome_arquivo = $nomeOriginal;
                $anexo->caminho_arquivo = $caminhoArquivo;
                $anexo->tipo_arquivo = $arquivo->getMimeType();
                $anexo->tamanho = $arquivo->getSize();
                $anexo->save();
            }

            return redirect()->route('notas.show', $nota->id)
                ->with('success', 'Comprovante salvo com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('notas.index')
                ->with('error', 'Erro ao salvar comprovante: ' . $e->getMessage());
        }
    }

    /**
     * Processar compartilhamento pendente após login
     */
    public function processarCompartilhamentoPendente()
    {
        if (!session()->has('compartilhamento_pendente')) {
            return redirect()->route('notas.index');
        }

        $dados = session('compartilhamento_pendente');
        session()->forget('compartilhamento_pendente');

        try {
            // Criar nova nota
            $nota = new Nota();
            $nota->user_id = Auth::id();
            $nota->titulo = $dados['title'] ?? 'Comprovante compartilhado';
            $nota->conteudo = $dados['text'] ?? '';
            $nota->save();

            // Se houver arquivo, processar
            if (isset($dados['arquivo']) && $dados['arquivo']) {
                // Processar arquivo...
            }

            return redirect()->route('notas.show', $nota->id)
                ->with('success', 'Comprovante salvo com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()->route('notas.index')
                ->with('error', 'Erro ao processar compartilhamento.');
        }
    }
}
