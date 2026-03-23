<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Painel;
use App\Models\HistoricoExibicao;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_aprovar_updates_status_and_moderador(): void
    {
        $user = User::factory()->create();
        $moderador = User::factory()->moderador()->create();
        $video = Video::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $video->aprovar($moderador->id);

        $video->refresh();
        $this->assertEquals('approved', $video->status);
        $this->assertEquals($moderador->id, $video->moderador_id);
        $this->assertNotNull($video->data_aprovacao);
    }

    public function test_aprovar_with_painel_id(): void
    {
        $user = User::factory()->create();
        $moderador = User::factory()->moderador()->create();
        $painel = Painel::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $video->aprovar($moderador->id, $painel->id);

        $video->refresh();
        $this->assertEquals($painel->id, $video->painel_id);
    }

    public function test_rejeitar_updates_status_and_motivo(): void
    {
        $user = User::factory()->create();
        $moderador = User::factory()->moderador()->create();
        $video = Video::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $video->rejeitar($moderador->id, 'Conteúdo inadequado');

        $video->refresh();
        $this->assertEquals('rejected', $video->status);
        $this->assertEquals($moderador->id, $video->moderador_id);
        $this->assertEquals('Conteúdo inadequado', $video->motivo_rejeicao);
        $this->assertNotNull($video->data_rejeicao);
    }

    public function test_marcar_como_exibido_increments_counter(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'approved',
            'vezes_exibido' => 0,
        ]);

        $video->marcarComoExibido();

        $video->refresh();
        $this->assertEquals(1, $video->vezes_exibido);
        $this->assertEquals('displayed', $video->status);
        $this->assertNotNull($video->data_exibicao);
    }

    public function test_marcar_como_exibido_does_not_change_status_if_not_approved(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'displayed',
            'vezes_exibido' => 1,
        ]);

        $video->marcarComoExibido();

        $video->refresh();
        $this->assertEquals(2, $video->vezes_exibido);
        // Status remains 'displayed' since conditional only triggers for 'approved'
        $this->assertEquals('displayed', $video->status);
    }

    public function test_duracao_formatada_attribute(): void
    {
        $user = User::factory()->create();

        $video = Video::factory()->create([
            'user_id' => $user->id,
            'duracao_segundos' => 90,
        ]);
        $this->assertEquals('01:30', $video->duracao_formatada);

        $video2 = Video::factory()->create([
            'user_id' => $user->id,
            'duracao_segundos' => 30,
        ]);
        $this->assertEquals('00:30', $video2->duracao_formatada);

        $video3 = Video::factory()->create([
            'user_id' => $user->id,
            'duracao_segundos' => null,
        ]);
        $this->assertNull($video3->duracao_formatada);
    }

    public function test_scope_pendentes(): void
    {
        $user = User::factory()->create();
        Video::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $pendentes = Video::pendentes()->get();

        $this->assertCount(2, $pendentes);
        $pendentes->each(fn ($v) => $this->assertEquals('pending', $v->status));
    }

    public function test_scope_aprovados(): void
    {
        $user = User::factory()->create();
        Video::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'rejected']);

        $aprovados = Video::aprovados()->get();

        $this->assertCount(1, $aprovados);
        $this->assertEquals('approved', $aprovados->first()->status);
    }

    public function test_scope_do_usuario(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Video::factory()->count(2)->create(['user_id' => $user1->id]);
        Video::factory()->count(3)->create(['user_id' => $user2->id]);

        $this->assertCount(2, Video::doUsuario($user1->id)->get());
        $this->assertCount(3, Video::doUsuario($user2->id)->get());
    }

    public function test_video_belongs_to_usuario(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $video->usuario);
        $this->assertEquals($user->id, $video->usuario->id);
    }

    public function test_video_belongs_to_moderador(): void
    {
        $user = User::factory()->create();
        $moderador = User::factory()->moderador()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'moderador_id' => $moderador->id,
        ]);

        $this->assertInstanceOf(User::class, $video->moderador);
        $this->assertEquals($moderador->id, $video->moderador->id);
    }

    public function test_video_belongs_to_painel(): void
    {
        $user = User::factory()->create();
        $painel = Painel::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'painel_id' => $painel->id,
        ]);

        $this->assertInstanceOf(Painel::class, $video->painel);
        $this->assertEquals($painel->id, $video->painel->id);
    }

    public function test_video_has_many_historico_exibicoes(): void
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

        $this->assertCount(2, $video->historicoExibicoes);
    }

    public function test_video_uses_soft_deletes(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);
        $videoId = $video->id;

        $video->delete();

        $this->assertSoftDeleted('videos', ['id' => $videoId]);
        $this->assertNotNull(Video::withTrashed()->find($videoId));
    }
}
