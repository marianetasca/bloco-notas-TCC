<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{

    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        return view('notifications.index', compact('notifications'));
    }

    /**
     * Buscar notificações não lidas (para AJAX)
     */
    public function unread(Request $request): JsonResponse
    {
        $unreadNotifications = $request->user()
            ->unreadNotifications()
            ->limit(5)
            ->get();

        return response()->json([
            'notifications' => $unreadNotifications,
            'count' => $request->user()->unreadNotifications()->count()
        ]);
    }

    /**
     * Marcar notificação como lida
     */
    public function markAsRead(Request $request, string $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas como lidas
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Todas as notificações foram marcadas como lidas.');
    }

    /**
     * Apagar uma notificação específica
     */
    public function destroy(Request $request, string $id)
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notificação removida com sucesso'
        ]);
    }

    /**
     * Apagar todas as notificações
     */
    public function deleteAll(Request $request)
    {
        $request->user()->notifications()->delete();

        return back()->with('success', 'Todas as notificações foram removidas.');
    }
}
