<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Video;
use App\Models\Painel;
use App\Jobs\ProcessarVideoJob;

class VideoController extends Controller
{
    /**
     * Lista os vídeos do cidadão logado
     */
    public function index()
    {
        $videos = Video::doUsuario(auth()->id())
            ->with('painel')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cidadao.videos.index', compact('videos'));
    }

    /**
     * Exibe formulário de envio de vídeo
     */
    public function create()
    {
        $paineis = Painel::where('ativo', true)->get();
        
        return view('cidadao.videos.create', compact('paineis'));
    }

    /**
     * Armazena um novo vídeo
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'video' => 'required|file|mimes:mp4,mov,avi,mkv|max:512000', // 500MB
            'painel_id' => 'nullable|exists:paineis,id'
        ]);

        // Salvar arquivo original
        $arquivoOriginal = $request->file('video')->store('videos/originais');

        // Criar registro do vídeo
        $video = Video::create([
            'user_id' => auth()->id(),
            'painel_id' => $request->painel_id,
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'arquivo_original' => $arquivoOriginal,
            'status' => 'processing'
        ]);

        // Disparar job de processamento
        ProcessarVideoJob::dispatch($video);

        return redirect()
            ->route('cidadao.videos.index')
            ->with('success', 'Vídeo enviado com sucesso! Aguarde o processamento e aprovação.');
    }

    /**
     * Exibe detalhes de um vídeo
     */
    public function show(Video $video)
    {
        // Verificar se o usuário pode ver este vídeo
        if ($video->user_id !== auth()->id() && !auth()->user()->isModerador()) {
            abort(403);
        }

        $video->load(['usuario', 'moderador', 'painel', 'historicoExibicoes']);

        return view('cidadao.videos.show', compact('video'));
    }

    /**
     * Remove um vídeo (soft delete)
     */
    public function destroy(Video $video)
    {
        // Verificar se o usuário pode deletar este vídeo
        if ($video->user_id !== auth()->id()) {
            abort(403);
        }

        // Não permitir deletar vídeos já aprovados ou exibidos
        if (in_array($video->status, ['approved', 'displayed'])) {
            return back()->withErrors(['error' => 'Não é possível remover vídeos aprovados ou já exibidos.']);
        }

        $video->delete();

        return redirect()
            ->route('cidadao.videos.index')
            ->with('success', 'Vídeo removido com sucesso.');
    }
}
