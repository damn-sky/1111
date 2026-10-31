# Локальный предпросмотр главной перед заливкой на сайт (FileZilla).
# Два режима — выберите в меню или запустите с аргументом: dev | build | open

param(
  [ValidateSet('dev', 'build', 'open')]
  [string]$Mode = 'menu'
)

$ErrorActionPreference = 'Stop'
$ThemeRoot = $PSScriptRoot
$NodeDir = 'E:\new-site.стекло-трейд.рф\.tools\node'
if (-not (Test-Path (Join-Path $NodeDir 'node.exe'))) {
  Write-Host 'Node не найден в .tools\node — установите или укажите путь.' -ForegroundColor Red
  exit 1
}
$env:PATH = "$NodeDir;$env:PATH"
$LandingSrc = Join-Path $ThemeRoot 'landing-src'
$Landing = Join-Path $ThemeRoot 'landing'
$PreviewUrl = 'http://127.0.0.1:8765/preview-local.html'
$DevUrl = 'http://127.0.0.1:5173/wp-content/themes/steklosistemy/landing/'

function Show-Menu {
  Write-Host ''
  Write-Host '=== Предпросмотр главной Стеклосистемы ===' -ForegroundColor Cyan
  Write-Host '1) dev   — правки в коде, горячая перезагрузка (landing-src)'
  Write-Host '2) build — собрать как на сайте + открыть preview-local.html'
  Write-Host '3) open  — только открыть уже собранный preview (после build)'
  Write-Host ''
  $c = Read-Host 'Номер (1/2/3)'
  switch ($c) {
    '1' { $script:Mode = 'dev' }
    '2' { $script:Mode = 'build' }
    '3' { $script:Mode = 'open' }
    default { $script:Mode = 'build' }
  }
}

if ($Mode -eq 'menu') { Show-Menu }

function Start-PreviewServer {
  if (-not (Test-Path (Join-Path $Landing 'preview-local.html'))) {
    Write-Host 'Нет landing/preview-local.html — сначала выполните build (пункт 2).' -ForegroundColor Red
    exit 1
  }
  $job = Get-Job -Name 'steklo-preview' -ErrorAction SilentlyContinue
  if ($job) { Stop-Job $job; Remove-Job $job -Force }
  Start-Job -Name 'steklo-preview' -ScriptBlock {
    param($dir, $nodePath)
    $env:PATH = "$nodePath;$env:PATH"
    Set-Location $dir
    npx --yes serve -l 8765 --no-port-switching .
  } -ArgumentList $Landing, $NodeDir | Out-Null
  Start-Sleep -Seconds 3
  Start-Process $PreviewUrl
  Write-Host "Открыто: $PreviewUrl" -ForegroundColor Green
  Write-Host 'Тексты для проверки: landing/preview-data.js (или landing-src/public/preview-data.js до сборки)'
  Write-Host 'Остановить сервер: Get-Job steklo-preview | Stop-Job; Remove-Job steklo-preview'
}

switch ($Mode) {
  'dev' {
    Write-Host "Dev-сервер: $DevUrl"
    Start-Process $DevUrl
    Set-Location $LandingSrc
    npm run dev
  }
  'build' {
    Set-Location $LandingSrc
    npm run build
    Start-PreviewServer
  }
  'open' {
    Start-PreviewServer
  }
}
