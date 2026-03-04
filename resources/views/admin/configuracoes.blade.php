@extends('layouts.app')

@section('title', 'Configurações do Sistema')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-cog mr-2"></i>
        Configurações do Sistema
    </h2>
    <p class="text-gray-600 mt-2">Configure as credenciais de integração com VNNOX e gov.assaí</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Formulário de Configuração -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <form action="{{ route('admin.configuracoes.salvar') }}" method="POST">
                @csrf

                <div class="mb-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-plug text-blue-600 mr-2"></i>
                        Integração VNNOX (NovaStar)
                    </h3>
                    
                    <!-- AppKey -->
                    <div class="mb-4">
                        <label for="vnnox_app_key" class="block text-gray-700 font-semibold mb-2">
                            AppKey *
                        </label>
                        <input 
                            type="text" 
                            id="vnnox_app_key" 
                            name="vnnox_app_key" 
                            value="{{ old('vnnox_app_key', $configuracao->vnnox_app_key ?? '') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Insira o AppKey fornecido pela NovaStar"
                            required
                        >
                        <p class="text-sm text-gray-500 mt-1">
                            Obtenha suas credenciais no portal NovaCloud Open Platform
                        </p>
                    </div>

                    <!-- AppSecret -->
                    <div class="mb-4">
                        <label for="vnnox_app_secret" class="block text-gray-700 font-semibold mb-2">
                            AppSecret *
                        </label>
                        <input 
                            type="password" 
                            id="vnnox_app_secret" 
                            name="vnnox_app_secret" 
                            value="{{ old('vnnox_app_secret', $configuracao->vnnox_app_secret ?? '') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Insira o AppSecret"
                            required
                        >
                        <p class="text-sm text-gray-500 mt-1">
                            Mantenha este valor em segredo
                        </p>
                    </div>

                    <!-- API URL -->
                    <div class="mb-4">
                        <label for="vnnox_api_url" class="block text-gray-700 font-semibold mb-2">
                            URL da API *
                        </label>
                        <input 
                            type="url" 
                            id="vnnox_api_url" 
                            name="vnnox_api_url" 
                            value="{{ old('vnnox_api_url', $configuracao->vnnox_api_url ?? 'https://api.vnnox.com') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="https://api.vnnox.com"
                            required
                        >
                    </div>
                </div>

                <!-- Informações de Segurança -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                    <h4 class="font-semibold text-yellow-900 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Importante
                    </h4>
                    <ul class="text-yellow-800 text-sm space-y-1">
                        <li><i class="fas fa-check mr-2"></i> As credenciais são usadas para autenticação com a API VNNOX</li>
                        <li><i class="fas fa-check mr-2"></i> Cada requisição gera um CheckSum SHA256 para segurança</li>
                        <li><i class="fas fa-check mr-2"></i> As credenciais são armazenadas de forma criptografada</li>
                        <li><i class="fas fa-check mr-2"></i> Após salvar, teste a conexão sincronizando os painéis</li>
                    </ul>
                </div>

                <!-- Botões -->
                <div class="flex items-center space-x-4">
                    <button 
                        type="submit" 
                        class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Salvar Configurações
                    </button>
                    
                    <a 
                        href="{{ route('admin.dashboard') }}" 
                        class="bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-400 transition"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Informações e Links Úteis -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Status da Integração -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                Status
            </h3>
            
            <div class="space-y-3">
                @if($configuracao && $configuracao->vnnox_app_key)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">VNNOX:</span>
                        <span class="text-green-600 font-semibold">
                            <i class="fas fa-check-circle mr-1"></i>
                            Configurado
                        </span>
                    </div>
                @else
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700">VNNOX:</span>
                        <span class="text-red-600 font-semibold">
                            <i class="fas fa-times-circle mr-1"></i>
                            Não configurado
                        </span>
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <span class="text-gray-700">gov.assaí:</span>
                    <span class="text-green-600 font-semibold">
                        <i class="fas fa-check-circle mr-1"></i>
                        Ativo
                    </span>
                </div>
            </div>
        </div>

        <!-- Links Úteis -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-link mr-2 text-blue-600"></i>
                Links Úteis
            </h3>
            
            <div class="space-y-2">
                <a href="https://docs.novastar.tech" target="_blank" 
                   class="block text-blue-600 hover:underline text-sm">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Documentação VNNOX
                </a>
                <a href="https://cloud.novastar.tech" target="_blank" 
                   class="block text-blue-600 hover:underline text-sm">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    NovaCloud Platform
                </a>
                <a href="{{ route('admin.paineis.sincronizar') }}" 
                   class="block text-blue-600 hover:underline text-sm"
                   onclick="event.preventDefault(); if(confirm('Sincronizar painéis agora?')) { document.getElementById('sync-form').submit(); }">
                    <i class="fas fa-sync mr-2"></i>
                    Sincronizar Painéis
                </a>
            </div>

            <form id="sync-form" action="{{ route('admin.paineis.sincronizar') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>

        <!-- Informações do Sistema -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-server mr-2 text-blue-600"></i>
                Sistema
            </h3>
            
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Laravel:</span>
                    <span class="font-semibold">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between">
                    <span>PHP:</span>
                    <span class="font-semibold">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Timezone:</span>
                    <span class="font-semibold">{{ config('app.timezone') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
