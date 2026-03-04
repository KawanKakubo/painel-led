@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-700">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <i class="fas fa-tv text-6xl text-blue-600 mb-4"></i>
            <h1 class="text-3xl font-bold text-gray-800">Painéis LED Assaí</h1>
            <p class="text-gray-600 mt-2">Entre com sua conta gov.assaí</p>
        </div>

        <!-- Formulário de Login -->
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <!-- CPF -->
            <div class="mb-4">
                <label for="cpf" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-id-card mr-1"></i> CPF
                </label>
                <input 
                    type="text" 
                    id="cpf" 
                    name="cpf" 
                    value="{{ old('cpf') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cpf') border-red-500 @enderror"
                    placeholder="000.000.000-00"
                    maxlength="14"
                    required
                >
                @error('cpf')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Senha -->
            <div class="mb-6">
                <label for="senha" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-lock mr-1"></i> Senha
                </label>
                <input 
                    type="password" 
                    id="senha" 
                    name="senha" 
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('senha') border-red-500 @enderror"
                    placeholder="Sua senha do gov.assaí"
                    required
                >
                @error('senha')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Lembrar-me -->
            <div class="mb-6 flex items-center">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember" 
                    class="mr-2"
                >
                <label for="remember" class="text-gray-700">Manter-me conectado</label>
            </div>

            <!-- Botão de Login -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 flex items-center justify-center"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>
                Entrar com gov.assaí
            </button>
        </form>

        <!-- Informações Adicionais -->
        <div class="mt-6 text-center text-sm text-gray-600">
            <p>Não possui conta gov.assaí?</p>
            <a href="https://gov.assai.pr.gov.br" target="_blank" class="text-blue-600 hover:underline">
                Cadastre-se aqui
            </a>
        </div>

        <!-- Footer do Card -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-xs text-gray-500">
            <p>Sistema integrado com o gov.assaí</p>
            <p>Cidade Inteligente - Assaí/PR</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Máscara de CPF
    document.getElementById('cpf').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{2})$/, '$1-$2');
        e.target.value = value;
    });
</script>
@endpush
