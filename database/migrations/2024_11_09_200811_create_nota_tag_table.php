<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nota_tag')) {
            Schema::create('nota_tag', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('nota_id');
                $table->unsignedBigInteger('tag_id');
                $table->timestamps();

                $table->foreign('nota_id')
                      ->references('id')
                      ->on('notas')
                      ->onDelete('cascade');

                $table->foreign('tag_id')
                      ->references('id')
                      ->on('tags')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('nota_tag');
    }
};
