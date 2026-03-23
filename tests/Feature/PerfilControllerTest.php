<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PerfilControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_completar_redirects_if_profile_already_complete(): void
    {
        $user = User::factory()->create(['perfil_completo' => true]);

        $response = $this->actingAs($user)->get(route('cidadao.perfil.completar'));

        $response->assertRedirect(route('cidadao.dashboard'));
    }

    public function test_completar_shows_form_if_profile_incomplete(): void
    {
        $user = User::factory()->perfilIncompleto()->create();

        $response = $this->actingAs($user)->get(route('cidadao.perfil.completar'));

        $response->assertStatus(200);
        $response->assertViewHas('user');
    }

    public function test_salvar_cidadao_profile(): void
    {
        $user = User::factory()->perfilIncompleto()->create();

        $response = $this->actingAs($user)->post(route('cidadao.perfil.salvar'), [
            'tipo_perfil' => 'cidadao',
            'bairro' => 'Centro',
        ]);

        $response->assertRedirect(route('cidadao.dashboard'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('cidadao', $user->tipo_perfil);
        $this->assertEquals('Centro', $user->bairro);
        $this->assertTrue($user->perfil_completo);
        $this->assertNull($user->cnpj);
    }

    public function test_salvar_comerciante_profile(): void
    {
        $user = User::factory()->perfilIncompleto()->create();

        $response = $this->actingAs($user)->post(route('cidadao.perfil.salvar'), [
            'tipo_perfil' => 'comerciante',
            'bairro' => 'Centro',
            'cnpj' => '12.345.678/0001-00',
            'nome_empresa' => 'Loja Teste',
            'ramo_atividade' => 'Alimentação',
        ]);

        $response->assertRedirect(route('cidadao.dashboard'));

        $user->refresh();
        $this->assertEquals('comerciante', $user->tipo_perfil);
        $this->assertEquals('12.345.678/0001-00', $user->cnpj);
        $this->assertEquals('Loja Teste', $user->nome_empresa);
        $this->assertTrue($user->perfil_completo);
    }

    public function test_salvar_comerciante_requires_business_fields(): void
    {
        $user = User::factory()->perfilIncompleto()->create();

        $response = $this->actingAs($user)->post(route('cidadao.perfil.salvar'), [
            'tipo_perfil' => 'comerciante',
            // Missing required fields: cnpj, nome_empresa, ramo_atividade
        ]);

        $response->assertSessionHasErrors(['cnpj', 'nome_empresa', 'ramo_atividade']);
    }

    public function test_salvar_validates_tipo_perfil(): void
    {
        $user = User::factory()->perfilIncompleto()->create();

        $response = $this->actingAs($user)->post(route('cidadao.perfil.salvar'), [
            'tipo_perfil' => 'invalid_type',
        ]);

        $response->assertSessionHasErrors('tipo_perfil');
    }

    public function test_salvar_clears_business_fields_for_cidadao(): void
    {
        $user = User::factory()->perfilIncompleto()->create([
            'cnpj' => '12345678000100',
            'nome_empresa' => 'Old Company',
            'ramo_atividade' => 'Tech',
        ]);

        $response = $this->actingAs($user)->post(route('cidadao.perfil.salvar'), [
            'tipo_perfil' => 'cidadao',
            'bairro' => 'Centro',
        ]);

        $user->refresh();
        $this->assertNull($user->cnpj);
        $this->assertNull($user->nome_empresa);
        $this->assertNull($user->ramo_atividade);
    }
}
