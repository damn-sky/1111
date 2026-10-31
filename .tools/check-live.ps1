$url = 'https://new-site.xn----htbbbudhytelf.xn--p1ai/'
$r = Invoke-WebRequest -Uri $url -UseBasicParsing -TimeoutSec 40
Write-Output ("status " + $r.StatusCode + " len " + $r.RawContentLength)
$html = $r.Content
Write-Output ("STEKLO " + ($html.Contains('window.STEKLO')))
Write-Output ("landing " + ($html.Contains('steklosistemy/landing')))
Write-Output ("root " + ($html.Contains('id="root"')))
Write-Output ("site-header " + ($html.Contains('site-header')))
$themes = [regex]::Matches($html, 'wp-content/themes/[^"'' ]+')
$themes | ForEach-Object { $_.Value } | Select-Object -Unique
Write-Output '--- snippet ---'
$i = $html.IndexOf('window.STEKLO')
if ($i -ge 0) { $html.Substring($i, [Math]::Min(500, $html.Length - $i)) }
$j = $html.IndexOf('<title')
if ($j -ge 0) { $html.Substring($j, [Math]::Min(200, $html.Length - $j)) }
