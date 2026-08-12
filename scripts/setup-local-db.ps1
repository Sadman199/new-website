# Create a dedicated local MariaDB user for BrokersCourt (XAMPP on Windows).
# Run once from PowerShell:  .\scripts\setup-local-db.ps1
# Then copy .env.local.example -> .env and set DB_PASSWORD to match.

param(
    [string]$DbName = "brokerscourt_data",
    [string]$DbUser = "brokerscourt_local",
    [string]$DbPassword = "brokerscourt_local_dev",
    [string]$MysqlBin = "C:\xampp\mysql\bin\mysql.exe"
)

$ErrorActionPreference = "Stop"

if (-not (Test-Path $MysqlBin)) {
    Write-Error "mysql.exe not found at $MysqlBin. Update -MysqlBin if XAMPP is installed elsewhere."
}

try {
    & $MysqlBin -h 127.0.0.1 -u root -e "SELECT 1" | Out-Null
} catch {
    Write-Error "MariaDB is not running. Start MySQL from XAMPP Control Panel, then run this script again."
}

$sql = @"
CREATE DATABASE IF NOT EXISTS `$DbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DbUser'@'127.0.0.1' IDENTIFIED BY '$DbPassword';
CREATE USER IF NOT EXISTS '$DbUser'@'localhost' IDENTIFIED BY '$DbPassword';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP, REFERENCES ON `$DbName`.* TO '$DbUser'@'127.0.0.1';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP, REFERENCES ON `$DbName`.* TO '$DbUser'@'localhost';
FLUSH PRIVILEGES;
"@

Write-Host "==> Creating local app user '$DbUser' for database '$DbName'..."
& $MysqlBin -h 127.0.0.1 -u root -e $sql

Write-Host ""
Write-Host "Done. Update your .env:"
Write-Host "  DB_HOST=127.0.0.1"
Write-Host "  DB_DATABASE=$DbName"
Write-Host "  DB_USERNAME=$DbUser"
Write-Host "  DB_PASSWORD=$DbPassword"
Write-Host ""
Write-Host "Then run: php artisan config:clear && php artisan db:health"
