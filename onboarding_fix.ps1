$src = "C:\xampp\htdocs\Laravel-12-HR-System-Management"
$out = "$env:USERPROFILE\Desktop\onboarding-fix.zip"
$tmp = "$env:USERPROFILE\Desktop\onboarding-tmp"
New-Item -ItemType Directory -Force -Path $tmp | Out-Null

$files = @(
    "resources\views\pages\onboarding.blade.php",
    "resources\views\auth\login.blade.php"
)

foreach ($f in $files) {
    $dest = Join-Path $tmp $f
    New-Item -ItemType Directory -Force -Path (Split-Path $dest) | Out-Null
    Copy-Item (Join-Path $src $f) $dest -ErrorAction SilentlyContinue
}

Compress-Archive -Path "$tmp\*" -DestinationPath $out -Force
Remove-Item -Recurse -Force $tmp
Write-Host "Done → $out" -ForegroundColor Green
