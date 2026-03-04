<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ConfiguracaoPainel;

class VNNOXService
{
    private $appKey;
    private $appSecret;
    private $apiUrl;

    public function __construct()
    {
        $config = ConfiguracaoPainel::getAtiva();
        
        if ($config) {
            $this->appKey = $config->vnnox_app_key;
            $this->appSecret = $config->vnnox_app_secret;
            $this->apiUrl = $config->vnnox_api_url;
        } else {
            // Fallback para variáveis de ambiente
            $this->appKey = config('paineis.vnnox.app_key');
            $this->appSecret = config('paineis.vnnox.app_secret');
            $this->apiUrl = config('paineis.vnnox.api_url', 'https://api.vnnox.com');
        }
    }

    /**
     * Gera o CheckSum para autenticação (SHA256)
     */
    private function generateCheckSum($nonce, $curTime)
    {
        return hash('sha256', $this->appSecret . $nonce . $curTime);
    }

    /**
     * Gera Nonce único para cada requisição
     */
    private function generateNonce()
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Gera os headers de autenticação para as requisições
     */
    private function getAuthHeaders()
    {
        $nonce = $this->generateNonce();
        $curTime = time();
        $checkSum = $this->generateCheckSum($nonce, $curTime);

        return [
            'AppKey' => $this->appKey,
            'Nonce' => $nonce,
            'CurTime' => $curTime,
            'CheckSum' => $checkSum,
            'Content-Type' => 'application/json',
        ];
    }

    /**
     * Lista todos os players disponíveis
     */
    public function listarPlayers()
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->get("{$this->apiUrl}/v2/player/list");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Erro ao listar players VNNOX', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exceção ao listar players VNNOX: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Verifica o status de um player específico
     */
    public function verificarStatusPlayer($playerId)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->get("{$this->apiUrl}/v2/player/status", [
                    'player_id' => $playerId
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do player: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Faz upload de mídia para a biblioteca VNNOX
     */
    public function uploadMidia($caminhoArquivo, $nomeArquivo)
    {
        try {
            // A API VNNOX pode ter um endpoint específico para upload
            // Este é um exemplo - ajustar conforme documentação real
            $response = Http::withHeaders($this->getAuthHeaders())
                ->attach('file', file_get_contents($caminhoArquivo), $nomeArquivo)
                ->post("{$this->apiUrl}/v2/media/upload");

            if ($response->successful()) {
                $data = $response->json();
                return $data['media_id'] ?? null;
            }

            Log::error('Erro ao fazer upload de mídia VNNOX', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exceção ao fazer upload de mídia: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Insere vídeo em exibição emergencial (prioritária)
     * Este é o método principal para exibir vídeos dos cidadãos
     */
    public function inserirExibicaoEmergencial($playerId, $mediaId, $duracaoSegundos = 30)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->post("{$this->apiUrl}/v2/player/emergency-program/page", [
                    'player_id' => $playerId,
                    'media_id' => $mediaId,
                    'duration' => $duracaoSegundos,
                    'priority' => 1
                ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()
                ];
            }

            Log::error('Erro ao inserir exibição emergencial', [
                'status' => $response->status(),
                'body' => $response->body(),
                'player_id' => $playerId,
                'media_id' => $mediaId
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao comunicar com o painel'
            ];
        } catch (\Exception $e) {
            Log::error('Exceção ao inserir exibição emergencial: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Atualiza a programação normal do player
     */
    public function atualizarProgramacaoNormal($playerId, $playlist)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->post("{$this->apiUrl}/v2/player/program/normal", [
                    'player_id' => $playerId,
                    'playlist' => $playlist
                ]);

            if ($response->successful()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar programação normal: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém o status atual de reprodução do player
     */
    public function obterStatusReproducao($playerId)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->post("{$this->apiUrl}/v2/player/current/running-status", [
                    'player_id' => $playerId
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao obter status de reprodução: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ajusta o brilho do painel
     */
    public function ajustarBrilho($playerId, $nivel)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->post("{$this->apiUrl}/v2/player/brightness", [
                    'player_id' => $playerId,
                    'brightness' => $nivel // 0-100
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Erro ao ajustar brilho: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Captura screenshot do que está sendo exibido
     */
    public function capturarScreenshot($playerId)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders())
                ->get("{$this->apiUrl}/v2/player/screenshot", [
                    'player_id' => $playerId
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao capturar screenshot: ' . $e->getMessage());
            return null;
        }
    }
}
