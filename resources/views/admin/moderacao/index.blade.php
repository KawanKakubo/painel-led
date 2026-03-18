@extends('layouts.app')

@section('title', 'Fila de Moderação')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-tasks mr-2"></i>
            Fila de Moderação
        </h2>
        <p class="text-gray-600 mt-2">Analise os vídeos enviados pelos cidadãos e comerciantes</p>
    </div>
    
    <!-- Filtros Técnicos -->
    <div class="mt-4 sm:mt-0 flex gap-2">
        <a href="{{ route('admin.moderacao.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition {{ !request('filtro_duracao') ? 'bg-gray-800 text-white hover:bg-gray-700' : '' }}">Todos</a>
        <a href="{{ route('admin.moderacao.index', ['filtro_duracao' => '15s']) }}" class="px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded-lg text-sm font-medium transition {{ request('filtro_duracao') == '15s' ? 'ring-2 ring-blue-500' : '' }}">Rotação Curta (15s)</a>
        <a href="{{ route('admin.moderacao.index', ['filtro_duracao' => 'longos']) }}" class="px-4 py-2 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-lg text-sm font-medium transition {{ request('filtro_duracao') == 'longos' ? 'ring-2 ring-purple-500' : '' }}">Tasks Únicas (>15s)</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário / Tipo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vídeo / Pré-visualização</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações (Aprovar/Reprovar)</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($videos as $video)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #{{ $video->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $video->usuario->name ?? 'Anônimo' }}</div>
                                <div class="text-xs text-gray-500 flex flex-col">
                                    <span class="font-bold text-indigo-600">{{ ucfirst($video->usuario->tipo_perfil ?? 'Cidadão') }}</span>
                                    <span>{{ $video->categoria_video ?? 'Categoria: Geral' }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <video src="{{ Storage::url($video->arquivo_processado ?? $video->arquivo_original) }}" class="h-16 w-24 object-cover bg-black rounded" controls></video>
                            <div>
                                <div class="text-sm text-gray-900 font-bold max-w-xs truncate">{{ $video->titulo }}</div>
                                <a href="{{ Storage::url($video->arquivo_processado ?? $video->arquivo_original) }}" download class="text-xs text-blue-600 hover:underline flex items-center mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Baixar Arquivo
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="flex flex-col">
                            <span class="text-xs {{ $video->plano_segundos == 15 || $video->duracao_segundos <= 15 ? 'text-blue-600 font-bold' : 'text-purple-600 font-bold' }}">
                                {{ $video->plano_segundos ?? ($video->duracao_segundos ?? '60') }} segundos
                            </span>
                            @if($video->semana_intencao)
                                <span class="text-xs mt-1">Semana: {{ $video->semana_intencao }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <!-- Botão Aprovar -->
                            <form action="{{ route('admin.moderacao.aprovar', $video) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded inline-flex items-center text-xs font-bold transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Aprovar
                                </button>
                            </form>
                            
                            <!-- Botão Reprovar (Abre Modal/Prompt) -->
                            <button type="button" onclick="openRejectModal({{ $video->id }})" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded inline-flex items-center text-xs font-bold transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reprovar
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col justify-center items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-lg">Nenhum vídeo pendente na fila.</p>
                            <p class="text-sm mt-1">A curadoria está em dia!</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="px-6 py-4">
        {{ $videos->links() }}
    </div>
</div>

<!-- Reject Modal (Escondido) -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Motivo da Reprovação
        </h3>
        <p class="text-sm text-gray-600 mb-4">Escolha ou digite o motivo. Isso será enviado como feedback automático para o usuário.</p>
        
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <select id="quickReasons" class="w-full px-3 py-2 border rounded-md mb-2 text-sm focus:ring-red-500 focus:border-red-500" onchange="document.getElementById('motivo_rejeicao').value = this.value">
                    <option value="">Selecione um motivo comum...</option>
                    <option value="Qualidade de áudio ou vídeo ruim">Qualidade de áudio ou vídeo ruim</option>
                    <option value="Conteúdo impróprio ou não autorizado">Conteúdo impróprio ou ofensivo</option>
                    <option value="Tempo de duração excedido na edição">Tempo estourado</option>
                    <option value="Problemas com Direitos Autorais na música">Problemas de Direitos Autorais (Música)</option>
                </select>
                <textarea name="motivo" id="motivo_rejeicao" rows="3" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-red-500 focus:border-red-500" placeholder="Ou digite o motivo detalhado..." required></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-sm text-white bg-red-600 hover:bg-red-700 rounded-md font-bold">Confirmar Reprovação</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(videoId) {
        document.getElementById('rejectModal').classList.remove('hidden');
        // Atualiza action do form
        document.getElementById('rejectForm').action = "/admin/moderacao/" + videoId + "/rejeitar";
        document.getElementById('motivo_rejeicao').value = "";
        document.getElementById('quickReasons').value = "";
    }
    
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection
