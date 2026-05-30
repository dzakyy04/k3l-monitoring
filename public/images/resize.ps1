Add-Type -AssemblyName System.Drawing
$imagePath = "c:\laragon\www\k3l-monitoring\public\images\logo-k3l-monitoring.jpeg"
$out192 = "c:\laragon\www\k3l-monitoring\public\images\logo-192.png"
$out512 = "c:\laragon\www\k3l-monitoring\public\images\logo-512.png"

$img = [System.Drawing.Image]::FromFile($imagePath)

$bmp192 = New-Object System.Drawing.Bitmap 192, 192
$g192 = [System.Drawing.Graphics]::FromImage($bmp192)
$g192.DrawImage($img, 0, 0, 192, 192)
$bmp192.Save($out192, [System.Drawing.Imaging.ImageFormat]::Png)

$bmp512 = New-Object System.Drawing.Bitmap 512, 512
$g512 = [System.Drawing.Graphics]::FromImage($bmp512)
$g512.DrawImage($img, 0, 0, 512, 512)
$bmp512.Save($out512, [System.Drawing.Imaging.ImageFormat]::Png)

$g192.Dispose()
$bmp192.Dispose()
$g512.Dispose()
$bmp512.Dispose()
$img.Dispose()
