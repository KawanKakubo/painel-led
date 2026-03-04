@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-tachometer-alt mr-2"></i>
        Dashboard Administrativo
    </h2>
    <p class="text-gray-600 mt-2">Visão geral do sistema de painéis de LED</p>
</div>

<!-- Estatísticas Principais -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Total de Vídeos</p>
                <p class="text-4xl font-bold">{{ $estatisticas['total_videos'] }}</p>
            </div>
            <i class="fas fa-video text-5xl text-blue-300"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm">Pendentes</p>
                <p class="text-4xl font-bold">{{ $estatisticas['pendentes'] }}</p>
            </div>
            <i class="fas fa-clock text-5xl text-yellow-300"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Aprovados Hoje</p>
                <p class="text-4xl font-bold">{{ $estatisticas['aprovados_hoje'] }}</p>
            </div>
            <i class="fas fa-check-circle text-5xl text-green-300"></i>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Exibidos Hoje</p>
                <p class="text-4xl font-bold">{{ $estatisticas['exibidos_hoje'] }}</p>
            </div>
            <i class="fas fa-play-circle text-5xl text-purple-300"></i>
        </div>
    </div>
</div>

<!-- Estatísticas Secundárias -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Cidadãos Cadastrados</p>
                <p class="text-3xl font-bold text-gray-800">{{ $estatisticas['total_cidadaos'] }}</p>
            </div>
            <i class="fas fa-users text-4xl text-gray-300"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Painéis Online</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $estatisticas['paineis_online'] }}/{{ $estatisticas['total_paineis'] }}
                </p>
            </div>
            <i class="fas fa-tv text-4xl text-green-300"></i>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm">Sistema</p>
                <p class="text-lg font-semibold text-green-600">
                    <i class="fas fa-check-circle mr-1"></i> Operacional
                </p>
            </div>
            <i class="fas fa-server text-4xl text-green-300"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Vídeos Pendentes -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800 flex items-center justify-between">
                <span>
                    <i class="fas fa-clock mr-2 text-yellow-600"></i>
                    Vídeos Pendentes de Moderação
                </span>
                @if($estatisticas['pendentes'] > 0)
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">
                        {{ $estatisticas['pendentes'] }}
                    </span>
                @endif
            </h3>
        </div>
        <div class="p-6">
            @if($videos_pendentes->count() > 0)
                <div class="space-y-4">
                    @foreach($videos_pendentes->take(5) as $video)
                        <div class="border-l-4 border-yellow-500 pl-4 py-2 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800">{{ $video->titulo }}</h4>
                                    <p class="text-sm text-gray-600">Por: {{ $video->usuario->name }}</p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $video->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.moderacao.show', $video) }}" 
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                                    Moderar
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($videos_pendentes->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.moderacao.index') }}" class="text-blue-600 hover:underline text-sm">
                            Ver todos ({{ $videos_pendentes->count() }}) <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-center py-8">
                    <i class="fas fa-check-circle text-4xl mb-2"></i><br>
                    Nenhum vídeo pendente
                </p>
            @endif
        </div>
    </div>

    <!-- Status dos Painéis -->
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800">
                <i class="fas fa-tv mr-2 text-blue-600"></i>
                Status dos Painéis
            </h3>
        </div>
        <div class="p-6">
            @if($paineis->count() > 0)
                <div class="space-y-3">
                    @foreach($paineis as $painel)
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center space-x-3">
                                @if($painel->online)
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                                @else
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $painel->nome }}</p>
                                    <p class="text-xs text-gray-500">{{ $painel->localizacao }}</p>
                                </div>
                            </div>
                            <span class="text-sm {{ $painel->online ? 'text-green-600' : 'text-red-600' }}">
                                {{ $painel->online ? 'Online' : 'Offline' }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.paineis.index') }}" class="text-blue-600 hover:underline text-sm">
                        Gerenciar painéis <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">
                    <i class="fas fa-tv text-4xl mb-2"></i><br>
                    Nenhum painel cadastrado
                </p>
            @endif
        </div>
    </div>
</div>

<!-- Vídeos Mais Exibidos e Cidadãos Ativos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Vídeos Populares -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-star mr-2 text-yellow-500"></i>
            Vídeos Mais Exibidos
        </h3>
        @if($videos_populares->count() > 0)
            <div class="space-y-3">
                @foreach($videos_populares as $video)
                    <div class="flex items-center justify-between p-3 border rounded hover:bg-gray-50">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ Str::limit($video->titulo, 40) }}</p>
                            <p class="text-xs text-gray-600">{{ $video->usuario->name }}</p>
                        </div>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $video->vezes_exibido }}x
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Nenhum vídeo exibido ainda</p>
        @endif
    </div>

    <!-- Cidadãos Mais Ativos -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-trophy mr-2 text-yellow-500"></i>
            Cidadãos Mais Ativos
        </h3>
        @if($cidadaos_ativos->count() > 0)
            <div class="space-y-3">
                @foreach($cidadaos_ativos as $cidadao)
                    <div class="flex items-center justify-between p-3 border rounded hover:bg-gray-50">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold">
                                {{ substr($cidadao->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $cidadao->name }}</p>
                                <p class="text-xs text-gray-600">Nível {{ $cidadao->nivel_acesso }}</p>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $cidadao->videos_count }} vídeos
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-center py-4">Nenhum cidadão com vídeos ainda</p>
        @endif
    </div>
</div>
@endsection
