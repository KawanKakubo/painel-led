<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function completar()
    {
        $user = Auth::user();
        
        // Se já tiver perfil completo, redireciona pro dashboard
        if ($user->perfil_completo) {
            return redirect()->route('cidadao.dashboard');
        }

        return view('cidadao.perfil.completar', compact('user'));
    }

    public function salvar(Request $request)
    {
        $request->validate([
            'tipo_perfil' => 'required|in:cidadao,comerciante',
            'bairro' => 'nullable|string|max:255',
            'cnpj' => 'required_if:tipo_perfil,comerciante|nullable|string|max:20',
            'nome_empresa' => 'required_if:tipo_perfil,comerciante|nullable|string|max:255',
            'ramo_atividade' => 'required_if:tipo_perfil,comerciante|nullable|string|max:255',
        ]);

        $user = Auth::user();
        
        $user->update([
            'tipo_perfil' => $request->tipo_perfil,
            'bairro' => $request->bairro,
            'cnpj' => $request->tipo_perfil === 'comerciante' ? $request->cnpj : null,
            'nome_empresa' => $request->tipo_perfil === 'comerciante' ? $request->nome_empresa : null,
            'ramo_atividade' => $request->tipo_perfil === 'comerciante' ? $request->ramo_atividade : null,
            'perfil_completo' => true,
        ]);

        return redirect()->route('cidadao.dashboard')->with('success', 'Perfil completado com sucesso!');
    }
}
