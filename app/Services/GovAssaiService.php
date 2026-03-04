<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GovAssaiService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('paineis.gov_assai.api_url');
    }

    /**
     * Autentica um cidadão usando CPF e senha do gov.assaí
     * 
     * @param string $cpf
     * @param string $senha
     * @return array
     */
    public function autenticar($cpf, $senha)
    {
        try {
            $response = Http::post("{$this->apiUrl}/api/cidadao/authenticate", [
                'cpf' => $this->limparCPF($cpf),
                'senha' => $senha
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success'] ?? false) {
                    return [
                        'success' => true,
                        'data' => $data['data']
                    ];
                }
                
                // Tratar códigos de erro específicos
                $errorCode = $data['error_code'] ?? null;
                $message = $this->getErrorMessage($errorCode, $data['message'] ?? 'Erro na autenticação');
                
                return [
                    'success' => false,
                    'error_code' => $errorCode,
                    'message' => $message
                ];
            }

            return [
                'success' => false,
                'message' => 'Não foi possível conectar ao gov.assaí'
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao autenticar no gov.assaí: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro ao conectar com o sistema gov.assaí'
            ];
        }
    }

    /**
     * Verifica se um CPF existe no gov.assaí
     * 
     * @param string $cpf
     * @return array
     */
    public function verificarCPF($cpf)
    {
        try {
            $response = Http::post("{$this->apiUrl}/api/cidadao/check-cpf", [
                'cpf' => $this->limparCPF($cpf)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => $data['success'] ?? false,
                    'exists' => $data['exists'] ?? false,
                    'cpf' => $data['cpf'] ?? null
                ];
            }

            return [
                'success' => false,
                'exists' => false
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao verificar CPF no gov.assaí: ' . $e->getMessage());
            
            return [
                'success' => false,
                'exists' => false
            ];
        }
    }

    /**
     * Obtém informações sobre os níveis de acesso
     * 
     * @return array|null
     */
    public function obterNiveisInfo()
    {
        try {
            $response = Http::get("{$this->apiUrl}/api/cidadao/niveis-info");

            if ($response->successful()) {
                return $response->json();
            }

            return null;

        } catch (\Exception $e) {
            Log::error('Erro ao obter níveis de acesso: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Remove máscara do CPF
     * 
     * @param string $cpf
     * @return string
     */
    private function limparCPF($cpf)
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    /**
     * Retorna mensagem de erro amigável baseada no código
     * 
     * @param string|null $errorCode
     * @param string $defaultMessage
     * @return string
     */
    private function getErrorMessage($errorCode, $defaultMessage)
    {
        $messages = [
            'CPF_NOT_FOUND' => 'CPF não encontrado. Você precisa se cadastrar no gov.assaí primeiro.',
            'INVALID_PASSWORD' => 'Senha incorreta. Tente novamente.',
            'ACCOUNT_INACTIVE' => 'Sua conta gov.assaí está inativa. Entre em contato com a prefeitura.',
            'VALIDATION_ERROR' => 'CPF ou senha inválidos.'
        ];

        return $messages[$errorCode] ?? $defaultMessage;
    }

    /**
     * Formata CPF com máscara
     * 
     * @param string $cpf
     * @return string
     */
    public function formatarCPF($cpf)
    {
        $cpf = $this->limparCPF($cpf);
        
        if (strlen($cpf) === 11) {
            return substr($cpf, 0, 3) . '.' . 
                   substr($cpf, 3, 3) . '.' . 
                   substr($cpf, 6, 3) . '-' . 
                   substr($cpf, 9, 2);
        }
        
        return $cpf;
    }
}
