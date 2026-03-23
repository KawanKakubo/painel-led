<?php

namespace Database\Factories;

use App\Models\ConfiguracaoPainel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConfiguracaoPainel>
 */
class ConfiguracaoPainelFactory extends Factory
{
    protected $model = ConfiguracaoPainel::class;

    public function definition(): array
    {
        return [
            'nome' => 'Configuração Principal',
            'vnnox_app_key' => fake()->uuid(),
            'vnnox_app_secret' => fake()->sha256(),
            'vnnox_api_url' => 'https://openapi-us.vnnox.com',
            'ativo' => true,
        ];
    }

    public function inativa(): static
    {
        return $this->state(fn (array $attributes) => [
            'ativo' => false,
        ]);
    }
}
