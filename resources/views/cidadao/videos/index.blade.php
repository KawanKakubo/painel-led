@extends('layouts.app')

@section('title', 'Meus Vídeos')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-video mr-2"></i>
            Meus Vídeos
        </h2>
        <p class="text-gray-600 mt-2">Gerencie os vídeos que você enviou</p>
    </div>
    <a href="{{ route('cidadao.videos.create') }}" 
       class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center">
        <i class="fas fa-plus-circle mr-2"></i>
        Enviar Novo Vídeo
    </a>
</div>

<!-- Filtros Rápidos -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex items-center space-x-4">
        <a href="?" class="px-4 py-2 rounded {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Todos ({{ $videos->total() }})
        </a>
        <a href="?status=pending" class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Pendentes
        </a>
        <a href="?status=approved" class="px-4 py-2 rounded {{ request('status') == 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Aprovados
        </a>
        <a href="?status=displayed" class="px-4 py-2 rounded {{ request('status') == 'displayed' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Exibidos
        </a>
        <a href="?status=rejected" class="px-4 py-2 rounded {{ request('status') == 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            Rejeitados
        </a>
    </div>
</div>

<!-- Lista de Vídeos -->
<div class="space-y-4">
    @forelse($videos as $video)
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-2">
                        <h3 class="text-xl font-bold text-gray-800">{{ $video->titulo }}</h3>
                        
                        @if($video->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-clock mr-1"></i> Aguardando Moderação
                            </span>
                        @elseif($video->status === 'processing')
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Processando
                            </span>
                        @elseif($video->status === 'approved')
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-check-circle mr-1"></i> Aprovado
                            </span>
                        @elseif($video->status === 'displayed')
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-star mr-1"></i> Exibido {{ $video->vezes_exibido }}x
                            </span>
                        @elseif($video->status === 'rejected')
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                <i class="fas fa-times-circle mr-1"></i> Rejeitado
                            </span>
                        @endif
                    </div>

                    @if($video->descricao)
                        <p class="text-gray-700 mb-3">{{ $video->descricao }}</p>
                    @endif

                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <span>
                            <i class="far fa-calendar mr-1"></i>
                            Enviado em {{ $video->created_at->format('d/m/Y H:i') }}
                        </span>
                        
                        @if($video->painel)
                            <span>
                                <i class="fas fa-tv mr-1"></i>
                                {{ $video->painel->nome }}
                            </span>
                        @endif

                        @if($video->duracao_formatada)
                            <span>
                                <i class="far fa-clock mr-1"></i>
                                {{ $video->duracao_formatada }}
                            </span>
                        @endif

                        @if($video->data_aprovacao)
                            <span>
                                <i class="fas fa-check mr-1"></i>
                                Aprovado em {{ $video->data_aprovacao->format('d/m/Y H:i') }}
                            </span>
                        @endif

                        @if($video->data_exibicao)
                            <span>
                                <i class="fas fa-play mr-1"></i>
                                Exibido em {{ $video->data_exibicao->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>

                    <!-- Motivo de Rejeição -->
                    @if($video->status === 'rejected' && $video->motivo_rejeicao)
                        <div class="mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                            <p class="text-red-800 text-sm">
                                <strong>Motivo da rejeição:</strong> {{ $video->motivo_rejeicao }}
                            </p>
                            @if($video->moderador)
                                <p class="text-red-600 text-xs mt-1">
                                    Moderado por {{ $video->moderador->name }} em {{ $video->data_rejeicao->format('d/m/Y H:i') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Ações -->
                <div class="ml-6 flex flex-col space-y-2">
                    <a href="{{ route('cidadao.videos.show', $video) }}" 
                       class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 text-center text-sm">
                        <i class="fas fa-eye mr-1"></i> Ver Detalhes
                    </a>

                    @if(in_array($video->status, ['pending', 'rejected']))
                        <form action="{{ route('cidadao.videos.destroy', $video) }}" method="POST" 
                              onsubmit="return confirm('Tem certeza que deseja remover este vídeo?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm">
                                <i class="fas fa-trash mr-1"></i> Remover
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Nenhum vídeo encontrado</h3>
            <p class="text-gray-500 mb-6">Você ainda não enviou nenhum vídeo.</p>
            <a href="{{ route('cidadao.videos.create') }}" 
               class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition inline-block">
                <i class="fas fa-plus-circle mr-2"></i>
                Enviar Primeiro Vídeo
            </a>
        </div>
    @endforelse
</div>

<!-- Paginação -->
@if($videos->hasPages())
    <div class="mt-8">
        {{ $videos->links() }}
    </div>
@endif
@endsection
