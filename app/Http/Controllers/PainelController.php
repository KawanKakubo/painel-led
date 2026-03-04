<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Painel;
use App\Models\ConfiguracaoPainel;
use App\Services\VNNOXService;

class PainelController extends Controller
{
    protected $vnnoxService;

    public function __construct(VNNOXService $vnnoxService)
    {
        $this->vnnoxService = $vnnoxService;
    }

    /**
     * Lista todos os painéis
     */
    public function index()
    {
        $paineis = Painel::withCount('videos')
            ->orderBy('nome')
            ->get();

        return view('admin.paineis.index', compact('paineis'));
    }

    /**
     * Exibe formulário de criação de painel
     */
    public function create()
    {
        return view('admin.paineis.create');
    }

    /**
     * Armazena novo painel
     */
    public function store(Request $request)
    {
        $request->validate([
            'player_id' => 'required|string|unique:paineis',
            'nome' => 'required|string|max:255',
            'localizacao' => 'nullable|string|max:255',
            'resolucao_largura' => 'nullable|integer|min:640',
            'resolucao_altura' => 'nullable|integer|min:480',
        ]);

        $painel = Painel::create($request->all());

        return redirect()
            ->route('admin.paineis.index')
            ->with('success', 'Painel cadastrado com sucesso!');
    }

    /**
     * Exibe detalhes de um painel
     */
    public function show(Painel $painel)
    {
        $painel->load(['videos' => function($query) {
            $query->latest()->limit(10);
        }]);

        // Buscar status do player na API VNNOX
        $status = $this->vnnoxService->verificarStatusPlayer($painel->player_id);

        return view('admin.paineis.show', compact('painel', 'status'));
    }

    /**
     * Exibe formulário de edição
     */
    public function edit(Painel $painel)
    {
        return view('admin.paineis.edit', compact('painel'));
    }

    /**
     * Atualiza um painel
     */
    public function update(Request $request, Painel $painel)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'localizacao' => 'nullable|string|max:255',
            'resolucao_largura' => 'nullable|integer|min:640',
            'resolucao_altura' => 'nullable|integer|min:480',
            'ativo' => 'boolean',
        ]);

        $painel->update($request->all());

        return redirect()
            ->route('admin.paineis.show', $painel)
            ->with('success', 'Painel atualizado com sucesso!');
    }

    /**
     * Sincroniza painéis com a API VNNOX
     */
    public function sincronizar()
    {
        $resultado = $this->vnnoxService->listarPlayers();

        if (!$resultado) {
            return back()->withErrors(['error' => 'Erro ao conectar com a API VNNOX']);
        }

        $players = $resultado['data'] ?? [];
        $sincronizados = 0;

        foreach ($players as $player) {
            Painel::updateOrCreate(
                ['player_id' => $player['player_id']],
                [
                    'nome' => $player['name'] ?? 'Painel ' . $player['player_id'],
                    'online' => $player['online'] ?? false,
                    'ultimo_heartbeat' => now(),
                ]
            );
            $sincronizados++;
        }

        return redirect()
            ->route('admin.paineis.index')
            ->with('success', "Sincronizado {$sincronizados} painéis com sucesso!");
    }

    /**
     * Verifica status em tempo real do painel
     */
    public function verificarStatus(Painel $painel)
    {
        $status = $this->vnnoxService->verificarStatusPlayer($painel->player_id);

        if ($status) {
            $painel->update([
                'online' => $status['online'] ?? false,
                'ultimo_heartbeat' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'painel' => $painel->fresh()
        ]);
    }

    /**
     * Ajusta brilho do painel
     */
    public function ajustarBrilho(Request $request, Painel $painel)
    {
        $request->validate([
            'nivel' => 'required|integer|min:0|max:100'
        ]);

        $sucesso = $this->vnnoxService->ajustarBrilho($painel->player_id, $request->nivel);

        if ($sucesso) {
            return response()->json([
                'success' => true,
                'message' => 'Brilho ajustado com sucesso'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Erro ao ajustar brilho'
        ], 500);
    }

    /**
     * Captura screenshot do que está sendo exibido
     */
    public function capturarScreenshot(Painel $painel)
    {
        $screenshot = $this->vnnoxService->capturarScreenshot($painel->player_id);

        return response()->json([
            'success' => $screenshot !== null,
            'screenshot' => $screenshot
        ]);
    }
}
