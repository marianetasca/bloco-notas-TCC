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
        $categoriasIds = Categoria::where('user_id', auth()->id())->pluck('id');

        $stats = [
            'total_notas' => Nota::where('user_id', auth()->id())->count(),
            'notas_concluidas' => Nota::where('user_id', auth()->id())
                ->where('concluido', true)->count(),
            'notas_pendentes' => Nota::where('user_id', auth()->id())
                ->where('concluido', false)->count(),
            'notas_vencidas' => Nota::where('user_id', auth()->id())
                ->where('data_vencimento', '<', now())
                ->where('concluido', false)
                ->count(),
        ];

        $notasPorCategoria = Categoria::where('user_id', auth()->id())
            ->withCount('notas')
            ->get();

        $notasPorPrioridade = Nota::where('user_id', auth()->id())
            ->select('prioridade_id')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('prioridade_id')
            ->with('prioridade')
            ->get();

        return view('dashboard', compact('stats', 'notasPorCategoria', 'notasPorPrioridade'));
    }
}
