<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Preferências de notificação
            $table->boolean('notificacoes_ativas')->default(true)->after('email');
            $table->json('preferencias_notificacao')->nullable()->after('notificacoes_ativas');
            $table->string('telefone')->nullable()->after('preferencias_notificacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notificacoes_ativas', 'preferencias_notificacao', 'telefone']);
        });
    }
};
