{{-- Ajuste o @extends conforme seu layout --}}
@extends('layouts.app')

@section('slot')
    <div class="container">
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

                            {{-- Ativar/Desativar notificações --}}
                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" id="notificacoes_ativas"
                                    name="notificacoes_ativas" value="1"
                                    {{ $user->notificacoes_ativas ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="notificacoes_ativas">
                                    Receber notificações de vencimento de notas
                                </label>
                                <div class="form-text">
                                    Ative para receber lembretes sobre notas próximas do vencimento
                                </div>
                            </div>

                            <div id="notification-settings" class="{{ !$user->notificacoes_ativas ? 'd-none' : '' }}">
                                {{-- Canais de notificação --}}
                                <div class="mb-4">
                                    <h6>Como você quer receber as notificações?</h6>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email" name="email"
                                            value="1" {{ $preferencias['email'] ?? true ? 'checked' : '' }}>
                                        <label class="form-check-label" for="email">
                                            <i class="bi bi-envelope"></i> Por email
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="whatsapp" name="whatsapp"
                                            value="1" {{ $preferencias['whatsapp'] ?? false ? 'checked' : '' }}>
                                        <label class="form-check-label" for="whatsapp">
                                            <i class="bi bi-whatsapp text-success"></i> Por WhatsApp
                                            <span class="badge bg-secondary ms-2">Em breve</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Telefone (para WhatsApp futuro) --}}
                                <div class="mb-4" id="whatsapp-settings" style="display: none;">
                                    <label for="telefone" class="form-label">Número do WhatsApp</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone"
                                        value="{{ $user->telefone }}" placeholder="(11) 99999-9999">
                                    <div class="form-text">
                                        Necessário para receber notificações via WhatsApp
                                    </div>
                                </div>

                                {{-- Dias de antecedência --}}
                                <div class="mb-4">
                                    <h6>Com quantos dias de antecedência?</h6>
                                    <div class="form-text mb-2">Você receberá notificações nestes períodos:</div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="7"
                                            {{ in_array(7, $preferencias['dias_antecedencia'] ?? [7, 1, 0]) ? 'checked' : '' }}>
                                        <label class="form-check-label">7 dias antes</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="3"
                                            {{ in_array(3, $preferencias['dias_antecedencia'] ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label">3 dias antes</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="1"
                                            {{ in_array(1, $preferencias['dias_antecedencia'] ?? [7, 1, 0]) ? 'checked' : '' }}>
                                        <label class="form-check-label">1 dia antes</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="dias_antecedencia[]"
                                            value="0"
                                            {{ in_array(0, $preferencias['dias_antecedencia'] ?? [7, 1, 0]) ? 'checked' : '' }}>
                                        <label class="form-check-label">No dia do vencimento</label>
                                    </div>
                                </div>

                                {{-- Horário de envio --}}
                                <div class="mb-4">
                                    <label for="horario_envio" class="form-label">Horário preferido para envio</label>
                                    <input type="time" class="form-control" id="horario_envio" name="horario_envio"
                                        value="{{ $preferencias['horario_envio'] ?? '09:00' }}" required>
                                    <div class="form-text">
                                        As notificações serão enviadas neste horário
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('notas.index') }}" class="btn btn-secondary me-2">Voltar</a>
                                <button type="submit" class="btn btn-primary-ed">
                                    <i class="bi bi-check-lg"></i> Salvar Preferências
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificacoesAtivas = document.getElementById('notificacoes_ativas');
            const notificationSettings = document.getElementById('notification-settings');
            const whatsappCheck = document.getElementById('whatsapp');
            const whatsappSettings = document.getElementById('whatsapp-settings');

            // Mostrar/ocultar configurações
            notificacoesAtivas.addEventListener('change', function() {
                if (this.checked) {
                    notificationSettings.classList.remove('d-none');
                } else {
                    notificationSettings.classList.add('d-none');
                }
            });

            // Mostrar campo telefone quando WhatsApp for selecionado
            whatsappCheck.addEventListener('change', function() {
                if (this.checked) {
                    whatsappSettings.style.display = 'block';
                } else {
                    whatsappSettings.style.display = 'none';
                }
            });

            // Verificar estado inicial do WhatsApp
            if (whatsappCheck.checked) {
                whatsappSettings.style.display = 'block';
            }
        });
    </script>
@endsection
