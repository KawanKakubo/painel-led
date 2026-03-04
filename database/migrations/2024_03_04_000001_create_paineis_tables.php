<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabela de configurações do sistema VNNOX
        Schema::create('configuracoes_painel', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->default('Configuração Principal');
            $table->string('vnnox_app_key');
            $table->string('vnnox_app_secret');
            $table->string('vnnox_api_url')->default('https://api.vnnox.com');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        // Tabela de painéis disponíveis
        Schema::create('paineis', function (Blueprint $table) {
            $table->id();
            $table->string('player_id')->unique(); // ID do player Taurus na VNNOX
            $table->string('nome'); // Nome do painel (ex: "Painel Avenida Rio de Janeiro")
            $table->string('localizacao')->nullable(); // Localização física
            $table->integer('resolucao_largura')->nullable();
            $table->integer('resolucao_altura')->nullable();
            $table->boolean('ativo')->default(true);
            $table->boolean('online')->default(false);
            $table->timestamp('ultimo_heartbeat')->nullable();
            $table->timestamps();
        });

        // Tabela de vídeos enviados pelos cidadãos
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('painel_id')->nullable()->constrained('paineis')->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('arquivo_original'); // Caminho do arquivo original
            $table->string('arquivo_processado')->nullable(); // Caminho do arquivo processado
            $table->enum('status', [
                'pending', // Aguardando moderação
                'processing', // Em processamento
                'approved', // Aprovado
                'rejected', // Rejeitado
                'displayed', // Exibido
                'archived' // Arquivado
            ])->default('pending');
            $table->text('motivo_rejeicao')->nullable();
            $table->foreignId('moderador_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('data_aprovacao')->nullable();
            $table->timestamp('data_rejeicao')->nullable();
            $table->timestamp('data_exibicao')->nullable();
            $table->integer('duracao_segundos')->nullable(); // Duração do vídeo
            $table->string('vnnox_media_id')->nullable(); // ID da mídia na VNNOX Cloud
            $table->integer('vezes_exibido')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabela de histórico de exibições
        Schema::create('historico_exibicoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('painel_id')->constrained('paineis')->onDelete('cascade');
            $table->timestamp('data_hora_inicio');
            $table->timestamp('data_hora_fim')->nullable();
            $table->boolean('exibicao_completa')->default(false);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Adicionar campos à tabela users existente
        Schema::table('users', function (Blueprint $table) {
            $table->string('gov_assai_id')->nullable()->unique()->after('id');
            $table->string('cpf')->nullable()->unique()->after('email');
            $table->string('celular')->nullable()->after('cpf');
            $table->integer('nivel_acesso')->default(1)->after('celular');
            $table->enum('role', ['admin', 'moderador', 'cidadao'])->default('cidadao')->after('nivel_acesso');
            $table->boolean('ativo')->default(true)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gov_assai_id', 'cpf', 'celular', 'nivel_acesso', 'role', 'ativo']);
        });
        
        Schema::dropIfExists('historico_exibicoes');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('paineis');
        Schema::dropIfExists('configuracoes_painel');
    }
};
