# ⚠️ CORREÇÃO CRÍTICA: Sistema de Upload de Vídeos

**Data:** 06/03/2026  
**Prioridade:** 🔴 CRÍTICA

---

## 🚨 PROBLEMA IDENTIFICADO

O sistema estava **completamente incorreto** em relação ao upload/exibição de vídeos. A API VNNOX **NÃO FAZ UPLOAD** de arquivos!

### ❌ Como Estava (INCORRETO):
```
1. Upload de vídeo → Laravel Storage
2. Tentar "upload" para API VNNOX ❌ (endpoint não existe)
3. Receber "media_id" ❌ (não existe)
4. Usar media_id para exibir ❌ (não funciona)
```

### ✅ Como Deve Ser (CORRETO):
```
1. Upload de vídeo → Laravel Storage PÚBLICO
2. Gerar URL pública acessível pela internet
3. Calcular MD5 e tamanho do arquivo
4. Enviar URL, MD5, tamanho e duração para API VNNOX
5. API VNNOX faz DOWNLOAD do seu servidor
```

---

## 🔧 CORREÇÕES APLICADAS

### 1. **Novo Método de Exibição**

**Arquivo:** `app/Services/VNNOXService.php`

**Novo método criado:**
```php
public function inserirExibicaoEmergencialComVideo(
    $playerIds,      // Player ID(s)
    $videoUrl,       // URL pública do vídeo
    $videoMd5,       // Hash MD5 do arquivo
    $videoSize,      // Tamanho em bytes
    $duracaoSegundos,// Duração em segundos
    $opcoes = []     // Opções adicionais
)
```

**Exemplo de uso:**
```php
$resultado = $vnnoxService->inserirExibicaoEmergencialComVideo(
    'player-123',
    'https://meusite.com/storage/videos/video123.mp4',
    'f5b0f315800cb4befb89b5dff42f1e34',
    1227710,
    45,
    ['name' => 'Vídeo do Cidadão João']
);
```

### 2. **Atualização do Job de Exibição**

**Arquivo:** `app/Jobs/ExibirVideoJob.php`

**Mudanças:**
- ✅ Remove tentativa de "upload"
- ✅ Gera URL pública do arquivo
- ✅ Usa MD5 e tamanho já calculados
- ✅ Chama novo método com estrutura correta

### 3. **Atualização do Job de Processamento**

**Arquivo:** `app/Jobs/ProcessarVideoJob.php`

**Mudanças:**
- ✅ Calcula e salva MD5 do arquivo processado
- ✅ Calcula e salva tamanho em bytes
- ✅ Dados ficam prontos para uso imediato

### 4. **Nova Migration**

**Arquivo:** `database/migrations/2026_03_06_000001_update_videos_table_for_vnnox_api.php`

**Mudanças:**
- ❌ Remove campo `vnnox_media_id` (não existe na API)
- ✅ Adiciona campo `md5_hash` (requerido pela API)
- ✅ Adiciona campo `tamanho_bytes` (requerido pela API)

### 5. **Atualização do Model**

**Arquivo:** `app/Models/Video.php`

**Mudanças:**
- ❌ Remove `vnnox_media_id` dos fillable
- ✅ Adiciona `md5_hash` nos fillable
- ✅ Adiciona `tamanho_bytes` nos fillable

---

## ⚙️ CONFIGURAÇÃO NECESSÁRIA

### 1. **Executar Nova Migration**

```bash
php artisan migrate
```

Isso irá:
- Remover coluna `vnnox_media_id`
- Adicionar coluna `md5_hash`
- Adicionar coluna `tamanho_bytes`

### 2. **Configurar Storage Público**

Os vídeos processados **DEVEM** estar acessíveis publicamente na internet.

#### Opção A: Storage Público do Laravel (Recomendado para desenvolvimento)

**1. Criar link simbólico:**
```bash
php artisan storage:link
```

**2. Verificar configuração em `config/filesystems.php`:**
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

**3. Garantir que `APP_URL` está correto no `.env`:**
```env
APP_URL=https://seu-dominio.com
```

**4. Mover vídeos processados para disco público:**

Atualizar `ProcessarVideoJob` para salvar em `public` disk:
```php
// TROCAR DE:
$caminhoProcessado = storage_path("app/videos/processados/{$nomeArquivo}_processed.mp4");

// PARA:
$caminhoProcessado = storage_path("app/public/videos/processados/{$nomeArquivo}_processed.mp4");
```

E atualizar caminho relativo no banco:
```php
'arquivo_processado' => "videos/processados/{$nomeArquivo}_processed.mp4"
```

#### Opção B: CDN/Cloud Storage (Recomendado para produção)

**AWS S3:**
```bash
composer require league/flysystem-aws-s3-v3 "^3.0"
```

**Configurar em `config/filesystems.php`:**
```php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'visibility' => 'public',
],
```

**Usar no código:**
```php
Storage::disk('s3')->put($caminho, $conteudo);
$urlPublica = Storage::disk('s3')->url($caminho);
```

### 3. **Testar Acessibilidade Pública**

**Verificar se a URL é acessível:**
```bash
curl -I https://seu-dominio.com/storage/videos/processados/video.mp4
```

Deve retornar `HTTP 200 OK`.

**Teste pela API VNNOX:**
A API VNNOX precisa conseguir fazer download da URL. Se estiver atrás de firewall ou em localhost, **NÃO FUNCIONARÁ**.

---

## 🧪 TESTES

### Teste 1: Upload e Processamento

```php
php artisan tinker

$video = \App\Models\Video::create([
    'user_id' => 1,
    'titulo' => 'Teste',
    'arquivo_original' => 'videos/originais/teste.mp4',
    'status' => 'processing'
]);

\App\Jobs\ProcessarVideoJob::dispatch($video);
```

Verificar no banco se foram salvos:
- `arquivo_processado`
- `md5_hash`
- `tamanho_bytes`
- `duracao_segundos`

### Teste 2: Geração de URL Pública

```php
php artisan tinker

$video = \App\Models\Video::find(1);
$url = \Illuminate\Support\Facades\Storage::url($video->arquivo_processado);
echo $url;
```

Copiar a URL e tentar acessar no navegador. Deve fazer download do vídeo.

### Teste 3: Exibição na API VNNOX

```php
php artisan tinker

$video = \App\Models\Video::where('status', 'approved')->first();
\App\Jobs\ExibirVideoJob::dispatch($video);
```

Verificar logs:
```bash
tail -f storage/logs/laravel.log
```

Deve mostrar:
```
Enviando vídeo para API VNNOX
url: https://...
md5: ...
size: ...
duration: ...
```

---

## 📋 CHECKLIST DE VERIFICAÇÃO

Antes de usar em produção, verificar:

- [ ] Nova migration executada (`php artisan migrate`)
- [ ] Storage público configurado (`php artisan storage:link`)
- [ ] `APP_URL` correto no `.env`
- [ ] Vídeos acessíveis publicamente (testar URL no navegador)
- [ ] Servidor com HTTPS (requerido para produção)
- [ ] Firewall permite acesso da API VNNOX aos arquivos
- [ ] Logs não mostram erros de "arquivo não encontrado" ou "URL inválida"

---

## ⚠️ PROBLEMAS COMUNS

### Erro: "URL não acessível"

**Causa:** Arquivo não está público ou servidor está em localhost.

**Solução:**
1. Verificar se `storage:link` foi executado
2. Testar URL diretamente no navegador
3. Se estiver em localhost, usar ngrok ou serviço similar para túnel público

### Erro: "MD5 verification failed"

**Causa:** MD5 calculado não corresponde ao arquivo.

**Solução:**
1. Recalcular MD5 do arquivo processado
2. Verificar se arquivo não foi corrompido durante processamento
3. Usar `md5_file()` em vez de `md5()` de string

### Erro: "File size mismatch"

**Causa:** Tamanho informado não corresponde ao arquivo real.

**Solução:**
1. Usar `filesize()` para obter tamanho real
2. Salvar tamanho em bytes (não KB ou MB)

### Erro: "Media download exception"

**Causa:** API VNNOX não conseguiu fazer download da URL.

**Solução:**
1. Verificar se URL está acessível publicamente
2. Verificar se servidor não está bloqueando IPs da VNNOX
3. Verificar se URL não tem autenticação/cookies requeridos
4. Testar download com `wget` ou `curl` da URL

---

## 📚 REFERÊNCIAS

- **Documentação API VNNOX:** [doc.md](doc.md)
- **Endpoint de Emergency Insertion:** Linha 772 do doc.md
- **Estrutura de Widgets:** Linha 850+ do doc.md
- **Exemplos de Vídeo:** Linha 3200+ do doc.md

---

## 🎯 PRÓXIMOS PASSOS

1. **Executar migration:** `php artisan migrate`
2. **Configurar storage público**
3. **Reprocessar vídeos existentes** para calcular MD5 e tamanho
4. **Testar fluxo completo** de upload → processamento → aprovação → exibição
5. **Monitorar logs** nas primeiras exibições
6. **Configurar CDN** para produção (AWS S3, Cloudflare, etc.)

---

**IMPORTANTE:** Sem essas correções, **NENHUM vídeo será exibido** nos painéis, pois o método antigo não funciona com a API VNNOX real!
