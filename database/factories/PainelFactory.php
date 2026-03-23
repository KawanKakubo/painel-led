<?php

namespace Database\Factories;

use App\Models\Painel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Painel>
 */
class PainelFactory extends Factory
{
    protected $model = Painel::class;

    public function definition(): array
    {
        return [
            'player_id' => fake()->unique()->uuid(),
            'nome' => 'Painel ' . fake()->streetName(),
            'localizacao' => fake()->address(),
            'resolucao_largura' => 1920,
            'resolucao_altura' => 1080,
            'ativo' => true,
            'online' => false,
            'ultimo_heartbeat' => null,
        ];
    }

    public function online(): static
    {
        return $this->state(fn (array $attributes) => [
            'online' => true,
            'ultimo_heartbeat' => now(),
        ]);
    }

    public function offline(): static
    {
        return $this->state(fn (array $attributes) => [
            'online' => false,
            'ultimo_heartbeat' => now()->subMinutes(30),
        ]);
    }

    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'ativo' => false,
        ]);
    }
}
