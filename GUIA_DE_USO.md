# 📘 Guia Rápido de Uso - Sistema de Painéis LED

## 🎯 Para Cidadãos

### Como Enviar um Vídeo

1. **Acesse o sistema**: http://localhost:8000 (ou URL fornecida)
2. **Faça login** com suas credenciais do **gov.assaí**:
   - CPF: seu CPF (pode usar máscara ou não)
   - Senha: sua senha do gov.assaí
3. Clique em **"Enviar Vídeo"** no menu
4. Preencha o formulário:
   - Título: dê um nome ao seu vídeo
   - Descrição: explique o que contém
   - Painel: escolha onde quer exibir (opcional)
   - Arquivo: selecione seu vídeo (MP4, MOV, AVI, MKV)
5. Aceite os termos e clique em **"Enviar Vídeo"**

### Acompanhando Seu Vídeo

#### Status Possíveis:

- 🔵 **Processando**: Vídeo está sendo convertido
- 🟡 **Pendente**: Aguardando aprovação do moderador
- 🟢 **Aprovado**: Vídeo aprovado, aguardando exibição
- 🟣 **Exibido**: Vídeo foi exibido no painel
- 🔴 **Rejeitado**: Vídeo não aprovado (você verá o motivo)

#### Ver Status:

1. Acesse **"Meus Vídeos"** no menu
2. Veja a lista de todos os seus vídeos
3. Clique em **"Ver Detalhes"** para mais informações

### Requisitos para Vídeos

✅ **Formato**: MP4, MOV, AVI ou MKV  
✅ **Duração**: Máximo 2 minutos  
✅ **Tamanho**: Máximo 500MB  
✅ **Conteúdo**: Sem material ofensivo, político ou comercial  
✅ **Direitos**: Você deve ter direitos sobre o conteúdo

---

## 🛡️ Para Moderadores

### Como Moderar Vídeos

1. **Acesse** o menu **"Moderação"**
2. Você verá a **fila de vídeos pendentes**
3. Para cada vídeo:
   - Assista ao conteúdo
   - Verifique título e descrição
   - Veja informações do cidadão

### Decidindo sobre um Vídeo

#### ✅ Aprovar:

1. Clique em **"Aprovar"** ou **"Aprovar e Exibir"**
2. Confirme a ação
3. O vídeo será marcado como aprovado
4. Se escolheu "Aprovar e Exibir", ele vai direto para o painel

#### ❌ Rejeitar:

1. Clique em **"Rejeitar"**
2. Digite o **motivo da rejeição** (seja claro e educado)
3. Confirme
4. O cidadão verá o motivo e poderá fazer correções

### Critérios de Aprovação

✅ Aprovar se:
- Conteúdo é apropriado para espaço público
- Não viola direitos autorais
- Qualidade técnica adequada
- Segue as diretrizes da prefeitura

❌ Rejeitar se:
- Conteúdo ofensivo, violento ou impróprio
- Material político-partidário
- Publicidade comercial não autorizada
- Viola direitos autorais
- Qualidade muito baixa

---

## 👨‍💼 Para Administradores

### Configuração Inicial

#### 1. Configurar Credenciais VNNOX

1. Acesse **Admin > Configurações**
2. Preencha:
   - **AppKey**: fornecida pela NovaStar
   - **AppSecret**: fornecer pela NovaStar
   - **URL da API**: geralmente `https://api.vnnox.com`
3. Clique em **"Salvar Configurações"**

#### 2. Sincronizar Painéis

1. Acesse **Admin > Painéis**
2. Clique em **"Sincronizar com VNNOX"**
3. O sistema buscará todos os painéis Taurus disponíveis
4. Os painéis serão cadastrados automaticamente

#### 3. Cadastrar Painel Manualmente (Opcional)

1. Acesse **Admin > Painéis**
2. Clique em **"Novo Painel"**
3. Preencha:
   - **Player ID**: ID do player Taurus (obter no VNNOX)
   - **Nome**: nome descritivo (ex: "Painel Avenida Principal")
   - **Localização**: endereço ou referência
   - **Resolução**: largura e altura em pixels
4. Salve

### Gerenciamento de Painéis

#### Verificar Status:

1. No Dashboard Admin, veja painéis online/offline
2. Ou acesse **Admin > Painéis** para lista completa
3. Clique em um painel para ver detalhes

#### Controlar Brilho:

1. Acesse o painel desejado
2. Use o controle de brilho (0-100%)
3. Ajuste conforme horário e visibilidade

#### Capturar Screenshot:

1. Na página do painel
2. Clique em **"Capturar Tela"**
3. Veja o que está sendo exibido no momento

### Promover Usuários

Para tornar um cidadão em **moderador** ou **admin**:

```bash
php artisan tinker
```

```php
# Buscar usuário por CPF
$user = App\Models\User::where('cpf', '12345678900')->first();

# Promover para moderador
$user->role = 'moderador';
$user->save();

# Ou promover para admin
$user->role = 'admin';
$user->save();
```

### Monitoramento

#### Dashboard Administrativo:

- Total de vídeos no sistema
- Vídeos pendentes de moderação
- Vídeos aprovados/exibidos hoje
- Cidadãos cadastrados
- Status dos painéis
- Vídeos mais populares
- Cidadãos mais ativos

#### Logs:

- Logs do Laravel: `storage/logs/laravel.log`
- Ver erros de API, jobs e processamento

---

## 🔧 Manutenção

### Processar Filas de Jobs

Os jobs processam vídeos e enviam para painéis.

**Modo Development:**
```bash
php artisan queue:work
```

**Produção (com Supervisor):**

Criar arquivo `/etc/supervisor/conf.d/painel-led-worker.conf`:

```ini
[program:painel-led-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /caminho/projeto/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/caminho/projeto/storage/logs/worker.log
```

Depois:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start painel-led-worker:*
```

### Limpar Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Backup do Banco de Dados

**MySQL:**
```bash
mysqldump -u usuario -p painel_led > backup_$(date +%Y%m%d).sql
```

**PostgreSQL:**
```bash
pg_dump painel_led > backup_$(date +%Y%m%d).sql
```

### Ver Jobs Falhados

```bash
php artisan queue:failed
```

Tentar novamente:
```bash
php artisan queue:retry all
```

---

## 🆘 Problemas Comuns

### 1. Vídeo não processa

**Possíveis causas:**
- FFmpeg não instalado
- Worker não está rodando
- Arquivo corrompido

**Solução:**
```bash
# Verificar FFmpeg
ffmpeg -version

# Iniciar worker
php artisan queue:work

# Ver logs
tail -f storage/logs/laravel.log
```

### 2. Não consigo fazer login

**Causa:** API gov.assaí offline ou credenciais erradas

**Solução:**
- Verificar se gov.assaí está acessível
- Testar login no site oficial do gov.assaí
- Verificar URL da API no `.env`

### 3. Painel não exibe vídeo

**Possíveis causas:**
- Painel offline
- Credenciais VNNOX erradas
- Sem conectividade com API

**Solução:**
1. Verificar status do painel no dashboard
2. Testar credenciais VNNOX em Configurações
3. Ver logs em `storage/logs/laravel.log`
4. Sincronizar painéis novamente

### 4. Vídeos enviados não aparecem

**Causa:** Job de processamento não rodou

**Solução:**
```bash
# Ver jobs pendentes
php artisan queue:work --once

# Ver jobs falhados
php artisan queue:failed

# Reprocessar job específico
php artisan queue:retry [id]
```

---

## 📞 Suporte

- **Documentação completa**: Ver README.md
- **Logs do sistema**: `storage/logs/laravel.log`
- **Documentação VNNOX**: https://docs.novastar.tech
- **Suporte Prefeitura**: suporte@assai.pr.gov.br

---

**Última atualização**: Março 2024  
**Versão do sistema**: 1.0.0
