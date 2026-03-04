<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ModeracaoController;
use App\Http\Controllers\PainelController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Página inicial redireciona para login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticação gov.assaí
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// AJAX para verificar CPF
Route::post('/check-cpf', [AuthController::class, 'checkCpf'])->name('check.cpf');

/*
|--------------------------------------------------------------------------
| Rotas do Cidadão (Autenticadas)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('cidadao')->name('cidadao.')->group(function () {
    // Dashboard do cidadão
    Route::get('/dashboard', [DashboardController::class, 'cidadao'])->name('dashboard');

    // Gerenciamento de vídeos
    Route::resource('videos', VideoController::class)->except(['edit', 'update']);
});

/*
|--------------------------------------------------------------------------
| Rotas de Moderação (Moderadores e Admins)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'moderador'])->prefix('admin/moderacao')->name('admin.moderacao.')->group(function () {
    // Fila de moderação
    Route::get('/', [ModeracaoController::class, 'index'])->name('index');
    Route::get('/{video}', [ModeracaoController::class, 'show'])->name('show');
    
    // Aprovar/Rejeitar
    Route::post('/{video}/aprovar', [ModeracaoController::class, 'aprovar'])->name('aprovar');
    Route::post('/{video}/rejeitar', [ModeracaoController::class, 'rejeitar'])->name('rejeitar');
    
    // Histórico
    Route::get('/historico/todos', [ModeracaoController::class, 'historico'])->name('historico');
});

/*
|--------------------------------------------------------------------------
| Rotas Administrativas (Apenas Admins)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard administrativo
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Gerenciamento de painéis
    Route::resource('paineis', PainelController::class);
    Route::post('/paineis/sincronizar', [PainelController::class, 'sincronizar'])->name('paineis.sincronizar');
    Route::post('/paineis/{painel}/status', [PainelController::class, 'verificarStatus'])->name('paineis.status');
    Route::post('/paineis/{painel}/brilho', [PainelController::class, 'ajustarBrilho'])->name('paineis.brilho');
    Route::get('/paineis/{painel}/screenshot', [PainelController::class, 'capturarScreenshot'])->name('paineis.screenshot');

    // Configurações do sistema
    Route::get('/configuracoes', [DashboardController::class, 'configuracoes'])->name('configuracoes');
    Route::post('/configuracoes', [DashboardController::class, 'salvarConfiguracoes'])->name('configuracoes.salvar');
});

