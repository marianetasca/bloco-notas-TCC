@extends('layouts.app')

@section('slot')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-bell text-warning-ed"></i> Preferências de Notificação
                        </h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('notification-preferences.update') }}">
                            @csrf
                            @method('PUT')

                            {{-- Ativar notificações --}}
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="notificacoes_ativas"
                                    name="notificacoes_ativas" value="1"
                                    {{ $user->notificacoes_ativas ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="notificacoes_ativas">
                                    Receber notificações de vencimento
                                </label>
                                <div class="form-text">
                                    Receba lembretes sobre notas próximas do vencimento
                                </div>
                            </div>

                            {{-- Configurações (só aparecem se ativado) --}}
                            <div id="settings" class="{{ !$user->notificacoes_ativas ? 'd-none' : '' }}">

                                {{-- Como receber --}}
                                <div class="mb-4">
                                    <h6>Em qual plataforma deseja receber as notificações?</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email" name="email"
                                            value="1" {{ ($preferencias['email'] ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email">
                                            <i class="bi bi-envelope"></i> Por email
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="whatsapp" name="whatsapp"
                                            disabled value="1"
                                            {{ $preferencias['whatsapp'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="whatsapp">
                                            <i class="bi bi-whatsapp text-success"></i> Por WhatsApp
                                            <span class="badge bg-secondary ms-2">Em breve</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Quando receber --}}
                                <div class="mb-4">
                                    <h6>Quando receber?</h6>
                                    <div class="form-text mb-2">Marque os períodos desejados:</div>

                                    @php
                                        $dias = $preferencias['dias_antecedencia'] ?? [7, 1, 0];
                                    @endphp

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="7" id="dias_7" {{ in_array(7, $dias) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dias_7">7 dias antes</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="3" id="dias_3" {{ in_array(3, $dias) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dias_3">3 dias antes</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="1" id="dias_1" {{ in_array(1, $dias) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dias_1">1 dia antes</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="0" id="dias_0" {{ in_array(0, $dias) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dias_0">No dia do vencimento</label>
                                    </div>
                                </div>

                                {{-- Horário --}}
                                <div class="alert alert-info mb-4">
                                    <i class="bi bi-clock"></i> As notificações são enviadas às 9h da manhã
                                </div>
                            </div>

                            {{-- Botões --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Voltar</a>
                                <button type="submit" class="btn btn-primary-ed">
                                    <i class="bi bi-check-lg"></i> Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        // Mostrar/esconder configurações
        document.getElementById('notificacoes_ativas').addEventListener('change', function() {
            document.getElementById('settings').classList.toggle('d-none', !this.checked);
        });
    </script>
@endsection
