<?php

namespace App\Http\Controllers;

use App\Models\Anexo;
use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Adicione esta linha



class AnexoController extends Controller
{

public function Anexo(Request $request, Nota $nota)
{
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $path = $file->store('anexos', 'public');

        $nota->anexos()->create([
            'nome' => $file->getClientOriginalName(),
            'caminho' => $path,
        ]);

        return response()->json(['success' => true, 'path' => $path]);
    }

    return response()->json(['success' => false], 400);
}




    public function destroy(Nota $nota, Anexo $anexo)
    {
        // Log para debug
        Log::info('Tentando excluir anexo', [
            'nota_id' => $nota->id,
            'anexo_id' => $anexo->id
        ]);

        try {
            // Verifica se o anexo pertence à nota
            if ($anexo->nota_id !== $nota->id) {
                return back()->with('error', 'Anexo não pertence a esta nota.');
            }

            // Deleta o arquivo físico
            Storage::disk('public')->delete($anexo->caminho);

            // Deleta o registro do banco
            $anexo->delete();

            return back()->with('success', 'Anexo excluído com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir anexo', [
                'erro' => $e->getMessage(),
                'nota_id' => $nota->id,
                'anexo_id' => $anexo->id
            ]);

            return back()->with('error', 'Erro ao excluir anexo.');
        }
    }
}
