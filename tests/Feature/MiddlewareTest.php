<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    // --- Admin Middleware ---

    public function test_admin_middleware_redirects_unauthenticated_user(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_middleware_blocks_cidadao(): void
    {
        $cidadao = User::factory()->cidadao()->create();

        $response = $this->actingAs($cidadao)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_middleware_blocks_moderador(): void
    {
        $moderador = User::factory()->moderador()->create();

        $response = $this->actingAs($moderador)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_middleware_allows_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    // --- Moderador Middleware ---

    public function test_moderador_middleware_redirects_unauthenticated_user(): void
    {
        $response = $this->get(route('admin.moderacao.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_moderador_middleware_blocks_cidadao(): void
    {
        $cidadao = User::factory()->cidadao()->create();

        $response = $this->actingAs($cidadao)->get(route('admin.moderacao.index'));

        $response->assertStatus(403);
    }

    public function test_moderador_middleware_allows_moderador(): void
    {
        $moderador = User::factory()->moderador()->create();

        $response = $this->actingAs($moderador)->get(route('admin.moderacao.index'));

        $response->assertStatus(200);
    }

    public function test_moderador_middleware_allows_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.moderacao.index'));

        $response->assertStatus(200);
    }
}
