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
            $table->string('tipo_perfil')->default('cidadao'); // cidadao ou comerciante
            $table->string('cnpj')->nullable();
            $table->string('nome_empresa')->nullable();
            $table->string('ramo_atividade')->nullable();
            $table->string('bairro')->nullable();
            $table->boolean('perfil_completo')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipo_perfil', 'cnpj', 'nome_empresa', 'ramo_atividade', 'bairro', 'perfil_completo']);
        });
    }
};
