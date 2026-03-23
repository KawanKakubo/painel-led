<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Painel;
use App\Models\ConfiguracaoPainel;
use App\Services\VNNOXService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class PainelControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();

        // Create default VNNOX config so VNNOXService doesn't fail on construct
        ConfiguracaoPainel::factory()->create();
    }

    public function test_cidadao_cannot_access_paineis(): void
    {
        $cidadao = User::factory()->cidadao()->create();

        $response = $this->actingAs($cidadao)->get(route('admin.paineis.index'));

        $response->assertStatus(403);
    }

    public function test_moderador_cannot_access_paineis(): void
    {
        $moderador = User::factory()->moderador()->create();

        $response = $this->actingAs($moderador)->get(route('admin.paineis.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_list_paineis(): void
    {
        Painel::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.paineis.index'));

        $response->assertStatus(200);
        $response->assertViewHas('paineis', function ($paineis) {
            return $paineis->count() === 3;
        });
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.paineis.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_store_painel(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.paineis.store'), [
            'player_id' => 'player-test-123',
            'nome' => 'Painel Teste',
            'localizacao' => 'Centro da Cidade',
            'resolucao_largura' => 1920,
            'resolucao_altura' => 1080,
        ]);

        $response->assertRedirect(route('admin.paineis.index'));
        $this->assertDatabaseHas('paineis', [
            'player_id' => 'player-test-123',
            'nome' => 'Painel Teste',
        ]);
    }

    public function test_store_validates_unique_player_id(): void
    {
        Painel::factory()->create(['player_id' => 'duplicate-id']);

        $response = $this->actingAs($this->admin)->post(route('admin.paineis.store'), [
            'player_id' => 'duplicate-id',
            'nome' => 'Painel Duplicado',
        ]);

        $response->assertSessionHasErrors('player_id');
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.paineis.store'), []);

        $response->assertSessionHasErrors(['player_id', 'nome']);
    }

    public function test_admin_can_show_painel(): void
    {
        $painel = Painel::factory()->create();

        $mockVnnox = Mockery::mock(VNNOXService::class);
        $mockVnnox->shouldReceive('verificarStatusPlayer')
            ->once()
            ->andReturn(['online' => true]);
        $this->app->instance(VNNOXService::class, $mockVnnox);

        $response = $this->actingAs($this->admin)->get(route('admin.paineis.show', $painel));

        $response->assertStatus(200);
        $response->assertViewHas('painel');
        $response->assertViewHas('status');
    }

    public function test_admin_can_edit_painel(): void
    {
        $painel = Painel::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('admin.paineis.edit', $painel));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_painel(): void
    {
        $painel = Painel::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('admin.paineis.update', $painel), [
            'nome' => 'Painel Atualizado',
            'localizacao' => 'Nova Localização',
            'resolucao_largura' => 3840,
            'resolucao_altura' => 2160,
        ]);

        $response->assertRedirect(route('admin.paineis.show', $painel));

        $painel->refresh();
        $this->assertEquals('Painel Atualizado', $painel->nome);
        $this->assertEquals('Nova Localização', $painel->localizacao);
    }

    public function test_sincronizar_creates_paineis_from_vnnox(): void
    {
        $mockVnnox = Mockery::mock(VNNOXService::class);
        $mockVnnox->shouldReceive('listarPlayers')
            ->once()
            ->andReturn([
                'data' => [
                    ['player_id' => 'vnnox-1', 'name' => 'Painel 1', 'online' => true],
                    ['player_id' => 'vnnox-2', 'name' => 'Painel 2', 'online' => false],
                ],
            ]);
        $this->app->instance(VNNOXService::class, $mockVnnox);

        $response = $this->actingAs($this->admin)->post(route('admin.paineis.sincronizar'));

        $response->assertRedirect(route('admin.paineis.index'));
        $this->assertDatabaseHas('paineis', ['player_id' => 'vnnox-1']);
        $this->assertDatabaseHas('paineis', ['player_id' => 'vnnox-2']);
    }

    public function test_sincronizar_handles_api_failure(): void
    {
        $mockVnnox = Mockery::mock(VNNOXService::class);
        $mockVnnox->shouldReceive('listarPlayers')
            ->once()
            ->andReturn(null);
        $this->app->instance(VNNOXService::class, $mockVnnox);

        $response = $this->actingAs($this->admin)->post(route('admin.paineis.sincronizar'));

        $response->assertSessionHasErrors('error');
    }

    public function test_verificar_status_returns_json(): void
    {
        $painel = Painel::factory()->create();

        $mockVnnox = Mockery::mock(VNNOXService::class);
        $mockVnnox->shouldReceive('verificarStatusPlayer')
            ->once()
            ->andReturn(['online' => true]);
        $this->app->instance(VNNOXService::class, $mockVnnox);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.paineis.status', $painel)
        );

        $response->assertOk()
            ->assertJson(['success' => true]);
    }

    public function test_ajustar_brilho_validates_nivel(): void
    {
        $painel = Painel::factory()->create();

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.paineis.brilho', $painel),
            ['nivel' => 150] // invalid: > 100
        );

        $response->assertStatus(422);
    }

    public function test_ajustar_brilho_success(): void
    {
        $painel = Painel::factory()->create();

        $mockVnnox = Mockery::mock(VNNOXService::class);
        $mockVnnox->shouldReceive('ajustarBrilho')
            ->once()
            ->andReturn(['success' => [$painel->player_id], 'fail' => []]);
        $this->app->instance(VNNOXService::class, $mockVnnox);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.paineis.brilho', $painel),
            ['nivel' => 75]
        );

        $response->assertOk()
            ->assertJson(['success' => true]);
    }

    public function test_cancelar_emergencia_success(): void
    {
        $painel = Painel::factory()->create();

        $mockVnnox = Mockery::mock(VNNOXService::class);
        $mockVnnox->shouldReceive('cancelarExibicaoEmergencial')
            ->once()
            ->andReturn(['success' => [$painel->player_id], 'fail' => []]);
        $this->app->instance(VNNOXService::class, $mockVnnox);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.paineis.cancelar-emergencia', $painel)
        );

        $response->assertOk()
            ->assertJson(['success' => true]);
    }
}
