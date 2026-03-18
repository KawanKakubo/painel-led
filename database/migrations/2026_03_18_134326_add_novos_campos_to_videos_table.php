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
        Schema::table('videos', function (Blueprint $table) {
            $table->string('categoria_video')->nullable();
            $table->integer('plano_segundos')->nullable();
            $table->string('semana_intencao')->nullable();
            $table->boolean('termo_aceito')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['categoria_video', 'plano_segundos', 'semana_intencao', 'termo_aceito']);
        });
    }
};
