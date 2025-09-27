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
        Schema::table('anexos', function (Blueprint $table) {
            // Remove a constraint atual
            $table->dropForeign(['nota_id']);

            // Modifica a coluna para aceitar null
            $table->foreignId('nota_id')->nullable()->change();

            // Recria a foreign key
            $table->foreign('nota_id')->references('id')->on('notas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anexos', function (Blueprint $table) {
            // Reverte as mudanças
            $table->dropForeign(['nota_id']);
            $table->foreignId('nota_id')->change();
            $table->foreign('nota_id')->references('id')->on('notas')->onDelete('cascade');
        });
    }
};
