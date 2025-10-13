@extends('layouts.app')

@section('slot')

    <div class="container py-">
        <div class="text-end mb-3 mt-3">
            <a href="{{ route('notification-preferences.edit') }}" class="btn btn-primary-ed">
                <i class="bi bi-gear"></i> Preferências das Notificações
            </a>
        </div>
        <div class="card shadow rounded-4 mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h2 class="h4 textColor mb-0">
                    <i class="bi bi-bell"></i> Minhas Notificações
                </h2>

                @if ($notifications->total() > 0)
                    <div class="btn-group">
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-check-all"></i> Marcar todas como lidas
                            </button>
                        </form>

                        <form action="{{ route('notifications.delete-all') }}" method="POST" class="d-inline ms-2"
                            onsubmit="return confirm('Tem certeza que deseja apagar todas as notificações?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Apagar todas
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            @if ($notifications->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-bell-slash" style="font-size: 3rem;"></i>
                    <p class="mt-3">Você não tem notificações</p>
                </div>
            @else
                <div class="list-group">
                    @foreach ($notifications as $notification)
                        <div class="list-group-item {{ is_null($notification->read_at) ? 'bg-light' : '' }}"
                            id="notification-{{ $notification->id }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    @if (is_null($notification->read_at))
                                        <span class="badge bg-primary">Nova</span>
                                    @endif
                                    {{ $notification->data['titulo'] ?? 'Notificação' }}
                                </h6>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">
                                Vence em {{ $notification->data['dias_restantes'] }}
                                {{ $notification->data['dias_restantes'] == 1 ? 'dia' : 'dias' }}
                            </p>
                            <small>
                                Data: {{ \Carbon\Carbon::parse($notification->data['data_vencimento'])->format('d/m/Y') }}
                            </small>

                            <div class="float-end">
                                @if (is_null($notification->read_at))
                                    <button class="btn btn-sm btn-link mark-as-read" data-id="{{ $notification->id }}">
                                        Marcar como lida
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-link text-danger delete-notification"
                                    data-id="{{ $notification->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        // Marcar como lida
        document.querySelectorAll('.mark-as-read').forEach(button => {
            button.addEventListener('click', function() {
                fetch(`/notifications/${this.dataset.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            });
        });

        // Apagar notificação
        document.querySelectorAll('.delete-notification').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Apagar esta notificação?')) {
                    fetch(`/notifications/${this.dataset.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(() => location.reload());
                }
            });
        });
    </script>
@endsection
