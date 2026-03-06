#!/usr/bin/env php
<?php

/**
 * Script de Teste da Integração VNNOX
 * 
 * Execute este script para validar a configuração da API VNNOX:
 * php teste-vnnox.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Services\VNNOXService;
use Illuminate\Support\Facades\Log;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║         TESTE DE INTEGRAÇÃO VNNOX - NovaCloud Platform         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";
echo "\n";

// Cores para output
function green($text) { return "\033[32m$text\033[0m"; }
function red($text) { return "\033[31m$text\033[0m"; }
function yellow($text) { return "\033[33m$text\033[0m"; }
function blue($text) { return "\033[34m$text\033[0m"; }

// 1. Verificar configuração
echo blue("1. Verificando configuração...\n");

$appKey = env('VNNOX_APP_KEY');
$appSecret = env('VNNOX_APP_SECRET');
$apiUrl = env('VNNOX_API_URL');

if (empty($appKey)) {
    echo red("✗ VNNOX_APP_KEY não configurado no .env\n");
    exit(1);
}
echo green("✓ VNNOX_APP_KEY: " . substr($appKey, 0, 8) . "...\n");

if (empty($appSecret)) {
    echo red("✗ VNNOX_APP_SECRET não configurado no .env\n");
    exit(1);
}
echo green("✓ VNNOX_APP_SECRET: " . substr($appSecret, 0, 8) . "...\n");

if (empty($apiUrl)) {
    echo red("✗ VNNOX_API_URL não configurado no .env\n");
    exit(1);
}
echo green("✓ VNNOX_API_URL: $apiUrl\n");

// Verificar se URL é correta
if (!str_contains($apiUrl, 'openapi-')) {
    echo yellow("⚠ A URL deve ser 'openapi-{region}.vnnox.com' (ex: openapi-us.vnnox.com)\n");
    echo yellow("  URL atual: $apiUrl\n");
}

echo "\n";

// 2. Verificar timestamp
echo blue("2. Verificando sincronização de tempo...\n");

$timestamp = time();
$datetime = date('Y-m-d H:i:s T', $timestamp);
echo green("✓ Timestamp local: $timestamp ($datetime)\n");

// Verificar se NTP está sincronizado (Linux)
if (PHP_OS_FAMILY === 'Linux') {
    exec('timedatectl status | grep "System clock synchronized"', $output);
    if (!empty($output) && str_contains($output[0], 'yes')) {
        echo green("✓ Relógio sincronizado com NTP\n");
    } else {
        echo yellow("⚠ Relógio pode não estar sincronizado. Execute: sudo timedatectl set-ntp true\n");
    }
}

echo "\n";

// 3. Testar conectividade
echo blue("3. Testando conectividade com VNNOX...\n");

try {
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode > 0) {
        echo green("✓ Servidor VNNOX acessível (HTTP $httpCode)\n");
    } else {
        echo red("✗ Não foi possível conectar ao servidor VNNOX\n");
        exit(1);
    }
} catch (Exception $e) {
    echo red("✗ Erro de conectividade: " . $e->getMessage() . "\n");
    exit(1);
}

echo "\n";

// 4. Testar autenticação
echo blue("4. Testando autenticação e listagem de players...\n");

try {
    $vnnox = new VNNOXService();
    
    echo "   Gerando credenciais de autenticação...\n";
    
    // Usar reflexão para acessar método privado (apenas para teste)
    $reflection = new ReflectionClass($vnnox);
    
    $generateNonce = $reflection->getMethod('generateNonce');
    $generateNonce->setAccessible(true);
    $nonce = $generateNonce->invoke($vnnox);
    echo "   Nonce: $nonce (tamanho: " . strlen($nonce) . " chars)\n";
    
    $curTime = (string) time();
    echo "   CurTime: $curTime\n";
    
    $generateCheckSum = $reflection->getMethod('generateCheckSum');
    $generateCheckSum->setAccessible(true);
    $checkSum = $generateCheckSum->invoke($vnnox, $nonce, $curTime);
    echo "   CheckSum: $checkSum\n";
    
    echo "\n   Enviando requisição para /v2/player/list...\n";
    
    $result = $vnnox->listarPlayers();
    
    if ($result === null) {
        echo red("✗ Falha na requisição. Verifique os logs em storage/logs/laravel.log\n");
        echo yellow("\nPossíveis causas:\n");
        echo yellow("  - AppKey ou AppSecret incorretos\n");
        echo yellow("  - Conta não autenticada empresarialmente na plataforma VNNOX\n");
        echo yellow("  - Diferença de tempo > 5 minutos entre cliente e servidor\n");
        echo yellow("  - Firewall bloqueando conexão HTTPS\n");
        exit(1);
    }
    
    if (isset($result['error'])) {
        echo red("✗ Erro da API VNNOX:\n");
        echo red("  Código: " . $result['error']['code'] . "\n");
        echo red("  Mensagem: " . $result['error']['message'] . "\n");
        
        if ($result['error']['code'] === 'INVALID_APPKEY') {
            echo yellow("\n  Solução: Verifique se o AppKey está correto no .env\n");
        } elseif ($result['error']['code'] === 'INVALID_CHECKSUM') {
            echo yellow("\n  Solução: Verifique se o AppSecret está correto\n");
            echo yellow("           Sincronize o relógio do servidor com NTP\n");
        } elseif ($result['error']['code'] === 'EXPIRED_REQUEST') {
            echo yellow("\n  Solução: Diferença de tempo > 5 minutos\n");
            echo yellow("           Execute: sudo timedatectl set-ntp true (Linux)\n");
            echo yellow("           Execute: w32tm /resync (Windows Admin)\n");
        }
        
        exit(1);
    }
    
    echo green("✓ Autenticação bem-sucedida!\n");
    
    if (isset($result['data']) && is_array($result['data'])) {
        $count = count($result['data']);
        echo green("✓ Total de players encontrados: $count\n\n");
        
        if ($count > 0) {
            echo blue("   Players cadastrados:\n");
            foreach ($result['data'] as $index => $player) {
                $num = $index + 1;
                $id = $player['player_id'] ?? 'N/A';
                $name = $player['name'] ?? $player['player_name'] ?? 'Sem nome';
                $status = $player['status'] ?? 'unknown';
                
                $statusColor = $status === 'online' ? 'green' : 'red';
                $statusIcon = $status === 'online' ? '●' : '○';
                
                echo "   $num. $name\n";
                echo "      ID: $id\n";
                echo "      Status: " . $$statusColor($statusIcon . " " . strtoupper($status)) . "\n";
                
                if ($index < $count - 1) echo "\n";
            }
        } else {
            echo yellow("⚠ Nenhum player cadastrado ainda\n");
            echo yellow("  Vincule devices TB/TU/T no VNNOX Media usando ViPlex Express\n");
        }
    }
    
} catch (Exception $e) {
    echo red("✗ Exceção capturada: " . $e->getMessage() . "\n");
    echo red("  Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n");
    exit(1);
}

echo "\n";

// 5. Resumo
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║                        TESTE CONCLUÍDO                         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n";

echo "\n";
echo green("✓ Integração VNNOX está funcionando corretamente!\n");
echo "\n";
echo blue("Próximos passos:\n");
echo "  1. Acesse http://127.0.0.1:8000/admin/paineis para gerenciar players\n";
echo "  2. Sincronize players usando o botão 'Sincronizar com VNNOX'\n";
echo "  3. Teste upload de vídeo como cidadão\n";
echo "  4. Aprove o vídeo como moderador\n";
echo "  5. Verifique a exibição no painel físico\n";
echo "\n";

exit(0);
