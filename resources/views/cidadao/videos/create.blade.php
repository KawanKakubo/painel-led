@extends('layouts.app')

@section('title', 'Enviar Vídeo')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-upload mr-2"></i>
            Enviar Novo Vídeo
        </h2>
        <p class="text-gray-600 mt-2">Siga os passos abaixo para enviar seu vídeo para o painel de LED</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 sm:p-8">
        <form action="{{ route('cidadao.videos.store') }}" method="POST" enctype="multipart/form-data" id="videoForm">
            @csrf

            <!-- Título -->
            <div class="mb-6">
                <label for="titulo" class="block text-gray-700 font-semibold mb-2">Título *</label>
                <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror" placeholder="Ex: Mensagem de Natal" required>
            </div>

            <!-- Mostrar de acordo com tipo de usuário -->
            @if(auth()->user()->tipo_perfil === 'comerciante')
            
                <!-- Sessão Comerciante -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="plano_segundos" class="block text-gray-700 font-semibold mb-2">Selecione o Plano *</label>
                        <select name="plano_segundos" id="plano_segundos" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Escolha...</option>
                            <option value="15">Plano Básico - 15 segundos</option>
                            <option value="30">Plano Padrão - 30 segundos</option>
                        </select>
                    </div>
                    <div>
                        <label for="semana_intencao" class="block text-gray-700 font-semibold mb-2">Semana de Exibição *</label>
                        <input type="week" id="semana_intencao" name="semana_intencao" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Ajuda a nossa curadoria a organizar as playlists.</p>
                    </div>
                </div>

            @else

                <!-- Sessão Cidadão -->
                <div class="mb-6">
                    <label for="categoria_video" class="block text-gray-700 font-semibold mb-2">Categoria do Vídeo *</label>
                    <select name="categoria_video" id="categoria_video" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">O que é este vídeo?</option>
                        <option value="Homenagem">Homenagem</option>
                        <option value="Aniversario">Aniversário</option>
                        <option value="Talento Local">Talento Local</option>
                        <option value="Sugestao de Melhoria">Sugestão de Melhoria</option>
                    </select>
                </div>
                
                <input type="hidden" name="plano_segundos" value="60">

            @endif

            <!-- Upload de Vídeo -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Arquivo de Vídeo *</label>
                <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition relative">
                    <input type="file" id="video" name="video" accept="video/mp4" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                    <div id="upload-prompt">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="text-lg text-gray-700 font-medium">Toque para selecionar ou arraste aqui</p>
                        <p class="text-sm text-gray-500 mt-1">Apenas .MP4</p>
                    </div>
                    
                    <div id="video-preview-container" class="hidden mt-4">
                        <div class="bg-blue-50 text-blue-800 p-4 rounded-lg flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                                <div>
                                    <p class="font-bold text-sm truncate" id="video-name"></p>
                                    <p class="text-xs text-blue-600" id="video-details"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="error-message" class="hidden mt-3 bg-red-100 text-red-700 p-3 rounded text-sm font-medium"></div>

                @error('video')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Termos e Condições -->
            <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="termo_aceito" class="mt-1 h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                    <span class="ml-3 text-sm text-gray-600">
                        Declaro que li e concordo com o <strong>Termo de Uso e Autorização de Imagem</strong>. O vídeo é de minha autoria, não viola direitos autorais e está adequado para todas as idades.
                    </span>
                </label>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" id="submitBtn" class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow hover:bg-blue-700 transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Enviar para Aprovação
                </button>
                <a href="{{ route('cidadao.dashboard') }}" class="w-full sm:w-auto text-center px-8 py-3 text-gray-600 font-medium hover:text-gray-900 transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const videoInput = document.getElementById('video');
    const uploadPrompt = document.getElementById('upload-prompt');
    const previewContainer = document.getElementById('video-preview-container');
    const videoName = document.getElementById('video-name');
    const videoDetails = document.getElementById('video-details');
    const errorMsg = document.getElementById('error-message');
    const form = document.getElementById('videoForm');
    const submitBtn = document.getElementById('submitBtn');

    // Identifica se é cidadão (plano_segundos hidden = 60s) ou comerciante (plano_segundos select)
    const isComerciante = document.getElementById('plano_segundos') && document.getElementById('plano_segundos').tagName === 'SELECT';

    videoInput.addEventListener('change', function(e) {
        if (!this.files || !this.files[0]) return;
        
        const file = this.files[0];
        errorMsg.classList.add('hidden');
        submitBtn.disabled = false;

        // Pré-filtro 1: Formato
        if (file.type !== 'video/mp4') {
            showError('Ops! Apenas arquivos MP4 são aceitos.');
            return;
        }

        // Pré-filtro 2: Tamanho (Ex: max 100MB)
        const sizeMB = file.size / (1024 * 1024);
        if (sizeMB > 100) {
            showError('Ops! Seu vídeo é muito grande. O tamanho máximo é de 100MB.');
            return;
        }

        // Pré-filtro 3: Duração em Tempo Real
        const videoElement = document.createElement('video');
        videoElement.preload = 'metadata';

        videoElement.onloadedmetadata = function() {
            window.URL.revokeObjectURL(videoElement.src);
            const duration = videoElement.duration;
            let maxDuration = 60; // Cidadão default

            if (isComerciante) {
                const planValue = document.getElementById('plano_segundos').value;
                if (!planValue) {
                    showError('Por favor, selecione seu Plano de segundos antes de enviar o vídeo.');
                    return;
                }
                maxDuration = parseInt(planValue);
            }

            if (duration > (maxDuration + 1)) {
                showError(`Ops! Seu vídeo tem ${Math.round(duration)} segundos, mas o limite é de ${maxDuration} segundos. Corte um pouco para enviar.`);
                return;
            }

            // Exibir Detalhes de sucesso
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            videoName.textContent = file.name;
            videoDetails.textContent = `${sizeMB.toFixed(1)} MB • ${Math.round(duration)} seg`;
        }

        videoElement.src = URL.createObjectURL(file);
    });

    function showError(msg) {
        errorMsg.textContent = msg;
        errorMsg.classList.remove('hidden');
        videoInput.value = ''; // reseta
        uploadPrompt.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        submitBtn.disabled = true;
    }

    form.addEventListener('submit', function(e) {
        if(submitBtn.disabled) { e.preventDefault(); return; }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg> Processando...';
    });
</script>
@endsection
