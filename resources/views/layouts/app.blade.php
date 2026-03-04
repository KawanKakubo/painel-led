<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sistema de Painéis LED Assaí</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    @auth
    <!-- Navegação -->
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <i class="fas fa-tv text-2xl"></i>
                    <h1 class="text-xl font-bold">Painéis LED Assaí</h1>
                </div>
                
                <div class="flex items-center space-x-6">
                    @if(auth()->user()->isModerador())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-200">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard Admin
                        </a>
                        <a href="{{ route('admin.moderacao.index') }}" class="hover:text-blue-200">
                            <i class="fas fa-check-circle mr-1"></i> Moderação
                            @if($pendentes = \App\Models\Video::where('status', 'pending')->count())
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-1">{{ $pendentes }}</span>
                            @endif
                        </a>
                    @endif
                    
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.paineis.index') }}" class="hover:text-blue-200">
                            <i class="fas fa-tv mr-1"></i> Painéis
                        </a>
                    @endif
                    
                    @if(auth()->user()->isCidadao())
                        <a href="{{ route('cidadao.dashboard') }}" class="hover:text-blue-200">
                            <i class="fas fa-home mr-1"></i> Início
                        </a>
                        <a href="{{ route('cidadao.videos.index') }}" class="hover:text-blue-200">
                            <i class="fas fa-video mr-1"></i> Meus Vídeos
                        </a>
                        <a href="{{ route('cidadao.videos.create') }}" class="hover:text-blue-200">
                            <i class="fas fa-plus-circle mr-1"></i> Enviar Vídeo
                        </a>
                    @endif
                    
                    <div class="border-l border-blue-500 pl-6">
                        <span class="mr-3">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-blue-200">
                                <i class="fas fa-sign-out-alt mr-1"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Conteúdo Principal -->
    <main class="container mx-auto px-4 py-8">
        <!-- Mensagens de Sucesso/Erro -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-6 mt-12">
        <p>&copy; {{ date('Y') }} Prefeitura Municipal de Assaí - Sistema de Painéis LED</p>
        <p class="text-sm text-gray-400 mt-2">Integrado com gov.assaí e NovaStar VNNOX</p>
    </footer>

    <!-- Alpine.js para interatividade -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    @stack('scripts')
</body>
</html>
