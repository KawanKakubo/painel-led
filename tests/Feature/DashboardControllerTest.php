<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Painel;
use App\Models\ConfiguracaoPainel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cidadao_dashboard_redirects_if_profile_incomplete(): void
    {
        $user = User::factory()->perfilIncompleto()->create();

        $response = $this->actingAs($user)->get(route('cidadao.dashboard'));

        $response->assertRedirect(route('cidadao.perfil.completar'));
    }

    public function test_cidadao_dashboard_shows_statistics(): void
    {
        $user = User::factory()->create(['perfil_completo' => true]);

        Video::factory()->count(2)->create(['user_id' => $user->id, 'status' => 'pending']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'displayed']);

        $response = $this->actingAs($user)->get(route('cidadao.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('estatisticas', function ($stats) {
            return $stats['total_videos'] === 4
                && $stats['pendentes'] === 2
                && $stats['aprovados'] === 1
                && $stats['exibidos'] === 1;
        });
        $response->assertViewHas('videos_recentes');
    }

    public function test_admin_dashboard_requires_admin_role(): void
    {
        $cidadao = User::factory()->cidadao()->create();

        $response = $this->actingAs($cidadao)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_dashboard_shows_statistics(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Video::factory()->count(3)->create(['user_id' => $user->id, 'status' => 'pending']);
        Painel::factory()->count(2)->create(['online' => true]);
        Painel::factory()->create(['online' => false]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('estatisticas');
        $response->assertViewHas('videos_pendentes');
        $response->assertViewHas('paineis');
    }

    public function test_configuracoes_page_accessible_by_admin(): void
    {
        $admin = User::factory()->admin()->create();
        ConfiguracaoPainel::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.configuracoes'));

        $response->assertStatus(200);
        $response->assertViewHas('configuracao');
    }

    public function test_salvar_configuracoes_updates_existing(): void
    {
        $admin = User::factory()->admin()->create();
        $config = ConfiguracaoPainel::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.configuracoes.salvar'), [
            'vnnox_app_key' => 'new-key-123',
            'vnnox_app_secret' => 'new-secret-456',
            'vnnox_api_url' => 'https://api.vnnox.com/v2',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $config->refresh();
        $this->assertEquals('new-key-123', $config->vnnox_app_key);
    }

    public function test_salvar_configuracoes_creates_new_when_none_exists(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.configuracoes.salvar'), [
            'vnnox_app_key' => 'first-key',
            'vnnox_app_secret' => 'first-secret',
            'vnnox_api_url' => 'https://api.vnnox.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('configuracoes_painel', [
            'vnnox_app_key' => 'first-key',
            'ativo' => true,
        ]);
    }

    public function test_salvar_configuracoes_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.configuracoes.salvar'), []);

        $response->assertSessionHasErrors(['vnnox_app_key', 'vnnox_app_secret', 'vnnox_api_url']);
    }
}
