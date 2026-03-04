# 🖥️ Sistema de Painéis LED - Assaí

Sistema web-based para gerenciamento de painéis de LED da cidade de Assaí/PR, integrado com a API NovaStar VNNOX e sistema de autenticação gov.assaí.

## 📋 Sobre o Projeto

Este sistema permite que cidadãos de Assaí enviem vídeos e conteúdos para serem exibidos nos painéis de LED públicos da cidade, criando uma experiência interativa e participativa similar à Times Square.

### ✨ Principais Funcionalidades

- **Autenticação via gov.assaí**: Login seguro usando CPF e senha do sistema unificado da prefeitura
- **Upload de Vídeos**: Cidadãos podem enviar vídeos para exibição nos painéis
- **Processamento Automático**: Conversão de vídeos para formato H.264/MP4 compatível com painéis Taurus
- **Sistema de Moderação**: Aprovação/rejeição de conteúdos antes da exibição
- **Integração VNNOX**: Comunicação direta com a API NovaStar para controle dos painéis
- **Dashboard Administrativo**: Monitoramento em tempo real dos painéis e vídeos
- **Gestão de Painéis**: Cadastro, sincronização e controle de múltiplos painéis

## 🏗️ Arquitetura

```
┌─────────────────┐
│   Cidadão       │
│  (gov.assaí)    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────┐
│    Laravel Backend      │
│  - Autenticação         │
│  - Moderação            │
│  - Processamento        │
└────────┬────────────────┘
         │
         ├──────────────────────────┐
         │                          │
         ▼                          ▼
┌─────────────────┐      ┌──────────────────┐
│  API gov.assaí  │      │   API VNNOX      │
│  (Autenticação) │      │  (NovaStar)      │
└─────────────────┘      └────────┬─────────┘
                                  │
                                  ▼
                         ┌──────────────────┐
                         │ Painéis Taurus   │
                         │  (LED Displays)  │
                         └──────────────────┘
```

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Banco de Dados**: MySQL/PostgreSQL/SQLite
- **Processamento**: FFmpeg para transcodificação de vídeos
- **Filas**: Laravel Queues para processamento assíncrono
- **Integrações**:
  - API NovaStar VNNOX (gerenciamento de painéis)
  - API gov.assaí (autenticação de cidadãos)

## 📦 Pré-requisitos

- PHP 8.2 ou superior
- Composer 2.x
- Node.js 18+ e NPM (para assets)
- MySQL 8.0+ / PostgreSQL 13+ / SQLite 3
- FFmpeg 4.x ou superior (para processamento de vídeos)
- Servidor web (Apache/Nginx) ou PHP built-in server

## 🚀 Instalação

### 1. Instalar Dependências

```bash
# Dependências PHP
composer install

# Dependências Node (se houver assets)
npm install
```

### 2. Configurar Ambiente

```bash
# Copiar arquivo de exemplo
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 3. Configurar Banco de Dados

Edite o arquivo `.env` com suas credenciais de banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=painel_led
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Configurar Integrações

Adicione as credenciais das APIs no `.env`:

```env
# API VNNOX (NovaStar)
VNNOX_APP_KEY=sua_app_key_aqui
VNNOX_APP_SECRET=sua_app_secret_aqui
VNNOX_API_URL=https://api.vnnox.com

# API gov.assaí
GOV_ASSAI_API_URL=https://gov.assai.pr.gov.br

# FFmpeg (ajustar conforme instalação)
# Windows:
FFMPEG_BINARY_PATH=C:/ffmpeg/bin/ffmpeg.exe
FFPROBE_BINARY_PATH=C:/ffmpeg/bin/ffprobe.exe
# Linux:
# FFMPEG_BINARY_PATH=/usr/bin/ffmpeg
# FFPROBE_BINARY_PATH=/usr/bin/ffprobe
```

### 5. Executar Migrações

```bash
php artisan migrate
```

### 6. Criar Usuário Administrador

```bash
php artisan tinker
```

Dentro do Tinker:

```php
$user = new App\Models\User();
$user->name = 'Administrador';
$user->email = 'admin@assai.pr.gov.br';
$user->cpf = '00000000000';
$user->role = 'admin';
$user->nivel_acesso = 3;
$user->ativo = true;
$user->password = bcrypt('senha-segura-aqui');
$user->save();
```

### 7. Configurar Storage

```bash
php artisan storage:link
```

### 8. Configurar Filas (Opcional para Produção)

Edite `.env`:

```env
QUEUE_CONNECTION=database
```

Execute o worker:

```bash
php artisan queue:work
```

### 9. Iniciar Servidor

```bash
php artisan serve
```

Acesse: http://localhost:8000

## 📁 Estrutura do Projeto

```
painel-led/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Autenticação gov.assaí
│   │   │   ├── VideoController.php         # Gerenciamento de vídeos
│   │   │   ├── ModeracaoController.php     # Sistema de moderação
│   │   │   ├── PainelController.php        # Gestão de painéis
│   │   │   └── DashboardController.php     # Dashboards
│   │   └── Middleware/
│   │       ├── EnsureUserIsAdmin.php       # Middleware admin
│   │       └── EnsureUserIsModerador.php   # Middleware moderador
│   ├── Models/
│   │   ├── User.php                        # Usuário/Cidadão
│   │   ├── Video.php                       # Vídeo enviado
│   │   ├── Painel.php                      # Painel de LED
│   │   ├── ConfiguracaoPainel.php          # Configurações VNNOX
│   │   └── HistoricoExibicao.php           # Log de exibições
│   ├── Services/
│   │   ├── VNNOXService.php                # Integração NovaStar
│   │   ├── GovAssaiService.php             # Integração gov.assaí
│   │   └── VideoProcessingService.php      # Processamento FFmpeg
│   └── Jobs/
│       ├── ProcessarVideoJob.php           # Job de processamento
│       ├── ExibirVideoJob.php              # Job de exibição
│       └── SincronizarPaineisJob.php       # Job de sincronização
├── database/
│   └── migrations/
│       └── 2024_03_04_000001_create_paineis_tables.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php               # Layout base
│       ├── auth/
│       │   └── login.blade.php             # Tela de login
│       ├── cidadao/
│       │   ├── dashboard.blade.php         # Dashboard cidadão
│       │   └── videos/
│       │       └── create.blade.php        # Upload de vídeo
│       └── admin/
│           ├── dashboard.blade.php         # Dashboard admin
│           └── moderacao/
│               └── index.blade.php         # Fila de moderação
├── routes/
│   └── web.php                             # Definição de rotas
└── config/
    └── paineis.php                         # Configurações do sistema
```

## 🔐 Níveis de Acesso

### Cidadão (`role: cidadao`)
- Ver próprios vídeos
- Enviar novos vídeos
- Acompanhar status de moderação

### Moderador (`role: moderador`)
- Todas as permissões de cidadão
- Aprovar/rejeitar vídeos
- Ver histórico de moderações

### Administrador (`role: admin`)
- Todas as permissões de moderador
- Gerenciar painéis
- Configurar sistema
- Sincronizar com API VNNOX
- Controlar brilho e status dos painéis

## 🎯 Fluxo de Funcionamento

### 1. Envio de Vídeo pelo Cidadão

```
1. Cidadão faz login com gov.assaí
2. Acessa "Enviar Vídeo"
3. Preenche título, descrição e seleciona arquivo
4. Sistema armazena arquivo original
5. Job ProcessarVideoJob é disparado
```

### 2. Processamento Automático

```
1. Validação do vídeo (formato, duração, tamanho)
2. Transcodificação para H.264/MP4
3. Redimensionamento para resolução do painel
4. Geração de thumbnail
5. Status muda para "pending" (aguardando moderação)
```

### 3. Moderação

```
1. Moderador acessa fila de moderação
2. Visualiza vídeo e informações
3. Aprova ou rejeita com motivo
4. Se aprovado, pode exibir imediatamente ou agendar
```

### 4. Exibição no Painel

```
1. Job ExibirVideoJob é disparado
2. Upload do arquivo para VNNOX Cloud
3. Chamada à API para "Emergency Insertion"
4. Vídeo é exibido no painel Taurus
5. Histórico de exibição é registrado
```

## 🔧 API NovaStar VNNOX

### Endpoints Utilizados

| Endpoint | Método | Descrição |
|----------|--------|-----------|
| `/v2/player/list` | GET | Lista painéis disponíveis |
| `/v2/player/status` | GET | Verifica status de um painel |
| `/v2/media/upload` | POST | Upload de mídia |
| `/v2/player/emergency-program/page` | POST | Exibição emergencial |
| `/v2/player/brightness` | POST | Ajusta brilho |
| `/v2/player/screenshot` | GET | Captura tela |

### Autenticação

O sistema gera automaticamente os headers de autenticação:

```php
AppKey: sua_app_key
Nonce: número_aleatório_único
CurTime: timestamp_utc
CheckSum: SHA256(AppSecret + Nonce + CurTime)
```

## 🌐 API gov.assaí

### Endpoints Utilizados

| Endpoint | Método | Descrição |
|----------|--------|-----------|
| `/api/cidadao/authenticate` | POST | Autenticar cidadão |
| `/api/cidadao/check-cpf` | POST | Verificar CPF |
| `/api/cidadao/niveis-info` | GET | Info níveis de acesso |

## 📊 Comandos Úteis

### Processar Filas

```bash
php artisan queue:work
```

### Sincronizar Painéis Manualmente

```bash
php artisan tinker
>>> App\Jobs\SincronizarPaineisJob::dispatch();
```

### Limpar Cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 🐛 Troubleshooting

### FFmpeg não encontrado

**Windows:**
```bash
# Baixar FFmpeg de https://ffmpeg.org/download.html
# Adicionar ao PATH ou configurar no .env:
FFMPEG_BINARY_PATH=C:/ffmpeg/bin/ffmpeg.exe
FFPROBE_BINARY_PATH=C:/ffmpeg/bin/ffprobe.exe
```

**Linux:**
```bash
sudo apt-get install ffmpeg
```

### Erro de permissão no Storage

```bash
chmod -R 775 storage bootstrap/cache
```

### Jobs não processam

```bash
# Verificar configuração da fila
php artisan queue:failed        # Ver jobs falhados
php artisan queue:retry all     # Tentar novamente
```

### Erro na API VNNOX

1. Verificar credenciais no `.env`
2. Verificar conectividade com `https://api.vnnox.com`
3. Checar logs: `storage/logs/laravel.log`

## 📝 Configuração Inicial

### 1. Cadastrar Configuração VNNOX

Acessar: `/admin/configuracoes`

Preencher:
- AppKey da NovaStar
- AppSecret da NovaStar
- URL da API (padrão: https://api.vnnox.com)

### 2. Sincronizar Painéis

Acessar: `/admin/paineis`

Clicar em "Sincronizar com VNNOX"

### 3. Criar Usuários

Os usuários são criados automaticamente no primeiro login via gov.assaí.

Para promover um usuário a moderador/admin:

```bash
php artisan tinker
>>> $user = App\Models\User::where('cpf', '00000000000')->first();
>>> $user->role = 'moderador'; // ou 'admin'
>>> $user->save();
```

## 🚀 Deploy em Produção

### Checklist

- [ ] Configurar `.env` com credenciais reais
- [ ] Usar banco de dados robusto (MySQL/PostgreSQL)
- [ ] Configurar fila com Redis ou Supervisor
- [ ] Ativar HTTPS (obrigatório para APIs)
- [ ] Configurar backups automáticos
- [ ] Configurar logs de aplicação
- [ ] Testar conectividade com APIs externas
- [ ] Configurar cron para jobs agendados:

```bash
* * * * * cd /caminho/para/projeto && php artisan schedule:run >> /dev/null 2>&1
```

## 📄 Licença

Este projeto foi desenvolvido para a Prefeitura Municipal de Assaí/PR.

## 👥 Suporte

Para dúvidas ou problemas:
- **E-mail**: suporte@assai.pr.gov.br
- **Documentação VNNOX**: https://docs.novastar.tech
- **Documentação gov.assaí**: Consulte equipe de TI da prefeitura

---

**Desenvolvido para transformar Assaí na Times Square do Norte Pioneiro! 🌟**
