<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Painel;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'painel_id' => null,
            'titulo' => fake()->sentence(3),
            'descricao' => fake()->paragraph(),
            'arquivo_original' => 'videos/originais/' . fake()->uuid() . '.mp4',
            'arquivo_processado' => null,
            'status' => 'pending',
            'motivo_rejeicao' => null,
            'moderador_id' => null,
            'data_aprovacao' => null,
            'data_rejeicao' => null,
            'data_exibicao' => null,
            'duracao_segundos' => fake()->numberBetween(5, 120),
            'tamanho_bytes' => fake()->numberBetween(100000, 50000000),
            'md5_hash' => md5(fake()->uuid()),
            'vezes_exibido' => 0,
            'categoria_video' => fake()->randomElement(['institucional', 'comercial', 'evento']),
            'plano_segundos' => fake()->randomElement([15, 30, 60]),
            'semana_intencao' => fake()->date(),
            'termo_aceito' => true,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'processing',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'moderador_id' => User::factory()->moderador(),
            'data_aprovacao' => now(),
            'arquivo_processado' => 'videos/processados/' . fake()->uuid() . '_processed.mp4',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'moderador_id' => User::factory()->moderador(),
            'data_rejeicao' => now(),
            'motivo_rejeicao' => fake()->sentence(),
        ]);
    }

    public function displayed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'displayed',
            'moderador_id' => User::factory()->moderador(),
            'data_aprovacao' => now()->subHour(),
            'data_exibicao' => now(),
            'arquivo_processado' => 'videos/processados/' . fake()->uuid() . '_processed.mp4',
            'vezes_exibido' => fake()->numberBetween(1, 10),
        ]);
    }
}
