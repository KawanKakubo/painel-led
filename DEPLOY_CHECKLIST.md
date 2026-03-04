# ✅ Checklist de Deploy - Sistema de Painéis LED

## 📋 Pré-Deploy

### Ambiente de Produção

- [ ] Servidor Linux (Ubuntu 22.04 LTS ou superior recomendado)
- [ ] PHP 8.2 ou superior instalado
- [ ] Composer 2.x instalado
- [ ] Nginx ou Apache configurado
- [ ] MySQL 8.0+ ou PostgreSQL 13+ instalado
- [ ] FFmpeg 4.x ou superior instalado
- [ ] Redis instalado (para filas e cache)
- [ ] Supervisor instalado (para workers)
- [ ] Certificado SSL válido (obrigatório para APIs)

### Requisitos de Hardware

**Mínimo:**
- 2 vCPUs
- 4GB RAM
- 50GB SSD

**Recomendado:**
- 4 vCPUs
- 8GB RAM
- 100GB SSD
- Backup automático configurado

---

## 🔧 Configuração do Servidor

### 1. Instalar Dependências

```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar PHP e extensões
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql \
  php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl \
  php8.2-zip php8.2-gd php8.2-bcmath php8.2-redis

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Instalar FFmpeg
sudo apt install -y ffmpeg

# Instalar Redis
sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Instalar Supervisor
sudo apt install -y supervisor
sudo systemctl enable supervisor
sudo systemctl start supervisor

# Instalar Nginx
sudo apt install -y nginx
sudo systemctl enable nginx
```

### 2. Configurar Nginx

Criar arquivo: `/etc/nginx/sites-available/painel-led`

```nginx
server {
    listen 80;
    server_name paineis.assai.pr.gov.br;
    
    # Redirecionar para HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name paineis.assai.pr.gov.br;
    root /var/www/painel-led/public;

    index index.php;
    charset utf-8;

    # Certificado SSL
    ssl_certificate /etc/ssl/certs/paineis.assai.pr.gov.br.crt;
    ssl_certificate_key /etc/ssl/private/paineis.assai.pr.gov.br.key;

    # Configurações SSL
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Logs
    access_log /var/log/nginx/painel-led-access.log;
    error_log /var/log/nginx/painel-led-error.log;

    # Upload de arquivos grandes (vídeos)
    client_max_body_size 512M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Ativar configuração:
```bash
sudo ln -s /etc/nginx/sites-available/painel-led /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 3. Configurar PHP-FPM

Editar: `/etc/php/8.2/fpm/php.ini`

```ini
upload_max_filesize = 512M
post_max_size = 512M
max_execution_time = 600
memory_limit = 512M
```

Reiniciar:
```bash
sudo systemctl restart php8.2-fpm
```

---

## 📦 Deploy da Aplicação

### 1. Clonar Repositório

```bash
cd /var/www
sudo git clone [URL_DO_REPOSITORIO] painel-led
cd painel-led
```

### 2. Instalar Dependências

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configurar Permissões

```bash
sudo chown -R www-data:www-data /var/www/painel-led
sudo chmod -R 755 /var/www/painel-led
sudo chmod -R 775 /var/www/painel-led/storage
sudo chmod -R 775 /var/www/painel-led/bootstrap/cache
```

### 4. Configurar .env

```bash
cp .env.example .env
nano .env
```

Configurações importantes:

```env
APP_NAME="Painéis LED Assaí"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://paineis.assai.pr.gov.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=painel_led
DB_USERNAME=painel_user
DB_PASSWORD=senha_segura_aqui

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

VNNOX_APP_KEY=sua_app_key_real
VNNOX_APP_SECRET=sua_app_secret_real
VNNOX_API_URL=https://api.vnnox.com

GOV_ASSAI_API_URL=https://gov.assai.pr.gov.br

FFMPEG_BINARY_PATH=/usr/bin/ffmpeg
FFPROBE_BINARY_PATH=/usr/bin/ffprobe
```

### 5. Executar Migrações

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Criar Usuário Admin

```bash
php artisan tinker
```

```php
$user = new App\Models\User();
$user->name = 'Administrador Sistema';
$user->email = 'admin@assai.pr.gov.br';
$user->cpf = '00000000000';
$user->role = 'admin';
$user->nivel_acesso = 3;
$user->ativo = true;
$user->password = bcrypt('senha_super_segura');
$user->save();
```

---

## 🔄 Configurar Workers (Supervisor)

Criar arquivo: `/etc/supervisor/conf.d/painel-led-worker.conf`

```ini
[program:painel-led-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/painel-led/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/painel-led/storage/logs/worker.log
stopwaitsecs=3600
```

Ativar:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start painel-led-worker:*
```

Verificar status:
```bash
sudo supervisorctl status
```

---

## ⏰ Configurar Cron

```bash
sudo crontab -e -u www-data
```

Adicionar:
```
* * * * * cd /var/www/painel-led && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔒 Segurança

### 1. Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 2. Fail2Ban (Proteção contra brute force)

```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 3. Backup Automático

Criar script: `/usr/local/bin/backup-painel-led.sh`

```bash
#!/bin/bash
BACKUP_DIR="/backups/painel-led"
DATE=$(date +%Y%m%d_%H%M%S)

# Criar diretório de backup
mkdir -p $BACKUP_DIR

# Backup do banco de dados
mysqldump -u painel_user -p'senha' painel_led > $BACKUP_DIR/db_$DATE.sql

# Backup dos arquivos
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/painel-led/storage/app/videos

# Manter apenas últimos 7 dias
find $BACKUP_DIR -mtime +7 -delete

echo "Backup concluído: $DATE"
```

Tornar executável:
```bash
sudo chmod +x /usr/local/bin/backup-painel-led.sh
```

Agendar (crontab):
```
0 2 * * * /usr/local/bin/backup-painel-led.sh >> /var/log/backup-painel-led.log 2>&1
```

---

## 🧪 Testes Pós-Deploy

### Funcionalidades Críticas

- [ ] Login com gov.assaí funciona
- [ ] Upload de vídeo processa corretamente
- [ ] Moderação de vídeos funciona
- [ ] Sincronização com VNNOX funciona
- [ ] Exibição no painel funciona
- [ ] Workers estão rodando (verificar com `supervisorctl status`)
- [ ] Logs não mostram erros críticos
- [ ] SSL está ativo e válido
- [ ] Uploads grandes (até 500MB) funcionam

### Monitoramento

```bash
# Ver logs em tempo real
tail -f /var/www/painel-led/storage/logs/laravel.log

# Ver logs do worker
tail -f /var/www/painel-led/storage/logs/worker.log

# Ver logs do Nginx
tail -f /var/log/nginx/painel-led-error.log

# Verificar status dos workers
sudo supervisorctl status

# Ver filas
php artisan queue:work --once
php artisan queue:failed
```

---

## 📊 Monitoramento de Produção

### Ferramentas Recomendadas

1. **Laravel Horizon** (para filas)
   ```bash
   composer require laravel/horizon
   php artisan horizon:install
   php artisan horizon:publish
   ```

2. **Laravel Telescope** (debugging em produção - com cuidado)
   ```bash
   composer require laravel/telescope --dev
   ```

3. **Sentry** (rastreamento de erros)
   - Criar conta em sentry.io
   - Instalar SDK: `composer require sentry/sentry-laravel`

### Métricas para Monitorar

- [ ] Uso de CPU e RAM
- [ ] Espaço em disco (vídeos)
- [ ] Taxa de sucesso dos jobs
- [ ] Tempo de resposta da aplicação
- [ ] Erros 5xx no Nginx
- [ ] Status dos painéis (online/offline)
- [ ] Fila de vídeos pendentes

---

## 🔄 Processo de Atualização

### Deploy de Novas Versões

```bash
# Entrar no diretório
cd /var/www/painel-led

# Ativar modo de manutenção
php artisan down

# Atualizar código
git pull origin main

# Instalar dependências
composer install --no-dev --optimize-autoloader

# Executar migrações
php artisan migrate --force

# Limpar e recriar caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar workers
sudo supervisorctl restart painel-led-worker:*

# Desativar modo de manutenção
php artisan up
```

---

## 📞 Contatos de Emergência

**Equipe de TI Prefeitura:**
- E-mail: ti@assai.pr.gov.br
- Telefone: (43) XXXX-XXXX

**Suporte NovaStar:**
- Documentação: https://docs.novastar.tech
- Suporte: support@novastar.tech

**gov.assaí:**
- Suporte técnico interno

---

## ✅ Checklist Final

- [ ] Aplicação acessível via HTTPS
- [ ] Certificado SSL válido
- [ ] Login gov.assaí funcional
- [ ] Upload e processamento de vídeos OK
- [ ] Integração VNNOX testada
- [ ] Workers rodando (Supervisor)
- [ ] Cron configurado
- [ ] Backups automáticos ativos
- [ ] Firewall configurado
- [ ] Logs sendo gerados corretamente
- [ ] Monitoramento configurado
- [ ] Documentação entregue à equipe
- [ ] Credenciais/acessos documentados
- [ ] Procedimento de rollback definido
- [ ] Contatos de emergência registrados

---

**Data do Deploy:** _____________  
**Responsável:** _____________  
**Status:** [ ] Concluído [ ] Pendente [ ] Com problemas
