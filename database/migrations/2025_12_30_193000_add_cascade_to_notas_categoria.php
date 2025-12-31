<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notas', function (Blueprint $table) {
            // Drop existing foreign key (if exists)
            $table->dropForeign(['categoria_id']);

            // Recreate FK with cascade on delete
            $table->foreign('categoria_id')
                  ->references('id')
                  ->on('categorias')
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);

            // Recreate FK without cascade (original broken state)
            $table->foreign('categoria_id')
                  ->references('id')
                  ->on('categorias');
        });
    }
};
