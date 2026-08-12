# Install XAMPP (if missing) and connect BrokersCourt local database.
# Run in PowerShell as Administrator:
#   Set-ExecutionPolicy -Scope Process Bypass -Force
#   cd F:\vscode
#   .\scripts\install-xampp-and-connect.ps1

$ErrorActionPreference = "Stop"

$XamppRoot = "C:\xampp"
$MysqlExe = Join-Path $XamppRoot "mysql\bin\mysql.exe"
$InstallerUrl = "https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe/download"
$InstallerPath = Join-Path $env:TEMP "xampp-installer.exe"
$ProjectRoot = Split-Path $PSScriptRoot -Parent
$DbName = "brokerscourt_data"

function Test-XamppReady {
    return (Test-Path $MysqlExe)
}

function Install-Xampp {
    if (Test-XamppReady) {
        Write-Host "==> XAMPP already installed at $XamppRoot"
        return
    }

    if (Test-Path $XamppRoot) {
        $items = Get-ChildItem $XamppRoot -Force -ErrorAction SilentlyContinue
        if ($items.Count -gt 0) {
            throw "C:\xampp is not empty. Close the XAMPP installer, rename/delete C:\xampp, then run this script again."
        }
    } else {
        New-Item -ItemType Directory -Path $XamppRoot -Force | Out-Null
        Remove-Item $XamppRoot -Force
    }

    Write-Host "==> Downloading XAMPP 8.2.12 (this may take a few minutes)..."
    Invoke-WebRequest -Uri $InstallerUrl -OutFile $InstallerPath -UseBasicParsing

    Write-Host "==> Installing XAMPP to $XamppRoot ..."
    $args = @(
        "--mode", "unattended",
        "--unattendedmodeui", "minimal",
        "--prefix", $XamppRoot
    )
    $proc = Start-Process -FilePath $InstallerPath -ArgumentList $args -Wait -PassThru
    if ($proc.ExitCode -ne 0) {
        throw "XAMPP installer exited with code $($proc.ExitCode). Run the installer manually and choose an empty folder."
    }

    if (-not (Test-XamppReady)) {
        throw "Install finished but mysql.exe was not found. Complete setup from the XAMPP installer UI."
    }
}

function Start-XamppMysql {
    $mysqlStart = Join-Path $XamppRoot "mysql_start.bat"
    if (Test-Path $mysqlStart) {
        Write-Host "==> Starting MySQL..."
        Start-Process -FilePath "cmd.exe" -ArgumentList "/c", "`"$mysqlStart`"" -WindowStyle Hidden -Wait
        Start-Sleep -Seconds 4
    }

    $tries = 0
    while ($tries -lt 10) {
        try {
            & $MysqlExe -h 127.0.0.1 -u root -e "SELECT 1" | Out-Null
            Write-Host "==> MySQL is running."
            return
        } catch {
            $tries++
            Start-Sleep -Seconds 2
        }
    }

    throw "MySQL did not start. Open XAMPP Control Panel and click Start next to MySQL, then run this script again."
}

function Initialize-Database {
  param(
    [string]$DbUser = "root",
    [string]$DbPassword = ""
  )

  Write-Host "==> Creating database '$DbName'..."
  if ($DbPassword) {
    & $MysqlExe -h 127.0.0.1 -u $DbUser -p$DbPassword -e "CREATE DATABASE IF NOT EXISTS ``$DbName`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  } else {
    & $MysqlExe -h 127.0.0.1 -u $DbUser -e "CREATE DATABASE IF NOT EXISTS ``$DbName`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  }
}

function Update-EnvFile {
    $envPath = Join-Path $ProjectRoot ".env"
    if (-not (Test-Path $envPath)) {
        Copy-Item (Join-Path $ProjectRoot ".env.example") $envPath
    }

    $content = Get-Content $envPath -Raw
    $content = $content -replace "(?m)^DB_HOST=.*$", "DB_HOST=127.0.0.1"
    $content = $content -replace "(?m)^DB_PORT=.*$", "DB_PORT=3306"
    $content = $content -replace "(?m)^DB_DATABASE=.*$", "DB_DATABASE=$DbName"
    $content = $content -replace "(?m)^DB_USERNAME=.*$", "DB_USERNAME=root"
    $content = $content -replace "(?m)^DB_PASSWORD=.*$", "DB_PASSWORD="
    Set-Content -Path $envPath -Value $content -NoNewline
    Write-Host "==> Updated .env database settings (root / empty password)."
}

function Run-LaravelSetup {
    Push-Location $ProjectRoot
    try {
        Write-Host "==> Running Laravel migrations..."
        & php artisan config:clear | Out-Null
        & php artisan migrate --force
        Write-Host "==> Running database health check..."
        & php artisan db:health
    } finally {
        Pop-Location
    }
}

Write-Host "BrokersCourt — XAMPP + database setup"
Write-Host "======================================"

Install-Xampp
Start-XamppMysql
Initialize-Database
Update-EnvFile
Run-LaravelSetup

Write-Host ""
Write-Host "Done. Start the site with:"
Write-Host "  cd F:\vscode"
Write-Host "  php artisan serve"
Write-Host ""
Write-Host "Then open: http://127.0.0.1:8000"
