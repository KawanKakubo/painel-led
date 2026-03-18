<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Interativo de Assaí</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">
    
    <!-- Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2 text-primary-600">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="text-xl font-bold text-gray-800">Painel Assaí</span>
                </div>
                <div>
                   <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full font-medium transition shadow-md inline-flex items-center">
                       <span>Login com Gov.br</span>
                   </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero com Live Preview Funcional -->
    <section class="bg-gradient-to-br from-blue-900 to-indigo-800 text-white py-12 w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <!-- Text / CTA -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                        Deixe sua marca no <span class="text-yellow-400">coração de Assaí</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-blue-200 mb-8 max-w-2xl mx-auto lg:mx-0">
                        O painel de LED no centro da cidade agora é seu! Seja você um cidadão querendo fazer uma homenagem, ou um comércio querendo destaque, envie seu vídeo e apareça para todos.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="bg-yellow-500 hover:bg-yellow-400 text-yellow-900 font-bold px-8 py-3 rounded-full text-lg shadow-lg transform hover:-translate-y-1 transition duration-200 text-center">
                            Envie seu Vídeo Agora
                        </a>
                        <a href="#mural" class="bg-blue-800 bg-opacity-50 hover:bg-opacity-70 border border-blue-500 text-white font-medium px-8 py-3 rounded-full text-lg shadow-lg transition duration-200 text-center">
                            Ver o Mural
                        </a>
                    </div>
                </div>

                <!-- Live Preview / Simulador do Painel -->
                <div class="relative mx-auto w-full max-w-md lg:max-w-full perspective-1000">
                    <div class="bg-gray-900 p-2 sm:p-4 rounded-xl shadow-2xl border-4 border-gray-800 relative transform rotate-y-2 lg:rotate-y-0">
                        <!-- Moldura extra para dar aspecto de Totem/Painel -->
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-32 h-2 bg-gray-700 rounded-t-lg"></div>
                        <div class="bg-black aspect-video rounded flex items-center justify-center overflow-hidden relative">
                            <!-- Overlay simulando o Ponto de LED (scanlines) -->
                            <div class="absolute inset-0 pointer-events-none opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjMDAwIiBmaWxsLW9wYWNpdHk9IjAuMSIvPgo8cGF0aCBkPSJNMCAweDR2MUgweiIgZmlsbD0icmdiYSgyNTUsIDI1NSLCAyNTUsIDAuMSkiLz4KPC9zdmc+')] z-10"></div>
                            
                            <!-- O que está rodando -->
                            <div class="text-center w-full relative z-0">
                                <video autoplay loop muted playsinline class="w-full h-full object-cover rounded opacity-80" poster="/storage/poster-default.jpg">
                                    <source src="https://assets.mixkit.co/videos/preview/mixkit-daytime-city-traffic-aerial-view-56-large.mp4" type="video/mp4">
                                </video>
                                <div class="absolute bottom-4 right-4 bg-red-600 animate-pulse text-white text-xs font-bold px-2 py-1 rounded">
                                    AO VIVO
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-blue-300 text-sm mt-3">Simulação do painel rodando agora</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona / Regras (Mobile-first steps) -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Como Funciona</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <!-- Passo 1 -->
                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100 relative mt-8 md:mt-0">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white w-12 h-12 flex items-center justify-center rounded-full text-xl font-bold ring-4 ring-white">1</div>
                    <div class="mt-8 mb-4">
                        <svg class="w-12 h-12 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Faça Login</h3>
                    <p class="text-gray-600">Use a conta do Governo para acesso rápido e seguro, confirmando que você é um cidadão ou comerciante local.</p>
                </div>
                
                <!-- Passo 2 -->
                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100 relative mt-8 md:mt-0">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white w-12 h-12 flex items-center justify-center rounded-full text-xl font-bold ring-4 ring-white">2</div>
                    <div class="mt-8 mb-4">
                        <svg class="w-12 h-12 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Envie seu Vídeo</h3>
                    <p class="text-gray-600">Escolha o motivo (homenagem, anúncio) e envie um vídeo curto. O sistema na hora avisa se está tudo certo.</p>
                </div>

                <!-- Passo 3 -->
                <div class="bg-gray-50 p-6 rounded-2xl shadow-sm border border-gray-100 relative mt-8 md:mt-0">
                    <div class="absolute -top-6 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white w-12 h-12 flex items-center justify-center rounded-full text-xl font-bold ring-4 ring-white">3</div>
                    <div class="mt-8 mb-4">
                        <svg class="w-12 h-12 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Apareça na Telona</h3>
                    <p class="text-gray-600">Após uma aprovação rápida pela prefeitura, seu vídeo entra na grade e brilha no centro de Assaí!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mural de Impacto -->
    <section id="mural" class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-2">Mural de Impacto</h2>
            <p class="text-center text-gray-600 mb-10">Veja quem já marcou presença no painel</p>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Vídeo 1 -->
                <div class="bg-white rounded-lg shadow overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1517436073-3b1b13d9617?auto=format&fit=crop&q=80&w=300&h=300" class="w-full h-48 object-cover" alt="Homenagem">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                    </div>
                    <div class="p-3">
                        <h4 class="font-bold text-sm text-gray-900 truncate">Homenagem Dia das Mães</h4>
                        <p class="text-xs text-gray-500">Por João Silva</p>
                    </div>
                </div>
                
                <!-- Vídeo 2 -->
                <div class="bg-white rounded-lg shadow overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&q=80&w=300&h=300" class="w-full h-48 object-cover" alt="Comércio">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                    </div>
                    <div class="p-3">
                        <h4 class="font-bold text-sm text-gray-900 truncate">Promoção Padaria Central</h4>
                        <p class="text-xs text-gray-500">Por Maria Clara</p>
                    </div>
                </div>

                <!-- Vídeo 3 -->
                <div class="bg-white rounded-lg shadow overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1511556532299-8f662fc26c06?auto=format&fit=crop&q=80&w=300&h=300" class="w-full h-48 object-cover" alt="Homenagem">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                    </div>
                    <div class="p-3">
                        <h4 class="font-bold text-sm text-gray-900 truncate">Parabéns Assaí!</h4>
                        <p class="text-xs text-gray-500">Escola Municipal</p>
                    </div>
                </div>

                <!-- Vídeo 4 -->
                <div class="bg-white rounded-lg shadow overflow-hidden relative group">
                    <img src="https://images.unsplash.com/photo-1444653614773-995cb1ef9efa?auto=format&fit=crop&q=80&w=300&h=300" class="w-full h-48 object-cover" alt="Comércio">
                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"></path></svg>
                    </div>
                    <div class="p-3">
                        <h4 class="font-bold text-sm text-gray-900 truncate">Vende-se Tudo</h4>
                        <p class="text-xs text-gray-500">Loja Centro</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Quero ter meu vídeo no mural também &rarr;</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; {{ date('Y') }} Prefeitura Municipal de Assaí. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
