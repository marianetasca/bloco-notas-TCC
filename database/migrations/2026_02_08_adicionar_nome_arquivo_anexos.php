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
        // Verificar se a coluna não existe antes de adicionar
        if (!Schema::hasColumn('anexos', 'nome_arquivo')) {
            Schema::table('anexos', function (Blueprint $table) {
                $table->string('nome_arquivo')->after('nota_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anexos', function (Blueprint $table) {
            $table->dropColumn('nome_arquivo');
        });
    }
};
