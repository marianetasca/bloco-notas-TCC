<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AnexoController extends Controller
{
    /**
     * Upload de anexo via Dropzone
     */
    /** @var int $userId */

    /*======== Método upload ========*/
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // 10MB
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('anexos/' . auth()->id(), 'public');

            $anexo = Anexo::create([
                'user_id' => auth()->id(),
                'nota_id' => null,
                'nome_original' => $file->getClientOriginalName(),
                'caminho' => $path,
                'tipo_mime' => $file->getMimeType(),
                'tamanho' => $file->getSize(),
            ]);

            return response()->json([
                'success' => true,
                'anexo_id' => $anexo->id,
                'nome' => $anexo->nome_original,
                'url' => Storage::url($path),
                'tamanho' => number_format($anexo->tamanho / 1024, 2) . ' KB'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro no upload', ['erro' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erro no upload'
            ], 500);
        }
    }


    //Remove anexo temporário via Dropzone
    /*======== Método remove ========*/
    public function remove(Anexo $anexo)  // ← Laravel injeta automaticamente o anexo pela URL
    {
        try {
            Log::info('=== REMOVENDO ANEXO ===', [
                'anexo_id' => $anexo->id,
                'anexo_nome' => $anexo->nome_original,
                'user_id' => auth()->id()
            ]);

            // Verificar permissão - MUITO IMPORTANTE!
            if ($anexo->user_id !== auth()->id()) {
                Log::warning('Tentativa de remover anexo sem permissão', [
                    'anexo_id' => $anexo->id,
                    'anexo_user_id' => $anexo->user_id,
                    'current_user_id' => auth()->id()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Você não tem permissão para remover este anexo'
                ], 403);
            }

            Log::info('Anexo encontrado e permissão verificada:', [
                'anexo' => $anexo->toArray()
            ]);

            // Remove o arquivo
            if (Storage::disk('public')->exists($anexo->caminho)) {
                Storage::disk('public')->delete($anexo->caminho);
                Log::info('Arquivo removido do storage:', ['caminho' => $anexo->caminho]);
            } else {
                Log::warning('Arquivo não encontrado no storage:', ['caminho' => $anexo->caminho]);
            }

            // Remove do banco
            $anexo->delete();

            Log::info('Anexo removido com sucesso do banco de dados');

            return response()->json([
                'success' => true,
                'message' => 'Anexo removido com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao remover anexo:', [
                'anexo_id' => $anexo->id ?? 'N/A',
                'erro' => $e->getMessage(),
                'linha' => $e->getLine(),
                'arquivo' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /*======== Método destroy ========*/
    //Exclui anexo de uma nota específica
    public function destroy(Nota $nota, Anexo $anexo)
    {
        // Verifica permissão
        if ($nota->user_id !== auth()->id()) {
            abort(403);
        }

        if ($anexo->nota_id !== $nota->id) {
            return back()->with('error', 'Anexo não pertence a esta nota.');
        }

        try {
            // Remove o arquivo do storage
            Storage::disk('public')->delete($anexo->caminho);

            // Remove do banco
            $anexo->delete();

            return back()->with('success', 'Anexo excluído com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir anexo', [
                'erro' => $e->getMessage(),
                'nota_id' => $nota->id,
                'anexo_id' => $anexo->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('error', 'Erro ao excluir anexo.');
        }
    }

    /*======== Método associar à nota ========*/
     //Associa anexos temporários a uma nota
    public function associarANota($notaId, array $anexosIds)
    {
        if (empty($anexosIds)) {
            return true;
        }

        return Anexo::whereIn('id', $anexosIds)
            ->where('user_id', auth()->id())
            ->whereNull('nota_id') // anexos temporários têm nota_id = null
            ->update(['nota_id' => $notaId]);
    }

    /*======== Método removerAnexosIds ========*/
     //Remove anexos marcados para remoção
    public function removerAnexosIds(array $anexosIds, $notaId)
    {
        if (empty($anexosIds)) {
            return true;
        }

        $anexosParaRemover = Anexo::whereIn('id', $anexosIds)
            ->where('nota_id', $notaId)
            ->where('user_id', auth()->id())
            ->get();

        foreach ($anexosParaRemover as $anexo) {
            try {
                // Remove o arquivo do storage
                Storage::disk('public')->delete($anexo->caminho);
                // Remove do banco
                $anexo->delete();
            } catch (\Exception $e) {
                Log::error('Erro ao remover anexo', [
                    'anexo_id' => $anexo->id,
                    'erro' => $e->getMessage()
                ]);
            }
        }

        return true;
    }

    /*======== Método limparTemporarios ========*/
    //Limpa anexos temporários antigos
    public function limparTemporarios()
    {
        try {
            // Remove anexos temporários com mais de 1 hora
            $anexosTemporarios = Anexo::whereNull('nota_id')
                ->where('user_id', auth()->id())
                ->where('created_at', '<', now()->subHour())
                ->get();

            foreach ($anexosTemporarios as $anexo) {
                Storage::disk('public')->delete($anexo->caminho);
                $anexo->delete();
            }

            return response()->json([
                'success' => true,
                'removidos' => $anexosTemporarios->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao limpar anexos temporários', [
                'erro' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao limpar anexos temporários'
            ], 500);
        }
    }

    /*======== Método formatarTamanho ========*/
    //Método helper para formatar tamanho
    private function formatarTamanho($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
