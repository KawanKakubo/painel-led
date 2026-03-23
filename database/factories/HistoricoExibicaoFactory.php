<?php

namespace Database\Factories;

use App\Models\HistoricoExibicao;
use App\Models\Video;
use App\Models\Painel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoricoExibicao>
 */
class HistoricoExibicaoFactory extends Factory
{
    protected $model = HistoricoExibicao::class;

    public function definition(): array
    {
        return [
            'video_id' => Video::factory(),
            'painel_id' => Painel::factory(),
            'data_hora_inicio' => now(),
            'data_hora_fim' => now()->addSeconds(30),
            'exibicao_completa' => true,
            'observacoes' => null,
        ];
    }

    public function incompleta(): static
    {
        return $this->state(fn (array $attributes) => [
            'exibicao_completa' => false,
            'data_hora_fim' => null,
            'observacoes' => 'Exibição interrompida',
        ]);
    }
}
