<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Painel;
use App\Services\VNNOXService;

class SincronizarPaineisJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    /**
     * Execute the job.
     */
    public function handle(VNNOXService $vnnoxService): void
    {
        Log::info("Iniciando sincronização de painéis");

        try {
            // Listar players na VNNOX
            $resultado = $vnnoxService->listarPlayers();

            if (!$resultado || !isset($resultado['data'])) {
                Log::warning("Nenhum player encontrado na API VNNOX");
                return;
            }

            $players = $resultado['data'];
            $sincronizados = 0;

            foreach ($players as $playerData) {
                $playerId = $playerData['player_id'] ?? $playerData['id'] ?? null;

                if (!$playerId) {
                    continue;
                }

                // Verificar status do player
                $status = $vnnoxService->verificarStatusPlayer($playerId);

                $painel = Painel::updateOrCreate(
                    ['player_id' => $playerId],
                    [
                        'nome' => $playerData['name'] ?? 'Painel ' . $playerId,
                        'online' => $status['online'] ?? false,
                        'ultimo_heartbeat' => now(),
                    ]
                );

                $sincronizados++;
                Log::info("Painel {$painel->nome} sincronizado");
            }

            Log::info("Sincronização concluída: {$sincronizados} painéis");

        } catch (\Exception $e) {
            Log::error("Erro ao sincronizar painéis: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Falha na sincronização de painéis: " . $exception->getMessage());
    }
}
