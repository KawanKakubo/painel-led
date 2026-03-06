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
use App\Models\Painel;
use App\Services\VideoProcessingService;

class ProcessarVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;
    public $timeout = 3600; // 1 hora para processar vídeos grandes

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
    public function handle(VideoProcessingService $videoService): void
    {
        Log::info("Iniciando processamento do vídeo ID: {$this->video->id}");

        try {
            // Caminho do arquivo original
            $caminhoOriginal = Storage::path($this->video->arquivo_original);

            // Validar vídeo
            $validacao = $videoService->validarVideo($caminhoOriginal);

            if (!$validacao['valid']) {
                $this->video->update([
                    'status' => 'rejected',
                    'motivo_rejeicao' => 'Validação automática: ' . $validacao['message']
                ]);

                Log::warning("Vídeo ID {$this->video->id} rejeitado automaticamente: {$validacao['message']}");
                return;
            }

            // Definir resolução baseada no painel
            $resolucaoLargura = 1920;
            $resolucaoAltura = 1080;

            if ($this->video->painel_id) {
                $painel = Painel::find($this->video->painel_id);
                if ($painel && $painel->resolucao_largura && $painel->resolucao_altura) {
                    $resolucaoLargura = $painel->resolucao_largura;
                    $resolucaoAltura = $painel->resolucao_altura;
                }
            }

            // Processar vídeo (transcodificar para H.264, redimensionar, etc.)
            $resultado = $videoService->processarVideo(
                $caminhoOriginal,
                $resolucaoLargura,
                $resolucaoAltura
            );

            if (!$resultado['success']) {
                $this->video->update([
                    'status' => 'rejected',
                    'motivo_rejeicao' => 'Erro no processamento: ' . $resultado['message']
                ]);

                Log::error("Erro ao processar vídeo ID {$this->video->id}: {$resultado['message']}");
                return;
            }

            // Atualizar vídeo com caminho processado e duração
            $this->video->update([
                'arquivo_processado' => $resultado['caminho'],
                'duracao_segundos' => $resultado['duracao_segundos'] ?? $this->video->duracao_segundos,
                'md5_hash' => md5_file($resultado['caminho']),
                'tamanho_bytes' => filesize($resultado['caminho']),
                'status' => 'pending' // Pronto para moderação
            ]);

            // Gerar thumbnail
            $videoService->gerarThumbnail($resultado['caminho']);

            Log::info("Vídeo ID {$this->video->id} processado com sucesso");

        } catch (\Exception $e) {
            Log::error("Exceção ao processar vídeo ID {$this->video->id}: " . $e->getMessage());
            
            $this->video->update([
                'status' => 'rejected',
                'motivo_rejeicao' => 'Erro técnico no processamento'
            ]);

            throw $e; // Re-throw para tentar novamente se configurado
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Falha permanente ao processar vídeo ID {$this->video->id}: " . $exception->getMessage());
        
        $this->video->update([
            'status' => 'rejected',
            'motivo_rejeicao' => 'Falha no processamento após múltiplas tentativas'
        ]);
    }
}
