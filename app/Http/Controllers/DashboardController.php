<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\Categoria;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Estatísticas básicas
        $stats = [
            'total_notas' => Nota::where('user_id', $userId)->count(),
            'notas_concluidas' => Nota::where('user_id', $userId)->where('concluido', true)->count(),
            'notas_pendentes' => Nota::where('user_id', $userId)->where('concluido', false)->count(),
            'notas_vencidas' => Nota::where('user_id', $userId)
                ->where('data_vencimento', '<', now())
                ->where('concluido', false)
                ->count(),
        ];

        // Notas por categoria
        $notasPorCategoria = Categoria::withCount(['notas' => function($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();

        // Notas por prioridade
        $notasPorPrioridade = Nota::where('user_id', $userId)
            ->select('prioridade', DB::raw('count(*) as total'))
            ->groupBy('prioridade')
            ->get();

        return view('dashboard', compact('stats', 'notasPorCategoria', 'notasPorPrioridade'));
    }
}
