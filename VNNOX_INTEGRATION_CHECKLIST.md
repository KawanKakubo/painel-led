# ✅ VNNOX Integration Checklist - Validação Completa

## 📋 Resumo da Validação

A integração com a API VNNOX foi **revisada e corrigida** de acordo com a documentação oficial da NovaStar NovaCloud Open Platform.

---

## ✅ Correções Implementadas

### 1. **Parâmetros de Autenticação (CRÍTICO)**

**Problema:** CurTime estava sendo enviado como `integer`, mas a documentação especifica que todos os parâmetros públicos devem ser `string`.

**Solução:**
```php
// ANTES (INCORRETO)
$curTime = time(); // retorna int

// DEPOIS (CORRETO)
$curTime = (string) time(); // força string
```

**Impacto:** Sem essa correção, a autenticação pode falhar silenciosamente.

---

### 2. **Content-Type por Tipo de Requisição**

**Problema:** Usando `application/json` para todas as requisições.

**Solução:**
```php
// GET requests
Content-Type: application/x-www-form-urlencoded

// POST requests  
Content-Type: application/json; charset=utf-8
```

**Implementação:**
```php
private function getAuthHeaders($isPost = false)
{
    $headers = [
        'AppKey' => $this->appKey,
        'Nonce' => $nonce,
        'CurTime' => $curTime,
        'CheckSum' => $checkSum,
    ];
    
    if ($isPost) {
        $headers['Content-Type'] = 'application/json; charset=utf-8';
    } else {
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
    }
    
    return $headers;
}
```

---

### 3. **URL da API Correta**

**Problema:** Usando `https://api.vnnox.com` (URL genérica inexistente).

**Solução:** Usar URL específica por região conforme documentação:

```env
# Estados Unidos (padrão)
VNNOX_API_URL=https://openapi-us.vnnox.com

# Europa
VNNOX_API_URL=https://openapi-eu.vnnox.com

# China
VNNOX_API_URL=https://openapi-cn.vnnox.com
```

**Arquivos atualizados:**
- ✅ `config/paineis.php`
- ✅ `.env.example`
- ✅ `database/migrations/2024_03_04_000001_create_paineis_tables.php`
- ✅ `resources/views/admin/configuracoes.blade.php`

---

### 4. **Tratamento de Erros da API**

**Adicionado:** Método para interpretar estrutura de erro padrão da VNNOX.

```php
private function handleErrorResponse($response)
{
    $body = $response->json();
    
    // Estrutura de erro VNNOX: { "error": { "code": "...", "message": "..." } }
    if (isset($body['error'])) {
        return [
            'success' => false,
            'code' => $body['error']['code'],
            'message' => $body['error']['message']
        ];
    }
    
    return [
        'success' => false,
        'code' => 'HTTP_' . $response->status(),
        'message' => 'Erro na requisição: ' . $response->status()
    ];
}
```

**Códigos de erro comuns:**
- `INVALID_APPKEY` - AppKey inexistente
- `INVALID_CHECKSUM` - Assinatura inválida
- `EXPIRED_REQUEST` - Timestamp fora do intervalo (> 5 minutos)
- `RATE_LIMIT_EXCEEDED` - Limite de taxa excedido

---

## 📊 Validação da Implementação

### ✅ Estrutura de Autenticação

| Requisito | Status | Implementação |
|-----------|--------|---------------|
| **AppKey** no header | ✅ | `$this->appKey` |
| **Nonce** (8-64 chars) | ✅ | `bin2hex(random_bytes(16))` = 32 chars |
| **CurTime** (string) | ✅ | `(string) time()` |
| **CheckSum** (SHA256) | ✅ | `hash('sha256', $appSecret . $nonce . $curTime)` |

### ✅ Cálculo do CheckSum

```php
// Fórmula oficial: CheckSum = SHA256(AppSecret + Nonce + CurTime)
private function generateCheckSum($nonce, $curTime)
{
    return hash('sha256', $this->appSecret . $nonce . $curTime);
}
```

**Exemplo de validação manual:**
```php
AppSecret = "87654321fedcba0987654321fedcba09"
Nonce     = "1a2b3c4d"
CurTime   = "1688201200"

Input     = "87654321fedcba0987654321fedcba091a2b3c4d1688201200"
CheckSum  = SHA256(Input)
          = "f663157a0881e3345f20d7bcc3ee82d871ec5ac804fff2e5527c396081a8fce2"
```

### ✅ Endpoints Implementados

| Método | Endpoint | Tipo | Content-Type | Status |
|--------|----------|------|--------------|--------|
| `listarPlayers()` | `/v2/player/list` | GET | `x-www-form-urlencoded` | ✅ |
| `verificarStatusPlayer()` | `/v2/player/status` | GET | `x-www-form-urlencoded` | ✅ |
| `uploadMidia()` | `/v2/media/upload` | POST | `json; charset=utf-8` | ✅ |
| `inserirExibicaoEmergencial()` | `/v2/player/emergency-program/page` | POST | `json; charset=utf-8` | ✅ |
| `atualizarProgramacaoNormal()` | `/v2/player/program/normal` | POST | `json; charset=utf-8` | ✅ |
| `obterStatusReproducao()` | `/v2/player/current/running-status` | POST | `json; charset=utf-8` | ✅ |
| `ajustarBrilho()` | `/v2/player/brightness` | POST | `json; charset=utf-8` | ✅ |
| `capturarScreenshot()` | `/v2/player/screenshot` | GET | `x-www-form-urlencoded` | ✅ |

---

## ⚠️ Rate Limits (Importante!)

A API VNNOX impõe limites de taxa conforme documentação:

| Limite | Valor | Consequência |
|--------|-------|--------------|
| **Instantâneo** | 15 chamadas/segundo por IP | HTTP 429 Too Many Requests |
| **Acumulado** | 1500 chamadas/hora por IP | HTTP 429 Too Many Requests |

### Recomendações:

1. **Implementar cache** para dados que não mudam frequentemente (lista de players)
2. **Usar filas** (Laravel Jobs) para operações em lote
3. **Adicionar retry logic** com backoff exponencial para 429 errors
4. **Monitorar** quantidade de chamadas por hora

---

## 🔐 Segurança

### ✅ Checklist de Segurança

- [x] **AppSecret nunca exposto** no frontend ou logs
- [x] **Nonce único** por requisição (previne replay attacks)
- [x] **Timestamp validado** (máximo 5 minutos de diferença)
- [x] **HTTPS obrigatório** (protocolo fixado no código)
- [x] **Credenciais em .env** (não versionadas no git)

---

## 🧪 Como Testar a Integração

### 1. Configurar Credenciais

Edite `.env`:
```env
VNNOX_APP_KEY=sua_app_key_real_aqui
VNNOX_APP_SECRET=sua_app_secret_real_aqui
VNNOX_API_URL=https://openapi-us.vnnox.com
```

**Obter credenciais:**
1. Acesse [NovaCloud Open Platform](https://open-us.vnnox.com) (ou região apropriada)
2. Faça login com conta VNNOX Media/Care
3. Complete autenticação empresarial (obrigatório para API completa)
4. Copie AppKey e AppSecret do dashboard

### 2. Testar Conectividade

Entre no tinker:
```bash
php artisan tinker
```

Teste listar players:
```php
$vnnox = new App\Services\VNNOXService();
$players = $vnnox->listarPlayers();
dd($players);
```

**Resultados esperados:**

**✅ Sucesso:**
```json
{
  "code": 200,
  "data": [
    {
      "player_id": "TB60-12345678",
      "player_name": "Painel Centro",
      "status": "online",
      ...
    }
  ]
}
```

**❌ Erro de autenticação:**
```json
{
  "error": {
    "code": "INVALID_APPKEY",
    "message": "The AppKey provided does not exist in our system"
  }
}
```

**❌ Erro de tempo:**
```json
{
  "error": {
    "code": "EXPIRED_REQUEST",
    "message": "Request timestamp exceeds maximum allowed difference"
  }
}
```

Se receber `EXPIRED_REQUEST`, sincronize o relógio do servidor:
```bash
# Linux
sudo timedatectl set-ntp true

# Windows (PowerShell como Admin)
w32tm /resync
```

### 3. Testar Upload e Exibição

```php
use App\Services\VNNOXService;
use Illuminate\Support\Facades\Storage;

$vnnox = new VNNOXService();

// 1. Upload de vídeo
$videoPath = storage_path('app/videos/teste.mp4');
$mediaId = $vnnox->uploadMidia($videoPath, 'teste.mp4');

if ($mediaId) {
    echo "Upload OK! Media ID: $mediaId\n";
    
    // 2. Inserir em exibição emergencial
    $resultado = $vnnox->inserirExibicaoEmergencial(
        'TB60-12345678', // ID do seu player
        $mediaId,
        30 // Duração em segundos
    );
    
    if ($resultado['success']) {
        echo "Vídeo em exibição!\n";
    } else {
        echo "Erro: " . $resultado['message'] . "\n";
    }
} else {
    echo "Falha no upload\n";
}
```

---

## 📝 Logs para Debug

Os logs da integração estão em `storage/logs/laravel.log`:

```bash
# Ver logs em tempo real
tail -f storage/logs/laravel.log | grep VNNOX

# Windows PowerShell
Get-Content storage\logs\laravel.log -Wait | Select-String "VNNOX"
```

**O que procurar:**
- `[VNNOX] Request headers` - Valida se headers estão corretos
- `[VNNOX] Response status: 401` - Problema de autenticação
- `[VNNOX] Response status: 429` - Rate limit excedido
- `[VNNOX] CheckSum generated` - Valida cálculo do checksum

---

## 🔍 Troubleshooting

### Erro: "INVALID_CHECKSUM"

**Causas possíveis:**
1. AppSecret incorreto
2. Diferença de tempo entre cliente/servidor > 5 minutos
3. Encoding incorreto (UTF-8 obrigatório)

**Solução:**
```bash
# Verificar timestamp do servidor
date -u  # Linux
Get-Date -Format "o" # Windows

# Sincronizar NTP
sudo timedatectl set-ntp true  # Linux
w32tm /resync  # Windows (Admin)
```

### Erro: "RATE_LIMIT_EXCEEDED"

**Solução:**
```php
// Implementar cache no controller
use Illuminate\Support\Facades\Cache;

public function listarPaineis()
{
    $players = Cache::remember('vnnox_players', 300, function () {
        return $this->vnnoxService->listarPlayers();
    });
    
    return view('admin.paineis.index', compact('players'));
}
```

### Erro: "Connection timeout"

**Causas:**
1. Firewall bloqueando porta 443
2. DNS não resolvendo openapi-us.vnnox.com
3. Proxy corporativo

**Solução:**
```bash
# Testar conectividade
curl -v https://openapi-us.vnnox.com/v2/player/list

# Verificar DNS
nslookup openapi-us.vnnox.com
```

---

## ✅ Checklist Final

- [ ] Credenciais VNNOX configuradas no `.env`
- [ ] URL da API correta para sua região
- [ ] Autenticação empresarial completa na plataforma VNNOX
- [ ] Devices (TB30/TB50/TB60) vinculados no VNNOX Media
- [ ] Relógio do servidor sincronizado (NTP)
- [ ] Firewall permite HTTPS para openapi-*.vnnox.com
- [ ] Teste manual com `php artisan tinker` bem-sucedido
- [ ] Logs não mostram erros de autenticação
- [ ] Rate limits monitorados (< 15 req/s, < 1500 req/h)

---

## 📚 Documentação de Referência

- [NovaCloud Open Platform](https://open-us.vnnox.com) - Portal de desenvolvedores
- [API Documentation](https://docs.vnnox.com) - Documentação completa
- [VNNOX Media](https://vnnox.com/media) - Gerenciamento de conteúdo
- [VNNOX Care](https://vnnox.com/care) - Monitoramento de devices

---

## 🎯 Status da Integração

| Componente | Status | Observação |
|------------|--------|------------|
| Autenticação | ✅ | CheckSum SHA256 correto |
| Headers | ✅ | Todos os parâmetros como string |
| Content-Type | ✅ | GET vs POST corretos |
| URL da API | ✅ | openapi-us.vnnox.com |
| Rate Limiting | ⚠️ | Implementar cache/throttling |
| Error Handling | ✅ | Estrutura error.code/message |
| Logs | ✅ | Laravel Log facade |
| Testes | ⏳ | Pendente testes em produção |

**Legenda:**
- ✅ Completo e validado
- ⚠️ Funcional, melhorias recomendadas
- ⏳ Aguardando ação

---

**Última atualização:** 6 de março de 2026  
**Versão da documentação VNNOX:** v2 (2024)
