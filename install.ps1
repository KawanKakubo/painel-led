# Script de Instalação - Sistema de Painéis LED
# Execute no PowerShell como Administrador

Write-Host "==================================================" -ForegroundColor Cyan
Write-Host "  Sistema de Painéis LED - Assaí/PR" -ForegroundColor Cyan
Write-Host "  Instalação Automática" -ForegroundColor Cyan
Write-Host "==================================================" -ForegroundColor Cyan
Write-Host ""

# Verificar se está no diretório correto
if (-not (Test-Path "artisan")) {
    Write-Host "ERRO: Execute este script na raiz do projeto Laravel!" -ForegroundColor Red
    exit 1
}

Write-Host "[1/9] Verificando dependências..." -ForegroundColor Yellow

# Verificar PHP
$phpVersion = php -v 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO: PHP não encontrado! Instale PHP 8.2 ou superior." -ForegroundColor Red
    exit 1
}
Write-Host "✓ PHP encontrado" -ForegroundColor Green

# Verificar Composer
$composerVersion = composer -v 2>$null
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO: Composer não encontrado! Instale o Composer." -ForegroundColor Red
    exit 1
}
Write-Host "✓ Composer encontrado" -ForegroundColor Green

Write-Host ""
Write-Host "[2/9] Instalando dependências do Composer..." -ForegroundColor Yellow
composer install --no-interaction --prefer-dist
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO ao instalar dependências!" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Dependências instaladas" -ForegroundColor Green

Write-Host ""
Write-Host "[3/9] Configurando arquivo .env..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "✓ Arquivo .env criado" -ForegroundColor Green
} else {
    Write-Host "! Arquivo .env já existe, pulando..." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "[4/9] Gerando chave da aplicação..." -ForegroundColor Yellow
php artisan key:generate --force
Write-Host "✓ Chave gerada" -ForegroundColor Green

Write-Host ""
Write-Host "[5/9] Criando diretórios necessários..." -ForegroundColor Yellow
$directories = @(
    "storage/app/videos",
    "storage/app/videos/originais",
    "storage/app/videos/processados",
    "storage/app/videos/thumbnails",
    "storage/logs"
)

foreach ($dir in $directories) {
    if (-not (Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "  Criado: $dir" -ForegroundColor Gray
    }
}
Write-Host "✓ Diretórios criados" -ForegroundColor Green

Write-Host ""
Write-Host "[6/9] Criando link simbólico do storage..." -ForegroundColor Yellow
php artisan storage:link
Write-Host "✓ Link criado" -ForegroundColor Green

Write-Host ""
Write-Host "[7/9] Configurando banco de dados..." -ForegroundColor Yellow
Write-Host "Escolha o banco de dados:" -ForegroundColor Cyan
Write-Host "1) SQLite (Recomendado para testes)" -ForegroundColor White
Write-Host "2) MySQL" -ForegroundColor White
Write-Host "3) PostgreSQL" -ForegroundColor White

$dbChoice = Read-Host "Opção (1-3)"

switch ($dbChoice) {
    "1" {
        # SQLite
        $dbFile = "database/database.sqlite"
        if (-not (Test-Path $dbFile)) {
            New-Item -ItemType File -Path $dbFile -Force | Out-Null
        }
        
        # Atualizar .env
        (Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' | Set-Content .env
        (Get-Content .env) -replace 'DB_DATABASE=.*', "DB_DATABASE=$((Get-Location).Path)\database\database.sqlite" | Set-Content .env
        
        Write-Host "✓ SQLite configurado" -ForegroundColor Green
    }
    "2" {
        # MySQL
        $dbHost = Read-Host "Host do MySQL (padrão: 127.0.0.1)"
        if ([string]::IsNullOrEmpty($dbHost)) { $dbHost = "127.0.0.1" }
        
        $dbPort = Read-Host "Porta (padrão: 3306)"
        if ([string]::IsNullOrEmpty($dbPort)) { $dbPort = "3306" }
        
        $dbName = Read-Host "Nome do banco"
        $dbUser = Read-Host "Usuário"
        $dbPass = Read-Host "Senha" -AsSecureString
        $dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
            [Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))
        
        (Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=mysql' | Set-Content .env
        (Get-Content .env) -replace 'DB_HOST=.*', "DB_HOST=$dbHost" | Set-Content .env
        (Get-Content .env) -replace 'DB_PORT=.*', "DB_PORT=$dbPort" | Set-Content .env
        (Get-Content .env) -replace 'DB_DATABASE=.*', "DB_DATABASE=$dbName" | Set-Content .env
        (Get-Content .env) -replace 'DB_USERNAME=.*', "DB_USERNAME=$dbUser" | Set-Content .env
        (Get-Content .env) -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$dbPassPlain" | Set-Content .env
        
        Write-Host "✓ MySQL configurado" -ForegroundColor Green
    }
    "3" {
        # PostgreSQL
        $dbHost = Read-Host "Host do PostgreSQL (padrão: 127.0.0.1)"
        if ([string]::IsNullOrEmpty($dbHost)) { $dbHost = "127.0.0.1" }
        
        $dbPort = Read-Host "Porta (padrão: 5432)"
        if ([string]::IsNullOrEmpty($dbPort)) { $dbPort = "5432" }
        
        $dbName = Read-Host "Nome do banco"
        $dbUser = Read-Host "Usuário"
        $dbPass = Read-Host "Senha" -AsSecureString
        $dbPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
            [Runtime.InteropServices.Marshal]::SecureStringToBSTR($dbPass))
        
        (Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=pgsql' | Set-Content .env
        (Get-Content .env) -replace 'DB_HOST=.*', "DB_HOST=$dbHost" | Set-Content .env
        (Get-Content .env) -replace 'DB_PORT=.*', "DB_PORT=$dbPort" | Set-Content .env
        (Get-Content .env) -replace 'DB_DATABASE=.*', "DB_DATABASE=$dbName" | Set-Content .env
        (Get-Content .env) -replace 'DB_USERNAME=.*', "DB_USERNAME=$dbUser" | Set-Content .env
        (Get-Content .env) -replace 'DB_PASSWORD=.*', "DB_PASSWORD=$dbPassPlain" | Set-Content .env
        
        Write-Host "✓ PostgreSQL configurado" -ForegroundColor Green
    }
    default {
        Write-Host "Opção inválida! Usando SQLite..." -ForegroundColor Yellow
        New-Item -ItemType File -Path "database/database.sqlite" -Force | Out-Null
        (Get-Content .env) -replace 'DB_CONNECTION=.*', 'DB_CONNECTION=sqlite' | Set-Content .env
    }
}

Write-Host ""
Write-Host "[8/9] Executando migrações..." -ForegroundColor Yellow
php artisan migrate --force
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERRO ao executar migrações!" -ForegroundColor Red
    exit 1
}
Write-Host "✓ Migrações executadas" -ForegroundColor Green

Write-Host ""
Write-Host "[9/9] Criando usuário administrador..." -ForegroundColor Yellow
Write-Host "Insira os dados do administrador:" -ForegroundColor Cyan

$adminName = Read-Host "Nome completo"
$adminEmail = Read-Host "E-mail"
$adminCPF = Read-Host "CPF (sem pontos/traços)"
$adminPass = Read-Host "Senha" -AsSecureString
$adminPassPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto(
    [Runtime.InteropServices.Marshal]::SecureStringToBSTR($adminPass))

# Criar arquivo temporário com comandos Tinker
$tinkerScript = @"
`$user = new App\Models\User();
`$user->name = '$adminName';
`$user->email = '$adminEmail';
`$user->cpf = '$adminCPF';
`$user->role = 'admin';
`$user->nivel_acesso = 3;
`$user->ativo = true;
`$user->password = bcrypt('$adminPassPlain');
`$user->save();
echo "Usuário criado com sucesso!\n";
"@

$tinkerScript | php artisan tinker
Write-Host "✓ Administrador criado" -ForegroundColor Green

Write-Host ""
Write-Host "==================================================" -ForegroundColor Green
Write-Host "  ✓ Instalação Concluída!" -ForegroundColor Green
Write-Host "==================================================" -ForegroundColor Green
Write-Host ""
Write-Host "Próximos passos:" -ForegroundColor Yellow
Write-Host ""
Write-Host "1. Configure as credenciais VNNOX no arquivo .env:" -ForegroundColor White
Write-Host "   - VNNOX_APP_KEY=sua_chave" -ForegroundColor Gray
Write-Host "   - VNNOX_APP_SECRET=seu_secret" -ForegroundColor Gray
Write-Host ""
Write-Host "2. Configure a URL do gov.assaí no .env:" -ForegroundColor White
Write-Host "   - GOV_ASSAI_API_URL=https://gov.assai.pr.gov.br" -ForegroundColor Gray
Write-Host ""
Write-Host "3. Instale o FFmpeg (se ainda não instalou):" -ForegroundColor White
Write-Host "   - Baixe em: https://ffmpeg.org/download.html" -ForegroundColor Gray
Write-Host "   - Configure o caminho no .env" -ForegroundColor Gray
Write-Host ""
Write-Host "4. Inicie o servidor:" -ForegroundColor White
Write-Host "   php artisan serve" -ForegroundColor Cyan
Write-Host ""
Write-Host "5. Inicie o worker de filas (em outro terminal):" -ForegroundColor White
Write-Host "   php artisan queue:work" -ForegroundColor Cyan
Write-Host ""
Write-Host "6. Acesse: http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "Login Admin:" -ForegroundColor Yellow
Write-Host "  CPF: $adminCPF" -ForegroundColor White
Write-Host "  Senha: (a que você definiu)" -ForegroundColor White
Write-Host ""
Write-Host "==================================================" -ForegroundColor Green
Write-Host "Documentação completa: README.md e GUIA_DE_USO.md" -ForegroundColor Cyan
Write-Host "==================================================" -ForegroundColor Green
