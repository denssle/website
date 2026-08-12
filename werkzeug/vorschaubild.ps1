# Erzeugt die Vorschaubilder für die Karte in sozialen Netzen (og:image) –
# eines je Sprache, 1200x630. Das ist das Format, das LinkedIn, Facebook und Co.
# als große Karte ausspielen; kleinere Bilder landen als Briefmarke neben dem
# Text.
#
# Aufruf aus dem Projektverzeichnis heraus:
#
#     powershell -ExecutionPolicy Bypass -File werkzeug/vorschaubild.ps1
#
# Die Bilder gehen nach public/vorschaubild.png und public/en/vorschaubild.png
# und werden von dort mit deployt. Dieses Skript selbst liegt bewusst außerhalb
# von public/ – es gehört zum Bauen, nicht zur Seite.
#
# ACHTUNG: Die Domain steht im Bild. Zieht die Seite um, muss sie hier wie in
# den absoluten URLs der Kopfdaten mitgezogen werden.
#
# Die Farben sind dieselben wie in style.css (Citadel-Farben aus dem Bemalrezept
# für die Salamanders). Ändern sie sich dort, gehören sie hier nachgezogen.

$ErrorActionPreference = 'Stop'
Add-Type -AssemblyName System.Drawing

$wurzel = Split-Path -Parent $PSScriptRoot
$domain = 'enzlor.uber.space'

# Je Sprache: Augenbraue, Unterzeile und Ziel. Der Name bleibt gleich.
$fassungen = @(
	@{
		Auge  = 'P R O J E K T E'
		Unter = 'Eine Webanwendung und ein Discord-Bot.'
		Ziel  = Join-Path $wurzel 'public/vorschaubild.png'
	},
	@{
		Auge  = 'P R O J E C T S'
		Unter = 'A web app and a Discord bot.'
		Ziel  = Join-Path $wurzel 'public/en/vorschaubild.png'
	}
)

function Farbe($hex) {
	[System.Drawing.ColorTranslator]::FromHtml($hex)
}

$grund = Farbe '#181818' # Nuln Oil
$karte = Farbe '#231f20' # Abaddon Black
$text = Farbe '#f5f5f0'  # White Scar
$leise = Farbe '#8a8c87' # Dawnstone
$gold = Farbe '#c8952a'  # Retributor Armour

foreach ($fassung in $fassungen) {
	$b = New-Object System.Drawing.Bitmap 1200, 630
	$g = [System.Drawing.Graphics]::FromImage($b)
	$g.SmoothingMode = 'AntiAlias'
	$g.TextRenderingHint = 'ClearTypeGridFit'

	$g.Clear($grund)

	# Karte mit goldener Kante, wie die Projektkacheln auf der Seite
	$rand = 48
	$k = New-Object System.Drawing.Rectangle $rand, $rand, (1200 - 2 * $rand), (630 - 2 * $rand)
	$g.FillRectangle((New-Object System.Drawing.SolidBrush $karte), $k)
	$g.DrawRectangle((New-Object System.Drawing.Pen $gold, 2), $k)

	# Goldener Balken links am Text – die Zier der Rüstung
	$g.FillRectangle((New-Object System.Drawing.SolidBrush $gold), 112, 196, 6, 250)

	$li = 152
	$px = [System.Drawing.GraphicsUnit]::Pixel
	$fAuge = New-Object System.Drawing.Font 'Segoe UI Semibold', 26, ([System.Drawing.FontStyle]::Regular), $px
	$fName = New-Object System.Drawing.Font 'Segoe UI', 96, ([System.Drawing.FontStyle]::Bold), $px
	$fUnter = New-Object System.Drawing.Font 'Segoe UI', 40, ([System.Drawing.FontStyle]::Regular), $px
	$fFuss = New-Object System.Drawing.Font 'Segoe UI', 24, ([System.Drawing.FontStyle]::Regular), $px

	# Ohne NoWrap bricht der Name um, sobald er knapp wird – hier soll er lieber
	# über den Rand hinauslaufen und beim Prüfen auffallen.
	$fmt = New-Object System.Drawing.StringFormat
	$fmt.FormatFlags = [System.Drawing.StringFormatFlags]::NoWrap

	$g.DrawString($fassung.Auge, $fAuge, (New-Object System.Drawing.SolidBrush $leise), $li, 196, $fmt)
	$g.DrawString('Dominik Hellweg', $fName, (New-Object System.Drawing.SolidBrush $text), ($li - 6), 240, $fmt)
	$g.DrawString($fassung.Unter, $fUnter, (New-Object System.Drawing.SolidBrush $leise), ($li - 3), 386, $fmt)
	$g.DrawString($domain, $fFuss, (New-Object System.Drawing.SolidBrush $gold), ($li - 3), 478, $fmt)

	$g.Dispose()
	$b.Save($fassung.Ziel, [System.Drawing.Imaging.ImageFormat]::Png)
	$b.Dispose()

	Write-Output "geschrieben: $($fassung.Ziel)"
}
