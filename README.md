# Einstiegsseite

Die gemeinsame Startseite unter [enzlor.uber.space](https://enzlor.uber.space/) –
von hier aus sind die übrigen Projekte erreichbar.

Gedacht als **Visitenkarte**: eine Stelle, an der alle Projekte zusammenlaufen –
die auf diesem Server laufenden ebenso wie die, die es nur als Quelltext gibt.
Ein neues Projekt gehört also hierher, auch wenn es gar keine Weboberfläche hat.

HTML, CSS und ein wenig PHP – kein Build, kein Node-Prozess, kein JavaScript.
Alles was ausgeliefert wird, liegt in `public/`; ein Push auf `main` synct es
nach `~/html` auf dem Uberspace.

Das PHP ist der Besucherzähler und sonst nichts. Er ist auch der Grund für die
einzige Anforderung an den Server: **das Web-Backend für `/` muss Apache sein**
(`uberspace web backend set / --apache`), denn nur der führt die `.php`-Dateien
aus. Landet die Seite hinter einem anderen Backend, liefert sie den Quelltext
statt der Seite.

```
public/
├── index.php         Deutsche Fassung (Standard), erreichbar unter /
├── datenschutz.html  Datenschutzhinweise
├── zaehler.php       Besucherzähler, von beiden Startseiten eingebunden
├── .htaccess         DirectoryIndex; sperrt den direkten Aufruf von zaehler.php
├── en/
│   ├── index.php     Englische Fassung, erreichbar unter /en/
│   └── privacy.html  Datenschutz, englisch (verbindlich ist die deutsche Fassung)
└── style.css         Gestaltung für alle Seiten
```

**Beide Sprachfassungen sind eigenständige Dateien.** Seit dem Zähler liegt PHP
auf dem Server, gemeinsame Bausteine wären also möglich – es bleibt trotzdem bei
zwei vollständigen Dateien. Eine Seite mit zwei Projektkarten rechtfertigt keine
Schablonen; wer eine Fassung liest, sieht sie ganz. Der Preis: eine Änderung am
Inhalt muss in **beiden** Dateien passieren.

Die Sprache wird **nicht** automatisch erkannt: `/` ist immer deutsch, gewechselt
wird über den Umschalter oben rechts.

Umgekehrt gehört **nicht** jedes Projekt hierher: was nicht öffentlich sein soll,
wird hier auch nicht genannt – weder in der Übersicht noch in dieser Datei.

## Farben

Die Palette stammt aus dem Bemalrezept für die **Salamanders** (Warhammer
40.000): Citadel-Farbtöne, Hex-Werte über [The Painting
Ledger](https://thepaintingledger.com/paints.html). Die Rollen folgen dem
Vorbild – Schwarz trägt, Grün handelt, Gold ziert:

| Token           | Hex       | Dose              | Rolle                        |
| --------------- | --------- | ----------------- | ---------------------------- |
| `--grund`       | `#181818` | Nuln Oil          | Seitengrund                  |
| `--karte`       | `#231F20` | Abaddon Black     | Projektkarten                |
| `--text`        | `#F5F5F0` | White Scar        | Fließtext                    |
| `--text-leise`  | `#8A8C87` | Dawnstone         | Nebensächliches              |
| `--linie`       | `#4A5055` | Eshin Grey        | Rahmen, Trennlinien          |
| `--akzent`      | `#285028` | Waaagh! Flesh     | Flächen (Hauptverweis)       |
| `--akzent-hell` | `#1A8C28` | Warpstone Glow    | Unterstreichungen            |
| `--gold`        | `#C8952A` | Retributor Armour | Kanten, Fokus, Zählerziffern |
| `--gold-hell`   | `#C8A038` | Liberator Gold    | Hover                        |
| `--live-grund`  | `#0A3818` | Caliban Green     | Status „Live"                |
| `--live-text`   | `#60C830` | Moot Green        | Status „Live"                |

**Gold nie als Fläche mit Text darauf** – dafür ist der Kontrast zu gering. Es
bleibt Kante, Ring und Ziffer.

Die Seite ist damit **nur noch dunkel**: `color-scheme: dark`, kein
`prefers-color-scheme`-Zweig mehr. Eine helle Fassung dieser Rüstung gibt es
nicht.

## Besucherzähler

Ein Aufruf zählt einmal pro Tag und Gerät; beide Sprachfassungen zahlen auf
denselben Stand ein. Wiedererkannt wird ein Gerät über einen Hash aus IP-Adresse
und Browserkennung, dessen Schlüssel täglich neu gewürfelt wird – über den
Tageswechsel hinaus bleibt nur eine Zahl übrig. Kein Cookie, kein JavaScript,
kein fremder Dienst. Fällt der Zähler aus, fehlt die Zeile im Fuß und sonst
nichts.

Der Stand liegt in `stand.json` **eine Ebene über dem DocumentRoot**, auf dem
Server also in `/var/www/virtual/<benutzer>/zaehler-daten/` – direkt neben dem
Verzeichnis, auf das `~/html` zeigt. Das ist Absicht: dort liefert der Webserver
die Datei nicht aus, und das Deployment (`rsync --delete` auf `~/html`) räumt sie
nicht weg. Sie legt sich beim ersten Aufruf selbst an; zum Zurücksetzen des
Zählers einfach löschen.

Lokal testen ohne Uberspace:

```
php -S 127.0.0.1:8000 -t public
```

Dabei entsteht `zaehler-daten/` im Projektordner (ist in `.gitignore`).
