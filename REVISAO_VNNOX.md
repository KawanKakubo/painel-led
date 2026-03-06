# RELATÓRIO DE REVISÃO - CONFORMIDADE COM API VNNOX

**Data:** 06/03/2026  
**Documento Base:** doc.md (Documentação Oficial OPEN VNNOX)  
**Escopo:** Integração com API VNNOX no VNNOXService.php

---

## ❌ PROBLEMAS CRÍTICOS ENCONTRADOS

### 1. **URL BASE DA API INCORRETA**

**Problema:**
- **Configurado:** `https://openapi-us.vnnox.com`
- **Correto (doc):** `https://open-us.vnnox.com`

**Impacto:** 🔴 CRÍTICO - Todas as chamadas à API estão falhando

**Arquivo:** `config/paineis.php` linha 21

**Correção Necessária:**
```php
// ERRADO
'api_url' => env('VNNOX_API_URL', 'https://openapi-us.vnnox.com'),

// CORRETO
'api_url' => env('VNNOX_API_URL', 'https://open-us.vnnox.com'),
```

---

### 2. **ENDPOINT DE BRILHO INCORRETO**

**Problema:**
- **Implementado:** `POST /v2/player/brightness` com `{player_id: string, brightness: integer}`
- **Correto (doc):** `POST /v2/player/real-time-control/brightness` com `{playerIds: array, value: integer}`

**Impacto:** 🔴 CRÍTICO - Ajuste de brilho não funciona

**Arquivo:** `app/Services/VNNOXService.php` método `ajustarBrilho()`

**Correção Necessária:**
```php
// ERRADO
$response = Http::withHeaders($this->getAuthHeaders(true))
    ->post("{$this->apiUrl}/v2/player/brightness", [
        'player_id' => $playerId,
        'brightness' => $nivel
    ]);

// CORRETO
$response = Http::withHeaders($this->getAuthHeaders(true))
    ->post("{$this->apiUrl}/v2/player/real-time-control/brightness", [
        'playerIds' => [$playerId],
        'value' => $nivel
    ]);
```

---

### 3. **ENDPOINT DE SCREENSHOT COMPLETAMENTE INCORRETO**

**Problema:**
- **Implementado:** `GET /v2/player/screenshot?player_id={id}` (síncrono)
- **Correto (doc):** `POST /v2/player/real-time-control/screen-capture` com `{playerIds: array, noticeUrl: string}` (ASSÍNCRONO com callback)

**Impacto:** 🔴 CRÍTICO - Captura de screenshot não funciona

**Arquivo:** `app/Services/VNNOXService.php` método `capturarScreenshot()`

**Observações:**
- A API VNNOX usa callbacks assíncronos para screenshots
- É necessário criar um endpoint de callback para receber a imagem
- O método atual tenta buscar o screenshot de forma síncrona, o que não é suportado

---

### 4. **ENDPOINT DE STATUS DO PLAYER INCORRETO**

**Problema:**
- **Implementado:** `GET /v2/player/status?player_id={id}`
- **Correto (doc):** `POST /v2/player/current/online-status` com `{playerIds: array}` ou `{playerSns: array}`

**Impacto:** 🔴 CRÍTICO - Verificação de status não funciona

**Arquivo:** `app/Services/VNNOXService.php` método `verificarStatusPlayer()`

**Correção Necessária:**
```php
// ERRADO
$response = Http::withHeaders($this->getAuthHeaders(false))
    ->get("{$this->apiUrl}/v2/player/status", [
        'player_id' => $playerId
    ]);

// CORRETO
$response = Http::withHeaders($this->getAuthHeaders(true))
    ->post("{$this->apiUrl}/v2/player/current/online-status", [
        'playerIds' => [$playerId]
    ]);
```

---

### 5. **ENDPOINT DE CONFIGURAÇÃO DE REPRODUÇÃO INCORRETO**

**Problema:**
- **Implementado:** `POST /v2/player/current/running-status` com `{player_id: string}`
- **Correto (doc):** `POST /v2/player/current/running-status` com `{playerIds: array, commands: array, noticeUrl: string}` (ASSÍNCRONO)

**Impacto:** 🟡 MÉDIO - Método existe mas estrutura incorreta

**Arquivo:** `app/Services/VNNOXService.php` método `obterStatusReproducao()`

**Observações:**
- A API requer um callback assíncrono (noticeUrl)
- É necessário especificar quais comandos obter (volumeValue, brightnessValue, etc.)

---

## ⚠️ ENDPOINTS NÃO DOCUMENTADOS OU INCORRETOS

### 6. **Upload de Mídia - PROBLEMA CRÍTICO IDENTIFICADO!** ⛔

**PROBLEMA FUNDAMENTAL:**
O método `uploadMidia()` tentava usar o endpoint `/v2/media/upload`, mas este endpoint **NÃO EXISTE** na API VNNOX!

**COMO A API VNNOX REALMENTE FUNCIONA:**

A API VNNOX **NÃO FAZ UPLOAD** de arquivos. O fluxo correto é:

1. **Você hospeda** o arquivo de vídeo em um servidor web acessível publicamente
2. **Você fornece a URL** pública do arquivo na requisição
3. **A API VNNOX faz o DOWNLOAD** do arquivo do seu servidor
4. **É obrigatório fornecer:** URL, MD5, tamanho em bytes, duração em ms

**Estrutura correta conforme documentação:**

```json
{
  "playerIds": ["player123"],
  "attribute": {
    "spotsType": "IMMEDIATELY",
    "normalProgramStatus": "PAUSE",
    "duration": 30000
  },
  "page": {
    "name": "emergency-video",
    "widgets": [{
      "type": "VIDEO",
      "url": "https://seu-servidor.com/videos/video.mp4",
      "md5": "f5b0f315800cb4befb89b5dff42f1e34",
      "size": 1227710,
      "duration": 30000,
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

**CORREÇÕES APLICADAS:**

1. ✅ Método `uploadMidia()` marcado como **DEPRECATED**
2. ✅ Criado novo método `inserirExibicaoEmergencialComVideo()` que aceita URL pública
3. ✅ Método antigo `inserirExibicaoEmergencial()` marcado como **DEPRECATED**
4. ✅ Atualizado `ExibirVideoJob` para usar o novo método
5. ✅ Adicionados campos `md5_hash` e `tamanho_bytes` na tabela `videos`
6. ✅ `ProcessarVideoJob` agora calcula e salva MD5 e tamanho automaticamente

**REQUISITOS DE INFRAESTRUTURA:**

⚠️ **CRÍTICO:** Os arquivos de vídeo processados **DEVEM estar acessíveis publicamente** via HTTP/HTTPS para que a API VNNOX possa fazer o download.

**Opções de hospedagem:**

1. **Storage público do Laravel** (configurar `storage/app/public` e `php artisan storage:link`)
2. **CDN/Cloud Storage** (AWS S3, Google Cloud Storage, Azure Blob)
3. **Servidor web próprio** com diretório público

**Exemplo de configuração:**

Em `config/filesystems.php`:
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
]
```

Executar:
```bash
php artisan storage:link
```

Isso criará um link simbólico de `public/storage` para `storage/app/public`, tornando os arquivos acessíveis publicamente.

---

## ✅ ENDPOINTS CORRETOS

### 1. **Lista de Players** - ✓ CORRETO
```
GET /v2/player/list
```
Implementação está de acordo com a documentação.

### 2. **Inserção de Exibição Emergencial** - ✓ PARCIALMENTE CORRETO
```
POST /v2/player/emergency-program/page
```
URL correta, mas precisa validar estrutura completa do payload.

### 3. **Programação Normal** - ✓ CORRETO (implementação básica)
```
POST /v2/player/program/normal
```

---

## 📊 ENDPOINTS DOCUMENTADOS MAS NÃO IMPLEMENTADOS

Os seguintes endpoints estão na documentação mas **NÃO foram implementados**:

1. **Cancelar Exibição Emergencial**
   - `POST /v2/player/emergency-program/cancel`
   - **Importância:** ALTA - Necessário para cancelar vídeos em exibição

2. **Ajuste de Volume**
   - `POST /v2/player/real-time-control/volume`
   - **Importância:** MÉDIA

3. **Mudança de Fonte de Vídeo**
   - `POST /v2/player/real-time-control/video-source`
   - **Importância:** BAIXA

4. **Status da Tela**
   - `POST /v2/player/real-time-control/screen-status`
   - **Importância:** MÉDIA

5. **Reiniciar Player**
   - `POST /v2/player/real-time-control/restart`
   - **Importância:** MÉDIA

6. **Controle de Energia da Tela**
   - `POST /v2/player/real-time-control/screen-power`
   - **Importância:** MÉDIA

7. **Sincronização NTP**
   - `POST /v2/player/real-time-control/ntp`
   - **Importância:** BAIXA

8. **Reprodução Síncrona**
   - `POST /v2/player/real-time-control/simulcast`
   - **Importância:** BAIXA (específico para múltiplos painéis sincronizados)

---

## 🔧 PROBLEMAS ADICIONAIS IDENTIFICADOS

### Estrutura de Response Inconsistente

A maioria dos endpoints de controle retorna:
```json
{
  "success": ["playerId1", "playerId2"],
  "fail": ["playerId3"]
}
```

Mas a implementação atual não trata adequadamente esses retornos em array.

### Falta de Tratamento de Callbacks

Vários endpoints da API VNNOX são **assíncronos** e requerem um `noticeUrl` para receber o resultado:
- Screenshot
- Status de Reprodução (running-status)
- Outros comandos avançados

**É necessário:**
1. Criar endpoints de callback no Laravel
2. Implementar lógica para processar callbacks
3. Armazenar resultados assíncronos

---

## 📝 RECOMENDAÇÕES DE CORREÇÃO

### Prioridade 1 (CRÍTICO - Corrigir Imediatamente)
1. ✅ Corrigir URL base da API
2. ✅ Corrigir endpoint de ajuste de brilho
3. ✅ Corrigir endpoint de verificação de status
4. ✅ Implementar endpoint de cancelamento de emergência

### Prioridade 2 (IMPORTANTE)
5. ✅ Reimplementar screenshot com callbacks
6. ✅ Corrigir obtenção de status de reprodução
7. ✅ Padronizar tratamento de respostas (success/fail arrays)

### Prioridade 3 (DESEJÁVEL)
8. Implementar endpoints de controle adicional (volume, reinício, etc.)
9. Criar documentação interna das integrações
10. Adicionar testes automatizados

---

## 🎯 PRÓXIMOS PASSOS

1. **Aplicar correções críticas** no VNNOXService.php
2. **Atualizar config/paineis.php** com URLs corretas
3. **Criar sistema de callbacks** para operações assíncronas
4. **Testar todos os endpoints** após correções
5. **Documentar mudanças** e criar guia de uso

---

## ⚙️ NOTAS TÉCNICAS

### Autenticação
A implementação da autenticação (CheckSum, Nonce, CurTime) está **CORRETA** e conforme a documentação.

### Content-Type Headers
A diferenciação entre GET (application/x-www-form-urlencoded) e POST (application/json) está **CORRETA**.

### Rate Limits
Documentação menciona:
- Máximo 15 chamadas/segundo por IP
- Máximo 1500 chamadas/hora por IP

**Recomendação:** Implementar rate limiting no lado do cliente para evitar bloqueios.

---

**FIM DO RELATÓRIO**
