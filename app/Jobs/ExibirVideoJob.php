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

            // Gerar URL pública do arquivo
            // IMPORTANTE: O arquivo DEVE estar acessível publicamente pela internet
            // para que a API VNNOX possa fazer o download
            $urlPublica = Storage::url($this->video->arquivo_processado);
            
            // Garantir que seja uma URL absoluta
            if (!filter_var($urlPublica, FILTER_VALIDATE_URL)) {
                $urlPublica = url($urlPublica);
            }

            // Usar MD5 e tamanho já calculados durante o processamento
            $md5 = $this->video->md5_hash;
            $tamanhoBytes = $this->video->tamanho_bytes;

            // Se não foram calculados antes, calcular agora
            if (!$md5) {
                $md5 = md5_file($caminhoArquivo);
                Log::warning("MD5 não estava calculado para vídeo ID {$this->video->id}. Calculando agora...");
            }

            if (!$tamanhoBytes) {
                $tamanhoBytes = filesize($caminhoArquivo);
                Log::warning("Tamanho não estava calculado para vídeo ID {$this->video->id}. Calculando agora...");
            }

            // Obter duração em segundos
            $duracaoSegundos = $this->video->duracao_segundos ?? 30;

            Log::info("Enviando vídeo para API VNNOX", [
                'video_id' => $this->video->id,
                'url' => $urlPublica,
                'md5' => $md5,
                'size' => $tamanhoBytes,
                'duration' => $duracaoSegundos
            ]);

            // Criar registro de histórico (início)
            $historico = HistoricoExibicao::create([
                'video_id' => $this->video->id,
                'painel_id' => $this->video->painel_id,
                'data_hora_inicio' => now(),
            ]);

            // Inserir exibição emergencial com URL pública
            $resultado = $vnnoxService->inserirExibicaoEmergencialComVideo(
                $painel->player_id,
                $urlPublica,
                $md5,
                $tamanhoBytes,
                $duracaoSegundos,
                [
                    'name' => $this->video->titulo,
                    'spotsType' => 'IMMEDIATELY',
                    'normalProgramStatus' => 'PAUSE'
                ]
            );

            if (isset($resultado['success']) && is_array($resultado['success'])) {
                $sucesso = in_array($painel->player_id, $resultado['success']);
                
                if ($sucesso) {
                    // Marcar vídeo como exibido
                    $this->video->marcarComoExibido();

                    // Atualizar histórico
                    $historico->update([
                        'data_hora_fim' => now()->addSeconds($duracaoSegundos),
                        'exibicao_completa' => true,
                        'observacoes' => 'Exibição enviada com sucesso via API VNNOX'
                    ]);

                    Log::info("Vídeo ID {$this->video->id} enviado para exibição com sucesso");
                } else {
                    $historico->update([
                        'exibicao_completa' => false,
                        'observacoes' => 'Player na lista de falhas: ' . json_encode($resultado['fail'] ?? [])
                    ]);

                    Log::error("Player {$painel->player_id} falhou ao receber vídeo ID {$this->video->id}");
                }
            } else {
                $historico->update([
                    'exibicao_completa' => false,
                    'observacoes' => 'Erro ao enviar: ' . ($resultado['message'] ?? 'Resposta inesperada da API')
                ]);

                Log::error("Erro ao exibir vídeo ID {$this->video->id}: " . ($resultado['message'] ?? 'Resposta inesperada'));
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
