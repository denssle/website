# Einstiegsseite

Die gemeinsame Startseite unter [enzlor.uber.space](https://enzlor.uber.space/) –
von hier aus sind die übrigen Projekte erreichbar.

Gedacht als **Visitenkarte**: eine Stelle, an der alle Projekte zusammenlaufen –
die auf diesem Server laufenden ebenso wie die, die es nur als Quelltext gibt.
Ein neues Projekt gehört also hierher, auch wenn es gar keine Weboberfläche hat.

Statisches HTML/CSS, kein Build, kein Node-Prozess. Alles was ausgeliefert wird,
liegt in `public/`.

Die Gestaltung ist bewusst **eigenständig** und nicht von Festival übernommen
(das nutzt [simple.css](https://simplecss.org/)): die Einstiegsseite steht vor
den Projekten, nicht in einem davon, und die verlinkten Projekte sehen ohnehin
untereinander verschieden aus.

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

## Deployment

`.github/workflows/deploy.yml` synct bei jedem Push auf `main` den Inhalt von
`public/` per rsync nach `~/html` auf dem Uberspace. Kein Supervisor, kein
Neustart – Apache liefert die Dateien direkt aus.

Benötigte Repository-Secrets (pro Repository neu zu setzen):
`UBERSPACE_HOST`, `UBERSPACE_USER`, `DEPLOY_KEY_PRIVATE`.

```bash
gh secret set DEPLOY_KEY_PRIVATE < ~/sync/Sonstiges/Schlüssel/ssh-keys/*uberspace
```

Der Umweg über die Datei ist Absicht: beim Einfügen über die Zwischenablage gehen
regelmäßig Zeilenumbrüche verloren, die Folge ist
`Error loading key "(stdin)": error in libcrypto`.

## Einrichtung auf dem Server (erledigt am 2026-08-11)

`~/html` ist der Apache-Docroot und der Workflow oben macht ihn zum Spiegel von
`public/` – alles andere darin wird gelöscht. Deshalb musste die
Festival-Anwendung, die vorher dort lag, erst umziehen. Beides ist erledigt:

**1. Festival umgezogen** (im Repository `denssle/festival`):

- `svelte.config.js`: `kit.paths.base = '/festival'` gesetzt. Ohne das zeigen
  sämtliche internen Links und Asset-Pfade weiterhin auf die Wurzel.
- `remote_path` im dortigen `deploy.yml` auf `~/festival-app` geändert.
- Auf dem Host zeigt `~/etc/services.d/festival.ini` per `directory=` auf
  `/home/enzlor/festival-app` (danach `supervisorctl reread && supervisorctl update`).

**2. Backends registriert:**

```bash
uberspace web backend set /festival --http --port 5173
uberspace web backend set / --apache
uberspace web backend list
```

Die Reihenfolge ist wichtig: `/festival` zuerst, sonst ist die Anwendung
zwischenzeitlich unter keiner URL erreichbar. Der Pfad wird mitsamt Präfix ans
Backend durchgereicht – deshalb muss `paths.base` in Festival dazu passen.

**3. Erst danach** hier auf `main` pushen.

> **Was schiefging:** Schritt 3 lief vor Schritt 1. Der Deploy räumte `~/html`
> leer und nahm dabei die `.env` der Festival-Anwendung mit (gitignored, nur auf
> dem Host, ohne Backup außerhalb von `/backup/daily.*`). Die App lief zunächst
> weiter, weil ihr Code im Speicher lag, und fiel erst eine Stunde später beim
> ersten Request aus, der ein Asset von der Platte brauchte. Die Reihenfolge oben
> ist kein Formalismus.

## Prüfen

```bash
curl -s -o /dev/null -w "%{http_code}\n" https://enzlor.uber.space/
curl -s -o /dev/null -w "%{http_code}\n" https://enzlor.uber.space/festival
```

Beide sollten `200` liefern. Bei `502` läuft das für den Pfad registrierte
Backend nicht – dann in die Logs schauen, statt zu raten:

```bash
uberspace web log apache_error enable
tail -20 ~/logs/webserver/error_log_apache
```

## Inhalt pflegen

Die Projektbeschreibungen stehen als Klartext in `public/index.html` und
`public/en/index.html`, eine `<li class="projekt">` je Projekt. Ein neues
Projekt ist ein weiterer solcher Block **in beiden Dateien**; die Statusmarke
gibt es in zwei Ausprägungen: `status--live` (grün) und ohne Zusatzklasse
(neutral, für alles ohne eigene Weboberfläche).

Das GitHub-Icon steht einmal je Datei als `<symbol id="icon-github">` am
Anfang des `<body>` und wird per `<use href="#icon-github">` eingesetzt.
