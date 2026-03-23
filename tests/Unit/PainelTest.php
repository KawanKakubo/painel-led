<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Painel;
use App\Models\Video;
use App\Models\HistoricoExibicao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PainelTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_online_returns_true_when_heartbeat_within_5_minutes(): void
    {
        $painel = Painel::factory()->create([
            'ultimo_heartbeat' => now()->subMinutes(3),
        ]);

        $this->assertTrue($painel->isOnline());
    }

    public function test_is_online_returns_false_when_heartbeat_older_than_5_minutes(): void
    {
        $painel = Painel::factory()->create([
            'ultimo_heartbeat' => now()->subMinutes(10),
        ]);

        $this->assertFalse($painel->isOnline());
    }

    public function test_is_online_returns_false_when_no_heartbeat(): void
    {
        $painel = Painel::factory()->create([
            'ultimo_heartbeat' => null,
        ]);

        $this->assertFalse($painel->isOnline());
    }

    public function test_resolucao_attribute_formats_correctly(): void
    {
        $painel = Painel::factory()->create([
            'resolucao_largura' => 1920,
            'resolucao_altura' => 1080,
        ]);

        $this->assertEquals('1920x1080', $painel->resolucao);
    }

    public function test_resolucao_attribute_returns_null_when_missing(): void
    {
        $painel = Painel::factory()->create([
            'resolucao_largura' => null,
            'resolucao_altura' => null,
        ]);

        $this->assertNull($painel->resolucao);
    }

    public function test_painel_has_many_videos(): void
    {
        $user = User::factory()->create();
        $painel = Painel::factory()->create();
        Video::factory()->count(3)->create([
            'user_id' => $user->id,
            'painel_id' => $painel->id,
        ]);

        $this->assertCount(3, $painel->videos);
    }

    public function test_painel_has_many_historico_exibicoes(): void
    {
        $user = User::factory()->create();
        $painel = Painel::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'painel_id' => $painel->id,
        ]);

        HistoricoExibicao::factory()->count(2)->create([
            'video_id' => $video->id,
            'painel_id' => $painel->id,
        ]);

        $this->assertCount(2, $painel->historicoExibicoes);
    }

    public function test_ativo_and_online_are_cast_to_boolean(): void
    {
        $painel = Painel::factory()->create([
            'ativo' => 1,
            'online' => 0,
        ]);

        $this->assertIsBool($painel->ativo);
        $this->assertIsBool($painel->online);
        $this->assertTrue($painel->ativo);
        $this->assertFalse($painel->online);
    }
}
