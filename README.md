# Einstiegsseite

Die gemeinsame Startseite unter [enzlor.uber.space](https://enzlor.uber.space/) –
von hier aus sind die übrigen Projekte erreichbar.

Gedacht als **Visitenkarte**: eine Stelle, an der alle Projekte zusammenlaufen –
die auf diesem Server laufenden ebenso wie die, die es nur als Quelltext gibt.
Ein neues Projekt gehört also hierher, auch wenn es gar keine Weboberfläche hat.

HTML/CSS, kein Build, kein Node-Prozess. Alles was ausgeliefert wird, liegt in
`public/`; ein Push auf `main` synct es nach `~/html` auf dem Uberspace. Einzige
Ausnahme von „statisch": der Besucherzähler im Fuß der Startseite, dafür sind die
beiden Startseiten `.php`.

```
public/
├── index.php         Deutsche Fassung (Standard), erreichbar unter /
├── datenschutz.html  Datenschutzhinweise
├── zaehler.php       Besucherzähler, von beiden Startseiten eingebunden
├── .htaccess         DirectoryIndex; sperrt den direkten Aufruf von zaehler.php
├── en/
│   ├── index.php     Englische Fassung, erreichbar unter /en/
│   └── privacy.html  Datenschutz, englisch (verbindlich ist die deutsche Fassung)
└── style.css         Gestaltung für alle (hell/dunkel über prefers-color-scheme)
```

**Beide Sprachfassungen sind eigenständige Dateien** – bewusst so, damit die
Seite ohne Build und ohne JavaScript auskommt. Der Preis: eine Änderung am
Inhalt muss in **beiden** Dateien passieren.

Die Sprache wird **nicht** automatisch erkannt: `/` ist immer deutsch, gewechselt
wird über den Umschalter oben rechts.

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
die Datei nicht aus, und das
Deployment (`rsync --delete` auf `~/html`) räumt ihn nicht weg. Die Datei legt
sich beim ersten Aufruf selbst an; zum Zurücksetzen des Zählers einfach löschen.

Lokal testen ohne Uberspace:

```
php -S 127.0.0.1:8000 -t public
```

Dabei entsteht `zaehler-daten/` im Projektordner (ist in `.gitignore`).

Ein Projekt ist bewusst **nicht** aufgeführt, solange das Projekt nicht
öffentlich sein soll.
