<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Nota;
use App\Models\Prioridade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('pt_BR');

        $userId = auth()->id();
        $query = Nota::where('user_id', $userId);

        // Filtros por data, mês e ano
        if ($request->filled('data')) {
            $query->whereDate('created_at', $request->input('data'));
        }

        if ($request->filled('mes')) {
            $query->whereMonth('created_at', $request->input('mes'));
        }

        if ($request->filled('ano')) {
            $query->whereYear('created_at', $request->input('ano'));
        }

        $notas = $query->get();

        // Estatísticas principais
        $notasConcluidas = $notas->where('concluido', true);

        $notasVencidas = $notas->filter(function ($nota) {
            return !$nota->concluido && $nota->data_vencimento && $nota->data_vencimento < now();
        });

        $notasPendentes = $notas->filter(function ($nota) {
            return !$nota->concluido && (
                !$nota->data_vencimento || $nota->data_vencimento >= now()
            );
        });

        $stats = [
            'total_notas' => $notas->count(),
            'notas_concluidas' => $notasConcluidas->count(),
            'notas_pendentes' => $notasPendentes->count(),
            'notas_vencidas' => $notasVencidas->count(),
        ];

        // Agrupamento por categoria
        $notasPorCategoria = Categoria::withCount(['notas as count' => function ($q) use ($userId, $request) {
            $q->where('user_id', $userId);
            if ($request->filled('data')) {
                $q->whereDate('created_at', $request->input('data'));
            }
            if ($request->filled('mes')) {
                $q->whereMonth('created_at', $request->input('mes'));
            }
            if ($request->filled('ano')) {
                $q->whereYear('created_at', $request->input('ano'));
            }
        }])->get();

        // Agrupamento por prioridade
        $notasPorPrioridade = Prioridade::with(['notas' => function ($q) use ($userId, $request) {
            $q->where('user_id', $userId);
            if ($request->filled('data')) {
                $q->whereDate('created_at', $request->input('data'));
            }
            if ($request->filled('mes')) {
                $q->whereMonth('created_at', $request->input('mes'));
            }
            if ($request->filled('ano')) {
                $q->whereYear('created_at', $request->input('ano'));
            }
        }])->get()->map(function ($prioridade) {
            return (object) [
                'prioridade' => $prioridade,
                'total' => $prioridade->notas->count()
            ];
        });

        // Dados para gráficos
        $graficoCategorias = $notasPorCategoria->mapWithKeys(function ($cat) {
            return [$cat->nome => $cat->count];
        });

        $graficoPrioridades = $notasPorPrioridade->mapWithKeys(function ($item) {
            return [$item->prioridade->nome => $item->total];
        });

        return view('dashboard', compact('stats', 'notasPorCategoria', 'notasPorPrioridade', 'graficoCategorias', 'graficoPrioridades'));
    }
}
