<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GovAssaiService;
use App\Models\User;

class AuthController extends Controller
{
    protected $govAssaiService;

    public function __construct(GovAssaiService $govAssaiService)
    {
        $this->govAssaiService = $govAssaiService;
    }

    /**
     * Exibe o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login usando gov.assaí
     */
    public function login(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string',
            'senha' => 'required|string'
        ]);

        // Autenticar no gov.assaí
        $resultado = $this->govAssaiService->autenticar(
            $request->cpf,
            $request->senha
        );

        if (!$resultado['success']) {
            return back()
                ->withErrors(['cpf' => $resultado['message']])
                ->withInput($request->only('cpf'));
        }

        $dadosCidadao = $resultado['data'];

        // Buscar ou criar usuário local
        $user = User::firstOrCreate(
            ['gov_assai_id' => $dadosCidadao['id']],
            [
                'name' => $dadosCidadao['nome'],
                'cpf' => $dadosCidadao['cpf'],
                'email' => $dadosCidadao['email'] ?? null,
                'celular' => $dadosCidadao['celular'] ?? null,
                'nivel_acesso' => $dadosCidadao['nivel_acesso'] ?? 1,
                'role' => 'cidadao',
                'ativo' => true,
            ]
        );

        // Atualizar dados em logins subsequentes
        $user->update([
            'name' => $dadosCidadao['nome'],
            'email' => $dadosCidadao['email'] ?? $user->email,
            'celular' => $dadosCidadao['celular'] ?? $user->celular,
            'nivel_acesso' => $dadosCidadao['nivel_acesso'] ?? $user->nivel_acesso,
        ]);

        // Fazer login na aplicação
        Auth::login($user, $request->filled('remember'));

        // Redirecionar baseado no role
        if ($user->isAdmin() || $user->isModerador()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('cidadao.dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Verifica se CPF existe no gov.assaí (AJAX)
     */
    public function checkCpf(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string'
        ]);

        $resultado = $this->govAssaiService->verificarCPF($request->cpf);

        return response()->json($resultado);
    }
}
