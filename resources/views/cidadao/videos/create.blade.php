@extends('layouts.app')

@section('title', 'Enviar Vídeo')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-upload mr-2"></i>
            Enviar Novo Vídeo
        </h2>
        <p class="text-gray-600 mt-2">Compartilhe seu conteúdo nos painéis de LED de Assaí</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <form action="{{ route('cidadao.videos.store') }}" method="POST" enctype="multipart/form-data" id="videoForm">
            @csrf

            <!-- Título -->
            <div class="mb-6">
                <label for="titulo" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-heading mr-1"></i> Título *
                </label>
                <input 
                    type="text" 
                    id="titulo" 
                    name="titulo" 
                    value="{{ old('titulo') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('titulo') border-red-500 @enderror"
                    placeholder="Ex: Mensagem de Natal para Assaí"
                    maxlength="255"
                    required
                >
                @error('titulo')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descrição -->
            <div class="mb-6">
                <label for="descricao" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-align-left mr-1"></i> Descrição
                </label>
                <textarea 
                    id="descricao" 
                    name="descricao" 
                    rows="4"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('descricao') border-red-500 @enderror"
                    placeholder="Descreva o conteúdo do seu vídeo..."
                    maxlength="1000"
                >{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Painel de destino -->
            <div class="mb-6">
                <label for="painel_id" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-tv mr-1"></i> Painel de LED (Opcional)
                </label>
                <select 
                    id="painel_id" 
                    name="painel_id"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Deixar moderador escolher</option>
                    @foreach($paineis as $painel)
                        <option value="{{ $painel->id }}" {{ old('painel_id') == $painel->id ? 'selected' : '' }}>
                            {{ $painel->nome }} @if($painel->localizacao) - {{ $painel->localizacao }} @endif
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Se não escolher, o moderador decidirá onde exibir</p>
            </div>

            <!-- Upload de Vídeo -->
            <div class="mb-6">
                <label for="video" class="block text-gray-700 font-semibold mb-2">
                    <i class="fas fa-file-video mr-1"></i> Arquivo de Vídeo *
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition">
                    <input 
                        type="file" 
                        id="video" 
                        name="video" 
                        accept="video/mp4,video/quicktime,video/x-msvideo,video/x-matroska"
                        class="hidden"
                        required
                    >
                    <label for="video" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-4"></i>
                        <p class="text-lg text-gray-700 mb-2">Clique para selecionar ou arraste o vídeo aqui</p>
                        <p class="text-sm text-gray-500">MP4, MOV, AVI ou MKV - Máx. 500MB</p>
                    </label>
                    <div id="fileName" class="mt-4 text-blue-600 font-semibold hidden"></div>
                </div>
                @error('video')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Requisitos -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <h4 class="font-semibold text-yellow-900 mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Requisitos do Vídeo
                </h4>
                <ul class="text-yellow-800 text-sm space-y-1">
                    <li><i class="fas fa-check mr-2"></i> Duração máxima: 2 minutos</li>
                    <li><i class="fas fa-check mr-2"></i> Não pode conter conteúdo ofensivo, político ou comercial</li>
                    <li><i class="fas fa-check mr-2"></i> Deve respeitar direitos autorais</li>
                    <li><i class="fas fa-check mr-2"></i> Será moderado antes da exibição</li>
                </ul>
            </div>

            <!-- Termos -->
            <div class="mb-6">
                <label class="flex items-start">
                    <input type="checkbox" class="mt-1 mr-2" required>
                    <span class="text-sm text-gray-700">
                        Declaro que o conteúdo enviado é de minha autoria ou possuo autorização para uso, 
                        e que não viola direitos autorais, não contém conteúdo ofensivo, e está de acordo 
                        com as diretrizes da Prefeitura de Assaí.
                    </span>
                </label>
            </div>

            <!-- Botões -->
            <div class="flex items-center space-x-4">
                <button 
                    type="submit" 
                    class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center"
                    id="submitBtn"
                >
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Vídeo
                </button>
                <a 
                    href="{{ route('cidadao.dashboard') }}" 
                    class="bg-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-400 transition"
                >
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Mostrar nome do arquivo selecionado
    document.getElementById('video').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameDiv = document.getElementById('fileName');
        
        if (fileName) {
            fileNameDiv.textContent = '📹 ' + fileName;
            fileNameDiv.classList.remove('hidden');
        }
    });

    // Indicador de upload
    document.getElementById('videoForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enviando...';
    });
</script>
@endpush
