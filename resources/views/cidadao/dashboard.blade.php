@extends('layouts.app')

@section('title', 'Meu Painel')

@section('content')
<div class="mb- 6">
    <h2 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-home mr-2"></i>
        Olá, {{ auth()->user()->name }}!
    </h2>
    <p class="text-gray-600 mt-2">Bem-vindo ao sistema de painéis de LED de Assaí</p>
</div>

<!-- Estatísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Total de Vídeos</p>
                <p class="text-3xl font-bold text-blue-600">{{ $estatisticas['total_videos'] }}</p>
            </div>
            <i class="fas fa-video text-4xl text-blue-200"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Aguardando Moderação</p>
                <p class="text-3xl font-bold text-yellow-600">{{ $estatisticas['pendentes'] }}</p>
            </div>
            <i class="fas fa-clock text-4xl text-yellow-200"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Aprovados</p>
                <p class="text-3xl font-bold text-green-600">{{ $estatisticas['aprovados'] }}</p>
            </div>
            <i class="fas fa-check-circle text-4xl text-green-200"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Exibidos</p>
                <p class="text-3xl font-bold text-purple-600">{{ $estatisticas['exibidos'] }}</p>
            </div>
            <i class="fas fa-play-circle text-4xl text-purple-200"></i>
        </div>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-8 rounded-lg shadow-lg mb-8">
    <h3 class="text-2xl font-bold mb-4">
        <i class="fas fa-lightbulb mr-2"></i>
        Envie seu vídeo para os painéis de LED da cidade!
    </h3>
    <p class="mb-6">Compartilhe mensagens, arte e conteúdo com toda a comunidade de Assaí através dos painéis digitais.</p>
    <a href="{{ route('cidadao.videos.create') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition inline-block">
        <i class="fas fa-plus-circle mr-2"></i>
        Enviar Novo Vídeo
    </a>
</div>

<!-- Vídeos Recentes -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">
        <i class="fas fa-history mr-2"></i>
        Meus Vídeos Recentes
    </h3>

    @if($videos_recentes->count() > 0)
        <div class="space-y-4">
            @foreach($videos_recentes as $video)
                <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $video->titulo }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($video->descricao, 100) }}</p>
                            <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                <span><i class="far fa-calendar mr-1"></i>{{ $video->created_at->format('d/m/Y H:i') }}</span>
                                @if($video->painel)
                                    <span><i class="fas fa-tv mr-1"></i>{{ $video->painel->nome }}</span>
                                @endif
                                @if($video->duracao_formatada)
                                    <span><i class="far fa-clock mr-1"></i>{{ $video->duracao_formatada }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            @if($video->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-clock mr-1"></i> Pendente
                                </span>
                            @elseif($video->status === 'processing')
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-spinner mr-1"></i> Processando
                                </span>
                            @elseif($video->status === 'approved')
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-check-circle mr-1"></i> Aprovado
                                </span>
                            @elseif($video->status === 'displayed')
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-star mr-1"></i> Exibido
                                </span>
                            @elseif($video->status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-times-circle mr-1"></i> Rejeitado
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('cidadao.videos.index') }}" class="text-blue-600 hover:underline">
                Ver todos os meus vídeos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-5xl mb-4"></i>
            <p class="text-lg">Você ainda não enviou nenhum vídeo.</p>
            <a href="{{ route('cidadao.videos.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                Envie seu primeiro vídeo agora!
            </a>
        </div>
    @endif
</div>

<!-- Informações -->
<div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded">
    <h4 class="font-semibold text-blue-900 mb-2">
        <i class="fas fa-info-circle mr-2"></i>
        Dicas para seu vídeo
    </h4>
    <ul class="text-blue-800 text-sm space-y-1">
        <li><i class="fas fa-check mr-2"></i> Duração máxima: 2 minutos</li>
        <li><i class="fas fa-check mr-2"></i> Formatos aceitos: MP4, MOV, AVI, MKV</li>
        <li><i class="fas fa-check mr-2"></i> Tamanho máximo: 500MB</li>
        <li><i class="fas fa-check mr-2"></i> Seu vídeo será moderado antes de ser exibido</li>
    </ul>
</div>
@endsection
