# CHANGELOG - CORREÇÕES DA INTEGRAÇÃO VNNOX

**Data:** 06/03/2026  
**Responsável:** Revisão completa conforme documentação oficial OPEN VNNOX  
**Documento Base:** doc.md

---

## 🔴 CORREÇÃO CRÍTICA: SISTEMA DE UPLOAD/EXIBIÇÃO COMPLETAMENTE REFEITO

### ⚠️ PROBLEMA FUNDAMENTAL DESCOBERTO

A implementação original estava **fundamentalmente incorreta** sobre como a API VNNOX funciona:

**❌ IMPLEMENTAÇÃO INCORRETA:**
- Tentava fazer "upload" de arquivos para a API VNNOX
- Esperava receber um "media_id" da API
- Usava esse "media_id" inexistente para exibir vídeos

**✅ COMPORTAMENTO REAL DA API VNNOX:**
- **NÃO há endpoint de upload**
- **NÃO há conceito de "media_id"**
- A API faz **DOWNLOAD** de URLs públicas que você fornece
- Você deve informar: **URL**, **MD5**, **tamanho em bytes**, **duração**

### 🆕 NOVO MÉTODO CORRETO

**Criado:** `inserirExibicaoEmergencialComVideo()`

```php
$resultado = $vnnoxService->inserirExibicaoEmergencialComVideo(
    $playerId,        // ou array de playerIds
    $urlPublica,      // URL acessível pela internet
    $md5Hash,         // Hash MD5 do arquivo
    $tamanhoBytes,    // Tamanho do arquivo em bytes
    $duracaoSegundos, // Duração em segundos
    [
        'name' => 'Nome do Vídeo',
        'spotsType' => 'IMMEDIATELY',
        'normalProgramStatus' => 'PAUSE'
    ]
);

// Retorna: {success: ['player1'], fail: []}
```

**Estrutura completa enviada à API:**
```json
{
  "playerIds": ["player123"],
  "attribute": {
    "spotsType": "IMMEDIATELY",
    "normalProgramStatus": "PAUSE",
    "duration": 45000
  },
  "page": {
    "name": "emergency-video",
    "widgets": [{
      "type": "VIDEO",
      "url": "https://meusite.com/storage/videos/video.mp4",
      "md5": "f5b0f315800cb4befb89b5dff42f1e34",
      "size": 1227710,
      "duration": 45000,
      "zIndex": 1,
      "layout": {
        "x": "0%",
        "y": "0%",
        "width": "100%",
        "height": "100%"
      },
      "inAnimation": {
        "type": "NONE",
        "duration": 1000
      }
    }]
  }
}
```

### 📦 MUDANÇAS NO BANCO DE DADOS

**Nova Migration:** `2026_03_06_000001_update_videos_table_for_vnnox_api.php`

**Campos Removidos:**
- ❌ `vnnox_media_id` (não existe na API real)

**Campos Adicionados:**
- ✅ `md5_hash` (VARCHAR 32) - Hash MD5 do arquivo processado
- ✅ `tamanho_bytes` (BIGINT) - Tamanho do arquivo em bytes

---

## ✅ CORREÇÕES APLICADAS

### 1. **URL Base da API Corrigida** ✓

**Arquivo:** `config/paineis.php`

**Mudança:**
```php
// ANTES (INCORRETO)
'api_url' => env('VNNOX_API_URL', 'https://openapi-us.vnnox.com'),

// DEPOIS (CORRETO)
'api_url' => env('VNNOX_API_URL', 'https://open-us.vnnox.com'),
```

**Impacto:** Esta era a causa raiz de todas as falhas nas requisições. Com essa mudança, todas as chamadas à API agora usam a URL correta.

**URLs Disponíveis:**
- US: `https://open-us.vnnox.com`
- EU: `https://open-eu.vnnox.com`
- AU: `https://open-au.vnnox.com`
- IN: `https://open-in.vnnox.com`

---

### 2. **Endpoint de Verificação de Status Corrigido** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `verificarStatusPlayer()`

**Mudanças:**
- **Endpoint:** `GET /v2/player/status` → `POST /v2/player/current/online-status`
- **Método HTTP:** `GET` → `POST`
- **Estrutura do body:**
  ```php
  // ANTES (INCORRETO)
  ['player_id' => $playerId]
  
  // DEPOIS (CORRETO)
  ['playerIds' => [$playerId]]
  ```

**Melhorias:**
- Agora aceita tanto um playerId único quanto um array de playerIds
- Retorna dados estruturados conforme documentação: `{playerId, sn, onlineStatus, lastOnlineTime}`

**Exemplo de uso:**
```php
// Um único player
$status = $vnnoxService->verificarStatusPlayer('player123');

// Múltiplos players
$status = $vnnoxService->verificarStatusPlayer(['player1', 'player2', 'player3']);
```

---

### 3. **Endpoint de Ajuste de Brilho Corrigido** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `ajustarBrilho()`

**Mudanças:**
- **Endpoint:** `/v2/player/brightness` → `/v2/player/real-time-control/brightness`
- **Estrutura do body:**
  ```php
  // ANTES (INCORRETO)
  [
    'player_id' => $playerId,
    'brightness' => $nivel
  ]
  
  // DEPOIS (CORRETO)
  [
    'playerIds' => [$playerId],
    'value' => $nivel
  ]
  ```

**Mudança de retorno:**
- Antes retornava `boolean`
- Agora retorna `array`: `{success: [...], fail: [...]}`

**Controller atualizado:** 
`app/Http/Controllers/PainelController.php` - Método `ajustarBrilho()` foi atualizado para processar a nova estrutura de resposta.

**Exemplo de uso:**
```php
// Um único painel
$resultado = $vnnoxService->ajustarBrilho('player123', 75);
// Retorna: {success: ['player123'], fail: []}

// Múltiplos painéis
$resultado = $vnnoxService->ajustarBrilho(['player1', 'player2'], 50);
// Retorna: {success: ['player1', 'player2'], fail: []}
```

---

### 4. **Endpoint de Screenshot Completamente Refeito** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `capturarScreenshot()`

**Mudanças CRÍTICAS:**
- **Endpoint:** `GET /v2/player/screenshot` → `POST /v2/player/real-time-control/screen-capture`
- **Método HTTP:** `GET` → `POST`
- **Comportamento:** Síncrono → **ASSÍNCRONO (com callback)**

**Nova estrutura:**
```php
// ANTES (INCORRETO)
GET /v2/player/screenshot?player_id=xxx
// Tentava obter resultado imediatamente (não suportado)

// DEPOIS (CORRETO)
POST /v2/player/real-time-control/screen-capture
Body: {
  playerIds: ['xxx'],
  noticeUrl: 'https://seu-servidor.com/callback'
}
// API VNNOX irá chamar seu noticeUrl quando o screenshot estiver pronto
```

**Callback implementado:**
- Nova rota: `POST /admin/paineis/{painel}/screenshot/callback`
- Controller: `PainelController::screenshotCallback()`
- A API VNNOX enviará: `{playerId, playerTime, screenShotUrl}`
- O callback **DEVE** retornar a string `"ok"` para confirmar recebimento

**IMPORTANTE:**
- A captura de screenshot é **assíncrona**
- Você solicita a captura e a API VNNOX chama seu callback quando pronto
- O `screenShotUrl` fornecido pela API tem validade limitada (geralmente 24h)
- Recomenda-se fazer download imediato da imagem

---

### 5. **Endpoint de Status de Reprodução Corrigido** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `obterStatusReproducao()`

**Mudanças:**
- Agora é **ASSÍNCRONO** (requer callback)
- Requer array de `commands` especificando o que obter
- Estrutura do body:
  ```php
  // ANTES (INCORRETO)
  ['player_id' => $playerId]
  
  // DEPOIS (CORRETO)
  [
    'playerIds' => [$playerId],
    'commands' => ['volumeValue', 'brightnessValue', 'timeValue'],
    'noticeUrl' => 'https://seu-servidor.com/callback'
  ]
  ```

**Comandos disponíveis:**
- `volumeValue` - Volume atual
- `brightnessValue` - Brilho atual
- `videoSourceValue` - Fonte de vídeo atual
- `timeValue` - Fuso horário e hora atual
- `screenPowerStatus` - Status de energia da tela
- `syncPlayStatus` - Status de reprodução síncrona
- `powerStatus` - Status de energia do cartão multifuncional

**Exemplo de uso:**
```php
$resultado = $vnnoxService->obterStatusReproducao(
    'player123',
    ['volumeValue', 'brightnessValue'],
    'https://meusite.com/callback'
);
```

---

## 🆕 NOVOS MÉTODOS IMPLEMENTADOS

### 1. **Cancelar Exibição Emergencial** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `cancelarExibicaoEmergencial()`

```php
public function cancelarExibicaoEmergencial($playerIds)
```

**Endpoint:** `POST /v2/player/emergency-program/cancel`

**Uso:**
```php
// Cancelar no painel específico
$resultado = $vnnoxService->cancelarExibicaoEmergencial('player123');

// Cancelar em múltiplos painéis
$resultado = $vnnoxService->cancelarExibicaoEmergencial(['player1', 'player2']);
```

**Controller:** Método `cancelarEmergencia()` adicionado ao `PainelController`

**Rota:** `POST /admin/paineis/{painel}/cancelar-emergencia`

---

### 2. **Ajustar Volume** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `ajustarVolume()`

```php
public function ajustarVolume($playerIds, $nivel)
```

**Endpoint:** `POST /v2/player/real-time-control/volume`

**Parâmetros:**
- `$playerIds`: string ou array de playerIds
- `$nivel`: integer (0-100)

**Uso:**
```php
$resultado = $vnnoxService->ajustarVolume('player123', 75);
// Retorna: {success: [...], fail: [...]}
```

---

### 3. **Reiniciar Player** ✓

**Arquivo:** `app/Services/VNNOXService.php` - Método `reiniciarPlayer()`

```php
public function reiniciarPlayer($playerIds)
```

**Endpoint:** `POST /v2/player/real-time-control/restart`

**Uso:**
```php
// Reiniciar um player
$resultado = $vnnoxService->reiniciarPlayer('player123');

// Reiniciar múltiplos players
$resultado = $vnnoxService->reiniciarPlayer(['player1', 'player2', 'player3']);
```

---

## 🔄 MUDANÇAS NA ESTRUTURA DE DADOS

### Respostas Padronizadas

A maioria dos endpoints de controle agora retorna:

```json
{
  "success": ["playerId1", "playerId2"],
  "fail": ["playerId3"]
}
```

**Importante:** Sempre verificar ambas as listas:
- `success`: Array de playerIds que executaram o comando com sucesso
- `fail`: Array de playerIds que falharam

### Exemplo de tratamento correto:

```php
$resultado = $vnnoxService->ajustarBrilho(['player1', 'player2', 'player3'], 80);

if ($resultado !== false && isset($resultado['success'])) {
    $sucessos = count($resultado['success']);
    $falhas = count($resultado['fail']);
    
    echo "Sucesso: $sucessos painéis";
    echo "Falhas: $falhas painéis";
}
```

---

## 📝 NOVAS ROTAS ADICIONADAS

```php
// Callback para receber screenshots da API VNNOX
POST /admin/paineis/{painel}/screenshot/callback

// Cancelar exibição emergencial
POST /admin/paineis/{painel}/cancelar-emergencia
```

**IMPORTANTE:** A rota de callback do screenshot precisa ser **acessível publicamente** para que a API VNNOX possa enviar os dados. Configure seu firewall/proxy adequadamente.

---

## ⚠️ BREAKING CHANGES

### 1. Método `ajustarBrilho()`

**ANTES:**
```php
$sucesso = $vnnoxService->ajustarBrilho($playerId, $nivel);
if ($sucesso) { ... }
```

**AGORA:**
```php
$resultado = $vnnoxService->ajustarBrilho($playerId, $nivel);
if ($resultado !== false && in_array($playerId, $resultado['success'])) { ... }
```

### 2. Método `capturarScreenshot()`

**ANTES:**
```php
$screenshot = $vnnoxService->capturarScreenshot($playerId);
// Retornava dados imediatamente (NÃO FUNCIONAVA)
```

**AGORA:**
```php
$resultado = $vnnoxService->capturarScreenshot($playerId, $callbackUrl);
// Solicita captura, resultado vem via callback posteriormente
```

### 3. Método `verificarStatusPlayer()`

**ANTES:**
```php
$status = $vnnoxService->verificarStatusPlayer($playerId);
// Retornava objeto simples
```

**AGORA:**
```php
$status = $vnnoxService->verificarStatusPlayer($playerId);
// Retorna: {playerId, sn, onlineStatus, lastOnlineTime}
// Suporta também array de playerIds
```

---

## 🚀 PRÓXIMOS PASSOS RECOMENDADOS

### Prioridade ALTA

1. **Atualizar o arquivo `.env`**
   ```env
   VNNOX_APP_KEY=sua-app-key
   VNNOX_APP_SECRET=seu-app-secret
   VNNOX_API_URL=https://open-us.vnnox.com
   ```

2. **Testar todos os endpoints corrigidos**
   - Verificar status do player
   - Ajustar brilho
   - Listar players
   - Inserir exibição emergencial
   - Cancelar exibição emergencial

3. **Configurar callback de screenshot**
   - Garantir que a URL de callback seja acessível publicamente
   - Implementar armazenamento local das imagens recebidas
   - Adicionar notificação em tempo real (WebSocket/Pusher)

### Prioridade MÉDIA

4. **Implementar armazenamento de screenshots**
   - Criar tabela `screenshots` no banco de dados
   - Fazer download automático das imagens do `screenShotUrl`
   - Armazenar em `storage/app/public/screenshots/`

5. **Adicionar mais controles na interface**
   - Botão para ajustar volume (novo método disponível)
   - Botão para reiniciar player (novo método disponível)
   - Indicador visual de status online/offline

6. **Implementar sistema de callbacks genérico**
   - Criar tabela para armazenar requisições pendentes
   - Criar sistema de processamento de callbacks assíncronos
   - Adicionar timeout e retry para operações assíncronas

### Prioridade BAIXA

7. **Implementar rate limiting**
   - API VNNOX limita: 15 req/seg, 1500 req/hora
   - Adicionar throttle nas chamadas
   - Criar fila para requisições em lote

8. **Adicionar monitoramento**
   - Log detalhado de todas as chamadas à API
   - Dashboard de estatísticas de uso
   - Alertas para falhas recorrentes

9. **Implementar endpoints adicionais da documentação**
   - Mudança de fonte de vídeo
   - Controle de energia da tela
   - Sincronização NTP
   - Reprodução síncrona (múltiplos painéis)
   - Logs de reprodução

---

## 📚 DOCUMENTAÇÃO ADICIONAL

### Estrutura de Autenticação (Mantida Correta)

A autenticação via headers está correta:

```php
Headers: {
  'AppKey': 'sua-app-key',
  'Nonce': 'string-aleatoria-32-chars',
  'CurTime': 'timestamp-unix-string',
  'CheckSum': 'sha256(appSecret + nonce + curTime)',
  'Content-Type': 'application/json' (POST) ou 'application/x-www-form-urlencoded' (GET)
}
```

### Tratamento de Erros HTTP

| Código | Descrição | Ação |
|--------|-----------|------|
| 200 | OK | Operação bem-sucedida |
| 400 | Bad Request | Verificar parâmetros |
| 401 | Unauthorized | Verificar AppKey/AppSecret |
| 403 | Forbidden | Permissões insuficientes |
| 406 | Not Acceptable | Validação de parâmetros falhou |
| 429 | Too Many Requests | Aguardar antes de nova tentativa |
| 500 | Internal Server Error | Erro no servidor VNNOX |
| 502 | Bad Gateway | Problema de rede |
| 503 | Service Unavailable | Servidor temporariamente indisponível |

---

## 🔍 TESTES RECOMENDADOS

### 1. Teste de Conectividade
```bash
curl -X GET https://open-us.vnnox.com/v2/player/list \
  -H "AppKey: SUA_APP_KEY" \
  -H "Nonce: test123456789012345678901234567890" \
  -H "CurTime: 1234567890" \
  -H "CheckSum: abc..."
```

### 2. Teste de Ajuste de Brilho
```php
$resultado = $vnnoxService->ajustarBrilho('seu-player-id', 50);
var_dump($resultado);
// Esperado: ['success' => ['seu-player-id'], 'fail' => []]
```

### 3. Teste de Status
```php
$status = $vnnoxService->verificarStatusPlayer('seu-player-id');
var_dump($status);
// Esperado: ['playerId' => '...', 'onlineStatus' => 1, ...]
```

---

## ✅ CHECKLIST DE DEPLOY

- [ ] Atualizar `.env` com credenciais VNNOX corretas
- [ ] Verificar que `VNNOX_API_URL` está com URL correta (sem "api" no subdomínio)
- [ ] Configurar firewall para permitir callbacks da API VNNOX
- [ ] Testar endpoint de callback de screenshot manualmente
- [ ] Executar `php artisan config:clear`
- [ ] Executar `php artisan cache:clear`
- [ ] Testar cada endpoint corrigido
- [ ] Monitorar logs durante primeiras chamadas
- [ ] Documentar qualquer comportamento inesperado

---

**Revisão completa concluída em 06/03/2026**
