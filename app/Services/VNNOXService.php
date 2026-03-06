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
     * CheckSum = SHA256(AppSecret + Nonce + CurTime)
     */
    private function generateCheckSum($nonce, $curTime)
    {
        return hash('sha256', $this->appSecret . $nonce . $curTime);
    }

    /**
     * Gera Nonce único para cada requisição (8-64 caracteres)
     */
    private function generateNonce()
    {
        return bin2hex(random_bytes(16)); // 32 caracteres hex
    }

    /**
     * Gera os headers de autenticação para as requisições
     * IMPORTANTE: Todos os parâmetros devem ser strings (conforme documentação)
     */
    private function getAuthHeaders($isPost = false)
    {
        $nonce = $this->generateNonce();
        $curTime = (string) time(); // DEVE SER STRING
        $checkSum = $this->generateCheckSum($nonce, $curTime);

        $headers = [
            'AppKey' => $this->appKey,
            'Nonce' => $nonce,
            'CurTime' => $curTime,
            'CheckSum' => $checkSum,
        ];

        // Content-Type diferente para GET e POST (conforme documentação)
        if ($isPost) {
            $headers['Content-Type'] = 'application/json; charset=utf-8';
        } else {
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }

        return $headers;
    }

    /**
     * Trata resposta de erro da API VNNOX
     */
    private function handleErrorResponse($response)
    {
        $body = $response->json();
        
        if (isset($body['error'])) {
            return [
                'success' => false,
                'code' => $body['error']['code'] ?? 'UNKNOWN_ERROR',
                'message' => $body['error']['message'] ?? 'Erro desconhecido'
            ];
        }
        
        return [
            'success' => false,
            'code' => 'HTTP_' . $response->status(),
            'message' => 'Erro na requisição: ' . $response->status()
        ];
    }

    /**
     * Lista todos os players disponíveis
     */
    public function listarPlayers()
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders(false))
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
     * Verifica o status de um ou múltiplos players
     * Conforme documentação: POST /v2/player/current/online-status
     * @param string|array $playerIds - Um playerId ou array de playerIds
     */
    public function verificarStatusPlayer($playerIds)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/current/online-status", [
                    'playerIds' => $playerIds
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Se requisitou apenas um player, retorna só ele
                return count($playerIds) === 1 ? $data[0] ?? null : $data;
            }

            Log::error('Erro ao verificar status do player', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao verificar status do player: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Faz upload de mídia para a biblioteca VNNOX
     * 
     * IMPORTANTE: A API VNNOX NÃO FAZ UPLOAD DIRETO!
     * Você precisa hospedar o arquivo em um servidor acessível publicamente
     * e fornecer a URL na programação.
     * 
     * Este método está DESATIVADO e retorna null.
     * Use o método inserirExibicaoEmergencialComVideo() que fornece a URL diretamente.
     * 
     * @deprecated Use inserirExibicaoEmergencialComVideo() em vez disso
     */
    public function uploadMidia($caminhoArquivo, $nomeArquivo)
    {
        Log::warning('Método uploadMidia() está deprecated. A API VNNOX não suporta upload direto.');
        Log::info('Use inserirExibicaoEmergencialComVideo() fornecendo a URL pública do arquivo.');
        return null;
    }

    /**
     * Insere vídeo em exibição emergencial (prioritária)
     * 
     * IMPORTANTE: A API VNNOX faz DOWNLOAD do vídeo da URL fornecida.
     * O arquivo deve estar acessível publicamente na internet.
     * 
     * Conforme documentação: POST /v2/player/emergency-program/page
     * 
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @param string $videoUrl - URL pública do vídeo (acessível pela API VNNOX)
     * @param string $videoMd5 - Hash MD5 do arquivo de vídeo
     * @param int $videoSize - Tamanho do arquivo em bytes
     * @param int $duracaoSegundos - Duração do vídeo em segundos
     * @param array $opcoes - Opções adicionais (opcional)
     * @return array|bool
     */
    public function inserirExibicaoEmergencialComVideo($playerIds, $videoUrl, $videoMd5, $videoSize, $duracaoSegundos, $opcoes = [])
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            // Duração em millisegundos
            $duracaoMs = $duracaoSegundos * 1000;

            // Estrutura do widget de vídeo conforme documentação
            $widget = [
                'type' => 'VIDEO',
                'url' => $videoUrl,
                'md5' => $videoMd5,
                'size' => (int) $videoSize,
                'duration' => (int) $duracaoMs,
                'zIndex' => $opcoes['zIndex'] ?? 1,
                'layout' => $opcoes['layout'] ?? [
                    'x' => '0%',
                    'y' => '0%',
                    'width' => '100%',
                    'height' => '100%'
                ],
                'inAnimation' => $opcoes['inAnimation'] ?? [
                    'type' => 'NONE',
                    'duration' => 1000
                ]
            ];

            // Se fornecido um nome para o widget
            if (isset($opcoes['name'])) {
                $widget['name'] = $opcoes['name'];
            }

            $payload = [
                'playerIds' => $playerIds,
                'attribute' => [
                    'spotsType' => $opcoes['spotsType'] ?? 'IMMEDIATELY', // IMMEDIATELY ou TIMING
                    'normalProgramStatus' => $opcoes['normalProgramStatus'] ?? 'PAUSE', // NORMAL ou PAUSE
                    'duration' => (int) $duracaoMs,
                ],
                'page' => [
                    'name' => $opcoes['pageName'] ?? 'emergency-video',
                    'widgets' => [$widget]
                ]
            ];

            // Se for agendado, adicionar horário
            if (($opcoes['spotsType'] ?? 'IMMEDIATELY') === 'TIMING' && isset($opcoes['timingTime'])) {
                $payload['attribute']['timingTime'] = $opcoes['timingTime'];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/emergency-program/page", $payload);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao inserir exibição emergencial', [
                'status' => $response->status(),
                'body' => $response->body(),
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao comunicar com o painel: ' . $response->body()
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
     * MÉTODO LEGADO - DEPRECATED
     * Insere vídeo em exibição emergencial usando media_id (NÃO FUNCIONA)
     * 
     * @deprecated Use inserirExibicaoEmergencialComVideo() que fornece URL diretamente
     */
    public function inserirExibicaoEmergencial($playerId, $mediaId, $duracaoSegundos = 30)
    {
        Log::warning('Método inserirExibicaoEmergencial() está deprecated.');
        Log::info('Use inserirExibicaoEmergencialComVideo() fornecendo URL, MD5 e tamanho do arquivo.');
        
        return [
            'success' => false,
            'message' => 'Método deprecated. Use inserirExibicaoEmergencialComVideo().'
        ];
    }

    /**
     * Atualiza a programação normal do player
     */
    public function atualizarProgramacaoNormal($playerId, $playlist)
    {
        try {
            $response = Http::withHeaders($this->getAuthHeaders(true))
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
     * IMPORTANTE: Esta API é ASSÍNCRONA - requer noticeUrl e commands array
     * Conforme documentação: POST /v2/player/current/running-status
     * 
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @param array $commands - Comandos a obter: volumeValue, brightnessValue, videoSourceValue, timeValue, screenPowerStatus, syncPlayStatus, powerStatus
     * @param string $noticeUrl - URL do callback para receber o resultado
     * @return array|null - {success: [...], fail: [...]} ou null em caso de erro
     */
    public function obterStatusReproducao($playerIds, $commands, $noticeUrl)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            // Comandos disponíveis conforme documentação:
            // volumeValue, brightnessValue, videoSourceValue, timeValue, 
            // screenPowerStatus, syncPlayStatus, powerStatus
            $validCommands = [
                'volumeValue', 'brightnessValue', 'videoSourceValue', 
                'timeValue', 'screenPowerStatus', 'syncPlayStatus', 'powerStatus'
            ];

            // Filtra apenas comandos válidos
            $commands = array_intersect($commands, $validCommands);

            if (empty($commands)) {
                Log::error('Nenhum comando válido fornecido para obterStatusReproducao');
                return null;
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/current/running-status", [
                    'playerIds' => $playerIds,
                    'commands' => $commands,
                    'noticeUrl' => $noticeUrl
                ]);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao obter status de reprodução', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao obter status de reprodução: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Ajusta o brilho de um ou múltiplos painéis
     * Conforme documentação: POST /v2/player/real-time-control/brightness
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @param int $nivel - Nível de brilho (0-100)
     * @return array|bool - Array com ['success' => [], 'fail' => []] ou false em caso de erro
     */
    public function ajustarBrilho($playerIds, $nivel)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/real-time-control/brightness", [
                    'playerIds' => $playerIds,
                    'value' => (int) $nivel // 0-100
                ]);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao ajustar brilho', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Erro ao ajustar brilho: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Captura screenshot do que está sendo exibido
     * IMPORTANTE: Esta API é ASSÍNCRONA - requer um noticeUrl para receber o resultado
     * Conforme documentação: POST /v2/player/real-time-control/screen-capture
     * 
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @param string $noticeUrl - URL do callback para receber o screenshot
     * @return array|null - {success: [...], fail: [...]} ou null em caso de erro
     */
    public function capturarScreenshot($playerIds, $noticeUrl)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/real-time-control/screen-capture", [
                    'playerIds' => $playerIds,
                    'noticeUrl' => $noticeUrl
                ]);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao solicitar screenshot', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Erro ao capturar screenshot: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cancela uma exibição emergencial em andamento
     * Conforme documentação: POST /v2/player/emergency-program/cancel
     * 
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @return array|bool - Array com ['success' => [], 'fail' => []] ou false em caso de erro
     */
    public function cancelarExibicaoEmergencial($playerIds)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/emergency-program/cancel", [
                    'playerIds' => $playerIds
                ]);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao cancelar exibição emergencial', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Erro ao cancelar exibição emergencial: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Ajusta o volume de um ou múltiplos painéis
     * Conforme documentação: POST /v2/player/real-time-control/volume
     * 
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @param int $nivel - Nível de volume (0-100)
     * @return array|bool - Array com ['success' => [], 'fail' => []] ou false em caso de erro
     */
    public function ajustarVolume($playerIds, $nivel)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/real-time-control/volume", [
                    'playerIds' => $playerIds,
                    'value' => (int) $nivel // 0-100
                ]);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao ajustar volume', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Erro ao ajustar volume: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reinicia um ou múltiplos players
     * Conforme documentação: POST /v2/player/real-time-control/restart
     * 
     * @param string|array $playerIds - Um playerId ou array de playerIds
     * @return array|bool - Array com ['success' => [], 'fail' => []] ou false em caso de erro
     */
    public function reiniciarPlayer($playerIds)
    {
        try {
            // Garante que seja sempre um array
            if (!is_array($playerIds)) {
                $playerIds = [$playerIds];
            }

            $response = Http::withHeaders($this->getAuthHeaders(true))
                ->post("{$this->apiUrl}/v2/player/real-time-control/restart", [
                    'playerIds' => $playerIds
                ]);

            if ($response->successful()) {
                return $response->json(); // Retorna {success: [...], fail: [...]}
            }

            Log::error('Erro ao reiniciar player', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Erro ao reiniciar player: ' . $e->getMessage());
            return false;
        }
    }
}
