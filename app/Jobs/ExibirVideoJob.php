<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use App\Models\HistoricoExibicao;
use App\Services\VNNOXService;

class ExibirVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $timeout = 300; // 5 minutos

    /**
     * Create a new job instance.
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     */
    public function handle(VNNOXService $vnnoxService): void
    {
        Log::info("Iniciando exibição do vídeo ID: {$this->video->id}");

        try {
            // Verificar se o vídeo está aprovado
            if ($this->video->status !== 'approved') {
                Log::warning("Vídeo ID {$this->video->id} não está aprovado. Status: {$this->video->status}");
                return;
            }

            // Verificar se tem painel associado
            if (!$this->video->painel_id) {
                Log::error("Vídeo ID {$this->video->id} não tem painel associado");
                return;
            }

            $painel = $this->video->painel;

            // Verificar se o painel está online
            if (!$painel->online) {
                Log::warning("Painel {$painel->nome} está offline");
                return;
            }

            // Caminho do arquivo processado
            $caminhoArquivo = Storage::path($this->video->arquivo_processado);

            if (!file_exists($caminhoArquivo)) {
                Log::error("Arquivo processado não encontrado: {$caminhoArquivo}");
                return;
            }

            // Upload para VNNOX Cloud (se ainda não foi feito)
            if (!$this->video->vnnox_media_id) {
                $mediaId = $vnnoxService->uploadMidia(
                    $caminhoArquivo,
                    basename($caminhoArquivo)
                );

                if (!$mediaId) {
                    Log::error("Erro ao fazer upload do vídeo para VNNOX");
                    return;
                }

                $this->video->update(['vnnox_media_id' => $mediaId]);
            }

            // Criar registro de histórico (início)
            $historico = HistoricoExibicao::create([
                'video_id' => $this->video->id,
                'painel_id' => $this->video->painel_id,
                'data_hora_inicio' => now(),
            ]);

            // Inserir exibição emergencial
            $resultado = $vnnoxService->inserirExibicaoEmergencial(
                $painel->player_id,
                $this->video->vnnox_media_id,
                $this->video->duracao_segundos ?? 30
            );

            if ($resultado['success']) {
                // Marcar vídeo como exibido
                $this->video->marcarComoExibido();

                // Atualizar histórico
                $historico->update([
                    'data_hora_fim' => now()->addSeconds($this->video->duracao_segundos ?? 30),
                    'exibicao_completa' => true,
                    'observacoes' => 'Exibição enviada com sucesso'
                ]);

                Log::info("Vídeo ID {$this->video->id} enviado para exibição com sucesso");
            } else {
                $historico->update([
                    'exibicao_completa' => false,
                    'observacoes' => 'Erro ao enviar: ' . ($resultado['message'] ?? 'Desconhecido')
                ]);

                Log::error("Erro ao exibir vídeo ID {$this->video->id}: " . ($resultado['message'] ?? 'Desconhecido'));
            }

        } catch (\Exception $e) {
            Log::error("Exceção ao exibir vídeo ID {$this->video->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Falha ao exibir vídeo ID {$this->video->id}: " . $exception->getMessage());
    }
}
