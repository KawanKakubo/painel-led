<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Agendar limpeza automática de vídeos rejeitados antigos
// Executa toda segunda-feira às 3h da manhã
Schedule::command('videos:limpar-antigos --dias=90')
    ->weeklyOn(1, '03:00')
    ->description('Limpar vídeos rejeitados com mais de 90 dias');
