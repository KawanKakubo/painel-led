<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Painel;
use App\Jobs\ExibirVideoJob;

class ModeracaoController extends Controller
{
    /**
     * Lista vídeos pendentes de moderação
     */
    public function index()
    {
        $videos = Video::pendentes()
            ->with(['usuario', 'painel'])
            ->orderBy('created_at', 'asc')
            ->paginate(15);

        return view('admin.moderacao.index', compact('videos'));
    }

    /**
     * Exibe detalhes do vídeo para moderação
     */
    public function show(Video $video)
    {
        $video->load(['usuario', 'painel']);
        $paineis = Painel::where('ativo', true)->get();

        return view('admin.moderacao.show', compact('video', 'paineis'));
    }

    /**
     * Aprova um vídeo
     */
    public function aprovar(Request $request, Video $video)
    {
        $request->validate([
            'painel_id' => 'required|exists:paineis,id'
        ]);

        $video->aprovar(auth()->id(), $request->painel_id);

        // Exibir imediatamente se solicitado
        if ($request->has('exibir_agora')) {
            ExibirVideoJob::dispatch($video);
            
            return redirect()
                ->route('admin.moderacao.index')
                ->with('success', 'Vídeo aprovado e enviado para exibição!');
        }

        return redirect()
            ->route('admin.moderacao.index')
            ->with('success', 'Vídeo aprovado com sucesso!');
    }

    /**
     * Rejeita um vídeo
     */
    public function rejeitar(Request $request, Video $video)
    {
        $request->validate([
            'motivo' => 'required|string|max:500'
        ]);

        $video->rejeitar(auth()->id(), $request->motivo);

        return redirect()
            ->route('admin.moderacao.index')
            ->with('success', 'Vídeo rejeitado.');
    }

    /**
     * Lista todos os vídeos (aprovados, rejeitados, exibidos)
     */
    public function historico(Request $request)
    {
        $query = Video::with(['usuario', 'moderador', 'painel'])
            ->orderBy('created_at', 'desc');

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtro por painel
        if ($request->filled('painel_id')) {
            $query->where('painel_id', $request->painel_id);
        }

        $videos = $query->paginate(20);
        $paineis = Painel::all();

        return view('admin.moderacao.historico', compact('videos', 'paineis'));
    }
}
