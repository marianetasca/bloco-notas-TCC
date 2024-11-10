<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Primeiro, criar a tabela prioridades
        Schema::create('prioridades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cor')->default('#000000');
            $table->integer('nivel')->unique();
            $table->timestamps();
        });

        // Depois, adicionar a coluna em notas
        if (!Schema::hasColumn('notas', 'prioridade_id')) {
            Schema::table('notas', function (Blueprint $table) {
                $table->foreignId('prioridade_id')->nullable()->constrained()->nullOnDelete();
                $table->date('data_entrega')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('notas', 'prioridade_id')) {
            Schema::table('notas', function (Blueprint $table) {
                $table->dropConstrainedForeignId('prioridade_id');
                $table->dropColumn('data_entrega');
            });
        }

        Schema::dropIfExists('prioridades');
    }
};
