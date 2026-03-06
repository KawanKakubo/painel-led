<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Esta migration corrige a estrutura da tabela videos para estar conforme
     * a API VNNOX, que NÃO faz upload de arquivos mas sim download de URLs.
     */
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            // Remover campo que não é mais usado
            // A API VNNOX não retorna media_id, ela faz download de URLs públicas
            if (Schema::hasColumn('videos', 'vnnox_media_id')) {
                $table->dropColumn('vnnox_media_id');
            }

            // Adicionar campos necessários para a API VNNOX
            // A API requer: url, md5, size (bytes), duration (ms)
            if (!Schema::hasColumn('videos', 'md5_hash')) {
                $table->string('md5_hash', 32)->nullable()->after('duracao_segundos')
                    ->comment('Hash MD5 do arquivo processado - requerido pela API VNNOX');
            }

            if (!Schema::hasColumn('videos', 'tamanho_bytes')) {
                $table->bigInteger('tamanho_bytes')->nullable()->after('md5_hash')
                    ->comment('Tamanho do arquivo em bytes - requerido pela API VNNOX');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            // Restaurar campo removido
            $table->string('vnnox_media_id')->nullable();

            // Remover campos adicionados
            if (Schema::hasColumn('videos', 'tamanho_bytes')) {
                $table->dropColumn('tamanho_bytes');
            }

            if (Schema::hasColumn('videos', 'md5_hash')) {
                $table->dropColumn('md5_hash');
            }
        });
    }
};
