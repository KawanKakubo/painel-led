<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_admin_returns_true_for_admin_role(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->isAdmin());
    }

    public function test_is_admin_returns_false_for_non_admin_roles(): void
    {
        $cidadao = User::factory()->cidadao()->create();
        $moderador = User::factory()->moderador()->create();

        $this->assertFalse($cidadao->isAdmin());
        $this->assertFalse($moderador->isAdmin());
    }

    public function test_is_moderador_returns_true_for_moderador_and_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $moderador = User::factory()->moderador()->create();

        $this->assertTrue($admin->isModerador());
        $this->assertTrue($moderador->isModerador());
    }

    public function test_is_moderador_returns_false_for_cidadao(): void
    {
        $cidadao = User::factory()->cidadao()->create();

        $this->assertFalse($cidadao->isModerador());
    }

    public function test_is_cidadao_returns_true_only_for_cidadao_role(): void
    {
        $cidadao = User::factory()->cidadao()->create();
        $admin = User::factory()->admin()->create();
        $moderador = User::factory()->moderador()->create();

        $this->assertTrue($cidadao->isCidadao());
        $this->assertFalse($admin->isCidadao());
        $this->assertFalse($moderador->isCidadao());
    }

    public function test_user_has_many_videos(): void
    {
        $user = User::factory()->create();
        Video::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->videos);
    }

    public function test_user_has_many_videos_moderados(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();

        Video::factory()->count(2)->create([
            'user_id' => $user->id,
            'moderador_id' => $moderador->id,
            'status' => 'approved',
            'data_aprovacao' => now(),
        ]);

        $this->assertCount(2, $moderador->videosModeredos);
    }

    public function test_password_is_hidden_on_serialization(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_ativo_is_cast_to_boolean(): void
    {
        $user = User::factory()->create(['ativo' => 1]);

        $this->assertIsBool($user->ativo);
        $this->assertTrue($user->ativo);
    }
}
