# Einstiegsseite

Die gemeinsame Startseite unter [enzlor.uber.space](https://enzlor.uber.space/) –
von hier aus sind die übrigen Projekte erreichbar.

Gedacht als **Visitenkarte**: eine Stelle, an der alle Projekte zusammenlaufen –
die auf diesem Server laufenden ebenso wie die, die es nur als Quelltext gibt.
Ein neues Projekt gehört also hierher, auch wenn es gar keine Weboberfläche hat.

Statisches HTML/CSS, kein Build, kein Node-Prozess. Alles was ausgeliefert wird,
liegt in `public/`.

```
public/
├── index.html      Deutsche Fassung (Standard), erreichbar unter /
├── en/
│   └── index.html  Englische Fassung, erreichbar unter /en/
└── style.css       Gestaltung für beide (hell/dunkel über prefers-color-scheme)
```

**Beide Sprachfassungen sind eigenständige Dateien** – bewusst so, damit die
Seite ohne Build und ohne JavaScript auskommt. Der Preis: eine Änderung am
Inhalt muss in **beiden** Dateien passieren. Sie sollten stets dieselbe Zahl an
`<li class="projekt">`-Blöcken haben.

Die Sprache wird **nicht** automatisch erkannt: `/` ist immer deutsch, gewechselt
wird über den Umschalter oben rechts. Das `hreflang`-Geflecht im `<head>` (`de`,
`en`, `x-default`) sagt Suchmaschinen, dass beide dasselbe in zwei Sprachen sind
– es braucht absolute URLs und muss beim Umzug auf eine eigene Domain
mitgezogen werden.

Zum Anschauen genügt es, `public/index.html` im Browser zu öffnen – bis auf die
Verweise auf `/festival` und `/config`, die erst auf dem Server stimmen.

Ein Projekt ist bewusst **nicht** aufgeführt, solange das Projekt nicht
öffentlich sein soll.

## Inhalt pflegen

Die Projektbeschreibungen stehen als Klartext in `public/index.html` und
`public/en/index.html`, eine `<li class="projekt">` je Projekt. Ein neues
Projekt ist ein weiterer solcher Block **in beiden Dateien**; die Statusmarke
gibt es in zwei Ausprägungen: `status--live` (grün) und ohne Zusatzklasse
(neutral, für alles ohne eigene Weboberfläche).

Das GitHub-Icon steht einmal je Datei als `<symbol id="icon-github">` am
Anfang des `<body>` und wird per `<use href="#icon-github">` eingesetzt.

## Deployment

`.github/workflows/deploy.yml` synct bei jedem Push auf `main` den Inhalt von
`public/` per rsync nach `~/html` auf dem Uberspace. Kein Build, kein
Supervisor, kein Neustart – Apache liefert die Dateien direkt aus.

`~/html` ist dabei ein **Spiegel von `public/`**: rsync läuft mit `--delete`,
alles andere in dem Verzeichnis wird gelöscht. Der Docroot gehört deshalb allein
diesem Projekt – Festival deployt nach `~/festival-app` und hängt am eigenen
Backend:

```bash
uberspace web backend list
# /         → apache (diese Seite)
# /festival → http:5173
```

Ausgenommen von `--delete` ist `.well-known` (ACME-Challenges u. ä.), das nicht
aus `public/` stammt.

> **Warum so ausdrücklich:** Am 2026-08-11 lag Festival noch in `~/html`. Der
> erste Deploy räumte das Verzeichnis leer und nahm `build/`, `package.json` und
> die `.env` mit (gitignored, nur auf dem Host). Die App lief zunächst weiter,
> weil ihr Code im Speicher lag, und fiel erst eine Stunde später beim ersten
> Request aus, der ein Asset von der Platte brauchte.

Benötigte Repository-Secrets: `UBERSPACE_HOST`, `UBERSPACE_USER`,
`DEPLOY_KEY_PRIVATE`.

## Prüfen

```bash
curl -s -o /dev/null -w "%{http_code}\n" https://enzlor.uber.space/
curl -s -o /dev/null -w "%{http_code}\n" https://enzlor.uber.space/festival
```

Beide sollten `200` liefern. Bei `502` läuft das für den Pfad registrierte
Backend nicht – dann in die Logs schauen, statt zu raten:

```bash
tail -20 ~/logs/webserver/error_log_apache
```
