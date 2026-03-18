@extends('layouts.app')

@section('title', 'Complete seu Perfil')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-xl mt-8">
    <div class="p-6">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Bem-vindo(a), {{ explode(' ', $user->name)[0] }}!</h2>
            <p class="text-gray-600 mt-2 text-sm">Para continuar, conte um pouco mais sobre como você usará o painel.</p>
        </div>

        <form action="{{ route('cidadao.perfil.salvar') }}" method="POST" id="perfil-form">
            @csrf

            <!-- Tipo de Perfil -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Eu quero usar o painel como:</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="tipo_perfil" value="cidadao" class="peer sr-only" checked onchange="toggleForm('cidadao')">
                        <div class="rounded-lg border-2 border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                            <svg class="w-8 h-8 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span class="font-medium text-gray-900 block">Cidadão</span>
                            <span class="text-xs text-gray-500">Homenagens, etc</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="tipo_perfil" value="comerciante" class="peer sr-only" onchange="toggleForm('comerciante')">
                        <div class="rounded-lg border-2 border-gray-200 p-4 text-center hover:bg-gray-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                            <svg class="w-8 h-8 text-blue-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="font-medium text-gray-900 block">Empresa</span>
                            <span class="text-xs text-gray-500">Anúncios, vendas</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Campos Cidadão -->
            <div id="campos-cidadao" class="space-y-4">
                <div>
                    <label for="bairro" class="block text-sm font-medium text-gray-700">Seu Bairro (opcional)</label>
                    <input type="text" name="bairro" id="bairro" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border" placeholder="Ex: Centro">
                </div>
            </div>

            <!-- Campos Comerciante -->
            <div id="campos-comerciante" class="space-y-4 hidden">
                <div>
                    <label for="cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border" placeholder="00.000.000/0000-00">
                </div>
                <div>
                    <label for="nome_empresa" class="block text-sm font-medium text-gray-700">Nome da Empresa</label>
                    <input type="text" name="nome_empresa" id="nome_empresa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border" placeholder="Ex: Padaria do João">
                </div>
                <div>
                    <label for="ramo_atividade" class="block text-sm font-medium text-gray-700">Ramo de Atividade</label>
                    <select name="ramo_atividade" id="ramo_atividade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-4 py-2 border">
                        <option value="">Selecione...</option>
                        <option value="Alimentação">Alimentação</option>
                        <option value="Vestuário">Vestuário</option>
                        <option value="Serviços">Serviços</option>
                        <option value="Tecnologia">Tecnologia</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 bg-red-50 text-red-600 p-3 rounded-md text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-8">
                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Concluir Cadastro e Acessar Painel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm(tipo) {
        const camposCidadao = document.getElementById('campos-cidadao');
        const camposComerciante = document.getElementById('campos-comerciante');

        if (tipo === 'comerciante') {
            camposCidadao.classList.add('hidden');
            camposComerciante.classList.remove('hidden');
        } else {
            camposCidadao.classList.remove('hidden');
            camposComerciante.classList.add('hidden');
        }
    }
    
    // Garantir que carregue no estado correto
    document.addEventListener('DOMContentLoaded', () => {
        const checkedRadio = document.querySelector('input[name="tipo_perfil"]:checked');
        if(checkedRadio) toggleForm(checkedRadio.value);
    });
</script>
@endsection