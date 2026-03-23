<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use App\Models\Painel;
use App\Jobs\ProcessarVideoJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('cidadao.videos.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_index_shows_only_user_videos(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Video::factory()->count(3)->create(['user_id' => $user->id]);
        Video::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('cidadao.videos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('videos', function ($videos) {
            return $videos->count() === 3;
        });
    }

    public function test_create_shows_form_with_active_paineis(): void
    {
        $user = User::factory()->create();
        Painel::factory()->count(2)->create(['ativo' => true]);
        Painel::factory()->create(['ativo' => false]);

        $response = $this->actingAs($user)->get(route('cidadao.videos.create'));

        $response->assertStatus(200);
        $response->assertViewHas('paineis', function ($paineis) {
            return $paineis->count() === 2;
        });
    }

    public function test_store_creates_video_and_dispatches_job(): void
    {
        Queue::fake();
        Storage::fake();

        $user = User::factory()->create();
        $painel = Painel::factory()->create();

        $response = $this->actingAs($user)->post(route('cidadao.videos.store'), [
            'titulo' => 'Meu Vídeo',
            'descricao' => 'Descrição do vídeo',
            'video' => UploadedFile::fake()->create('video.mp4', 10000, 'video/mp4'),
            'painel_id' => $painel->id,
            'categoria_video' => 'institucional',
            'plano_segundos' => 15,
        ]);

        $response->assertRedirect(route('cidadao.videos.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('videos', [
            'user_id' => $user->id,
            'titulo' => 'Meu Vídeo',
            'status' => 'processing',
        ]);

        Queue::assertPushed(ProcessarVideoJob::class);
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('cidadao.videos.store'), []);

        $response->assertSessionHasErrors(['titulo', 'video']);
    }

    public function test_show_allows_owner_to_view(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('cidadao.videos.show', $video));

        $response->assertStatus(200);
        $response->assertViewHas('video');
    }

    public function test_show_allows_moderador_to_view_any_video(): void
    {
        $user = User::factory()->create();
        $moderador = User::factory()->moderador()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($moderador)->get(route('cidadao.videos.show', $video));

        $response->assertStatus(200);
    }

    public function test_show_forbids_other_cidadao_from_viewing(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $video = Video::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($otherUser)->get(route('cidadao.videos.show', $video));

        $response->assertStatus(403);
    }

    public function test_destroy_soft_deletes_pending_video(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->delete(route('cidadao.videos.destroy', $video));

        $response->assertRedirect(route('cidadao.videos.index'));
        $this->assertSoftDeleted('videos', ['id' => $video->id]);
    }

    public function test_destroy_blocks_removal_of_approved_video(): void
    {
        $user = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->delete(route('cidadao.videos.destroy', $video));

        $response->assertSessionHasErrors('error');
        $this->assertDatabaseHas('videos', ['id' => $video->id, 'deleted_at' => null]);
    }

    public function test_destroy_forbids_other_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $video = Video::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($otherUser)->delete(route('cidadao.videos.destroy', $video));

        $response->assertStatus(403);
    }
}
