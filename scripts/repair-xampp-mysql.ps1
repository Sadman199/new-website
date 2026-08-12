# Repair common XAMPP MariaDB privilege-table corruption on Windows.
# Run when you see: "Host 'localhost' is not allowed to connect"
# or "Index for table 'db' is corrupt".

param(
    [string]$MysqlDir = "C:\xampp\mysql"
)

$ErrorActionPreference = "Stop"
$mysqld = Join-Path $MysqlDir "bin\mysqld.exe"
$ariaChk = Join-Path $MysqlDir "bin\aria_chk.exe"
$dataMysql = Join-Path $MysqlDir "data\mysql"

if (-not (Test-Path $ariaChk)) {
    Write-Error "aria_chk.exe not found. Set -MysqlDir to your XAMPP mysql folder."
}

Write-Host "==> Stopping mysqld..."
Get-Process mysqld -ErrorAction SilentlyContinue | Stop-Process -Force
Start-Sleep -Seconds 2

foreach ($table in @("global_priv", "db")) {
    $path = Join-Path $dataMysql $table
    if (Test-Path $path) {
        Write-Host "==> Repairing mysql.$table ..."
        & $ariaChk -r $path
    }
}

Write-Host "==> Starting mysqld..."
Start-Process -FilePath $mysqld -ArgumentList "--defaults-file=$MysqlDir\bin\my.ini" -WindowStyle Hidden
Start-Sleep -Seconds 4

Write-Host "==> Testing connection..."
& (Join-Path $MysqlDir "bin\mysql.exe") -h 127.0.0.1 -u root -e "SELECT 1 AS ok;"
Write-Host "Done."
