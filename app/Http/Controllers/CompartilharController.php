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
        // Log básico para debug — registra headers, cookies e se há arquivo
        \Log::info('receberCompartilhamento called', [
            'path' => $request->path(),
            'method' => $request->method(),
            'headers' => [
                'content-type' => $request->header('content-type'),
                'cookie' => $request->header('cookie'),
                'user-agent' => $request->header('user-agent')
            ],
            'has_file' => $request->hasFile('arquivo'),
        ]);

        // Verificar se usuário está autenticado
        if (!Auth::check()) {
            // Salvar dados na sessão e redirecionar para login
            // IMPORTANTE: não armazenamos o UploadedFile diretamente na sessão (não serializável).
            // Em vez disso, vamos salvar o arquivo temporariamente em 'public/compartilhamentos_tmp'
            $data = [
                'title' => $request->input('title'),
                'text' => $request->input('text'),
                'arquivo_path' => null,
                'arquivo_original_name' => null,
                'arquivo_mime' => null,
                'arquivo_size' => null,
            ];

            if ($request->hasFile('arquivo')) {
                // Validar arquivo antes de salvar temporariamente
                $request->validate([
                    'arquivo' => 'required|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx'
                ]);

                $file = $request->file('arquivo');
                // Salva no disco 'public' em pasta temporária
                $path = $file->store('compartilhamentos_tmp', 'public');

                $data['arquivo_path'] = $path;
                $data['arquivo_original_name'] = $file->getClientOriginalName();
                $data['arquivo_mime'] = $file->getMimeType();
                $data['arquivo_size'] = $file->getSize();
            }

            session([
                'compartilhamento_pendente' => $data
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

                // Salvar arquivo no disco público (em pasta do usuário)
                $caminhoArquivo = $arquivo->store('anexos/' . Auth::id(), 'public');
                $nomeOriginal = $arquivo->getClientOriginalName();

                // Criar registro de anexo (compatível com o modelo Anexo)
                $anexo = new Anexo();
                $anexo->nota_id = $nota->id;
                $anexo->user_id = Auth::id();
                $anexo->nome_original = $nomeOriginal;
                $anexo->caminho = $caminhoArquivo;
                $anexo->tipo_mime = $arquivo->getMimeType();
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

            // Se houver arquivo temporário, mover para pasta do usuário e criar anexo
            if (!empty($dados['arquivo_path']) && Storage::disk('public')->exists($dados['arquivo_path'])) {
                $oldPath = $dados['arquivo_path'];
                $filename = basename($oldPath);
                $newPath = 'anexos/' . Auth::id() . '/' . $filename;

                // Mover para pasta final
                Storage::disk('public')->move($oldPath, $newPath);

                // Criar registro de anexo
                $anexo = new Anexo();
                $anexo->nota_id = $nota->id;
                $anexo->user_id = Auth::id();
                $anexo->nome_original = $dados['arquivo_original_name'] ?? $filename;
                $anexo->caminho = $newPath;
                $anexo->tipo_mime = $dados['arquivo_mime'] ?? Storage::disk('public')->mimeType($newPath);
                $anexo->tamanho = $dados['arquivo_size'] ?? Storage::disk('public')->size($newPath);
                $anexo->save();
            }

            return redirect()->route('notas.show', $nota->id)
                ->with('success', 'Comprovante salvo com sucesso!');

        } catch (\Exception $e) {
            return redirect()->route('notas.index')
                ->with('error', 'Erro ao processar compartilhamento.');
        }
    }
}
