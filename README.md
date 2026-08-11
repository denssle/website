# Einstiegsseite

Die gemeinsame Startseite unter [enzlor.uber.space](https://enzlor.uber.space/) –
von hier aus sind die übrigen Projekte erreichbar.

Gedacht als **Visitenkarte**: eine Stelle, an der alle Projekte zusammenlaufen –
die auf diesem Server laufenden ebenso wie die, die es nur als Quelltext gibt.
Ein neues Projekt gehört also hierher, auch wenn es gar keine Weboberfläche hat.

Statisches HTML/CSS, kein Build, kein Node-Prozess. Alles was ausgeliefert wird,
liegt in `public/`; ein Push auf `main` synct es nach `~/html` auf dem Uberspace.

```
public/
├── index.html        Deutsche Fassung (Standard), erreichbar unter /
├── datenschutz.html  Datenschutzhinweise
├── en/
│   ├── index.html    Englische Fassung, erreichbar unter /en/
│   └── privacy.html  Datenschutz, englisch (verbindlich ist die deutsche Fassung)
└── style.css         Gestaltung für alle (hell/dunkel über prefers-color-scheme)
```

**Beide Sprachfassungen sind eigenständige Dateien** – bewusst so, damit die
Seite ohne Build und ohne JavaScript auskommt. Der Preis: eine Änderung am
Inhalt muss in **beiden** Dateien passieren.

Die Sprache wird **nicht** automatisch erkannt: `/` ist immer deutsch, gewechselt
wird über den Umschalter oben rechts.

Ein Projekt ist bewusst **nicht** aufgeführt, solange das Projekt nicht
öffentlich sein soll.
