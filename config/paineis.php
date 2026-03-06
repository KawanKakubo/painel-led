<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações da API VNNOX (NovaStar)
    |--------------------------------------------------------------------------
    |
    | Credenciais e configurações para integração com a plataforma VNNOX
    | que gerencia os painéis de LED Taurus.
    |
    | IMPORTANTE: A URL da API varia por região:
    | - US: https://openapi-us.vnnox.com
    | - EU: https://openapi-eu.vnnox.com
    | - CN: https://openapi-cn.vnnox.com
    |
    | Rate Limits:
    | - Máximo 15 chamadas por segundo por IP
    | - Máximo 1500 chamadas por hora por IP
    |
    */

    'vnnox' => [
        'app_key' => env('VNNOX_APP_KEY', ''),
        'app_secret' => env('VNNOX_APP_SECRET', ''),
        'api_url' => env('VNNOX_API_URL', 'https://open-us.vnnox.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações da API gov.assaí
    |--------------------------------------------------------------------------
    |
    | URL da API do sistema gov.assaí para autenticação de cidadãos.
    |
    */

    'gov_assai' => [
        'api_url' => env('GOV_ASSAI_API_URL', 'https://gov.assai.pr.gov.br'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações do FFmpeg
    |--------------------------------------------------------------------------
    |
    | Caminhos para os binários do FFmpeg e FFprobe usados no processamento
    | de vídeos.
    |
    */

    'ffmpeg' => [
        'binary_path' => env('FFMPEG_BINARY_PATH', '/usr/bin/ffmpeg'),
        'ffprobe_path' => env('FFPROBE_BINARY_PATH', '/usr/bin/ffprobe'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Vídeo
    |--------------------------------------------------------------------------
    |
    | Limites e configurações para upload e processamento de vídeos.
    |
    */

    'video' => [
        'max_size_mb' => env('VIDEO_MAX_SIZE_MB', 500),
        'max_duration_seconds' => env('VIDEO_MAX_DURATION_SECONDS', 120),
        'default_bitrate_1080p' => 8000, // 8 Mbps
        'default_bitrate_4k' => 40000, // 40 Mbps
        'allowed_formats' => ['mp4', 'mov', 'avi', 'mkv'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Moderação
    |--------------------------------------------------------------------------
    |
    | Configurações relacionadas ao processo de moderação de conteúdo.
    |
    */

    'moderacao' => [
        'auto_reject_enabled' => env('AUTO_REJECT_ENABLED', true),
        'notification_enabled' => env('MODERACAO_NOTIFICATION_ENABLED', true),
    ],
];
