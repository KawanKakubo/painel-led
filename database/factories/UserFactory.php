<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'gov_assai_id' => fake()->unique()->uuid(),
            'cpf' => fake()->unique()->numerify('###########'),
            'celular' => fake()->numerify('##9########'),
            'nivel_acesso' => 1,
            'role' => 'cidadao',
            'ativo' => true,
            'tipo_perfil' => 'cidadao',
            'perfil_completo' => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'nivel_acesso' => 3,
        ]);
    }

    public function moderador(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'moderador',
            'nivel_acesso' => 2,
        ]);
    }

    public function cidadao(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'cidadao',
            'nivel_acesso' => 1,
        ]);
    }

    public function comerciante(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_perfil' => 'comerciante',
            'cnpj' => fake()->numerify('##.###.###/####-##'),
            'nome_empresa' => fake()->company(),
            'ramo_atividade' => fake()->randomElement(['Alimentação', 'Vestuário', 'Tecnologia']),
            'bairro' => fake()->streetName(),
            'perfil_completo' => true,
        ]);
    }

    public function perfilIncompleto(): static
    {
        return $this->state(fn (array $attributes) => [
            'perfil_completo' => false,
        ]);
    }
}
