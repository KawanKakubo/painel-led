<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use Carbon\Carbon;

class LimparVideosAntigos extends Command
{
    /**
     * Nome e assinatura do comando
     */
    protected $signature = 'videos:limpar-antigos 
                            {--dias=90 : Número de dias para manter vídeos rejeitados}
                            {--dry-run : Apenas listar, não deletar}';

    /**
     * Descrição do comando
     */
    protected $description = 'Remove vídeos rejeitados antigos para liberar espaço em disco';

    /**
     * Execute o comando
     */
    public function handle()
    {
        $dias = $this->option('dias');
        $dryRun = $this->option('dry-run');

        $dataLimite = Carbon::now()->subDays($dias);

        $this->info("Buscando vídeos rejeitados antes de {$dataLimite->format('d/m/Y')}...");

        // Buscar vídeos rejeitados antigos
        $videos = Video::where('status', 'rejected')
            ->where('data_rejeicao', '<', $dataLimite)
            ->get();

        if ($videos->isEmpty()) {
            $this->info('✓ Nenhum vídeo antigo encontrado para limpar.');
            return 0;
        }

        $this->info("Encontrados {$videos->count()} vídeos para limpar:");

        $espacoLiberado = 0;

        foreach ($videos as $video) {
            $tamanho = $video->tamanho_bytes ?? 0;
            $tamanhoMB = round($tamanho / 1024 / 1024, 2);

            $this->line("  - ID {$video->id}: {$video->titulo} ({$tamanhoMB} MB)");

            if (!$dryRun) {
                // Deletar arquivo original
                if ($video->arquivo_original && Storage::exists($video->arquivo_original)) {
                    Storage::delete($video->arquivo_original);
                }

                // Deletar arquivo processado
                if ($video->arquivo_processado && Storage::exists($video->arquivo_processado)) {
                    Storage::delete($video->arquivo_processado);
                }

                // Soft delete do registro
                $video->delete();

                $espacoLiberado += $tamanho;
            }
        }

        if ($dryRun) {
            $this->warn('⚠ Modo DRY-RUN: Nenhum arquivo foi deletado.');
            $espacoTotalMB = round(array_sum($videos->pluck('tamanho_bytes')->toArray()) / 1024 / 1024, 2);
            $this->info("Espaço que seria liberado: {$espacoTotalMB} MB");
        } else {
            $espacoLiberadoMB = round($espacoLiberado / 1024 / 1024, 2);
            $espacoLiberadoGB = round($espacoLiberado / 1024 / 1024 / 1024, 2);
            
            $this->info("✓ {$videos->count()} vídeos removidos");
            $this->info("✓ Espaço liberado: {$espacoLiberadoMB} MB ({$espacoLiberadoGB} GB)");
        }

        return 0;
    }
}
