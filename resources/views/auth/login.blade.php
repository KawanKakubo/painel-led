<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Painéis LED Assaí</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <div class="flex-grow flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 p-4 sm:p-8">
        
        <div class="bg-white p-8 sm:p-10 rounded-3xl shadow-2xl w-full max-w-md relative">
            
            @if(session('error') || $errors->any())
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="font-bold">Houve um problema</span>
                    </div>
                    @if(session('error'))
                        <p class="text-sm ml-6">{{ session('error') }}</p>
                    @endif
                    @if($errors->any())
                        <ul class="list-disc list-inside text-sm ml-6">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-4">
                    <i class="fas fa-tv text-4xl text-blue-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight">Painéis LED</h1>
                <p class="text-gray-500 mt-2 font-medium">Acesso via gov.assaí</p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="cpf" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-id-card mr-1 text-gray-400"></i> CPF
                    </label>
                    <input
                        type="text"
                        id="cpf"
                        name="cpf"
                        value="{{ old('cpf') }}"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors @error('cpf') border-red-500 focus:ring-red-500 @enderror"
                        placeholder="000.000.000-00"
                        maxlength="14"
                        required
                    >
                </div>

                <div class="mb-6">
                    <label for="senha" class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-1 text-gray-400"></i> Senha
                    </label>
                    <input
                        type="password"
                        id="senha"
                        name="senha"
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-colors @error('senha') border-red-500 focus:ring-red-500 @enderror"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="mb-8 flex items-center justify-between">
                    <label class="flex items-center text-gray-600 cursor-pointer hover:text-gray-800 transition">
                        <input type="checkbox" name="remember" class="mr-2 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                        <span class="text-sm font-medium">Manter-me conectado</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-blue-700 active:bg-blue-800 transition-all duration-200 flex items-center justify-center shadow-lg shadow-blue-600/30"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Entrar no Sistema
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100 text-center text-sm">
                <p class="text-gray-500 mb-1">Não possui conta gov.assaí?</p>
                <a href="https://gov.assai.pr.gov.br" target="_blank" class="text-blue-600 font-semibold hover:text-blue-800 underline transition">
                    Criar meu cadastro único
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.getElementById('cpf').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d)/, '$1.$2');
            value = value.replace(/(\d{3})(\d{2})$/, '$1-$2');
            e.target.value = value;
        });
    </script>
</body>
</html>
