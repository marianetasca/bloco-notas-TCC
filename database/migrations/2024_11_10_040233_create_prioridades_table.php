<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->date('data_vencimento')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->default('media');
        });
    }

    public function down()
    {
        Schema::table('notas', function (Blueprint $table) {
            $table->dropColumn(['data_vencimento', 'prioridade']);
        });
    }
};
