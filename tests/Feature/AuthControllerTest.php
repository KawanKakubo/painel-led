<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\GovAssaiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_login_success_creates_user_and_redirects_cidadao(): void
    {
        $mockService = Mockery::mock(GovAssaiService::class);
        $mockService->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    'id' => 'gov-123',
                    'nome' => 'João da Silva',
                    'cpf' => '12345678901',
                    'email' => 'joao@test.com',
                    'celular' => '44999999999',
                    'nivel_acesso' => 1,
                ],
            ]);

        $this->app->instance(GovAssaiService::class, $mockService);

        $response = $this->post(route('login.submit'), [
            'cpf' => '123.456.789-01',
            'senha' => 'password123',
        ]);

        $response->assertRedirect(route('cidadao.dashboard'));
        $this->assertDatabaseHas('users', [
            'gov_assai_id' => 'gov-123',
            'name' => 'João da Silva',
            'cpf' => '12345678901',
        ]);
        $this->assertAuthenticated();
    }

    public function test_login_success_redirects_admin_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create([
            'gov_assai_id' => 'gov-admin',
        ]);

        $mockService = Mockery::mock(GovAssaiService::class);
        $mockService->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    'id' => 'gov-admin',
                    'nome' => $admin->name,
                    'cpf' => $admin->cpf,
                    'email' => $admin->email,
                    'celular' => $admin->celular,
                    'nivel_acesso' => 3,
                ],
            ]);

        $this->app->instance(GovAssaiService::class, $mockService);

        $response = $this->post(route('login.submit'), [
            'cpf' => $admin->cpf,
            'senha' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_failure_returns_with_errors(): void
    {
        $mockService = Mockery::mock(GovAssaiService::class);
        $mockService->shouldReceive('autenticar')
            ->once()
            ->andReturn([
                'success' => false,
                'message' => 'CPF não encontrado.',
            ]);

        $this->app->instance(GovAssaiService::class, $mockService);

        $response = $this->post(route('login.submit'), [
            'cpf' => '00000000000',
            'senha' => 'wrongpass',
        ]);

        $response->assertSessionHasErrors('cpf');
        $this->assertGuest();
    }

    public function test_login_validates_required_fields(): void
    {
        $response = $this->post(route('login.submit'), []);

        $response->assertSessionHasErrors(['cpf', 'senha']);
    }

    public function test_logout_clears_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_check_cpf_returns_json(): void
    {
        $mockService = Mockery::mock(GovAssaiService::class);
        $mockService->shouldReceive('verificarCPF')
            ->once()
            ->with('12345678901')
            ->andReturn([
                'success' => true,
                'exists' => true,
                'cpf' => '12345678901',
            ]);

        $this->app->instance(GovAssaiService::class, $mockService);

        $response = $this->postJson(route('check.cpf'), [
            'cpf' => '12345678901',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'exists' => true,
            ]);
    }
}
