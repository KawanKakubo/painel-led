@extends('layouts.app')

@section('title', 'Moderação de Vídeos')

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-bold text-gray-800">
        <i class="fas fa-check-circle mr-2"></i>
        Fila de Moderação
    </h2>
    <p class="text-gray-600 mt-2">Aprovar ou rejeitar vídeos enviados por cidadãos</p>
</div>

<!-- Estatísticas Rápidas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-yellow-100 border-l-4 border-yellow-500 p-6 rounded shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-800 text-sm font-semibold">Aguardando Moderação</p>
                <p class="text-3xl font-bold text-yellow-900">{{ $videos->total() }}</p>
            </div>
            <i class="fas fa-clock text-4xl text-yellow-400"></i>
        </div>
    </div>

    <div class="bg-green-100 border-l-4 border-green-500 p-6 rounded shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-800 text-sm font-semibold">Aprovados por Você</p>
                <p class="text-3xl font-bold text-green-900">
                    {{ auth()->user()->videosModeredos()->where('status', 'approved')->count() }}
                </p>
            </div>
            <i class="fas fa-check-circle text-4xl text-green-400"></i>
        </div>
    </div>

    <div class="bg-blue-100 border-l-4 border-blue-500 p-6 rounded shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-800 text-sm font-semibold">Tempo Médio</p>
                <p class="text-2xl font-bold text-blue-900">~2 min</p>
            </div>
            <i class="fas fa-hourglass-half text-4xl text-blue-400"></i>
        </div>
    </div>
</div>

<!-- Lista de Vídeos Pendentes -->
<div class="bg-white rounded-lg shadow">
    <div class="border-b border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-gray-800">
                Vídeos Pendentes
            </h3>
            <a href="{{ route('admin.moderacao.historico') }}" class="text-blue-600 hover:underline text-sm">
                <i class="fas fa-history mr-1"></i>
                Ver histórico completo
            </a>
        </div>
    </div>

    <div class="p-6">
        @if($videos->count() > 0)
            <div class="space-y-6">
                @foreach($videos as $video)
                    <div class="border rounded-lg p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $video->titulo }}</h4>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $video->usuario->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $video->created_at->format('d/m/Y H:i') }}
                                    </span>
                                    @if($video->duracao_formatada)
                                        <span>
                                            <i class="fas fa-stopwatch mr-1"></i>
                                            {{ $video->duracao_formatada }}
                                        </span>
                                    @endif
                                    @if($video->painel)
                                        <span>
                                            <i class="fas fa-tv mr-1"></i>
                                            {{ $video->painel->nome }}
                                        </span>
                                    @endif
                                </div>

                                @if($video->descricao)
                                    <p class="text-gray-700 mb-3">{{ $video->descricao }}</p>
                                @endif

                                <!-- Badges de Status -->
                                <div class="flex items-center space-x-2">
                                    @if($video->status === 'processing')
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Processando
                                        </span>
                                    @endif
                                    
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs">
                                        <i class="fas fa-id-badge mr-1"></i>
                                        Nível {{ $video->usuario->nivel_acesso }}
                                    </span>
                                </div>
                            </div>

                            <!-- Preview de Vídeo (se houver) -->
                            @if($video->arquivo_processado && Storage::exists($video->arquivo_processado))
                                <div class="ml-6">
                                    <video 
                                        class="w-64 h-36 bg-black rounded"
                                        controls
                                    >
                                        <source src="{{ Storage::url($video->arquivo_processado) }}" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                        </div>

                        <!-- Ações de Moderação -->
                        <div class="border-t pt-4 mt-4">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('admin.moderacao.show', $video) }}" 
                                   class="text-blue-600 hover:underline text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Ver detalhes completos
                                </a>

                                <div class="flex items-center space-x-3">
                                    <!-- Botão Rejeitar -->
                                    <button 
                                        onclick="openRejectModal({{ $video->id }}, '{{ $video->titulo }}')"
                                        class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition flex items-center"
                                    >
                                        <i class="fas fa-times-circle mr-2"></i>
                                        Rejeitar
                                    </button>

                                    <!-- Botão Aprovar -->
                                    <form action="{{ route('admin.moderacao.aprovar', $video) }}" method="POST" class="inline">
                                        @csrf
                                        @if($video->painel_id)
                                            <input type="hidden" name="painel_id" value="{{ $video->painel_id }}">
                                        @else
                                            <input type="hidden" name="painel_id" value="{{ $paineis->first()->id ?? '' }}">
                                        @endif
                                        <button 
                                            type="submit"
                                            class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition flex items-center"
                                            onclick="return confirm('Aprovar este vídeo?')"
                                        >
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Aprovar
                                        </button>
                                    </form>

                                    <!-- Botão Aprovar e Exibir -->
                                    <form action="{{ route('admin.moderacao.aprovar', $video) }}" method="POST" class="inline">
                                        @csrf
                                        @if($video->painel_id)
                                            <input type="hidden" name="painel_id" value="{{ $video->painel_id }}">
                                        @else
                                            <input type="hidden" name="painel_id" value="{{ $paineis->first()->id ?? '' }}">
                                        @endif
                                        <input type="hidden" name="exibir_agora" value="1">
                                        <button 
                                            type="submit"
                                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition flex items-center"
                                            onclick="return confirm('Aprovar e exibir este vídeo agora?')"
                                        >
                                            <i class="fas fa-play-circle mr-2"></i>
                                            Aprovar e Exibir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Paginação -->
            <div class="mt-6">
                {{ $videos->links() }}
            </div>
        @else
            <div class="text-center py-12 text-gray-500">
                <i class="fas fa-check-circle text-6xl text-green-400 mb-4"></i>
                <p class="text-xl font-semibold">Nenhum vídeo pendente!</p>
                <p class="text-sm mt-2">Todos os vídeos foram moderados.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Rejeição -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
        <div class="border-b border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-800">Rejeitar Vídeo</h3>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6">
                <p class="text-gray-700 mb-4" id="rejectVideoTitle"></p>
                
                <label for="motivo" class="block text-gray-700 font-semibold mb-2">
                    Motivo da Rejeição *
                </label>
                <textarea 
                    id="motivo" 
                    name="motivo" 
                    rows="4"
                    class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                    placeholder="Explique por que este vídeo não pode ser exibido..."
                    required
                ></textarea>
            </div>
            <div class="border-t border-gray-200 p-6 flex items-center justify-end space-x-3">
                <button 
                    type="button"
                    onclick="closeRejectModal()"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
                >
                    Cancelar
                </button>
                <button 
                    type="submit"
                    class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                >
                    Confirmar Rejeição
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openRejectModal(videoId, videoTitle) {
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectVideoTitle').textContent = 'Rejeitando: ' + videoTitle;
        document.getElementById('rejectForm').action = `/admin/moderacao/${videoId}/rejeitar`;
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('motivo').value = '';
    }
</script>
@endpush
