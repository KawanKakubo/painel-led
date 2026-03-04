<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Coordinate\Dimension;

class VideoProcessingService
{
    /**
     * Processa o vídeo para o formato adequado aos painéis Taurus
     * - Codec: H.264
     * - Container: MP4
     * - Resolução: ajustada ao painel
     * - Bitrate: otimizado
     */
    public function processarVideo($caminhoOriginal, $resolucaoLargura = 1920, $resolucaoAltura = 1080)
    {
        try {
            // Verifica se FFmpeg está disponível
            if (!class_exists('\FFMpeg\FFMpeg')) {
                Log::warning('FFMpeg não instalado. Vídeo não será processado.');
                return [
                    'success' => false,
                    'message' => 'Sistema de processamento de vídeo não configurado'
                ];
            }

            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => config('paineis.ffmpeg.binary_path', '/usr/bin/ffmpeg'),
                'ffprobe.binaries' => config('paineis.ffmpeg.ffprobe_path', '/usr/bin/ffprobe'),
                'timeout'          => 3600,
                'ffmpeg.threads'   => 4,
            ]);

            $video = $ffmpeg->open($caminhoOriginal);

            // Configurar formato H.264
            $format = new X264();
            $format->setKiloBitrate(8000); // 8 Mbps para 1080p
            $format->setAudioCodec('aac');
            $format->setAudioKiloBitrate(192);

            // Nome do arquivo processado
            $nomeArquivo = pathinfo($caminhoOriginal, PATHINFO_FILENAME);
            $caminhoProcessado = storage_path("app/videos/processados/{$nomeArquivo}_processed.mp4");

            // Garantir que o diretório existe
            if (!is_dir(dirname($caminhoProcessado))) {
                mkdir(dirname($caminhoProcessado), 0755, true);
            }

            // Redimensionar para a resolução do painel
            $video
                ->filters()
                ->resize(new Dimension($resolucaoLargura, $resolucaoAltura))
                ->synchronize();

            // Salvar vídeo processado
            $video->save($format, $caminhoProcessado);

            // Obter duração do vídeo
            $duracao = $this->obterDuracaoVideo($caminhoProcessado);

            return [
                'success' => true,
                'caminho' => $caminhoProcessado,
                'duracao_segundos' => $duracao
            ];

        } catch (\Exception $e) {
            Log::error('Erro ao processar vídeo: ' . $e->getMessage(), [
                'arquivo' => $caminhoOriginal
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao processar vídeo: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtém a duração do vídeo em segundos
     */
    public function obterDuracaoVideo($caminhoVideo)
    {
        try {
            if (!class_exists('\FFMpeg\FFMpeg')) {
                return null;
            }

            $ffprobe = \FFMpeg\FFProbe::create([
                'ffprobe.binaries' => config('paineis.ffmpeg.ffprobe_path', '/usr/bin/ffprobe'),
            ]);

            return (int) $ffprobe
                ->format($caminhoVideo)
                ->get('duration');

        } catch (\Exception $e) {
            Log::error('Erro ao obter duração do vídeo: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Valida se o arquivo é um vídeo válido
     */
    public function validarVideo($caminhoVideo)
    {
        $mimeType = mime_content_type($caminhoVideo);
        $mimeTypesValidos = [
            'video/mp4',
            'video/mpeg',
            'video/quicktime',
            'video/x-msvideo',
            'video/x-matroska'
        ];

        if (!in_array($mimeType, $mimeTypesValidos)) {
            return [
                'valid' => false,
                'message' => 'Formato de vídeo não suportado'
            ];
        }

        // Verificar tamanho (máximo 500MB)
        $tamanhoMB = filesize($caminhoVideo) / 1024 / 1024;
        if ($tamanhoMB > 500) {
            return [
                'valid' => false,
                'message' => 'Arquivo muito grande. Máximo: 500MB'
            ];
        }

        // Verificar duração (máximo 2 minutos = 120 segundos)
        $duracao = $this->obterDuracaoVideo($caminhoVideo);
        if ($duracao && $duracao > 120) {
            return [
                'valid' => false,
                'message' => 'Vídeo muito longo. Máximo: 2 minutos'
            ];
        }

        return [
            'valid' => true,
            'duracao' => $duracao,
            'tamanho_mb' => round($tamanhoMB, 2)
        ];
    }

    /**
     * Gera thumbnail do vídeo
     */
    public function gerarThumbnail($caminhoVideo, $segundos = 1)
    {
        try {
            if (!class_exists('\FFMpeg\FFMpeg')) {
                return null;
            }

            $ffmpeg = FFMpeg::create([
                'ffmpeg.binaries'  => config('paineis.ffmpeg.binary_path', '/usr/bin/ffmpeg'),
                'ffprobe.binaries' => config('paineis.ffmpeg.ffprobe_path', '/usr/bin/ffprobe'),
            ]);

            $video = $ffmpeg->open($caminhoVideo);
            
            $nomeArquivo = pathinfo($caminhoVideo, PATHINFO_FILENAME);
            $caminhoThumbnail = storage_path("app/videos/thumbnails/{$nomeArquivo}.jpg");

            // Garantir que o diretório existe
            if (!is_dir(dirname($caminhoThumbnail))) {
                mkdir(dirname($caminhoThumbnail), 0755, true);
            }

            $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($segundos));
            $frame->save($caminhoThumbnail);

            return $caminhoThumbnail;

        } catch (\Exception $e) {
            Log::error('Erro ao gerar thumbnail: ' . $e->getMessage());
            return null;
        }
    }
}
