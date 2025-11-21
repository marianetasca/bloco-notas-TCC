<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Nota;
use App\Models\Prioridade;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('pt_BR');

        $userId = Auth::id();
        $query = Nota::where('user_id', $userId);

        $highlightId = $request->get('highlight') ?? session('highlightId');

        // Filtros por data: permitir filtrar por data de vencimento ou data de criação.
        // O usuário pode escolher com o campo `filtro_por` (valores: 'vencimento'|'criacao').
        $filterBy = $request->input('filtro_por', 'vencimento');
        $dateColumn = $filterBy === 'criacao' ? 'created_at' : 'data_vencimento';

        if ($request->filled('data')) {
            $query->whereDate($dateColumn, $request->input('data'));
        }

        if ($request->filled('mes')) {
            $query->whereMonth($dateColumn, $request->input('mes'));
        }

        if ($request->filled('ano')) {
            $query->whereYear($dateColumn, $request->input('ano'));
        }

        // Obtenha coleção completa para cálculos/estatísticas e uma versão paginada
        $notasCollection = (clone $query)->get();

        // Lista paginada para exibir no dashboard (com relacionamentos carregados)
        $notas = (clone $query)
            ->with(['categoria', 'prioridade', 'tags'])
            ->orderBy('data_vencimento')
            ->paginate(10)
            ->withQueryString();

        // Estatísticas principais
        $notasConcluidas = $notasCollection->where('concluido', true);

        $notasVencidas = $notasCollection->filter(function ($nota) {
            return !$nota->concluido && $nota->data_vencimento && $nota->data_vencimento < now();
        });

        $notasPendentes = $notasCollection->filter(function ($nota) {
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
        $notasPorCategoria = Categoria::where('user_id', $userId)->withCount(['notas as count' => function ($q) use ($userId, $request, $dateColumn) {
            $q->where('user_id', $userId)->whereNull('deleted_at'); //para nao contar com as notas da lixeira
            if ($request->filled('data')) {
                $q->whereDate($dateColumn, $request->input('data'));
            }
            if ($request->filled('mes')) {
                $q->whereMonth($dateColumn, $request->input('mes'));
            }
            if ($request->filled('ano')) {
                $q->whereYear($dateColumn, $request->input('ano'));
            }
        }])->get();

        // Agrupamento por prioridade
        $notasPorPrioridade = Prioridade::with(['notas' => function ($q) use ($userId, $request, $dateColumn) {
            $q->where('user_id', $userId)->whereNull('deleted_at');
            if ($request->filled('data')) {
                $q->whereDate($dateColumn, $request->input('data'));
            }
            if ($request->filled('mes')) {
                $q->whereMonth($dateColumn, $request->input('mes'));
            }
            if ($request->filled('ano')) {
                $q->whereYear($dateColumn, $request->input('ano'));
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

        return view('dashboard', compact('stats', 'notasPorCategoria', 'notasPorPrioridade', 'graficoCategorias', 'graficoPrioridades', 'notas', 'highlightId'));
    }
}
