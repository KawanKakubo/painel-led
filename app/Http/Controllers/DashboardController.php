<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Painel;
use App\Models\User;
use App\Models\HistoricoExibicao;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Dashboard do cidadão
     */
    public function cidadao()
    {
        $user = auth()->user();
        
        $estatisticas = [
            'total_videos' => Video::doUsuario($user->id)->count(),
            'pendentes' => Video::doUsuario($user->id)->where('status', 'pending')->count(),
            'aprovados' => Video::doUsuario($user->id)->where('status', 'approved')->count(),
            'exibidos' => Video::doUsuario($user->id)->where('status', 'displayed')->count(),
        ];

        $videos_recentes = Video::doUsuario($user->id)
            ->with('painel')
            ->latest()
            ->limit(5)
            ->get();

        return view('cidadao.dashboard', compact('estatisticas', 'videos_recentes'));
    }

    /**
     * Dashboard administrativo
     */
    public function admin()
    {
        // Estatísticas gerais
        $estatisticas = [
            'total_videos' => Video::count(),
            'pendentes' => Video::where('status', 'pending')->count(),
            'aprovados_hoje' => Video::where('status', 'approved')
                ->whereDate('data_aprovacao', today())
                ->count(),
            'exibidos_hoje' => Video::where('status', 'displayed')
                ->whereDate('data_exibicao', today())
                ->count(),
            'total_cidadaos' => User::where('role', 'cidadao')->count(),
            'paineis_online' => Painel::where('online', true)->count(),
            'total_paineis' => Painel::count(),
        ];

        // Vídeos pendentes de moderação
        $videos_pendentes = Video::pendentes()
            ->with('usuario')
            ->latest()
            ->limit(10)
            ->get();

        // Painéis com status
        $paineis = Painel::orderBy('nome')->get();

        // Últimas exibições
        $ultimas_exibicoes = HistoricoExibicao::with(['video', 'painel'])
            ->latest('data_hora_inicio')
            ->limit(10)
            ->get();

        // Vídeos mais exibidos
        $videos_populares = Video::where('vezes_exibido', '>', 0)
            ->orderBy('vezes_exibido', 'desc')
            ->limit(5)
            ->get();

        // Cidadãos mais ativos
        $cidadaos_ativos = User::where('role', 'cidadao')
            ->withCount('videos')
            ->orderBy('videos_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'estatisticas',
            'videos_pendentes',
            'paineis',
            'ultimas_exibicoes',
            'videos_populares',
            'cidadaos_ativos'
        ));
    }

    /**
     * Configurações do sistema
     */
    public function configuracoes()
    {
        $configuracao = \App\Models\ConfiguracaoPainel::getAtiva();
        
        return view('admin.configuracoes', compact('configuracao'));
    }

    /**
     * Salva configurações do sistema
     */
    public function salvarConfiguracoes(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'vnnox_app_key' => 'required|string',
            'vnnox_app_secret' => 'required|string',
            'vnnox_api_url' => 'required|url',
        ]);

        $configuracao = \App\Models\ConfiguracaoPainel::getAtiva();

        if ($configuracao) {
            $configuracao->update($request->only([
                'vnnox_app_key',
                'vnnox_app_secret',
                'vnnox_api_url'
            ]));
        } else {
            \App\Models\ConfiguracaoPainel::create([
                'nome' => 'Configuração Principal',
                'vnnox_app_key' => $request->vnnox_app_key,
                'vnnox_app_secret' => $request->vnnox_app_secret,
                'vnnox_api_url' => $request->vnnox_api_url,
                'ativo' => true,
            ]);
        }

        return back()->with('success', 'Configurações salvas com sucesso!');
    }
}
