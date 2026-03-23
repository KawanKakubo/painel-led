<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Painel;
use App\Jobs\ExibirVideoJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;

class ModeracaoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_cidadao_cannot_access_moderacao(): void
    {
        $cidadao = User::factory()->cidadao()->create();

        $response = $this->actingAs($cidadao)->get(route('admin.moderacao.index'));

        $response->assertStatus(403);
    }

    public function test_moderador_can_access_moderacao(): void
    {
        $moderador = User::factory()->moderador()->create();
        
        $response = $this->actingAs($moderador)->get(route('admin.moderacao.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_access_moderacao(): void
    {
        $admin = User::factory()->admin()->create();
        
        $response = $this->actingAs($admin)->get(route('admin.moderacao.index'));

        $response->assertStatus(200);
    }

    public function test_index_lists_pending_videos(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();

        Video::factory()->count(3)->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($moderador)->get(route('admin.moderacao.index'));

        $response->assertStatus(200);
        $response->assertViewHas('videos', function ($videos) {
            return $videos->count() === 3;
        });
    }

    public function test_show_displays_video_for_moderation(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($moderador)->get(route('admin.moderacao.show', $video));

        $response->assertStatus(200);
        $response->assertViewHas('video');
        $response->assertViewHas('paineis');
    }

    public function test_aprovar_changes_video_status(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();
        $painel = Painel::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'painel_id' => $painel->id,
        ]);

        $response = $this->actingAs($moderador)->post(
            route('admin.moderacao.aprovar', $video),
            ['painel_id' => $painel->id]
        );

        $response->assertRedirect(route('admin.moderacao.index'));
        $response->assertSessionHas('success');

        $video->refresh();
        $this->assertEquals('approved', $video->status);
        $this->assertEquals($moderador->id, $video->moderador_id);
    }

    public function test_aprovar_with_exibir_agora_dispatches_job(): void
    {
        Queue::fake();

        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();
        $painel = Painel::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'painel_id' => $painel->id,
        ]);

        $response = $this->actingAs($moderador)->post(
            route('admin.moderacao.aprovar', $video),
            ['painel_id' => $painel->id, 'exibir_agora' => true]
        );

        $response->assertRedirect(route('admin.moderacao.index'));
        Queue::assertPushed(ExibirVideoJob::class);
    }

    public function test_rejeitar_requires_motivo(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($moderador)->post(
            route('admin.moderacao.rejeitar', $video),
            []
        );

        $response->assertSessionHasErrors('motivo');
    }

    public function test_rejeitar_changes_video_status(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($moderador)->post(
            route('admin.moderacao.rejeitar', $video),
            ['motivo' => 'Conteúdo impróprio']
        );

        $response->assertRedirect(route('admin.moderacao.index'));

        $video->refresh();
        $this->assertEquals('rejected', $video->status);
        $this->assertEquals('Conteúdo impróprio', $video->motivo_rejeicao);
    }

    public function test_historico_lists_all_videos_with_filters(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();

        Video::factory()->create(['user_id' => $user->id, 'status' => 'approved']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'rejected']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'pending']);

        $response = $this->actingAs($moderador)->get(route('admin.moderacao.historico'));

        $response->assertStatus(200);
        $response->assertViewHas('videos');
        $response->assertViewHas('paineis');
    }

    public function test_historico_filters_by_status(): void
    {
        $moderador = User::factory()->moderador()->create();
        $user = User::factory()->create();

        Video::factory()->count(2)->create(['user_id' => $user->id, 'status' => 'approved']);
        Video::factory()->create(['user_id' => $user->id, 'status' => 'rejected']);

        $response = $this->actingAs($moderador)->get(
            route('admin.moderacao.historico', ['status' => 'approved'])
        );

        $response->assertStatus(200);
        $response->assertViewHas('videos', function ($videos) {
            return $videos->count() === 2;
        });
    }
}
